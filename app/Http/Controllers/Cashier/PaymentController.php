<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Reservation;
use App\Services\PaymentDeadlineService;
use App\Notifications\ReservationStatusChanged;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List all cash-pending reservations.
     */
    public function index(Request $request)
    {
        $query = Reservation::with('user', 'facility')
            ->whereIn('payment_status', ['cash_pending', 'partially_paid'])
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('payment_deadline');

        // Optionally show expired too if ?include_expired=1
        if (!$request->boolean('include_expired')) {
            $query->where('payment_deadline', '>', now());
        }

        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('booking_reference', 'like', "%$q%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%"));
            });
        }

        $reservations = $query->latest()->paginate(15)->withQueryString();

        $facilities = \App\Models\Facility::orderBy('name')->get(['id', 'name']);

        return view('cashier.payments.index', compact('reservations', 'facilities'));
    }

    /**
     * Show a single reservation with confirmation form.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'facility', 'cashierSignedBy');
        return view('cashier.payments.show', compact('reservation'));
    }

    /**
     * Confirm cash payment — marks as paid, records cashier details.
     */
    public function confirm(Request $request, Reservation $reservation)
    {
        if ($reservation->payment_status === 'paid') {
            return back()->with('error', 'This reservation is already marked as paid.');
        }

        if (!in_array($reservation->payment_status, ['cash_pending', 'partially_paid'])) {
            return back()->with('error', 'This reservation is not eligible for cash confirmation.');
        }

        $remaining = (float) $reservation->remaining_balance;

        $validated = $request->validate([
            'payment_type'        => ['required', 'in:full,partial'],
            'amount_collected'    => ['required_if:payment_type,partial', 'nullable', 'numeric', 'min:0.01', "max:{$remaining}"],
            'cash_receipt_number' => ['nullable', 'string', 'max:100'],
            'cash_payment_notes'  => ['nullable', 'string', 'max:500'],
        ]);

        $paymentType = $validated['payment_type'];

        // Force full payment if already partially paid
        if ($reservation->payment_status === 'partially_paid') {
            $paymentType = 'full';
        }

        if ($paymentType === 'full') {
            $amountCollected = $remaining;
            $newAmountPaid   = (float) $reservation->total_price;
            $newPaymentStatus = 'paid';
            $newDeadline     = null;
        } else {
            $amountCollected = (float) $validated['amount_collected'];
            $newAmountPaid   = (float) ($reservation->amount_paid ?? 0) + $amountCollected;

            if ($newAmountPaid >= (float) $reservation->total_price) {
                $newPaymentStatus = 'paid';
                $newDeadline     = null;
            } else {
                $newPaymentStatus = 'partially_paid';
                $newDeadline = $reservation->payment_deadline ?: PaymentDeadlineService::forReservation($reservation);
            }
        }

        $reservation->update([
            'payment_status'      => $newPaymentStatus,
            'amount_paid'         => $newAmountPaid,
            'payment_deadline'    => $newDeadline,
            'cashier_signed_by'   => auth()->id(),
            'cashier_signed_at'   => now(),
            'cash_receipt_number' => $validated['cash_receipt_number'] ?? null,
            'cash_payment_notes'  => $validated['cash_payment_notes'] ?? null,
        ]);

        // Notify the guest (never crash payment confirmation on mail failure)
        $notifyStatus = ($newPaymentStatus === 'paid') ? 'payment_received' : 'partially_paid';
        safe_notify($reservation->user, new ReservationStatusChanged($reservation, $notifyStatus));

        // Audit log
        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'payment_confirmed',
            'target_type' => 'Reservation',
            'target_id'   => $reservation->id,
            'details'     => "Cashier confirmed {$paymentType} payment of ₱" . number_format($amountCollected, 2) . " for reservation #{$reservation->booking_reference}. New Status: {$newPaymentStatus}.",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('cashier.payments.show', $reservation)
            ->with('success', "Payment confirmed for {$reservation->booking_reference}. Slip is ready to print.");
    }

    /**
     * Printable payment report / statement dashboard.
     */
    public function report(Request $request)
    {
        $data = $this->buildStatementData($request);
        return view('cashier.payments.report', $data);
    }

    /**
     * Dedicated Official Print View (WYSIWYG Document Canvas & Toolbar)
     */
    public function print(Request $request)
    {
        $data = $this->buildStatementData($request);
        return view('cashier.payments.print', $data);
    }

    /**
     * Export Filtered Statement Ledger to CSV
     */
    public function exportCsv(Request $request)
    {
        $data = $this->buildStatementData($request);
        $reservations = $data['reservations'];
        $dateLabel    = $data['dateRangeLabel'];

        $filename = 'SURE-Cashier-Statement-' . now()->format('Ymd-His') . '.csv';

        $fp = fopen('php://memory', 'r+');
        fputs($fp, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

        fputcsv($fp, ['=== OFFICIAL STATEMENT OF CASHIER COLLECTIONS & SETTLED ACCOUNTS ===']);
        fputcsv($fp, ['Reporting Period', $dateLabel]);
        fputcsv($fp, ['Cashier Scope', $data['selectedCashier'] ? $data['selectedCashier']->name : 'All Cashiers']);
        fputcsv($fp, ['Facility Scope', $data['selectedFacility'] ? $data['selectedFacility']->name : 'All Facilities']);
        fputcsv($fp, ['Payment Method Filter', ucfirst($data['paymentMethod'] ?? 'all')]);
        fputcsv($fp, ['Date Generated', now()->format('Y-m-d H:i:s')]);
        fputcsv($fp, []);

        fputcsv($fp, ['FINANCIAL OVERVIEW']);
        fputcsv($fp, ['Total Transactions', $data['totalCount']]);
        fputcsv($fp, ['Total Revenue Collected (PHP)', number_format($data['totalAmount'], 2, '.', '')]);
        fputcsv($fp, ['Cash Collections - OTC (PHP)', number_format($data['cashCollected'], 2, '.', '')]);
        fputcsv($fp, ['Digital Gateway - PayMongo (PHP)', number_format($data['digitalCollected'], 2, '.', '')]);
        fputcsv($fp, []);

        fputcsv($fp, [
            '#',
            'Booking Reference',
            'Guest Name',
            'Guest Email',
            'Facility',
            'Event Date',
            'Settled Date',
            'Payment Method',
            'Amount Paid (PHP)',
            'Official Receipt / Gateway Ref',
            'Confirmed By'
        ]);

        $idx = 1;
        foreach ($reservations as $r) {
            $methodLabel = match($r->payment_method) {
                'cash'     => 'Cash (Over-The-Counter)',
                'paymongo' => 'PayMongo Digital Gateway',
                default    => ucfirst($r->payment_method ?? 'Unknown')
            };

            $receipt = $r->cash_receipt_number ?: ($r->payment_id ?: 'N/A');
            $confirmedBy = $r->payment_method === 'paymongo' 
                ? 'PayMongo Online Gateway' 
                : ($r->cashierSignedBy->name ?? 'System Cashier');

            fputcsv($fp, [
                $idx++,
                $r->booking_reference ?? ('#' . $r->id),
                $r->user->name ?? 'Guest User',
                $r->user->email ?? 'N/A',
                $r->facility->name ?? 'N/A',
                $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('Y-m-d') : 'N/A',
                $r->cashier_signed_at ? \Carbon\Carbon::parse($r->cashier_signed_at)->format('Y-m-d H:i') : ($r->updated_at ? $r->updated_at->format('Y-m-d H:i') : 'N/A'),
                $methodLabel,
                number_format($r->total_price ?? 0, 2, '.', ''),
                $receipt,
                $confirmedBy,
            ]);
        }

        fputcsv($fp, []);
        fputcsv($fp, ['', '', '', '', '', '', '', 'TOTAL COLLECTED (PHP)', number_format($data['totalAmount'], 2, '.', '')]);

        rewind($fp);
        $csvContent = stream_get_contents($fp);
        fclose($fp);

        return response($csvContent, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"; filename*=UTF-8\'\'' . rawurlencode($filename),
            'Content-Length'      => strlen($csvContent),
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma'              => 'public',
        ]);
    }

    /**
     * Core Data Aggregation & Query Engine for Statement Report
     */
    private function buildStatementData(Request $request): array
    {
        $datePreset    = $request->input('date_preset', 'all');
        $dateFrom      = $request->input('date_from');
        $dateTo        = $request->input('date_to');
        $facilityId    = $request->input('facility_id');
        $cashierId     = $request->input('cashier_id');
        $paymentMethod = $request->input('payment_method', 'all');

        // Resolve dates
        [$startRange, $endRange, $dateRangeLabel] = $this->resolveDateRange($datePreset, $dateFrom, $dateTo);

        $query = Reservation::with(['user', 'facility.category', 'cashierSignedBy'])
            ->where('payment_status', 'paid');

        // Date filter: cash payments have cashier_signed_at, digital payments have updated_at
        if ($startRange && $endRange) {
            $query->where(function ($q) use ($startRange, $endRange) {
                $q->whereBetween('cashier_signed_at', [$startRange->startOfDay(), $endRange->endOfDay()])
                  ->orWhere(function ($sub) use ($startRange, $endRange) {
                      $sub->whereNull('cashier_signed_at')
                          ->whereBetween('updated_at', [$startRange->startOfDay(), $endRange->endOfDay()]);
                  });
            });
        } elseif ($startRange) {
            $query->where(function ($q) use ($startRange) {
                $q->whereDate('cashier_signed_at', '>=', $startRange->toDateString())
                  ->orWhere(function ($sub) use ($startRange) {
                      $sub->whereNull('cashier_signed_at')
                          ->whereDate('updated_at', '>=', $startRange->toDateString());
                  });
            });
        } elseif ($endRange) {
            $query->where(function ($q) use ($endRange) {
                $q->whereDate('cashier_signed_at', '<=', $endRange->toDateString())
                  ->orWhere(function ($sub) use ($endRange) {
                      $sub->whereNull('cashier_signed_at')
                          ->whereDate('updated_at', '<=', $endRange->toDateString());
                  });
            });
        }

        if (!empty($facilityId) && $facilityId !== 'all') {
            $query->where('facility_id', $facilityId);
        }

        if (!empty($cashierId) && $cashierId !== 'all') {
            $query->where('cashier_signed_by', $cashierId);
        }

        if (!empty($paymentMethod) && $paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        $reservations = $query->latest('cashier_signed_at')->latest('updated_at')->get();

        $totalCount       = $reservations->count();
        $totalAmount      = (float) $reservations->sum('total_price');
        $cashCollected    = (float) $reservations->where('payment_method', 'cash')->sum('total_price');
        $digitalCollected = (float) $reservations->where('payment_method', '!=', 'cash')->sum('total_price');
        $cashCount        = $reservations->where('payment_method', 'cash')->count();
        $digitalCount     = $reservations->where('payment_method', '!=', 'cash')->count();

        $facilities = \App\Models\Facility::orderBy('name')->get(['id', 'name']);
        $cashiers   = \App\Models\User::whereIn('role', ['cashier', 'admin', 'superadmin'])
            ->orderBy('name')->get(['id', 'name', 'role']);

        $selectedFacility = (!empty($facilityId) && $facilityId !== 'all') ? $facilities->firstWhere('id', $facilityId) : null;
        $selectedCashier  = (!empty($cashierId) && $cashierId !== 'all') ? $cashiers->firstWhere('id', $cashierId) : null;

        $siteName = function_exists('branding') ? (branding('system_name') ?? config('app.name', 'SURE')) : config('app.name', 'SURE');

        $reportTitle = 'STATEMENT OF CASHIER COLLECTIONS & SETTLED ACCOUNTS';
        $docReference = 'SURE-CSH-' . now()->format('Ymd') . '-' . str_pad($totalCount, 3, '0', STR_PAD_LEFT);

        // Signatories (live customizable in print view)
        $currentUser = auth()->user();
        $preparedByName  = $request->input('prepared_by_name', $currentUser ? $currentUser->name : 'Cashier Staff');
        $preparedByTitle = $request->input('prepared_by_title', ($currentUser && method_exists($currentUser, 'roleLabel')) ? ($currentUser->roleLabel() . ' / Disbursing Officer') : 'Processing Cashier');
        $verifiedByName  = $request->input('verified_by_name', 'Dr. Roberto M. Santos');
        $verifiedByTitle = $request->input('verified_by_title', 'Head of Financial & Cashiering Services');

        return compact(
            'reservations',
            'totalCount',
            'totalAmount',
            'cashCollected',
            'digitalCollected',
            'cashCount',
            'digitalCount',
            'facilities',
            'cashiers',
            'selectedFacility',
            'selectedCashier',
            'paymentMethod',
            'datePreset',
            'dateFrom',
            'dateTo',
            'dateRangeLabel',
            'siteName',
            'reportTitle',
            'docReference',
            'preparedByName',
            'preparedByTitle',
            'verifiedByName',
            'verifiedByTitle'
        );
    }

    /**
     * Resolve Date Presets & Custom Ranges
     */
    private function resolveDateRange(string $preset, ?string $from, ?string $to): array
    {
        $now = now();
        switch ($preset) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end   = $now->copy()->endOfDay();
                $label = 'Today (' . $now->format('M d, Y') . ')';
                break;
            case 'this_week':
                $start = $now->copy()->startOfWeek();
                $end   = $now->copy()->endOfWeek();
                $label = 'This Week (' . $start->format('M d') . ' – ' . $end->format('M d, Y') . ')';
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                $label = $now->format('F Y');
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end   = $now->copy()->subMonth()->endOfMonth();
                $label = $start->format('F Y');
                break;
            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                $label = 'Annual ' . $now->year;
                break;
            case 'custom':
                $start = $from ? \Carbon\Carbon::parse($from)->startOfDay() : null;
                $end   = $to ? \Carbon\Carbon::parse($to)->endOfDay() : null;
                if ($start && $end) {
                    $label = $start->format('M d, Y') . ' – ' . $end->format('M d, Y');
                } elseif ($start) {
                    $label = 'From ' . $start->format('M d, Y');
                } elseif ($end) {
                    $label = 'Until ' . $end->format('M d, Y');
                } else {
                    $label = 'Beginning → Present';
                }
                break;
            case 'all':
            default:
                $start = null;
                $end   = null;
                $label = 'Beginning → Present';
                break;
        }
        return [$start, $end, $label];
    }
}
