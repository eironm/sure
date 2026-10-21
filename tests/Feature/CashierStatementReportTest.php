<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\FacilityCategory;
use App\Models\Reservation;
use App\Models\SystemSetting;
use App\Models\User;
use App\Services\BrandingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierStatementReportTest extends TestCase
{
    use RefreshDatabase;

    private User $cashier;
    private User $guest;
    private Facility $facility;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cashier = User::factory()->create([
            'role'      => 'cashier',
            'is_active' => true,
            'name'      => 'Alice Cashier',
        ]);

        $this->guest = User::factory()->create([
            'role'      => 'guest',
            'is_active' => true,
            'name'      => 'John Doe',
            'email'     => 'john@example.com',
        ]);

        $category = FacilityCategory::create(['name' => 'Sports Complex']);
        $this->facility = Facility::factory()->create([
            'name'        => 'University Gymnasium',
            'category_id' => $category->id,
        ]);
    }

    protected function tearDown(): void
    {
        BrandingService::flush();
        SystemSetting::flushCache();
        parent::tearDown();
    }

    private function createPaidReservation(array $overrides = []): Reservation
    {
        return Reservation::create(array_merge([
            'user_id'            => $this->guest->id,
            'facility_id'        => $this->facility->id,
            'event_title'        => 'University Event',
            'reason'             => 'Official tournament and sports activity',
            'start_date'         => now()->format('Y-m-d'),
            'end_date'           => now()->format('Y-m-d'),
            'start_time'         => '08:00',
            'end_time'           => '17:00',
            'number_of_people'   => 50,
            'total_price'        => 5000.00,
            'amount_paid'        => 5000.00,
            'payment_status'     => 'paid',
            'payment_method'     => 'cash',
            'cashier_signed_by'  => $this->cashier->id,
            'cashier_signed_at'  => now(),
            'cash_receipt_number'=> 'OR-2026-0001',
            'status'             => 'approved',
            'booking_reference'  => 'SURE-TEST-001',
        ], $overrides));
    }

    public function test_cashier_can_view_statement_report()
    {
        $this->createPaidReservation([
            'booking_reference'   => 'SURE-TEST-001',
            'cash_receipt_number' => 'OR-2026-0001',
            'total_price'         => 5000.00,
        ]);

        $response = $this->actingAs($this->cashier)->get(route('cashier.report'));

        $response->assertOk();
        $response->assertSee('Payment Statement Report');
        $response->assertSee('Settled Bookings');
        $response->assertSee('Total Revenue Collected');
        $response->assertSee('Cash (OTC)');
        $response->assertSee('PayMongo Digital');
        $response->assertSee('SURE-TEST-001');
        $response->assertSee('OR-2026-0001');
        $response->assertSee('5,000.00');
        $response->assertSee('Print Statement');
        $response->assertSee('Export CSV');
    }

    public function test_cashier_can_access_print_view()
    {
        $this->createPaidReservation([
            'booking_reference'   => 'SURE-TEST-002',
            'cash_receipt_number' => 'OR-2026-0002',
            'total_price'         => 3500.00,
        ]);

        $response = $this->actingAs($this->cashier)->get(route('cashier.report.print'));

        $response->assertOk();
        $response->assertSee('Official Printable Statement Preview');
        $response->assertSee('report-canvas-table');
        $response->assertSee('doc-header-fixed-wrap');
        $response->assertSee('SURE-TEST-002');
        $response->assertSee('OR-2026-0002');
        $response->assertSee('3,500.00');
        $response->assertSee('Certified &amp; Verified By:', false);
        $response->assertSee('Prepared &amp; Processed By:', false);
    }

    public function test_cashier_can_export_csv()
    {
        $this->createPaidReservation([
            'booking_reference'   => 'SURE-TEST-003',
            'cash_receipt_number' => 'OR-2026-0003',
            'total_price'         => 2000.00,
        ]);

        $response = $this->actingAs($this->cashier)->get(route('cashier.report.export-csv'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $content = $response->getContent();
        $this->assertStringContainsString('SURE-TEST-003', $content);
        $this->assertStringContainsString('OR-2026-0003', $content);
        $this->assertStringContainsString('2000.00', $content);
    }

    public function test_letterhead_cms_settings_reflect_in_cashier_statement()
    {
        $this->createPaidReservation();

        SystemSetting::setValue('report_header_line2', 'TEST UNIVERSITY SYSTEM LETTERHEAD');
        SystemSetting::setValue('report_header_line3', 'DIRECTORATE OF CASHIERING SERVICES');
        BrandingService::flush();

        $response = $this->actingAs($this->cashier)->get(route('cashier.report'));
        $response->assertOk();
        $response->assertSee('TEST UNIVERSITY SYSTEM LETTERHEAD');
        $response->assertSee('DIRECTORATE OF CASHIERING SERVICES');

        $printResponse = $this->actingAs($this->cashier)->get(route('cashier.report.print'));
        $printResponse->assertOk();
        $printResponse->assertSee('TEST UNIVERSITY SYSTEM LETTERHEAD');
        $printResponse->assertSee('DIRECTORATE OF CASHIERING SERVICES');
    }

    public function test_payment_method_filter_isolates_records()
    {
        // Cash payment
        $this->createPaidReservation([
            'booking_reference' => 'CASH-ONLY-REF',
            'payment_method'    => 'cash',
            'total_price'       => 1200.00,
        ]);

        // PayMongo payment
        $this->createPaidReservation([
            'booking_reference' => 'PAYMONGO-ONLY-REF',
            'payment_method'    => 'paymongo',
            'payment_id'        => 'cs_test_998877',
            'cashier_signed_by' => null,
            'cashier_signed_at' => null,
            'total_price'       => 4500.00,
        ]);

        // Filter Cash only
        $resCash = $this->actingAs($this->cashier)->get(route('cashier.report', ['payment_method' => 'cash']));
        $resCash->assertOk();
        $resCash->assertSee('CASH-ONLY-REF');
        $resCash->assertDontSee('PAYMONGO-ONLY-REF');

        // Filter PayMongo only
        $resDigital = $this->actingAs($this->cashier)->get(route('cashier.report', ['payment_method' => 'paymongo']));
        $resDigital->assertOk();
        $resDigital->assertSee('PAYMONGO-ONLY-REF');
        $resDigital->assertDontSee('CASH-ONLY-REF');
    }

    public function test_unauthorized_guest_cannot_access_cashier_report()
    {
        $response = $this->actingAs($this->guest)->get(route('cashier.report'));
        $this->assertNotEquals(200, $response->status());
    }
}
