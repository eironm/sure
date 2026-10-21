<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\FacilityTimeSlotController;
use App\Http\Controllers\Admin\FacilityBlackoutDateController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\SystemSettingController as SuperAdminSettingController;
use App\Http\Controllers\SuperAdmin\HomepageSettingController;
use App\Http\Controllers\SuperAdmin\LegalSettingController as SuperAdminLegalController;
use App\Http\Controllers\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Cashier\PaymentController as CashierPaymentController;
use App\Models\AuditLog;
use App\Models\User;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReservationDocumentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* --- Root route ----------------------------------------------------------- */
Route::get('/', function () {
    return view('home-page');
})->name('home');

/* --- Public legal pages (footer links) ------------------------------------ */
Route::get('/legal/{doc}', [LegalPageController::class, 'show'])
    ->name('legal.show')
    ->where('doc', 'terms|privacy|data_collection');
Route::get('/legal/cookies', [LegalPageController::class, 'cookies'])->name('legal.cookies');

/* --- Public contact form (landing page) ----------------------------------- */
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:6,1');

/* --- Shared authenticated routes ------------------------------------------ */
Route::middleware(['auth', 'verified'])->group(function () {

    // Smart dashboard redirect based on role
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        return match($role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin', 'staff' => redirect()->route('admin.dashboard'),
            'cashier'    => redirect()->route('cashier.dashboard'),
            default      => redirect()->route('guest.dashboard'),
        };
    })->name('dashboard');

    /* --- Guest Routes ------------------------------------------------------ */
    Route::prefix('guest')->name('guest.')->group(function () {
        Route::get('/dashboard', [GuestController::class, 'index'])->name('dashboard');
        Route::get('/reserve',   [GuestController::class, 'showReserve'])->name('reserve');
        Route::post('/reserve',  [ReservationController::class, 'store'])->name('reserve.store');
        Route::get('/history',   [GuestController::class, 'history'])->name('history');
        Route::get('/facility-board', [GuestController::class, 'facilityBoard'])->name('facility-board');
        Route::post('/facility-board/pencil', [GuestController::class, 'pencilBook'])->name('facility-board.pencil');
        Route::delete('/facility-board/pencil/{pencilBooking}', [GuestController::class, 'pencilCancel'])->name('facility-board.pencil-cancel');
        Route::get('/facilities', function () {
            $categories = \App\Models\FacilityCategory::orderBy('name')->get()
                ->each(function ($c) {
                    $c->mergeCasts(['created_at' => 'date:M. d, Y', 'updated_at' => 'date:M. d, Y g:i A']);
                });

            $facilities = \App\Models\Facility::where('is_available', true)
                ->with(['category', 'tags', 'images', 'featuredImage'])
                ->get()
                ->map(function ($f) {
                    // Keep models (blade needs collection methods) but force
                    // timestamps to serialize as human wordings in @json.
                    $f->mergeCasts(['created_at' => 'date:M. d, Y', 'updated_at' => 'date:M. d, Y g:i A']);
                    $f->category?->mergeCasts(['created_at' => 'date:M. d, Y', 'updated_at' => 'date:M. d, Y g:i A']);
                    $f->tags->each(function ($t) {
                        $t->mergeCasts(['created_at' => 'date:M. d, Y', 'updated_at' => 'date:M. d, Y g:i A']);
                    });
                    $createdAt = $f->getAttribute('created_at');
                    $f->setAttribute('created_at', $createdAt ? \Carbon\Carbon::parse($createdAt)->format('M. d, Y') : null);
                    $updatedAt = $f->getAttribute('updated_at');
                    $f->setAttribute('updated_at', $updatedAt ? \Carbon\Carbon::parse($updatedAt)->format('M. d, Y g:i A') : null);
                    $f->images = $f->images->map(function ($img) {
                        $img->mergeCasts(['created_at' => 'date:M. d, Y', 'updated_at' => 'date:M. d, Y g:i A']);
                        $ca = $img->getAttribute('created_at');
                        $img->setAttribute('created_at', $ca ? \Carbon\Carbon::parse($ca)->format('M. d, Y g:i A') : null);
                        $ua = $img->getAttribute('updated_at');
                        $img->setAttribute('updated_at', $ua ? \Carbon\Carbon::parse($ua)->format('M. d, Y g:i A') : null);
                        return $img;
                    })->values();
                    if ($f->featuredImage) {
                        $img = $f->featuredImage;
                        $img->mergeCasts(['created_at' => 'date:M. d, Y', 'updated_at' => 'date:M. d, Y g:i A']);
                        $ca = $img->getAttribute('created_at');
                        $img->setAttribute('created_at', $ca ? \Carbon\Carbon::parse($ca)->format('M. d, Y g:i A') : null);
                        $ua = $img->getAttribute('updated_at');
                        $img->setAttribute('updated_at', $ua ? \Carbon\Carbon::parse($ua)->format('M. d, Y g:i A') : null);
                        $f->setRelation('featuredImage', $img);
                    }
                    return $f;
                })
                ->values();

            return view('guest.facilities', compact('facilities', 'categories'));
        })->name('facilities');

        // Payment management
        Route::post('/reservations/{reservation}/resume-payment', [ReservationController::class, 'resumePayment'])->name('reservation.resume-payment');
        Route::post('/reservations/{reservation}/switch-cash',    [ReservationController::class, 'switchToCash'])->name('reservation.switch-cash');
        Route::post('/reservations/{reservation}/cancel-payment', [ReservationController::class, 'cancelPayment'])->name('reservation.cancel-payment');
        Route::post('/reservations/{reservation}/cancel',         [ReservationController::class, 'cancelPayment'])->name('reservation.cancel');
        Route::get('/reservations/{reservation}/slip',            [ReservationController::class, 'printSlip'])->name('reservation.slip');
        Route::get('/reservations/{reservation}/confirmation',    [ReservationController::class, 'confirmation'])->name('reservation.confirmation');
        Route::get('/reservations/{reservation}/rental-agreement',[ReservationController::class, 'rentalAgreement'])->name('reservation.rental-agreement');
        Route::get('/reservations/{reservation}/documents/{document}/view', [ReservationDocumentController::class, 'view'])->name('reservation.documents.view');
        Route::get('/reservations/{reservation}/documents/{document}/download', [ReservationDocumentController::class, 'download'])->name('reservation.documents.download');
    });

    /* --- Notifications ----------------------------------------------------- */
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'follow'])->name('notifications.follow');
    Route::post('/notifications/{id}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    /* --- Profile ----------------------------------------------------------- */
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/contact', [ProfileController::class, 'updateContact'])->name('profile.contact.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar',   [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
});

