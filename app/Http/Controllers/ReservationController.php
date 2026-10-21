<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\FacilityTimeSlot;
use App\Models\SlotReservation;
use App\Models\SystemSetting;
use App\Models\User;
use App\Notifications\ReservationStatusChanged;
use App\Services\ReservationAvailability;
use App\Services\PaymentDeadlineService;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Store a new reservation with robust validation and atomic transactions.
     * Handles the full 5-step wizard: event details, venue & arrangements,
     * contact & endorsement, review & quotation, payment method.
     */
    public function store(Request $request)
    {
        // Build allowed payment methods from whitelabel toggles
        $allowedPaymentMethods = ['waived']; // always allow waived (internal users)
        if (SystemSetting::getValue('payment_method_cash_enabled', '1'))     $allowedPaymentMethods[] = 'cash';
        if (SystemSetting::getValue('payment_method_paymongo_enabled', '1')) $allowedPaymentMethods[] = 'paymongo';
        // Fallback: if admin disabled everything, still allow cash at minimum
        if (count($allowedPaymentMethods) === 1) $allowedPaymentMethods[] = 'cash';
        $paymentMethodRule = 'required|in:' . implode(',', $allowedPaymentMethods);

        // Auto-advance end_date for overnight events submitted on the same calendar day
        if ($request->filled(['start_date', 'end_date', 'start_time', 'end_time'])) {
            if ($request->start_date === $request->end_date && \Carbon\Carbon::parse($request->end_time)->lt(\Carbon\Carbon::parse($request->start_time))) {
                $request->merge([
                    'end_date' => \Carbon\Carbon::parse($request->start_date)->addDay()->toDateString()
                ]);
            }
        }

        $validated = $request->validate([
            // Step 1: Event Details
            'event_title'       => 'required|string|max:50|regex:/^[\p{L}\p{N}\s.,\-\'\"\/()&]+$/u',
            'start_date'        => 'required|date_format:Y-m-d|after_or_equal:today|before_or_equal:+1 year',
            'end_date'          => 'required|date_format:Y-m-d|after_or_equal:start_date|before_or_equal:+1 year',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $sd = $request->input('start_date');
                    $ed = $request->input('end_date');
                    $st = $request->input('start_time');
                    if ($sd && $ed && $st && $value) {
                        $sDt = \Carbon\Carbon::parse("$sd $st");
                        $eDt = \Carbon\Carbon::parse("$ed $value");
                        if ($eDt->lte($sDt)) {
                            $fail('The reservation end date & time must be after the start date & time.');
                        }
                    }
                },
            ],
            'number_of_people'  => 'required|integer|min:1',
            'reason'            => 'required|string|max:400|regex:/^[\p{L}\p{N}\s.,\-\'\"\/()&?!:;\r\n]+$/u',
            'documents'         => 'nullable|array|max:5',
            'documents.*'       => 'file|mimes:pdf,doc,docx,png,jpg,jpeg,webp|max:10240',

            // Step 2: Venue & Arrangements
            'facility_id'                  => 'required|integer|exists:facilities,id',
            'arrangements'                 => 'nullable|json',

            // Step 3: Contact & Endorsement
            'reserved_by_name'  => 'required|string|max:255',
            'reserved_by_email' => 'required|email|max:255',
            'reserved_by_phone' => ['required', 'string', 'max:50', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            'end_user_type'     => 'required|in:college,department,organization,personal',
            'end_user_name'     => 'required|string|max:255',
            'endorsed_by_name'     => 'nullable|string|max:255',
            'endorsed_by_position' => 'nullable|string|max:255',

            // Step 5: Payment (dynamically built from enabled settings)
            'payment_method'    => $paymentMethodRule,

            // Agreement
            'agreement_accepted' => 'required|accepted',
        ], [
            'reserved_by_phone.regex'   => 'Please enter a valid contact number with country code (e.g., +639171234567 or 09171234567).',
            'event_title.max'           => 'The event title may not exceed 50 characters.',
            'event_title.regex'         => 'The event title must not contain emojis or special symbols.',
            'reason.max'                => 'The purpose of reservation may not exceed 400 characters.',
            'reason.regex'              => 'The purpose of reservation must not contain emojis or special symbols.',
            'start_date.before_or_equal'=> 'Reservations can only be scheduled up to 1 year in advance.',
            'end_date.before_or_equal'  => 'Reservations can only be scheduled up to 1 year in advance.',
            'documents.max'             => 'You may only upload a maximum of 5 supporting documents.',
            'documents.*.max'           => 'Each supporting document may not exceed 10MB.',
            'documents.*.mimes'         => 'Supporting documents must be PDF, Word (.doc, .docx), PNG, JPG, or WEBP files.',
        ]);

        if (!PaymentDeadlineService::isDateEligible($validated['start_date'])) {
            $earliest = PaymentDeadlineService::earliestEligibleDate()->format('F j, Y');
            return redirect()->back()->withInput()->withErrors(['start_date' => "This date is not yet available for reservation under the current payment deadline policy. The earliest available booking date is {$earliest}."]);
        }

        // Parse arrangements from JSON string
        $arrangementSelections = [];
        if (!empty($validated['arrangements'])) {
            $arrangementSelections = json_decode($validated['arrangements'], true) ?? [];
        }

        $allowMultipleAdvanceReservations = (bool) SystemSetting::getValue('allow_multiple_advance_reservations', '0');

        if (!$allowMultipleAdvanceReservations) {
            // DOUBLE-BOOKING SAFEGUARD
            $now         = now();
            $today       = $now->toDateString();
            $currentTime = $now->format('H:i:s');

            $existingReservation = Reservation::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($today, $currentTime) {
                    $query->where('end_date', '>', $today)
                          ->orWhere(function ($q) use ($today, $currentTime) {
                              $q->where('end_date', $today)
                                ->where('end_time', '>', $currentTime);
                          });
                })
                ->first();

            if ($existingReservation) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'You already have an active reservation (Reference: ' . $existingReservation->booking_reference . '). Please wait for it to be completed or cancelled before making a new booking.');
            }
        }

        $facility = Facility::findOrFail($validated['facility_id']);

        // 1. Final Backend Availability Check
        $availability = ReservationAvailability::checkAvailability(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        if (!$availability['available']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Conflict Detected: ' . $availability['reason']);
        }

        // 2. Capacity Advisory Check (Advisory instead of blocking)
        $capacityAdvisory = null;
        if ($validated['number_of_people'] > $facility->capacity) {
            $capacityAdvisory = "Capacity Advisory: Expected attendees ({$validated['number_of_people']}) exceeds standard facility capacity ({$facility->capacity}). The organizer accepted responsibility for crowd control and spatial constraints.";
        }

        try {
            return DB::transaction(function () use ($validated, $facility, $arrangementSelections, $request, $capacityAdvisory) {
                // Row lock for concurrent booking prevention
                Facility::whereKey($facility->id)->lockForUpdate()->first();

                $recheck = ReservationAvailability::checkAvailability(
                    $facility,
                    $validated['start_date'],
                    $validated['end_date'],
                    $validated['start_time'],
                    $validated['end_time']
                );

                if (!$recheck['available']) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Conflict Detected: ' . $recheck['reason']);
                }

                // 3. Determine client type and fee waiver
                $user       = Auth::user();
                $clientType = $user->client_type ?? 'external';
                $isInternal = $clientType === 'internal';

                // 4. Calculate Full Quotation
                $quotation = ReservationAvailability::calculateQuotation(
                    $facility,
                    $validated['start_date'],
                    $validated['end_date'],
                    $validated['start_time'],
                    $validated['end_time'],
                    $arrangementSelections,
                    $isInternal
                );

                $totalPrice     = $quotation['grand_total'];
                $isWaived       = $quotation['is_waived'];
                $paymentMethod  = $validated['payment_method'];

                // 5. Determine payment deadline & initial status
                $createdAt = now();

                if ($isWaived) {
                    // Internal waived: no payment needed
                    $paymentDeadline = null;
                    $paymentStatus   = 'paid';
                } elseif ($paymentMethod === 'cash') {
                    $paymentDeadline = PaymentDeadlineService::calculate($createdAt, Carbon::parse($validated["start_date"] . " " . $validated["start_time"]));
                    $paymentStatus   = 'cash_pending';
                } else {
                    $paymentDeadline = PaymentDeadlineService::calculate($createdAt, Carbon::parse($validated["start_date"] . " " . $validated["start_time"]));
                    $paymentStatus   = 'pending';
                }

                // 6. Determine endorsement status
                $requireEndorsement = $isInternal
                    && (bool) SystemSetting::getValue('require_endorsement_internal', '1');
                $endorsementStatus = 'not_required';
                if ($requireEndorsement && !empty($validated['endorsed_by_name'])) {
                    $endorsementStatus = 'pending';
                } elseif ($requireEndorsement) {
                    $endorsementStatus = 'pending';
                }

                // 7. Security deposit
                $securityDeposit = $quotation['security_deposit'];
                $depositStatus   = ($securityDeposit > 0 && !$isWaived) ? 'pending' : 'not_required';

                // 8. Create Main Reservation
                $reservation = Reservation::create([
                    'user_id'              => Auth::id(),
                    'facility_id'          => $facility->id,
                    'start_date'           => $validated['start_date'],
                    'end_date'             => $validated['end_date'],
                    'start_time'           => $validated['start_time'],
                    'end_time'             => $validated['end_time'],
                    'reason'               => $validated['reason'],
                    'number_of_people'     => $validated['number_of_people'],
                    'status'               => 'pending',
                    'total_price'          => $totalPrice,
                    'booking_reference'    => ReservationAvailability::generateBookingReference(),
                    'payment_method'       => $isWaived ? 'waived' : $paymentMethod,
                    'payment_status'       => $paymentStatus,
                    'payment_deadline'     => $paymentDeadline,
                    'admin_remarks'        => $capacityAdvisory,

                    // New fields
                    'event_title'          => $validated['event_title'],
                    'end_user_type'        => $validated['end_user_type'],
                    'end_user_name'        => $validated['end_user_name'],
                    'reserved_by_name'     => $validated['reserved_by_name'],
                    'reserved_by_email'    => $validated['reserved_by_email'],
                    'reserved_by_phone'    => $validated['reserved_by_phone'],
                    'endorsed_by_name'     => $validated['endorsed_by_name'] ?? null,
                    'endorsed_by_position' => $validated['endorsed_by_position'] ?? null,
                    'endorsement_status'   => $endorsementStatus,
                    'client_type_snapshot' => $clientType,
                    'is_fee_waived'        => $isWaived,
                    'security_deposit'     => $securityDeposit,
                    'security_deposit_status' => $depositStatus,
                    'arrangements'         => $quotation['arrangements'],
                    'quotation_breakdown'  => $quotation['breakdown'],
                    'agreement_accepted'   => true,
                    'agreement_accepted_at'=> now(),
                ]);

                // 8b. Process and attach uploaded supporting documents
                if ($request->hasFile('documents')) {
                    $targetDir = public_path('uploads/reservation_documents');
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }

                    foreach ($request->file('documents') as $file) {
                        if ($file && $file->isValid()) {
                            $origName = $file->getClientOriginalName();
                            $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                            $filename = \Illuminate\Support\Str::uuid() . '.' . $ext;
                            $file->move($targetDir, $filename);
                            $storedPath = 'uploads/reservation_documents/' . $filename;

                            $reservation->documents()->create([
                                'original_name' => $origName,
                                'file_path'     => $storedPath,
                                'file_type'     => $file->getClientMimeType() ?: 'application/octet-stream',
                                'file_size'     => @filesize($targetDir . DIRECTORY_SEPARATOR . $filename) ?: 0,
                                'document_type' => 'supporting_document',
                            ]);
                        }
                    }
                }

                // 9. Link Slot Reservations
                $this->attachSlotReservations($reservation, $facility);

                // 10. Notify Admins & User (never crash a new reservation
                // because the mail transport is down — log and continue)
                $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
                if ($admins->isNotEmpty()) {
                    try {
                        Notification::send($admins, new ReservationStatusChanged($reservation, 'submitted'));
                    } catch (\Exception $e) {
                        // A down mail/broadcast transport must never roll back a
                        // reservation that has already been persisted.
                        Log::warning('Admin reservation notification failed', [
                            'error'          => $e->getMessage(),
                            'reservation_id' => $reservation->id,
                            'reservation_ref'=> $reservation->booking_reference,
                        ]);
                    }
                }

                if ($isWaived) {
                    // Internal waived: notify about submission (no payment needed)
                    safe_notify(Auth::user(), new ReservationStatusChanged($reservation, 'submitted'));

                    return redirect()->route('guest.reservation.confirmation', $reservation)
                        ->with('success', "Reservation submitted! Fees waived for internal users. Ref: {$reservation->booking_reference}. Our team will review your request shortly.");
                }

                // Notify User about payment status
                if ($paymentMethod === 'cash') {
                    safe_notify(Auth::user(), new ReservationStatusChanged($reservation, 'cash_pending'));
                } else {
                    safe_notify(Auth::user(), new ReservationStatusChanged($reservation, 'payment_pending'));
                }

                // 11. Branch by payment method
                if ($paymentMethod === 'cash') {
                    return redirect()->route('guest.reservation.confirmation', $reservation)
                        ->with('success', "Reservation submitted! Please pay at the cashier by {$reservation->deadlineForHumans()}. Ref: {$reservation->booking_reference}");
                }

                // PayMongo
                $payMongo = new \App\Services\PayMongoService();

                if ($totalPrice > 0 && $payMongo->isConfigured()) {
                    $checkout = $payMongo->createCheckoutSession([
                        'amount'      => (int) round($totalPrice * 100),
                        'description' => "Reservation for {$facility->name} — {$reservation->event_title}",
                        'remarks'     => "Date: {$reservation->start_date} | Ref: {$reservation->booking_reference}",
                        'reference'   => $reservation->booking_reference,
                        'success_url' => route('guest.reservation.confirmation', ['reservation' => $reservation->id, 'paid' => 1]),
                        'cancel_url'  => route('guest.dashboard', ['payment' => 'cancelled', 'ref' => $reservation->booking_reference]),
                    ]);

                    if ($checkout['success']) {
                        $reservation->update(['payment_id' => $checkout['checkout_session_id']]);
                        return redirect()->away($checkout['checkout_url']);
                    }

                    Log::error('PayMongo checkout failed', ['error' => $checkout['error'] ?? 'Unknown']);
                    return redirect()->route('guest.dashboard')
                        ->with('error', 'Booking created, but payment initialization failed. Please try paying from your dashboard.');
                }

                if ($totalPrice > 0) {
                    // SECURITY: gateway unconfigured must NOT grant a free 'paid'
                    // status on a priced booking. Fall back to cash payment with
                    // a deadline so the cashier still collects.
                    $reservation->update([
                        'payment_method'   => 'cash',
                        'payment_status'   => 'cash_pending',
                        'payment_deadline' => PaymentDeadlineService::forReservation($reservation),
                    ]);
                    Log::warning('PayMongo not configured — priced reservation routed to cash payment.', [
                        'reservation_id' => $reservation->id,
                    ]);
                    return redirect()->route('guest.reservation.confirmation', $reservation)
                        ->with('success', "Reservation submitted! Online payment is currently unavailable — please pay at the cashier by {$reservation->deadlineForHumans()}. Ref: {$reservation->booking_reference}");
                }

                // Genuinely free reservation (price = 0)
                $reservation->update(['payment_status' => 'paid']);
                return redirect()->route('guest.dashboard')
                    ->with('success', "Reservation submitted! Reference: {$reservation->booking_reference}. Our team will review your request shortly.");
            });
        } catch (\Exception $e) {
            Log::error('Reservation Failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'payload' => $validated,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred while processing your booking. Please try again.');
        }
    }

    /**
     * Re-initiate a PayMongo checkout for an abandoned digital payment.
     */
    public function resumePayment(Reservation $reservation)
    {
        $this->authorizeReservationManage($reservation);

        if (!$reservation->isPendingPayment() || $reservation->payment_method !== 'paymongo') {
            return redirect()->route('guest.dashboard')->with('error', 'This reservation cannot be resumed.');
        }

        $payMongo = new \App\Services\PayMongoService();
        $checkout = $payMongo->createCheckoutSession([
            'amount'      => (int) round($reservation->total_price * 100),
            'description' => "Reservation for {$reservation->facility->name}",
            'remarks'     => "Date: {$reservation->start_date} | Ref: {$reservation->booking_reference}",
            'reference'   => $reservation->booking_reference,
            'success_url' => route('guest.reservation.confirmation', ['reservation' => $reservation->id, 'paid' => 1]),
            'cancel_url'  => route('guest.dashboard', ['payment' => 'cancelled', 'ref' => $reservation->booking_reference]),
        ]);

        if ($checkout['success']) {
            $reservation->update(['payment_id' => $checkout['checkout_session_id']]);
            return redirect()->away($checkout['checkout_url']);
        }

        return redirect()->route('guest.dashboard')->with('error', 'Failed to start payment session. Please try again.');
    }

    /**
     * Convert an abandoned PayMongo reservation to Cash payment.
     */
    public function switchToCash(Reservation $reservation)
    {
        $this->authorizeReservationManage($reservation);

        if (!$reservation->isPendingPayment()) {
            return redirect()->route('guest.dashboard')->with('error', 'This reservation cannot be changed.');
        }

        $newDeadline = PaymentDeadlineService::forReservation($reservation);

        $reservation->update([
            'payment_method'   => 'cash',
            'payment_status'   => 'cash_pending',
            'payment_deadline' => $newDeadline,
        ]);

        return redirect()->route('guest.reservation.confirmation', $reservation)
            ->with('success', "Switched to cash payment. Please pay at the cashier by {$reservation->deadlineForHumans()}.");
    }

    /**
     * Guest-initiated cancellation of a reservation (frees the slot).
     */
    public function cancelPayment(Reservation $reservation)
    {
        $this->authorizeReservationManage($reservation);

        // Cannot cancel if already closed, denied, or cancelled
        if (in_array($reservation->status, ['cancelled', 'denied', 'completed'], true)) {
            return redirect()->back()->with('error', 'This reservation is already ' . $reservation->status . ' and cannot be cancelled.');
        }

        // Fully approved and fully paid reservations require administrative review
        if ($reservation->status === 'approved' && $reservation->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Approved reservations with confirmed full payment cannot be cancelled directly. Please contact the administration.');
        }

        $reservation->update([
            'status'              => 'cancelled',
            'cancellation_reason' => request('cancellation_reason', 'Cancelled by guest'),
        ]);

        self::notifyAdmins($reservation, 'cancelled');
        safe_notify(Auth::user(), new ReservationStatusChanged($reservation, 'cancelled'));

        return redirect()->back()->with('success', 'Your reservation has been cancelled and the slot is now free.');
    }

    /**
     * Show the printable slip for a reservation.
     * When returning from a PayMongo checkout (success_url contains ?paid=1),
     * we poll the session to confirm payment and mark it if verified.
     */
    /**
     * Show the payment confirmation/pending page.
     */
    public function confirmation(Reservation $reservation, \Illuminate\Http\Request $request)
    {
        $this->authorizeReservation($reservation);
        $reservation->load(['facility', 'user']);

        // If returning from PayMongo success redirect and not yet marked paid, verify via API
        if (
            $request->boolean('paid') &&
            $reservation->payment_method === 'paymongo' &&
            $reservation->payment_status !== 'paid' &&
            $reservation->payment_id
        ) {
            $payMongo = new \App\Services\PayMongoService();
            $session  = $payMongo->getCheckoutSession($reservation->payment_id);

            if ($session && ($session['attributes']['payment_intent']['attributes']['status'] ?? null) === 'succeeded') {
                $reservation->update(['payment_status' => 'paid']);
                $reservation->refresh();

                // Notify user + admins (mail failures must not roll back payment)
                safe_notify($reservation->user, new \App\Notifications\ReservationStatusChanged($reservation, 'payment_received'));
                self::notifyAdmins($reservation, 'payment_received');
            } elseif ($session) {
                $checkoutStatus = $session['attributes']['status'] ?? null;
                if ($checkoutStatus === 'paid') {
                    $reservation->update(['payment_status' => 'paid']);
                    $reservation->refresh();
                }
            }
        }

        return view('guest.reservations.confirmation', compact('reservation'));
    }

    public function printSlip(Reservation $reservation, \Illuminate\Http\Request $request)
    {
        $this->authorizeReservation($reservation);
        $reservation->load(['facility', 'user']);

        return view('guest.reservation-slip', compact('reservation'));
    }

    /**
     * Show the printable rental agreement for a reservation.
     */
    public function rentalAgreement(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);
        $reservation->load(['facility', 'user']);

        $cancellationPolicy = SystemSetting::getValue(
            'cancellation_policy_text',
            'Cancellations made less than 3 days before the event will result in forfeiture of the security deposit.'
        );

        return view('guest.rental-agreement', compact('reservation', 'cancellationPolicy'));
    }


    /* ─── Private Helpers ───────────────────────────────────────── */

    /**
     * View access: the owner, or any operational role that legitimately
     * needs to see slips/agreements (admin, superadmin, staff, cashier).
     */
    private function authorizeReservation(Reservation $reservation): void
    {
        $user = Auth::user();

        if ($reservation->user_id === $user->id) {
            return;
        }

        if (in_array($user->role, ['admin', 'superadmin', 'staff', 'cashier'], true)) {
            return;
        }

        abort(403);
    }

    /**
     * Manage access: payment lifecycle actions (resume, switch to cash,
     * cancel) are restricted to the owner or admin-level roles only.
     * Cashiers and staff must not mutate other users' payment state.
     */
    private function authorizeReservationManage(Reservation $reservation): void
    {
        $user = Auth::user();

        if ($reservation->user_id === $user->id) {
            return;
        }

        if (in_array($user->role, ['admin', 'superadmin'], true)) {
            return;
        }

        abort(403);
    }

    /**
     * API for checking availability (Frontend).
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date'  => 'required|date_format:Y-m-d',
            'end_date'    => 'required|date_format:Y-m-d',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i',
        ]);

        if ($validated['start_date'] === $validated['end_date'] && \Carbon\Carbon::parse($validated['end_time'])->lt(\Carbon\Carbon::parse($validated['start_time']))) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDay()->toDateString();
        }

        $facility     = Facility::findOrFail($validated['facility_id']);
        $availability = ReservationAvailability::checkAvailability(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        $price = ReservationAvailability::calculatePrice(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        return response()->json(array_merge($availability, [
            'total_price' => number_format($price, 2),
        ]));
    }

    /**
     * Notify administrators about a reservation status change.
     *
     * Mail/broadcast failures must NEVER bubble up: a broken mail transport
     * must not prevent a status change from being recorded or a booking from
     * completing. Failures are logged and swallowed.
     */
    private static function notifyAdmins(Reservation $reservation, string $status): void
    {
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        if ($admins->isEmpty()) {
            return;
        }

        try {
            Notification::send($admins, new ReservationStatusChanged($reservation, $status));
        } catch (\Exception $e) {
            Log::warning('Admin reservation notification failed', [
                'error'            => $e->getMessage(),
                'reservation_id'   => $reservation->id,
                'reservation_ref'  => $reservation->booking_reference,
                'notification_for' => $status,
            ]);
        }
    }

    /**
     * Attach individual slot records for the reservation period.
     */
    private function attachSlotReservations(Reservation $reservation, Facility $facility)
    {
        $period = CarbonPeriod::create($reservation->start_date, $reservation->end_date);

        foreach ($period as $date) {
            $slots = FacilityTimeSlot::where('facility_id', $facility->id)
                ->where('day_of_week', $date->dayOfWeek)
                ->where('is_active', true)
                ->where('start_time', '<', $reservation->end_time->format('H:i'))
                ->where('end_time', '>', $reservation->start_time->format('H:i'))
                ->get();

            if ($slots->isNotEmpty()) {
                foreach ($slots as $slot) {
                    SlotReservation::create([
                        'reservation_id'         => $reservation->id,
                        'facility_time_slot_id'  => $slot->id,
                        'reserved_date'          => $date->toDateString(),
                        'quantity_booked'        => 1,
                        'price_snapshot'         => $slot->price_per_hour ?? $facility->price_per_hour,
                    ]);
                }
            } else {
                Log::info('No specific time slots found for reservation on ' . $date->toDateString());
            }
        }
    }

    /**
     * Show reservation details.
     */
    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $reservation->load(['facility', 'user', 'slotReservations.facilityTimeSlot']);
        return view('guest.reservation-detail', compact('reservation'));
    }

    /**
     * Cancel a pending reservation (admin-reviewed stage).
     */
    public function cancel(Reservation $reservation)
    {
        return $this->cancelPayment($reservation);
    }
}
