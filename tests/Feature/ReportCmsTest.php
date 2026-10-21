<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use App\Services\BrandingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ReportCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->superadmin = User::factory()->create([
            'role' => 'superadmin',
            'is_active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        BrandingService::flush();
        SystemSetting::flushCache();
        parent::tearDown();
    }

    public function test_superadmin_can_access_report_cms_studio(): void
    {
        $response = $this->actingAs($this->superadmin)->get(route('superadmin.settings.reports'));
        $response->assertOk();
        $response->assertSee('Report Letterhead');
        $response->assertSee('Word-Style Builder');
        $response->assertSee('Pre-Made Banner Image');
        $response->assertSee('Live A4 Printable Preview');
        $response->assertSee('Philippine State University (SUC / CHED)');
    }

    public function test_admin_can_access_report_letterhead_cms(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.reports.letterhead-cms'));
        $response->assertOk();
        $response->assertSee('Report Letterhead');
        $response->assertSee('Live A4 Printable Preview');
    }

    public function test_staff_cannot_access_or_update_report_letterhead_cms(): void
    {
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);

        // Direct access to CMS page is forbidden
        $this->actingAs($staff)
            ->get(route('admin.reports.letterhead-cms'))
            ->assertForbidden();

        // Attempting to update letterhead settings is forbidden
        $this->actingAs($staff)
            ->put(route('admin.reports.letterhead-cms.update'), [
                'settings' => ['report_header_line1' => 'Hacked Header'],
            ])
            ->assertForbidden();
    }

    public function test_staff_cannot_see_letterhead_cms_button_on_reports_pages(): void
    {
        $staff = User::factory()->create(['role' => 'staff', 'is_active' => true]);

        // Reports master list
        $indexResponse = $this->actingAs($staff)->get(route('admin.reports.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee(route('admin.reports.letterhead-cms'), false);

        // Reports print preview
        $printResponse = $this->actingAs($staff)->get(route('admin.reports.print'));
        $printResponse->assertOk();
        $printResponse->assertDontSee(route('admin.reports.letterhead-cms'), false);

        // Verify Admin DOES see the button
        $adminIndex = $this->actingAs($this->admin)->get(route('admin.reports.index'));
        $adminIndex->assertOk();
        $adminIndex->assertSee(route('admin.reports.letterhead-cms'), false);

        $adminPrint = $this->actingAs($this->admin)->get(route('admin.reports.print'));
        $adminPrint->assertOk();
        $adminPrint->assertSee(route('admin.reports.letterhead-cms'), false);
    }


    public function test_user_can_save_report_letterhead_settings(): void
    {
        $payload = [
            'settings' => [
                'report_header_mode' => 'builder',
                'report_header_line1' => 'REPUBLIC OF TESTING',
                'report_header_line2' => 'NATIONAL TEST UNIVERSITY',
                'report_header_line3' => 'OFFICE OF FACILITY SERVICES',
                'report_header_line4' => 'Test University Blvd, Innovation Campus',
                'report_header_line5' => 'Tel: 123-4567 | info@test.edu.ph',
                'report_header_divider_style' => 'single',
                'report_footer_left_title' => 'System: Automated Facility Test System',
                'report_footer_left_line1' => 'Direct Hotline: 8888',
                'report_footer_center_text' => 'ISO 9001:2026 CERTIFIED (TEST)',
            ],
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), $payload);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        BrandingService::flush();

        $this->assertEquals('REPUBLIC OF TESTING', SystemSetting::getValue('report_header_line1'));
        $this->assertEquals('NATIONAL TEST UNIVERSITY', SystemSetting::getValue('report_header_line2'));
        $this->assertEquals('OFFICE OF FACILITY SERVICES', SystemSetting::getValue('report_header_line3'));
        $this->assertEquals('single', SystemSetting::getValue('report_header_divider_style'));
        $this->assertEquals('ISO 9001:2026 CERTIFIED (TEST)', SystemSetting::getValue('report_footer_center_text'));
    }

    public function test_print_view_reflects_customized_letterhead_settings(): void
    {
        SystemSetting::setValue('report_header_mode', 'builder');
        SystemSetting::setValue('report_header_line1', 'REPUBLIC OF THE CUSTOM TEST');
        SystemSetting::setValue('report_header_line2', 'CUSTOM POLYTECHNIC STATE UNIVERSITY');
        SystemSetting::setValue('report_header_line3', 'CAMPUS MANAGEMENT & LOGISTICS');
        SystemSetting::setValue('report_header_line4', 'Technology Park, Academic City');
        SystemSetting::setValue('report_header_line5', 'contact@stateuniv.edu.ph');
        SystemSetting::setValue('report_header_divider_style', 'thick');
        SystemSetting::setValue('report_footer_left_line1', 'Document Control No: CUSTOM-2026');
        SystemSetting::setValue('report_footer_center_text', 'ISO CERTIFIED TEST OFFICE');
        BrandingService::flush();

        $response = $this->actingAs($this->admin)->get(route('admin.reports.print', [
            'report_type' => 'reservations',
        ]));

        $response->assertOk();
        $response->assertSee('REPUBLIC OF THE CUSTOM TEST');
        $response->assertSee('CUSTOM POLYTECHNIC STATE UNIVERSITY');
        $response->assertSee('CAMPUS MANAGEMENT & LOGISTICS');
        $response->assertSee('Technology Park, Academic City');
        $response->assertSee('contact@stateuniv.edu.ph');
        $response->assertSee('rule-thick');
        $response->assertSee('border-top: 3px solid', false);
        $response->assertSee('-webkit-print-color-adjust: exact', false);
        $response->assertSee('flex-direction: row !important;', false);
        $response->assertSee('Document Control No: CUSTOM-2026');
        $response->assertSee('ISO CERTIFIED TEST OFFICE');
    }

    public function test_print_view_renders_image_banner_mode_when_configured(): void
    {
        SystemSetting::setValue('report_header_mode', 'image');
        SystemSetting::setValue('report_footer_mode', 'image');
        SystemSetting::setValue('report_header_image', 'uploads/branding/test-banner.png');
        SystemSetting::setValue('report_footer_image', 'uploads/branding/test-footer.png');
        BrandingService::flush();

        $response = $this->actingAs($this->admin)->get(route('admin.reports.print', [
            'report_type' => 'reservations',
        ]));

        $response->assertOk();
        $response->assertSee('uploads/branding/test-banner.png');
        $response->assertSee('uploads/branding/test-footer.png');
    }

    public function test_uploading_report_seal_logo_saves_file(): void
    {
        $file = UploadedFile::fake()->image('custom_seal.png', 120, 120);

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_header_left_logo' => $file,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $savedPath = SystemSetting::getValue('report_header_left_logo');
        $this->assertNotEmpty($savedPath);
        $this->assertStringStartsWith('uploads/branding/', $savedPath);

        if (file_exists(public_path($savedPath))) {
            @unlink(public_path($savedPath));
        }
    }

    public function test_uploading_report_footer_banner_saves_file(): void
    {
        $file = UploadedFile::fake()->image('footer_banner.png', 800, 100);

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_footer_mode' => 'image',
                'report_footer_image' => $file,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $savedPath = SystemSetting::getValue('report_footer_image');
        $this->assertNotEmpty($savedPath);
        $this->assertStringStartsWith('uploads/branding/', $savedPath);

        if (file_exists(public_path($savedPath))) {
            @unlink(public_path($savedPath));
        }
    }

    public function test_invalid_file_format_returns_user_centric_error_message(): void
    {
        $badFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_header_left_logo' => $badFile,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('settings.report_header_left_logo');

        $errors = session('errors')->get('settings.report_header_left_logo');
        $this->assertNotEmpty($errors);
        // Ensure no developer-centric internal field names like 'The settings.report_header_left_logo field must be...'
        $this->assertStringNotContainsString('The settings.report_header_left_logo', $errors[0]);
        // Ensure friendly human label is used
        $this->assertStringContainsString('The Left Seal (Primary)', $errors[0]);
    }

    public function test_oversized_file_returns_user_centric_error_message(): void
    {
        // 5MB file (exceeds 4MB limit)
        $bigFile = UploadedFile::fake()->image('huge_logo.png')->size(5120);

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_header_left_logo' => $bigFile,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('settings.report_header_left_logo');

        $errors = session('errors')->get('settings.report_header_left_logo');
        $this->assertNotEmpty($errors);
        $this->assertStringNotContainsString('The settings.report_header_left_logo', $errors[0]);
        $this->assertStringContainsString('The Left Seal (Primary) must not be larger than 4MB', $errors[0]);
    }

    public function test_user_can_save_banner_fit_and_height_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_header_mode' => 'image',
                'report_header_image_height' => '125',
                'report_header_image_fit' => 'width',
                'report_footer_mode' => 'image',
                'report_footer_image_height' => '75',
                'report_footer_image_fit' => 'width',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('125', SystemSetting::getValue('report_header_image_height'));
        $this->assertEquals('width', SystemSetting::getValue('report_header_image_fit'));
        $this->assertEquals('75', SystemSetting::getValue('report_footer_image_height'));
        $this->assertEquals('width', SystemSetting::getValue('report_footer_image_fit'));
    }

    public function test_user_can_save_and_print_drag_position_and_scale_settings(): void
    {
        SystemSetting::setValue('report_header_image', 'uploads/branding/test_hdr.png');
        SystemSetting::setValue('report_footer_image', 'uploads/branding/test_ftr.png');

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_header_mode' => 'image',
                'report_header_image_pos_x' => '15',
                'report_header_image_pos_y' => '-8',
                'report_header_image_scale' => '1.25',
                'report_footer_mode' => 'image',
                'report_footer_image_pos_x' => '-10',
                'report_footer_image_pos_y' => '5',
                'report_footer_image_scale' => '0.95',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('15', SystemSetting::getValue('report_header_image_pos_x'));
        $this->assertEquals('-8', SystemSetting::getValue('report_header_image_pos_y'));
        $this->assertEquals('1.25', SystemSetting::getValue('report_header_image_scale'));
        $this->assertEquals('-10', SystemSetting::getValue('report_footer_image_pos_x'));
        $this->assertEquals('5', SystemSetting::getValue('report_footer_image_pos_y'));
        $this->assertEquals('0.95', SystemSetting::getValue('report_footer_image_scale'));

        // Verify print view renders exact CSS transforms
        $printResponse = $this->actingAs($this->admin)->get(route('admin.reports.print'));
        $printResponse->assertStatus(200);
        $printResponse->assertSee('transform: translate(15px, -8px) scale(1.25)', false);
        $printResponse->assertSee('transform: translate(-10px, 5px) scale(0.95)', false);
    }

    public function test_user_can_save_and_print_flush_edge_positioning(): void
    {
        SystemSetting::setValue('report_header_image', 'uploads/branding/test_hdr.png');
        SystemSetting::setValue('report_footer_image', 'uploads/branding/test_ftr.png');

        $response = $this->actingAs($this->admin)->put(route('admin.reports.letterhead-cms.update'), [
            'settings' => [
                'report_header_mode' => 'image',
                'report_header_edge' => 'flush',
                'report_header_offset_y' => '0',
                'report_footer_mode' => 'image',
                'report_footer_edge' => 'flush',
                'report_footer_offset_y' => '0',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('flush', SystemSetting::getValue('report_header_edge'));
        $this->assertEquals('0', SystemSetting::getValue('report_header_offset_y'));
        $this->assertEquals('flush', SystemSetting::getValue('report_footer_edge'));
        $this->assertEquals('0', SystemSetting::getValue('report_footer_offset_y'));

        // Verify print view renders flush CSS classes and zero-edge positioning
        $printResponse = $this->actingAs($this->admin)->get(route('admin.reports.print'));
        $printResponse->assertStatus(200);
        $printResponse->assertSee('doc-header-banner-wrap is-flush', false);
        $printResponse->assertSee('doc-footer-banner-wrap text-center is-flush', false);
    }
}