/* --- Admin Panel ---------------------------------------------------------- */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Reservation Management
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/calendar-events', [AdminReservationController::class, 'calendarEvents'])->name('reservations.calendarEvents');
    Route::get('/reservations/{reservation}', [AdminReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/approve', [AdminReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('/reservations/{reservation}/deny', [AdminReservationController::class, 'deny'])->name('reservations.deny');
    Route::post('/reservations/{reservation}/cancel', [AdminReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('/reservations/{reservation}/documents/{document}/view', [ReservationDocumentController::class, 'view'])->name('reservations.documents.view');
    Route::get('/reservations/{reservation}/documents/{document}/download', [ReservationDocumentController::class, 'download'])->name('reservations.documents.download');

    // User Management (admin manages guest + cashier only).
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');

    // Facility Management
    Route::resource('facilities', FacilityController::class);
    // Instant / draft image uploads (AJAX)
    Route::post('/facilities/images/upload', [FacilityController::class, 'uploadImage'])->name('facilities.uploadImage');
    Route::post('/facilities/images/reorder', [FacilityController::class, 'reorderImages'])->name('facilities.reorderImages');
    Route::post('/facilities/images/{image}/set-featured', [FacilityController::class, 'setFeaturedImage'])->name('facilities.setFeaturedImage');
    Route::delete('/facilities/images/{image}/delete-image', [FacilityController::class, 'deleteImage'])->name('facilities.deleteImage');

    // Time Slot Management
    Route::prefix('facilities/{facility}')->name('facilities.')->group(function () {
        Route::get('/time-slots', [FacilityTimeSlotController::class, 'index'])->name('time-slots.index');
        Route::get('/time-slots/create', [FacilityTimeSlotController::class, 'create'])->name('time-slots.create');
        Route::post('/time-slots', [FacilityTimeSlotController::class, 'store'])->name('time-slots.store');
        Route::get('/time-slots/{timeSlot}/edit', [FacilityTimeSlotController::class, 'edit'])->name('time-slots.edit');
        Route::put('/time-slots/{timeSlot}', [FacilityTimeSlotController::class, 'update'])->name('time-slots.update');
        Route::delete('/time-slots/{timeSlot}', [FacilityTimeSlotController::class, 'destroy'])->name('time-slots.destroy');
        Route::get('/time-slots/bulk/create', [FacilityTimeSlotController::class, 'bulkCreate'])->name('time-slots.bulk-create');
        Route::post('/time-slots/bulk', [FacilityTimeSlotController::class, 'bulkStore'])->name('time-slots.bulk-store');
    });

    // Settings: Categories & Tags
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('tags', TagController::class)->except(['create', 'show', 'edit']);
        Route::resource('logistical-items', \App\Http\Controllers\Admin\LogisticalItemController::class)->except(['create', 'show', 'edit']);

        // Blackout Dates
        Route::get('/blackout-dates', [FacilityBlackoutDateController::class, 'index'])->name('blackout-dates.index');
        Route::post('/blackout-dates', [FacilityBlackoutDateController::class, 'store'])->name('blackout-dates.store');
        Route::delete('/blackout-dates/{blackoutDate}', [FacilityBlackoutDateController::class, 'destroy'])->name('blackout-dates.destroy');
    });

    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'admin'])->name('audit-logs');

    // Reports & Analytics Engine
    Route::get('/reports',                [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print',          [\App\Http\Controllers\Admin\ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/export-csv',     [\App\Http\Controllers\Admin\ReportController::class, 'exportCsv'])->name('reports.export-csv');
    Route::get('/reports/letterhead-cms', [SuperAdminSettingController::class, 'category'])->defaults('tab', 'reports')->name('reports.letterhead-cms');
    Route::put('/reports/letterhead-cms', [SuperAdminSettingController::class, 'update'])->name('reports.letterhead-cms.update');
});

