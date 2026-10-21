<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandingSettingsCleanUiTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::where('role', 'superadmin')->first() ?: User::factory()->create(['role' => 'superadmin']);
    }

    public function test_superadmin_can_access_branding_page_with_clean_sections(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.settings.branding'));

        $response->assertOk();

        // 8 Clean Section Cards exist
        $response->assertSee('id="sec-identity"', false);
        $response->assertSee('id="sec-colors"', false);
        $response->assertSee('id="sec-hero"', false);
        $response->assertSee('id="sec-contact"', false);
        $response->assertSee('id="sec-social"', false);
        $response->assertSee('id="sec-seo"', false);
        $response->assertSee('id="sec-email"', false);
        $response->assertSee('id="sec-policies"', false);

        // Sidebar subsection jump links exist
        $response->assertSee('href="#sec-identity"', false);
        $response->assertSee('href="#sec-colors"', false);
        $response->assertSee('href="#sec-hero"', false);
        $response->assertSee('href="#sec-contact"', false);
        $response->assertSee('href="#sec-social"', false);
        $response->assertSee('href="#sec-seo"', false);
        $response->assertSee('href="#sec-email"', false);
        $response->assertSee('href="#sec-policies"', false);

        // Report Letterhead & Footer is properly named in sidebar subnav
        $response->assertSee('Report Letterhead &amp; Footer', false);

        // The misplaced raw report inputs are NOT on this page
        $response->assertDontSee('name="settings[report_header_mode]"', false);
        $response->assertDontSee('name="settings[report_header_line1]"', false);
        $response->assertDontSee('name="settings[report_footer_left_title]"', false);
    }

    public function test_superadmin_can_update_branding_settings(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('superadmin.settings.system.update'), [
            'settings' => [
                'system_name'     => 'SURE Elite Facilities',
                'system_tagline'  => 'Premier Reservations Portal',
                'brand_primary'   => '#3b82f6',
                'contact_email'   => 'support@sure-facilities.edu',
                'internal_fee_waived' => '1',
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('SURE Elite Facilities', SystemSetting::getValue('system_name'));
        $this->assertEquals('Premier Reservations Portal', SystemSetting::getValue('system_tagline'));
        $this->assertEquals('#3b82f6', SystemSetting::getValue('brand_primary'));
        $this->assertEquals('support@sure-facilities.edu', SystemSetting::getValue('contact_email'));
        $this->assertEquals('1', SystemSetting::getValue('internal_fee_waived'));
    }
}
