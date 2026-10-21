<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use App\Services\BrandingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteAppearanceHeroGradientTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create(['role' => 'superadmin']);
    }

    public function test_superadmin_can_view_hero_gradient_toggle_in_site_appearance(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.settings.appearance'));

        $response->assertOk();
        $response->assertSee('id="setting-hero_gradient_enabled"', false);
        $response->assertSee('Hero Gradient Overlay', false);
        $response->assertSee('hero_gradient_enabled', false);
        $response->assertSee('id="hero-gradient-preview"', false);
    }

    public function test_superadmin_can_disable_hero_gradient_overlay(): void
    {
        $response = $this->actingAs($this->superAdmin)->put(route('superadmin.settings.system.update'), [
            'settings' => [
                'hero_gradient_enabled' => '0',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('0', SystemSetting::getValue('hero_gradient_enabled'));
    }

    public function test_homepage_renders_neutral_scrim_when_hero_gradient_is_disabled(): void
    {
        SystemSetting::setValue('hero_gradient_enabled', '0');
        BrandingService::flush();

        $response = $this->get('/');
        $response->assertOk();

        // Must output the neutral dark scrim without color gradient
        $response->assertSee('.hn-hero-scrim', false);
        $response->assertSee('rgba(15, 23, 42, .88) 0%,', false);
        $response->assertSee('rgba(15, 23, 42, .45) 100%) !important;', false);
    }

    public function test_homepage_renders_custom_gradient_when_enabled(): void
    {
        SystemSetting::setValue('hero_gradient_enabled', '1');
        SystemSetting::setValue('hero_gradient_start', '#e11d48');
        SystemSetting::setValue('hero_gradient_end', '#4c0519');
        BrandingService::flush();

        $response = $this->get('/');
        $response->assertOk();

        $response->assertSee('#e11d48 82%,', false);
        $response->assertSee('#4c0519 100%', false);
    }

    public function test_homepage_renders_neutral_scrim_when_both_fields_are_empty(): void
    {
        SystemSetting::setValue('hero_gradient_enabled', '1');
        SystemSetting::setValue('hero_gradient_start', '');
        SystemSetting::setValue('hero_gradient_end', '');
        SystemSetting::setValue('brand_primary', '#ff6c0a');
        BrandingService::flush();

        $response = $this->get('/');
        $response->assertOk();

        // Must output the neutral dark scrim and must NOT leak brand color into hero scrim
        $response->assertSee('.hn-hero-scrim', false);
        $response->assertSee('rgba(15, 23, 42, .45) 100%) !important;', false);
        $response->assertDontSee('#ff6c0a 82%', false);
    }
}