/* --- SuperAdmin Panel ----------------------------------------------------- */
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Full User Management (all roles, no delete - only activate/deactivate)
    Route::get('/users',                          [SuperAdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create',                   [SuperAdminUserController::class, 'create'])->name('users.create');
    Route::post('/users',                         [SuperAdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',              [SuperAdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',                   [SuperAdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-active',    [SuperAdminUserController::class, 'toggleActive'])->name('users.toggleActive');

    // CMS Branding (moved from admin)
    Route::get('/settings/system',  fn () => redirect()->route('superadmin.settings.branding'))->name('settings.system');
    Route::put('/settings/system',  [SuperAdminSettingController::class, 'update'])->name('settings.system.update');

    // System Settings - one dedicated page per category
    Route::get('/settings/branding',      [SuperAdminSettingController::class, 'category'])->defaults('tab', 'branding')->name('settings.branding');
    Route::get('/settings/payments',      [SuperAdminSettingController::class, 'category'])->defaults('tab', 'payments')->name('settings.payments');
    Route::get('/settings/announcements', [SuperAdminSettingController::class, 'category'])->defaults('tab', 'announcements')->name('settings.announcements');
    Route::get('/settings/pages',         [SuperAdminSettingController::class, 'category'])->defaults('tab', 'pages')->name('settings.pages');
    Route::get('/settings/appearance',    [SuperAdminSettingController::class, 'category'])->defaults('tab', 'appearance')->name('settings.appearance');
    Route::get('/settings/logo',          [SuperAdminSettingController::class, 'category'])->defaults('tab', 'logo')->name('settings.logo');
    Route::get('/settings/reports',       [SuperAdminSettingController::class, 'category'])->defaults('tab', 'reports')->name('settings.reports');

    // Whitelabel Legal Documents (Terms, Privacy, Data Collection) - multi-language, Markdown
    Route::get('/settings/legal',   [SuperAdminLegalController::class, 'index'])->name('settings.legal');
    Route::put('/settings/legal',   [SuperAdminLegalController::class, 'update'])->name('settings.legal.update');
    Route::get('/settings/homepage',   fn () => redirect()->route('superadmin.settings.hero'))->name('settings.homepage');
    Route::put('/settings/homepage',   [HomepageSettingController::class, 'update'])->name('settings.homepage.update');

    // Homepage Editor - one dedicated page per section
    Route::get('/settings/home/hero',      [HomepageSettingController::class, 'category'])->defaults('tab', 'hero')->name('settings.hero');
    Route::get('/settings/home/about',     [HomepageSettingController::class, 'category'])->defaults('tab', 'about')->name('settings.about');
    Route::get('/settings/home/features',  [HomepageSettingController::class, 'category'])->defaults('tab', 'features')->name('settings.features');
    Route::get('/settings/home/gallery',   [HomepageSettingController::class, 'category'])->defaults('tab', 'gallery')->name('settings.gallery');
    Route::get('/settings/home/faq',       [HomepageSettingController::class, 'category'])->defaults('tab', 'faq')->name('settings.faq');
    Route::get('/settings/home/contact',   [HomepageSettingController::class, 'category'])->defaults('tab', 'contact')->name('settings.contact');

    // Audit Logs (full access)
    Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'superadmin'])->name('audit-logs');
});

/* --- Cashier Panel --------------------------------------------------------- */
Route::middleware(['auth', 'cashier.panel'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/dashboard', [CashierDashboardController::class, 'index'])->name('dashboard');

    // Cash Payment Queue
    Route::get('/payments',                         [CashierPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{reservation}',           [CashierPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{reservation}/confirm',  [CashierPaymentController::class, 'confirm'])->name('payments.confirm');

    // Printable Report/Statement
    Route::get('/report',            [CashierPaymentController::class, 'report'])->name('report');
    Route::get('/report/print',      [CashierPaymentController::class, 'print'])->name('report.print');
    Route::get('/report/export-csv', [CashierPaymentController::class, 'exportCsv'])->name('report.export-csv');

    // Print slip redirect (already printable via guest route but needs cashier access too)
    Route::get('/reservations/{reservation}/slip', [\App\Http\Controllers\ReservationController::class, 'printSlip'])->name('reservation.slip');
});

/* --- API Routes for Calendar ----------------------------------------------- */
Route::middleware(['auth', 'verified'])->prefix('api')->group(function () {
    Route::get('/facilities/search', [CalendarController::class, 'searchFacilities']);
    Route::post('/calculate-price', [CalendarController::class, 'calculatePrice']);
    Route::post('/calculate-quotation', [CalendarController::class, 'calculateQuotation']);
    Route::post('/check-availability', [CalendarController::class, 'checkAvailability']);
    Route::get('/check-global-blackout', [CalendarController::class, 'checkGlobalBlackout']);

    /* --- Wizard API (redesigned reservation wizard) ------------------------- */
    Route::get('/wizard/match', [\App\Http\Controllers\WizardController::class, 'match']);
    Route::post('/wizard/quotation', [\App\Http\Controllers\WizardController::class, 'quotation']);
    Route::get('/wizard/facility-hours', [\App\Http\Controllers\WizardController::class, 'facilityHours']);
});

/* --- PayMongo Webhook (no auth) -------------------------------------------- */
Route::post('/webhooks/paymongo', [\App\Http\Controllers\PayMongoWebhookController::class, 'handle'])
    ->name('webhooks.paymongo')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/* --- Internal Cron (token-protected) --------------------------------------- */
Route::get('/internal/expire-reservations', [\App\Http\Controllers\InternalCronController::class, 'handle'])
    ->name('internal.expire')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/* --- Dev Email Preview (local only) ---------------------------------------- */
if (app()->environment('local')) {
    Route::get('/dev/email-preview/{template}', function (string $template) {
        // Build dummy User for auth-related previews
        $user = new \App\Models\User([
            'first_name'       => 'Juan',
            'middle_name'      => 'Dela',
            'last_name'        => 'Cruz',
            'name'             => 'Juan Dela Cruz',
            'email'            => 'juan@example.com',
            'client_type'      => 'internal',
            'affiliation_type' => 'Student',
            'college_department'=> 'College of Engineering',
            'is_active'        => true,
        ]);

        // Build dummy Reservation for reservation-related previews
        $facility = new \App\Models\Facility([
            'name' => 'University Auditorium',
        ]);
        $reservation = new \App\Models\Reservation([
            'id'                => 42,
            'booking_reference' => 'SURE-20260720-ABC123',
            'event_title'       => 'Annual Student Council Summit',
            'start_date'        => now()->addDays(7),
            'end_date'          => now()->addDays(7),
            'total_price'       => 5000.00,
            'amount_paid'       => 2500.00,
            'payment_method'    => 'cash',
            'payment_status'    => 'pending',
            'payment_deadline'  => now()->addDays(7),
            'status'            => 'pending',
            'admin_remarks'     => 'Schedule conflict with another event.',
            'is_fee_waived'     => false,
            'cancellation_reason' => 'Changed event plans.',
        ]);
        $reservation->setRelation('facility', $facility);
        $reservation->setRelation('user', $user);

        $templates = [
            // Auth emails
            'welcome-registration'     => ['emails.auth.welcome-registration', ['user' => $user]],
            'account-approved'         => ['emails.auth.account-approved', ['user' => $user]],
            'account-deactivated'      => ['emails.auth.account-deactivated', ['user' => $user]],
            'reset-password'           => ['emails.auth.reset-password', ['resetUrl' => url('/reset-password/fake-token?email=juan@example.com')]],
            'verify-email'             => ['emails.auth.verify-email', ['verificationUrl' => url('/verify-email/1/fake-hash')]],
            'verification-code'        => ['emails.auth.verification-code', ['user' => $user, 'code' => '849201', 'minutes' => 10]],
            // Reservation emails
            'reservation-submitted'    => ['emails.reservations.reservation-submitted', ['reservation' => $reservation]],
            'reservation-approved'     => ['emails.reservations.reservation-approved', ['reservation' => $reservation]],
            'reservation-denied'       => ['emails.reservations.reservation-denied', ['reservation' => $reservation]],
            'reservation-cancelled'    => ['emails.reservations.reservation-cancelled', ['reservation' => $reservation]],
            'payment-received'         => ['emails.reservations.payment-received', ['reservation' => $reservation]],
            'payment-pending'          => ['emails.reservations.payment-pending', ['reservation' => $reservation]],
            'reservation-expired'      => ['emails.reservations.reservation-expired', ['reservation' => $reservation]],
        ];

        if (!isset($templates[$template])) {
            $available = implode(', ', array_keys($templates));
            return response("Template '{$template}' not found. Available: {$available}", 404);
        }

        [$view, $data] = $templates[$template];
        return view($view, $data);
    })->name('dev.email-preview');
}

require __DIR__.'/auth.php';