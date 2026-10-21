<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\Reservation;
use App\Models\ReservationDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ReservationDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!File::isDirectory(public_path('uploads/reservation_documents'))) {
            File::makeDirectory(public_path('uploads/reservation_documents'), 0755, true);
        }
    }

    private function createTestReservation(User $user, Facility $facility): Reservation
    {
        return Reservation::create([
            'user_id' => $user->id,
            'facility_id' => $facility->id,
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'status' => 'pending',
            'booking_reference' => 'TEST-' . uniqid(),
            'reason' => 'Official event',
            'number_of_people' => 10,
        ]);
    }

    public function test_guest_can_upload_documents_when_creating_reservation(): void
    {
        $user = User::factory()->create(['role' => 'guest']);
        $facility = Facility::factory()->create([
            'capacity' => 100,
        ]);

        for ($day = 0; $day <= 6; $day++) {
            FacilityTimeSlot::create([
                'facility_id' => $facility->id,
                'day_of_week' => $day,
                'start_time' => '08:00',
                'end_time' => '18:00',
                'capacity' => 10,
                'is_active' => true,
            ]);
        }

        $pdfFile = UploadedFile::fake()->create('formal_request_letter.pdf', 150, 'application/pdf');
        $imgFile = UploadedFile::fake()->image('layout_proposal.png', 400, 400);

        $startDate = now()->addDays(7)->toDateString();
        $endDate = $startDate;

        $response = $this->actingAs($user)->post(route('guest.reserve.store'), [
            'event_title' => 'Corporate Assembly',
            'facility_id' => $facility->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'start_time' => '09:00',
            'end_time' => '12:00',
            'number_of_people' => 20,
            'reason' => 'Formal corporate general assembly',
            'client_type' => 'internal',
            'reserved_by_name' => 'John Requester',
            'reserved_by_email' => 'john@example.com',
            'reserved_by_phone' => '09123456789',
            'end_user_name' => 'Student Council',
            'end_user_type' => 'organization',
            'payment_method' => 'cash',
            'agreement_accepted' => '1',
            'documents' => [$pdfFile, $imgFile],
        ]);

        $reservation = Reservation::where('user_id', $user->id)->first();
        $this->assertNotNull($reservation);

        $this->assertCount(2, $reservation->documents);

        $firstDoc = $reservation->documents->where('original_name', 'formal_request_letter.pdf')->first();
        $this->assertNotNull($firstDoc);
        $this->assertEquals('pdf', $firstDoc->file_category);
        $this->assertFileExists(public_path($firstDoc->file_path));

        $secondDoc = $reservation->documents->where('original_name', 'layout_proposal.png')->first();
        $this->assertNotNull($secondDoc);
        $this->assertEquals('image', $secondDoc->file_category);
        $this->assertFileExists(public_path($secondDoc->file_path));

        // Clean up files
        if (File::exists(public_path($firstDoc->file_path))) {
            File::delete(public_path($firstDoc->file_path));
        }
        if (File::exists(public_path($secondDoc->file_path))) {
            File::delete(public_path($secondDoc->file_path));
        }
    }

    public function test_guest_can_view_and_download_own_document_in_browser(): void
    {
        $user = User::factory()->create(['role' => 'guest']);
        $facility = Facility::factory()->create(['capacity' => 100]);
        $reservation = $this->createTestReservation($user, $facility);

        $fileName = 'test_' . uniqid() . '.pdf';
        $relPath = 'uploads/reservation_documents/' . $fileName;
        File::put(public_path($relPath), '%PDF-1.4 test content');

        $doc = ReservationDocument::create([
            'reservation_id' => $reservation->id,
            'file_name' => $fileName,
            'original_name' => 'endorsement_letter.pdf',
            'file_path' => $relPath,
            'file_type' => 'application/pdf',
            'file_size' => 2048,
        ]);

        // 1. View route (inline)
        $viewResponse = $this->actingAs($user)->get(route('guest.reservation.documents.view', [$reservation, $doc]));
        $viewResponse->assertOk();
        $this->assertStringContainsString('inline', $viewResponse->headers->get('Content-Disposition'));
        $this->assertStringContainsString('endorsement_letter.pdf', $viewResponse->headers->get('Content-Disposition'));
        $viewResponse->assertHeader('Content-Type', 'application/pdf');

        // 2. Download route (attachment)
        $downloadResponse = $this->actingAs($user)->get(route('guest.reservation.documents.download', [$reservation, $doc]));
        $downloadResponse->assertOk();
        $this->assertStringContainsString('attachment', $downloadResponse->headers->get('Content-Disposition'));
        $this->assertStringContainsString('endorsement_letter.pdf', $downloadResponse->headers->get('Content-Disposition'));

        // Clean up
        if (File::exists(public_path($relPath))) {
            File::delete(public_path($relPath));
        }
    }

    public function test_other_guest_cannot_view_or_download_unauthorized_document(): void
    {
        $owner = User::factory()->create(['role' => 'guest']);
        $intruder = User::factory()->create(['role' => 'guest']);
        $facility = Facility::factory()->create(['capacity' => 100]);
        $reservation = $this->createTestReservation($owner, $facility);

        $fileName = 'test_secret_' . uniqid() . '.pdf';
        $relPath = 'uploads/reservation_documents/' . $fileName;
        File::put(public_path($relPath), '%PDF-1.4 secret content');

        $doc = ReservationDocument::create([
            'reservation_id' => $reservation->id,
            'file_name' => $fileName,
            'original_name' => 'secret_request.pdf',
            'file_path' => $relPath,
            'file_type' => 'application/pdf',
            'file_size' => 1024,
        ]);

        $response = $this->actingAs($intruder)->get(route('guest.reservation.documents.view', [$reservation, $doc]));
        $response->assertForbidden();

        $responseDownload = $this->actingAs($intruder)->get(route('guest.reservation.documents.download', [$reservation, $doc]));
        $responseDownload->assertForbidden();

        // Clean up
        if (File::exists(public_path($relPath))) {
            File::delete(public_path($relPath));
        }
    }

    public function test_admin_and_staff_can_view_and_download_documents(): void
    {
        $guest = User::factory()->create(['role' => 'guest']);
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);
        $facility = Facility::factory()->create(['capacity' => 100]);
        $reservation = $this->createTestReservation($guest, $facility);

        $fileName = 'test_admin_doc_' . uniqid() . '.pdf';
        $relPath = 'uploads/reservation_documents/' . $fileName;
        File::put(public_path($relPath), '%PDF-1.4 sample content');

        $doc = ReservationDocument::create([
            'reservation_id' => $reservation->id,
            'file_name' => $fileName,
            'original_name' => 'permit_request.pdf',
            'file_path' => $relPath,
            'file_type' => 'application/pdf',
            'file_size' => 4096,
        ]);

        // Admin access via admin route
        $adminResp = $this->actingAs($admin)->get(route('admin.reservations.documents.view', [$reservation, $doc]));
        $adminResp->assertOk();
        $this->assertStringContainsString('inline', $adminResp->headers->get('Content-Disposition'));

        // Staff access via admin route (role:admin,staff)
        $staffResp = $this->actingAs($staff)->get(route('admin.reservations.documents.view', [$reservation, $doc]));
        $staffResp->assertOk();

        // Admin download
        $adminDownload = $this->actingAs($admin)->get(route('admin.reservations.documents.download', [$reservation, $doc]));
        $adminDownload->assertOk();
        $this->assertStringContainsString('attachment', $adminDownload->headers->get('Content-Disposition'));

        // Clean up
        if (File::exists(public_path($relPath))) {
            File::delete(public_path($relPath));
        }
    }

    public function test_admin_calendar_and_show_api_include_reservation_documents(): void
    {
        $guest = User::factory()->create(['role' => 'guest']);
        $admin = User::factory()->create(['role' => 'admin']);
        $facility = Facility::factory()->create(['capacity' => 100]);
        $reservation = $this->createTestReservation($guest, $facility);

        $doc = ReservationDocument::create([
            'reservation_id' => $reservation->id,
            'file_name' => 'calendar_test.pdf',
            'original_name' => 'endorsement.pdf',
            'file_path' => 'uploads/reservation_documents/calendar_test.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 1024,
        ]);

        // 1. calendarEvents includes documents in extendedProps
        $calResp = $this->actingAs($admin)->getJson(route('admin.reservations.calendarEvents', [
            'start' => now()->startOfMonth()->toDateString(),
            'end' => now()->addMonth()->endOfMonth()->toDateString(),
        ]));
        $calResp->assertOk();
        $events = $calResp->json();
        $this->assertNotEmpty($events);

        $foundEvent = null;
        foreach ($events as $event) {
            if (isset($event['extendedProps']['reservationId']) && $event['extendedProps']['reservationId'] == $reservation->id) {
                $foundEvent = $event;
                break;
            }
        }
        $this->assertNotNull($foundEvent);
        $this->assertArrayHasKey('documents', $foundEvent['extendedProps']);
        $this->assertCount(1, $foundEvent['extendedProps']['documents']);
        $this->assertEquals('endorsement.pdf', $foundEvent['extendedProps']['documents'][0]['original_name']);

        // 2. show method returns JSON with documents
        $showResp = $this->actingAs($admin)->getJson(route('admin.reservations.show', $reservation));
        $showResp->assertOk();
        $showResp->assertJsonPath('reservationId', $reservation->id);
        $showResp->assertJsonPath('documents.0.original_name', 'endorsement.pdf');
    }
}

