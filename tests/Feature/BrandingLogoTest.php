<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use App\Services\BrandingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BrandingLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_logo_variant_resolves_light_and_white_independently(): void
    {
        $lightFile = 'uploads/branding/BaBs1LBIyjgpB7VOPwum.png';
        $whiteFile = 'uploads/branding/22wU23D4VGK4RDyMCzF6.png';

        SystemSetting::setValue('system_logo', 'uploads/branding/rvySzahcBA8a36LyUr5P.png');
        SystemSetting::setValue('logo_light', $lightFile);
        SystemSetting::setValue('logo_white', $whiteFile);
        BrandingService::flush();

        $this->assertEquals(asset($lightFile), BrandingService::logoVariant('light'));
        $this->assertEquals(asset($whiteFile), BrandingService::logoVariant('white'));
        $this->assertEquals(asset($lightFile), BrandingService::logoForBg('light'));
        $this->assertEquals(asset($whiteFile), BrandingService::logoForBg('dark'));
    }

    public function test_logo_variants_page_renders_each_variant_preview(): void
    {
        $superadmin = User::where('role', 'superadmin')->first();
        if (!$superadmin) {
            $superadmin = User::factory()->create(['role' => 'superadmin']);
        }

        $lightFile = 'uploads/branding/BaBs1LBIyjgpB7VOPwum.png';
        $whiteFile = 'uploads/branding/22wU23D4VGK4RDyMCzF6.png';

        SystemSetting::setValue('logo_light', $lightFile);
        SystemSetting::setValue('logo_white', $whiteFile);
        BrandingService::flush();

        $response = $this->actingAs($superadmin)->get(route('superadmin.settings.logo'));
        $response->assertOk();
        $response->assertSee($lightFile);
        $response->assertSee($whiteFile);
        $content = $response->getContent();
        $this->assertStringContainsString('src="' . asset($lightFile) . '"' . "\n" . '                                         id="lv-preview-logo_light"', $content);
        $this->assertStringContainsString('src="' . asset($whiteFile) . '"' . "\n" . '                                         id="lv-preview-logo_white"', $content);
    }

    public function test_uploading_logo_light_updates_setting_correctly(): void
    {
        $superadmin = User::where('role', 'superadmin')->first();
        if (!$superadmin) {
            $superadmin = User::factory()->create(['role' => 'superadmin']);
        }

        SystemSetting::create([
            'key'   => 'logo_light',
            'value' => 'assets/logo-light.png',
            'type'  => 'image',
            'group' => 'branding',
            'label' => 'Light Logo (light backgrounds)',
        ]);

        $file = $this->createTestImage('new_light.png', 200, 100);

        $response = $this->actingAs($superadmin)->put(route('superadmin.settings.system.update'), [
            'settings' => [
                'logo_light' => $file,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $updated = SystemSetting::getValue('logo_light');
        $this->assertNotNull($updated);
        $this->assertStringStartsWith('uploads/branding/', $updated);
        $this->assertStringEndsWith('.png', $updated);

        // Verify that brand_logo() and logoVariant('light') resolve the newly uploaded logo
        $this->assertEquals(asset($updated), BrandingService::logo());
        $this->assertEquals(asset($updated), BrandingService::logoVariant('light'));

        if (file_exists(public_path($updated))) {
            @unlink(public_path($updated));
        }
    }

    public function test_uploading_cropped_logo_white_updates_setting_correctly(): void
    {
        $superadmin = User::where('role', 'superadmin')->first();
        if (!$superadmin) {
            $superadmin = User::factory()->create(['role' => 'superadmin']);
        }

        SystemSetting::create([
            'key'   => 'logo_white',
            'value' => 'assets/logo-light.png',
            'type'  => 'image',
            'group' => 'branding',
            'label' => 'White Logo (dark backgrounds)',
        ]);

        $file = $this->createTestImage('cropped_white.png', 200, 100);

        $response = $this->actingAs($superadmin)->put(route('superadmin.settings.system.update'), [
            'settings' => [
                'cropped_input_logo_white' => $file,
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $updated = SystemSetting::getValue('logo_white');
        $this->assertNotNull($updated);
        $this->assertStringStartsWith('uploads/branding/', $updated);
        $this->assertStringEndsWith('.png', $updated);

        if (file_exists(public_path($updated))) {
            @unlink(public_path($updated));
        }
    }

    protected function createTestImage(string $filename = 'test.png', int $width = 200, int $height = 100): UploadedFile
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'test_img_');
        $img = imagecreatetruecolor($width, $height);
        imagepng($img, $tempPath);
        imagedestroy($img);

        return new UploadedFile($tempPath, $filename, 'image/png', null, true);
    }
}
