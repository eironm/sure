<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Interactive Reports Dashboard
     */
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);

        $facilities = Facility::orderBy('name')->get(['id', 'name', 'category_id']);
        $categories = FacilityCategory::orderBy('name')->get(['id', 'name']);
        $clients    = User::where('role', 'guest')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.reports.index', array_merge($data, compact(
            'facilities',
            'categories',
            'clients'
        )));
    }

    /**
     * Print-Ready Document View (Replicates official letterhead reference attachments)
     */
    public function print(Request $request)
    {
        $data = $this->buildReportData($request);

        $reportTitle = $this->resolveReportTitle($request, $data);
        $dateRangeLabel = $data['dateRangeLabel'];
        $groupBy = $request->input('group_by', 'none');
        $viewType = $request->input('view_type');

        // Auto-switch view type based on active tab / report type if not explicitly set or if 'print' is passed
        if (!$viewType || $viewType === 'print' || !in_array($viewType, ['table', 'grouped', 'dispatch', 'cancellations', 'analytics'])) {
            if ($data['reportType'] === 'analytics') {
                $viewType = 'analytics';
            } elseif ($data['reportType'] === 'dispatch') {
                $viewType = 'dispatch';
            } elseif ($data['reportType'] === 'cancellations') {
                $viewType = 'cancellations';
            } elseif ($groupBy !== 'none') {
                $viewType = 'grouped';
            } else {
                $viewType = 'table';
            }
        }

        return view('admin.reports.print', array_merge($data, compact(
            'reportTitle',
            'dateRangeLabel',
            'groupBy',
            'viewType'
        )));
    }

    /**
     * Export Filtered Report Data to CSV
     */
    public function exportCsv(Request $request)
    {
        $data = $this->buildReportData($request);
        $reservations = $data['rawReservations'];
        $reportType   = $data['reportType'];
        $dateLabel    = $data['dateRangeLabel'];

        // Clean filename with clear prefix and date
        $typeNames = [
            'reservations'  => 'Reservations-Report',
            'income'        => 'Income-Statement',
            'facilities'    => 'Facility-Utilization',
            'clients'       => 'Client-Activity',
            'dispatch'      => 'Logistics-Dispatch',
            'cancellations' => 'Cancellations-Audit',
            'analytics'     => 'Executive-Analytics',
        ];

        $prefix = $typeNames[$reportType] ?? 'Facility-Report';
        $filename = 'SURE-' . $prefix . '-' . now()->format('Ymd-His') . '.csv';

        // Write CSV into memory buffer
        $fp = fopen('php://memory', 'r+');
        // Add UTF-8 BOM for Microsoft Excel & Windows compatibility
        fputs($fp, "\xEF\xBB\xBF");

        if ($reportType === 'income') {
            // Revenue & Financial Ledger
            fputcsv($fp, ['=== STATEMENT OF INCOME & REVENUE ===']);
            fputcsv($fp, ['Reporting Period', $dateLabel]);
            fputcsv($fp, ['Date Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($fp, []);
            fputcsv($fp, ['FINANCIAL OVERVIEW']);
            fputcsv($fp, ['Total Revenue Collected (PHP)', number_format($data['totalCollected'], 2, '.', '')]);
            fputcsv($fp, ['Cash Collections - OTC (PHP)', number_format($data['cashCollected'], 2, '.', '')]);
            fputcsv($fp, ['Digital Gateway Collections - PayMongo (PHP)', number_format($data['digitalCollected'], 2, '.', '')]);
            fputcsv($fp, ['Pending Cashier Receivables (PHP)', number_format($data['pendingReceivables'], 2, '.', '')]);
            fputcsv($fp, []);
            fputcsv($fp, ['REVENUE BY FACILITY']);
            fputcsv($fp, ['#', 'Facility Name', 'Total Bookings', 'Paid Revenue (PHP)', 'Pending Receivable (PHP)', 'Gross Value (PHP)', 'Share of Revenue (%)']);
            $fIdx = 1;
            foreach ($data['facilityBreakdown'] as $fb) {
                $share = $data['totalCollected'] > 0 ? round(($fb['paid_total'] / $data['totalCollected']) * 100, 1) : 0;
                fputcsv($fp, [
                    $fIdx++,
                    $fb['name'],
                    $fb['count'],
                    number_format($fb['paid_total'], 2, '.', ''),
                    number_format($fb['pending_total'], 2, '.', ''),
                    number_format($fb['total_revenue'], 2, '.', ''),
                    $share . '%',
                ]);
            }
            fputcsv($fp, []);
            fputcsv($fp, ['REVENUE BY CATEGORY']);
            fputcsv($fp, ['#', 'Category Name', 'Total Bookings', 'Paid Revenue (PHP)', 'Gross Value (PHP)']);
            $cIdx = 1;
            foreach ($data['categoryBreakdown'] as $cb) {
                fputcsv($fp, [
                    $cIdx++,
                    $cb['category'],
                    $cb['count'],
                    number_format($cb['revenue'], 2, '.', ''),
                    number_format($cb['total'], 2, '.', ''),
                ]);
            }
        } elseif ($reportType === 'facilities') {
            // Facility Utilization Leaderboard
            fputcsv($fp, ['#', 'Facility Name', 'Total Bookings', 'Unique Clients', 'Paid Revenue (PHP)', 'Pending Receivable (PHP)', 'Gross Total (PHP)']);
            $idx = 1;
            foreach ($data['facilityBreakdown'] as $fac) {
                fputcsv($fp, [
                    $idx++,
                    $fac['name'],
                    $fac['count'],
                    $fac['unique_clients'],
                    number_format($fac['paid_total'], 2, '.', ''),
                    number_format($fac['pending_total'], 2, '.', ''),
                    number_format($fac['total_revenue'], 2, '.', ''),
                ]);
            }
        } elseif ($reportType === 'clients') {
            // Client Activity Breakdown
            fputcsv($fp, ['#', 'Client Name', 'Email', 'Phone Number', 'Total Bookings', 'Paid Bookings', 'Total Spent (PHP)', 'Total Committed (PHP)', 'Latest Reservation Date']);
            $idx = 1;
            foreach ($data['clientBreakdown'] as $cb) {
                fputcsv($fp, [
                    $idx++,
                    $cb['name'],
                    $cb['email'],
                    $cb['phone'],
                    $cb['count'],
                    $cb['paid_count'],
                    number_format($cb['total_spent'], 2, '.', ''),
                    number_format($cb['total_committed'], 2, '.', ''),
                    $cb['latest_booking'] ? Carbon::parse($cb['latest_booking'])->format('Y-m-d') : 'N/A',
                ]);
            }
        } elseif ($reportType === 'dispatch') {
            // Operations & Setup Dispatch
            fputcsv($fp, [
                '#',
                'Reference Number',
                'Event Title',
                'Facility',
                'Start Date',
                'End Date',
                'Start Time',
                'End Time',
                'Schedule Type',
                'Client Name',
                'Client Phone',
                'Client Email',
                'Requested Logistical Items',
                'Total Equipment Units',
                'Logistics Fee (PHP)',
                'Booking Status',
            ]);

            $index = 1;
            foreach ($reservations as $res) {
                $arrs = is_string($res->arrangements) ? json_decode($res->arrangements, true) : $res->arrangements;
                $itemsSummary = [];
                $unitsCount = 0;
                if (is_array($arrs)) {
                    foreach ($arrs as $a) {
                        $qty = (int) ($a['quantity'] ?? 1);
                        $unitsCount += $qty;
                        $itemsSummary[] = ($a['name'] ?? 'Item') . ' (x' . $qty . ')';
                    }
                }
                $isOvernight = ($res->end_date && Carbon::parse($res->end_date)->gt(Carbon::parse($res->start_date))) || ($res->end_time < $res->start_time);

                fputcsv($fp, [
                    $index++,
                    $res->booking_reference ?? ('#' . $res->id),
                    $res->event_title ?? ($res->reason ?? ($res->purpose ?? 'Facility Booking')),
                    $res->facility->name ?? 'N/A',
                    Carbon::parse($res->start_date)->format('Y-m-d'),
                    Carbon::parse($res->end_date)->format('Y-m-d'),
                    Carbon::parse($res->start_time)->format('h:i A'),
                    Carbon::parse($res->end_time)->format('h:i A'),
                    $isOvernight ? 'Overnight' : 'Standard',
                    $res->user->name ?? ($res->reserved_by_name ?? 'Guest User'),
                    $res->user->phone_number ?? ($res->reserved_by_phone ?? 'N/A'),
                    $res->user->email ?? ($res->reserved_by_email ?? 'N/A'),
                    implode('; ', $itemsSummary) ?: 'None requested',
                    $unitsCount,
                    number_format($res->arrangements_total ?? 0, 2, '.', ''),
                    ucfirst($res->status),
                ]);
            }
        } elseif ($reportType === 'cancellations') {
            // Cancellations & Forfeitures Audit
            fputcsv($fp, [
                '#',
                'Reference Number',
                'Event Title',
                'Facility',
                'Client Name',
                'Client Phone',
                'Scheduled Date',
                'Cancelled Date',
                'Cancellation Reason',
                'Original Total Price (PHP)',
                'Amount Paid (PHP)',
                'Security Deposit Paid (PHP)',
                'Deposit Status',
                'Forfeited Retained (PHP)',
                'Refund Amount (PHP)',
                'Cancellation Type',
            ]);

            $index = 1;
            foreach ($reservations as $res) {
                $cancelledDate = $res->cancelled_at 
                    ? Carbon::parse($res->cancelled_at)->format('Y-m-d H:i') 
                    : ($res->updated_at ? $res->updated_at->format('Y-m-d H:i') : 'N/A');
                
                $depStatus = $res->security_deposit_status ?? 'N/A';
                $forfeited = ($depStatus === 'forfeited') ? (float)($res->security_deposit_paid ?? 0) : 0.00;
                $refunded  = ($depStatus === 'refunded')  ? (float)($res->security_deposit_paid ?? 0) : 0.00;

                fputcsv($fp, [
                    $index++,
                    $res->booking_reference ?? ('#' . $res->id),
                    $res->event_title ?? ($res->reason ?? ($res->purpose ?? 'Facility Booking')),
                    $res->facility->name ?? 'N/A',
                    $res->user->name ?? 'Guest User',
                    $res->user->phone_number ?? 'N/A',
                    Carbon::parse($res->start_date)->format('Y-m-d'),
                    $cancelledDate,
                    $res->cancellation_reason ?: ($res->status === 'expired_unpaid_cancelled' ? 'Expired unpaid deadline' : 'Cancelled by user/admin'),
                    number_format($res->total_price ?? 0, 2, '.', ''),
                    number_format($res->amount_paid ?? 0, 2, '.', ''),
                    number_format($res->security_deposit_paid ?? 0, 2, '.', ''),
                    ucfirst($depStatus),
                    number_format($forfeited, 2, '.', ''),
                    number_format($refunded, 2, '.', ''),
                    $res->status === 'expired_unpaid_cancelled' ? 'Lapsed Payment Window' : 'Manual Cancellation',
                ]);
            }
        } elseif ($reportType === 'analytics') {
            // Executive Analytics Summary
            fputcsv($fp, ['=== EXECUTIVE FACILITY & REVENUE STATISTICS ===']);
            fputcsv($fp, ['Period', $dateLabel]);
            fputcsv($fp, ['Date Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($fp, []);
            fputcsv($fp, ['KEY PERFORMANCE INDICATORS']);
            fputcsv($fp, ['Total Bookings', $data['totalCount']]);
            fputcsv($fp, ['Approved & Confirmed', $data['approvedCount']]);
            fputcsv($fp, ['Pending Review / Settlement', $data['pendingCount']]);
            fputcsv($fp, ['Cancelled / Expired Slots', $data['cancelledCount']]);
            fputcsv($fp, ['Total Revenue Collected (PHP)', number_format($data['totalCollected'], 2, '.', '')]);
            fputcsv($fp, ['Pending Receivables (PHP)', number_format($data['pendingReceivables'], 2, '.', '')]);
            fputcsv($fp, []);
            fputcsv($fp, ['MONTHLY BOOKINGS TREND (' . now()->year . ')']);
            fputcsv($fp, ['Month', 'Bookings Count', 'Paid Revenue (PHP)']);
            foreach ($data['monthlyTrend'] as $mKey => $mData) {
                fputcsv($fp, [$mData['full'], $mData['count'], number_format($mData['revenue'], 2, '.', '')]);
            }
            fputcsv($fp, []);
            fputcsv($fp, ['PAYMENT SETTLEMENT DISTRIBUTION']);
            fputcsv($fp, ['Status', 'Count', 'Percentage (%)']);
            foreach ($data['paymentDistribution'] as $pLabel => $pVal) {
                $pct = $data['totalCount'] > 0 ? round(($pVal / $data['totalCount']) * 100) : 0;
                fputcsv($fp, [$pLabel, $pVal, $pct . '%']);
            }
        } else {
            // Master Reservations Register (Default)
            fputcsv($fp, [
                '#',
                'Reference Number',
                'Event Title',
                'Client Name',
                'Client Email',
                'Client Phone',
                'Facility',
                'Category',
                'Start Date',
                'End Date',
                'Start Time',
                'End Time',
                'Schedule Type',
                'Booking Status',
                'Payment Status',
                'Payment Method',
                'Total Price (PHP)',
                'Amount Paid (PHP)',
                'Balance Due (PHP)',
                'Security Deposit Paid (PHP)',
                'Booking Purpose',
                'Date Submitted',
            ]);

            $index = 1;
            foreach ($reservations as $res) {
                $isOvernight = ($res->end_date && Carbon::parse($res->end_date)->gt(Carbon::parse($res->start_date))) || ($res->end_time < $res->start_time);

                fputcsv($fp, [
                    $index++,
                    $res->booking_reference ?? ('#' . $res->id),
                    $res->event_title ?? ($res->reason ?? ($res->purpose ?? 'Facility Booking')),
                    $res->user->name ?? 'Guest User',
                    $res->user->email ?? 'N/A',
                    $res->user->phone_number ?? 'N/A',
                    $res->facility->name ?? 'N/A',
                    $res->facility->category->name ?? 'Uncategorized',
                    Carbon::parse($res->start_date)->format('Y-m-d'),
                    Carbon::parse($res->end_date)->format('Y-m-d'),
                    Carbon::parse($res->start_time)->format('h:i A'),
                    Carbon::parse($res->end_time)->format('h:i A'),
                    $isOvernight ? 'Overnight' : 'Standard',
                    ucfirst($res->status),
                    ucfirst(str_replace('_', ' ', $res->payment_status ?? 'unpaid')),
                    strtoupper($res->payment_method ?? 'N/A'),
                    number_format($res->total_price ?? 0, 2, '.', ''),
                    number_format($res->amount_paid ?? 0, 2, '.', ''),
                    number_format($res->balance_due ?? ($res->total_price - ($res->amount_paid ?? 0)), 2, '.', ''),
                    number_format($res->security_deposit_paid ?? 0, 2, '.', ''),
                    $res->reason ?? ($res->purpose ?? ''),
                    $res->created_at ? $res->created_at->format('Y-m-d H:i:s') : '',
                ]);
            }
        }

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
     * Core Data Aggregation & Query Engine
     */
    private function buildReportData(Request $request): array
    {
        $reportType   = $request->input('report_type', 'reservations'); // 'reservations', 'income', 'facilities', 'clients', 'analytics', 'dispatch', 'cancellations'
        $datePreset   = $request->input('date_preset', 'all');
        $dateFrom     = $request->input('date_from');
        $dateTo       = $request->input('date_to');
        $facilityId   = $request->input('facility_id');
        $categoryId   = $request->input('category_id');
        $payStatus    = $request->input('payment_status');
        $status       = $request->input('status');
        $clientId     = $request->input('client_id');
        $groupBy      = $request->input('group_by', 'none');
        $search       = $request->input('search');

        // Advanced filter parameters
        $urgency      = $request->input('urgency', 'all'); // 'all', 'expiring_soon', 'overdue', 'deposit_paid'
        $timeShift    = $request->input('time_shift', 'all'); // 'all', 'day', 'night_overnight', 'weekday', 'weekend'
        $hasLogistics = $request->input('has_logistics', 'all'); // 'all', '1', '0'
        $clientType   = $request->input('client_type', 'all'); // 'all', 'internal', 'external'

        // Signatory parameters
        $preparedByName  = $request->input('prepared_by_name', auth()->user()->name ?? 'Administrator');
        $preparedByTitle = $request->input('prepared_by_title', 'Facility Operations & Administration');
        $approvedByName  = $request->input('approved_by_name', 'Dr. Roberto V. Sanchez');
        $approvedByTitle = $request->input('approved_by_title', 'Director, Auxiliary & Physical Services');

        // Smart defaults based on specialized report types or view types
        $reqViewType = $request->input('view_type');
        if (($reportType === 'cancellations' || $reqViewType === 'cancellations') && (empty($status) || $status === 'all')) {
            $status = 'cancelled_all';
        }
        if (($reportType === 'dispatch' || $reqViewType === 'dispatch') && $hasLogistics === 'all' && !$request->has('has_logistics')) {
            $hasLogistics = '1';
        }

        // Resolve dates
        [$startRange, $endRange, $dateRangeLabel] = $this->resolveDateRange($datePreset, $dateFrom, $dateTo);

        // Base query
        $query = Reservation::with(['user', 'facility.category', 'cashierSignedBy'])
            ->select('reservations.*');

        // Date filter (based on reservation start_date or created_at)
        if ($startRange && $endRange) {
            $startDateStr = $startRange->toDateString();
            $endDateStr   = $endRange->toDateString();
            $startDtStr   = $startRange->copy()->startOfDay()->toDateTimeString();
            $endDtStr     = $endRange->copy()->endOfDay()->toDateTimeString();

            $query->where(function ($q) use ($startDateStr, $endDateStr, $startDtStr, $endDtStr) {
                $q->whereBetween('start_date', [$startDateStr, $endDateStr])
                  ->orWhereBetween('created_at', [$startDtStr, $endDtStr]);
            });
        } elseif ($startRange) {
            $query->whereDate('start_date', '>=', $startRange->toDateString());
        } elseif ($endRange) {
            $query->whereDate('start_date', '<=', $endRange->toDateString());
        }

        // Facility filter
        if (!empty($facilityId) && $facilityId !== 'all') {
            $query->where('facility_id', $facilityId);
        }

        // Category filter
        if (!empty($categoryId) && $categoryId !== 'all') {
            $query->whereHas('facility', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // Payment status filter
        if (!empty($payStatus) && $payStatus !== 'all') {
            if ($payStatus === 'unpaid') {
                $query->whereIn('payment_status', ['unpaid', 'pending']);
            } else {
                $query->where('payment_status', $payStatus);
            }
        }

        // Reservation Status filter
        if (!empty($status) && $status !== 'all') {
            if ($status === 'cancelled_all') {
                $query->whereIn('status', ['cancelled', 'expired_unpaid_cancelled']);
            } else {
                $query->where('status', $status);
            }
        }

        // Client filter
        if (!empty($clientId) && $clientId !== 'all') {
            $query->where('user_id', $clientId);
        }

        // Payment Urgency / Aging filter
        if ($urgency === 'expiring_soon') {
            $query->whereIn('payment_status', ['unpaid', 'cash_pending', 'pending'])
                  ->whereNotIn('status', ['cancelled', 'expired_unpaid_cancelled', 'denied'])
                  ->whereNotNull('payment_deadline')
                  ->where('payment_deadline', '>=', now())
                  ->where('payment_deadline', '<=', now()->addHours(48));
        } elseif ($urgency === 'overdue') {
            $query->whereIn('payment_status', ['unpaid', 'cash_pending', 'pending'])
                  ->whereNotIn('status', ['cancelled', 'expired_unpaid_cancelled', 'denied'])
                  ->whereNotNull('payment_deadline')
                  ->where('payment_deadline', '<', now());
        } elseif ($urgency === 'deposit_paid') {
            $query->where(function ($q) {
                $q->where('security_deposit_paid', '>', 0)
                  ->orWhere('payment_status', 'partially_paid');
            });
        }

        // Time shift filter
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        if ($timeShift === 'day') {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereColumn('start_date', 'end_date')
                        ->orWhereNull('end_date');
                })
                ->where('start_time', '>=', '05:00:00')
                ->where('start_time', '<', '18:00:00')
                ->where('end_time', '<=', '19:00:00')
                ->whereColumn('end_time', '>=', 'start_time');
            });
        } elseif ($timeShift === 'night_overnight') {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('end_date')
                        ->whereColumn('end_date', '>', 'start_date');
                })
                ->orWhere('start_time', '>=', '18:00:00')
                ->orWhere('end_time', '>', '19:00:00')
                ->orWhereColumn('end_time', '<', 'start_time');
            });
        } elseif ($timeShift === 'weekday') {
            if ($isSqlite) {
                $query->whereRaw("strftime('%w', start_date) BETWEEN '1' AND '5'");
            } else {
                $query->whereRaw("DAYOFWEEK(start_date) BETWEEN 2 AND 6");
            }
        } elseif ($timeShift === 'weekend') {
            if ($isSqlite) {
                $query->whereRaw("strftime('%w', start_date) IN ('0', '6')");
            } else {
                $query->whereRaw("DAYOFWEEK(start_date) IN (1, 7)");
            }
        }

        // Logistics Requested Filter (Database-agnostic: handles MySQL native JSON vs SQLite TEXT)
        if ($hasLogistics === '1') {
            if ($isSqlite) {
                $query->whereNotNull('arrangements')
                      ->whereNotIn('arrangements', ['', '[]', 'null']);
            } else {
                $query->whereNotNull('arrangements')
                      ->whereRaw("JSON_TYPE(arrangements) = 'ARRAY' AND JSON_LENGTH(arrangements) > 0");
            }
        } elseif ($hasLogistics === '0') {
            if ($isSqlite) {
                $query->where(function ($q) {
                    $q->whereNull('arrangements')
                      ->orWhereIn('arrangements', ['', '[]', 'null']);
                });
            } else {
                $query->where(function ($q) {
                    $q->whereNull('arrangements')
                      ->orWhereRaw("JSON_TYPE(arrangements) != 'ARRAY' OR JSON_LENGTH(arrangements) = 0");
                });
            }
        }

        // Client Affiliation Filter
        if ($clientType === 'internal') {
            $query->where('client_type_snapshot', 'internal');
        } elseif ($clientType === 'external') {
            $query->where('client_type_snapshot', 'external');
        }

        // Search term (Column in database is 'reason', not 'purpose')
        if (!empty($search)) {
            $term = '%' . trim($search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('booking_reference', 'like', $term)
                  ->orWhere('reason', 'like', $term)
                  ->orWhere('event_title', 'like', $term)
                  ->orWhere('cancellation_reason', 'like', $term)
                  ->orWhereHas('user', function ($uq) use ($term) {
                      $uq->where('name', 'like', $term)
                         ->orWhere('email', 'like', $term);
                  })
                  ->orWhereHas('facility', function ($fq) use ($term) {
                      $fq->where('name', 'like', $term);
                  });
            });
        }

        // Clone query for metrics calculations before ordering
        $rawReservations = (clone $query)->orderBy('start_date', 'desc')->orderBy('start_time', 'asc')->get();

        // High-level KPI aggregates
        $totalCount    = $rawReservations->count();
        $approvedCount = $rawReservations->where('status', 'approved')->count();
        $pendingCount  = $rawReservations->where('status', 'pending')->count();
        $cancelledCount= $rawReservations->whereIn('status', ['cancelled', 'expired_unpaid_cancelled'])->count();
        $deniedCount   = $rawReservations->where('status', 'denied')->count();

        // Financial calculations
        $totalPriceSum    = (float) $rawReservations->where('status', '!=', 'cancelled')->sum('total_price');
        $paidReservations = $rawReservations->filter(function ($r) {
            return $r->payment_status === 'paid' || $r->status === 'approved';
        });

        $totalCollected = (float) $rawReservations->sum(function ($r) {
            if ($r->status === 'cancelled') return 0;
            if ($r->payment_status === 'paid') return (float) $r->total_price;
            return (float) ($r->amount_paid ?? 0);
        });

        $pendingReceivables = (float) $rawReservations->sum(function ($r) {
            if (in_array($r->status, ['cancelled', 'denied'])) return 0;
            if ($r->payment_status === 'paid') return 0;
            return (float) ($r->balance_due ?? ($r->total_price - ($r->amount_paid ?? 0)));
        });

        $cashCollected = (float) $rawReservations->where('payment_method', 'cash')->where('payment_status', 'paid')->sum('total_price');
        $digitalCollected = (float) $rawReservations->where('payment_method', '!=', 'cash')->where('payment_status', 'paid')->sum('total_price');

        // Logistics Dispatch Metrics
        $totalLogisticsUnits = 0;
        $dispatchBookingsCount = 0;
        foreach ($rawReservations as $r) {
            $arrs = is_string($r->arrangements) ? json_decode($r->arrangements, true) : $r->arrangements;
            if (is_array($arrs) && !empty($arrs)) {
                $dispatchBookingsCount++;
                foreach ($arrs as $item) {
                    $totalLogisticsUnits += (int) ($item['quantity'] ?? 1);
                }
            }
        }

        // Cancellations & Forfeiture Metrics
        $totalForfeitedDeposits = 0.0;
        $totalRefundedAmount = 0.0;
        $cancelledBookingsList = $rawReservations->filter(function ($r) {
            return in_array($r->status, ['cancelled', 'expired_unpaid_cancelled']);
        });
        foreach ($cancelledBookingsList as $cr) {
            if ($cr->security_deposit_status === 'forfeited') {
                $totalForfeitedDeposits += (float) ($cr->security_deposit_paid ?? 0);
            } elseif ($cr->security_deposit_status === 'refunded') {
                $totalRefundedAmount += (float) ($cr->security_deposit_paid ?? 0);
            }
        }

        // Grouping Strategy
        $groupedData = $this->groupReservations($rawReservations, $groupBy);

        // Facility Breakdown ("Who reserved gym vs auditorium")
        $facilityBreakdown = $rawReservations->groupBy(function ($r) {
            return $r->facility->name ?? 'Unassigned Facility';
        })->map(function ($items, $facilityName) {
            $count = $items->count();
            $paidTotal = $items->where('payment_status', 'paid')->sum('total_price');
            $pendingTotal = $items->whereIn('payment_status', ['unpaid', 'cash_pending', 'partially_paid', 'pending'])->sum('total_price');
            $uniqueClients = $items->pluck('user_id')->unique()->count();
            return [
                'name'           => $facilityName,
                'count'          => $count,
                'paid_total'     => $paidTotal,
                'pending_total'  => $pendingTotal,
                'total_revenue'  => $items->sum('total_price'),
                'unique_clients' => $uniqueClients,
                'items'          => $items,
            ];
        })->sortByDesc('count');

        // Client Activity Breakdown ("How many reservations per client")
        $clientBreakdown = $rawReservations->groupBy('user_id')->map(function ($items) {
            $user = $items->first()->user;
            return [
                'user'            => $user,
                'name'            => $user->name ?? 'Guest User',
                'email'           => $user->email ?? 'N/A',
                'phone'           => $user->phone_number ?? 'N/A',
                'count'           => $items->count(),
                'paid_count'      => $items->where('payment_status', 'paid')->count(),
                'total_spent'     => $items->where('payment_status', 'paid')->sum('total_price'),
                'total_committed' => $items->sum('total_price'),
                'latest_booking'  => $items->max('start_date'),
                'reservations'    => $items,
            ];
        })->sortByDesc('count');

        // Monthly Trend (Jan - Dec / Periods for Bar Chart)
        $monthlyTrend = $this->buildMonthlyTrend($rawReservations);

        // Payment status distribution for Donut Chart
        $paymentDistribution = [
            'Paid'          => $rawReservations->where('payment_status', 'paid')->count(),
            'Cash Pending'  => $rawReservations->where('payment_status', 'cash_pending')->count(),
            'Partial Paid'  => $rawReservations->where('payment_status', 'partially_paid')->count(),
            'Unpaid/Pending'=> $rawReservations->whereIn('payment_status', ['unpaid', 'pending', null])->count(),
        ];

        // Category breakdown
        $categoryBreakdown = $rawReservations->groupBy(function ($r) {
            return $r->facility->category->name ?? 'General / Other';
        })->map(function ($items, $catName) {
            return [
                'category' => $catName,
                'count'    => $items->count(),
                'revenue'  => $items->where('payment_status', 'paid')->sum('total_price'),
                'total'    => $items->sum('total_price'),
            ];
        })->sortByDesc('revenue');

        // Generate Analytical Insight Text (for Attachment 4)
        $insightText = $this->generateInsightText(
            $totalCount,
            $totalCollected,
            $pendingReceivables,
            $facilityBreakdown,
            $monthlyTrend,
            $dateRangeLabel
        );

        return [
            'reportType'             => $reportType,
            'datePreset'             => $datePreset,
            'dateFrom'               => $dateFrom,
            'dateTo'                 => $dateTo,
            'dateRangeLabel'         => $dateRangeLabel,
            'facilityId'             => $facilityId,
            'categoryId'             => $categoryId,
            'payStatus'              => $payStatus,
            'status'                 => $status,
            'clientId'               => $clientId,
            'groupBy'                => $groupBy,
            'search'                 => $search,
            'urgency'                => $urgency,
            'timeShift'              => $timeShift,
            'hasLogistics'           => $hasLogistics,
            'clientType'             => $clientType,
            'preparedByName'         => $preparedByName,
            'preparedByTitle'        => $preparedByTitle,
            'approvedByName'         => $approvedByName,
            'approvedByTitle'        => $approvedByTitle,
            'rawReservations'        => $rawReservations,
            'groupedData'            => $groupedData,
            'totalCount'             => $totalCount,
            'approvedCount'          => $approvedCount,
            'pendingCount'           => $pendingCount,
            'cancelledCount'         => $cancelledCount,
            'deniedCount'            => $deniedCount,
            'totalPriceSum'          => $totalPriceSum,
            'totalCollected'         => $totalCollected,
            'pendingReceivables'     => $pendingReceivables,
            'cashCollected'          => $cashCollected,
            'digitalCollected'       => $digitalCollected,
            'facilityBreakdown'      => $facilityBreakdown,
            'clientBreakdown'        => $clientBreakdown,
            'monthlyTrend'           => $monthlyTrend,
            'paymentDistribution'    => $paymentDistribution,
            'categoryBreakdown'      => $categoryBreakdown,
            'insightText'            => $insightText,
            'totalLogisticsUnits'    => $totalLogisticsUnits,
            'dispatchBookingsCount'  => $dispatchBookingsCount,
            'totalForfeitedDeposits' => $totalForfeitedDeposits,
            'totalRefundedAmount'    => $totalRefundedAmount,
        ];
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
                $label = 'This Week (' . $start->format('M d') . ' - ' . $end->format('M d, Y') . ')';
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

            case 'this_quarter':
                $start = $now->copy()->startOfQuarter();
                $end   = $now->copy()->endOfQuarter();
                $quarter = ceil($now->month / 3);
                $label = 'Q' . $quarter . ' ' . $now->year . ' (' . $start->format('M d') . ' - ' . $end->format('M d, Y') . ')';
                break;

            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                $label = 'Annual ' . $now->year;
                break;

            case 'custom':
                try {
                    $start = (!empty($from) && strtotime($from)) ? Carbon::parse($from)->startOfDay() : null;
                } catch (\Throwable) {
                    $start = null;
                }
                try {
                    $end = (!empty($to) && strtotime($to)) ? Carbon::parse($to)->endOfDay() : null;
                } catch (\Throwable) {
                    $end = null;
                }

                if ($start && $end) {
                    $label = $start->format('M d, Y') . ' - ' . $end->format('M d, Y');
                } elseif ($start) {
                    $label = 'From ' . $start->format('M d, Y');
                } elseif ($end) {
                    $label = 'Until ' . $end->format('M d, Y');
                } else {
                    $label = 'All Records';
                }
                break;

            case 'all':
            default:
                $start = null;
                $end   = null;
                $label = 'All Records';
                break;
        }

        return [$start, $end, $label];
    }

    /**
     * Group reservations collection based on group strategy
     */
    private function groupReservations(Collection $reservations, string $groupBy): Collection
    {
        switch ($groupBy) {
            case 'facility':
                return $reservations->groupBy(function ($r) {
                    return $r->facility->name ?? 'Unassigned Facility';
                });

            case 'payment_status':
                return $reservations->groupBy(function ($r) {
                    $st = $r->payment_status ?? 'unpaid';
                    if ($st === 'paid') return 'Paid Bookings';
                    if ($st === 'cash_pending') return 'Cash Payment Pending (Over-The-Counter)';
                    if ($st === 'partially_paid') return 'Partially Paid (Deposit Received)';
                    return 'Unpaid / Pending Verification';
                });

            case 'month':
                return $reservations->groupBy(function ($r) {
                    $d = Carbon::parse($r->start_date);
                    return $d->format('F Y') . ' (' . $d->copy()->startOfMonth()->format('m/d/Y') . ' - ' . $d->copy()->endOfMonth()->format('m/d/Y') . ')';
                });

            case 'status':
                return $reservations->groupBy(function ($r) {
                    return ucfirst($r->status) . ' Reservations';
                });

            case 'client':
                return $reservations->groupBy(function ($r) {
                    return $r->user->name ?? 'Guest User #' . $r->user_id;
                });

            case 'none':
            default:
                return collect(['All Records' => $reservations]);
        }
    }

    /**
     * Build monthly breakdown array for charts & tables
     */
    private function buildMonthlyTrend(Collection $reservations): array
    {
        $months = [];
        $currentYear = now()->year;

        for ($m = 1; $m <= 12; $m++) {
            $monthDate = Carbon::createFromDate($currentYear, $m, 1);
            $monthKey = $monthDate->format('M');
            $months[$monthKey] = [
                'label'   => $monthDate->format('M'),
                'full'    => $monthDate->format('F'),
                'count'   => 0,
                'revenue' => 0.0,
            ];
        }

        foreach ($reservations as $r) {
            $date = Carbon::parse($r->start_date);
            if ($date->year === $currentYear) {
                $monthKey = $date->format('M');
                if (isset($months[$monthKey])) {
                    $months[$monthKey]['count']++;
                    if ($r->payment_status === 'paid') {
                        $months[$monthKey]['revenue'] += (float) $r->total_price;
                    }
                }
            }
        }

        return $months;
    }

    /**
     * Generate dynamic insightful narrative for executive analytics report (Attachment 4)
     */
    private function generateInsightText(
        int $totalCount,
        float $totalCollected,
        float $pendingReceivables,
        Collection $facilityBreakdown,
        array $monthlyTrend,
        string $dateRangeLabel
    ): string {
        if ($totalCount === 0) {
            return 'No reservations were recorded during the selected period (' . $dateRangeLabel . '). Marketing outreach and promotional scheduling may be scheduled to stimulate campus facility utilization.';
        }

        $topFacility = $facilityBreakdown->first();
        $topFacilityName = $topFacility['name'] ?? 'Primary Campus Facilities';
        $topFacilityCount = $topFacility['count'] ?? 0;
        $topFacilityShare = $totalCount > 0 ? round(($topFacilityCount / $totalCount) * 100) : 0;

        // Find peak month
        $peakMonth = collect($monthlyTrend)->sortByDesc('count')->first();
        $peakMonthName = $peakMonth['full'] ?? 'this term';
        $peakMonthCount = $peakMonth['count'] ?? 0;

        $paidPercentage = ($totalCollected + $pendingReceivables) > 0
            ? round(($totalCollected / ($totalCollected + $pendingReceivables)) * 100)
            : 100;

        $insight = "During the period ({$dateRangeLabel}), a total of {$totalCount} reservations were processed across university venues. ";
        $insight .= "<strong>{$topFacilityName}</strong> emerged as the most requested facility with {$topFacilityCount} bookings ({$topFacilityShare}% of total demand). ";
        if ($peakMonthCount > 0) {
            $insight .= "Activity peaked in {$peakMonthName} with {$peakMonthCount} bookings. ";
        }
        $insight .= "Financial settlement stands at <strong>" . number_format($paidPercentage) . "%</strong> collected (₱" . number_format($totalCollected, 2) . "), with ₱" . number_format($pendingReceivables, 2) . " pending cashier settlement or deadline enforcement.";

        return $insight;
    }

    private function resolveReportTitle(Request $request, array $data): string
    {
        $reportType = $data['reportType'];
        $viewType   = $request->input('view_type');
        $groupBy    = $data['groupBy'];
        $dateLabel  = $data['dateRangeLabel'];

        if ($reportType === 'dispatch' || $viewType === 'dispatch') {
            return 'LOGISTICS & SETUP DISPATCH CHECKLIST';
        }

        if ($reportType === 'cancellations' || $viewType === 'cancellations') {
            return 'CANCELLATIONS & FORFEITURE AUDIT REPORT';
        }

        if ($reportType === 'analytics' || $viewType === 'analytics') {
            return 'FACILITY & REVENUE STATISTICS DASHBOARD';
        }

        if ($reportType === 'income') {
            return 'CERTIFIED STATEMENT OF INCOME & REVENUE';
        }

        if ($reportType === 'clients') {
            return 'SUMMARY OF RESERVATIONS BY CLIENT';
        }

        if ($reportType === 'facilities') {
            return 'FACILITY UTILIZATION & RESERVATIONS AUDIT';
        }

        if ($groupBy === 'facility') {
            return 'RESERVATION LIST GROUPED BY FACILITY';
        }

        if ($groupBy === 'payment_status') {
            return 'RESERVATIONS GROUPED BY PAYMENT STATUS';
        }

        if ($groupBy === 'month') {
            return 'RESERVATION AUDIT (' . strtoupper($dateLabel) . ')';
        }

        return 'CERTIFIED LIST OF FACILITY RESERVATIONS';
    }
}
