<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageHeroUploadTest extends TestCase
{
    use RefreshDatabase;
    public function test_superadmin_can_upload_hero_fallback_image(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $file = UploadedFile::fake()->image('test_fallback.jpg', 1920, 1080)->size(12000); // 12MB fake image

        $response = $this->actingAs($superadmin)->put(route('superadmin.settings.homepage.update'), [
            'editing_section'   => 'hero',
            'hero_eyebrow'      => 'Test Eyebrow',
            'hero_heading'      => 'Test Heading',
            'hero_cta_text'     => 'Book Space',
            'hero_sub'          => 'Test Subtitle',
            'hero_media_poster' => $file,
        ]);

        $response->assertRedirect(route('superadmin.settings.hero'));
        $response->assertSessionHas('success');

        $setting = SystemSetting::where('key', 'homepage_content')->first();
        $this->assertNotNull($setting);
        $data = json_decode($setting->value, true);

        $posterPath = data_get($data, 'hero.media.poster');
        $this->assertNotNull($posterPath);
        $this->assertStringStartsWith('uploads/homepage/hero-poster-', $posterPath);

        // Verify the file was actually stored on disk and clean it up
        $fullPath = public_path($posterPath);
        $this->assertFileExists($fullPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public function test_post_too_large_exception_redirects_back_with_friendly_error(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $this->actingAs($superadmin);

        $response = $this->from(route('superadmin.settings.hero'))
            ->call(
                'POST',
                route('superadmin.settings.homepage.update'),
                [],
                [],
                [],
                ['CONTENT_LENGTH' => 200 * 1024 * 1024] // 200MB exceeds post_max_size
            );

        $response->assertRedirect(route('superadmin.settings.hero'));
        $response->assertSessionHas('error');
    }

    public function test_superadmin_can_upload_image_as_hero_media_and_it_renders_on_homepage(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $imageFile = UploadedFile::fake()->image('custom_hero.jpg', 1920, 1080)->size(5000); // 5MB image

        $response = $this->actingAs($superadmin)->put(route('superadmin.settings.homepage.update'), [
            'editing_section'   => 'hero',
            'hero_media'        => $imageFile,
            'hero_media_enabled'=> '1',
        ]);

        $response->assertRedirect(route('superadmin.settings.hero'));
        $response->assertSessionHas('success');

        $setting = SystemSetting::where('key', 'homepage_content')->first();
        $this->assertNotNull($setting);
        $data = json_decode($setting->value, true);

        $this->assertEquals('image', data_get($data, 'hero.media.type'));
        $mediaUrl = data_get($data, 'hero.media.url');
        $this->assertNotNull($mediaUrl);
        $this->assertStringStartsWith('uploads/homepage/hero-media-', $mediaUrl);

        // Verify the file was stored
        $fullPath = public_path($mediaUrl);
        $this->assertFileExists($fullPath);

        // Now visit the homepage and ensure the uploaded image URL is in the HTML, NOT the fallback poster!
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('<img class="hn-hero-media-image" src="' . asset($mediaUrl) . '"', false);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public function test_superadmin_can_access_homepage_settings_category_pages(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($superadmin)->get(route('superadmin.settings.hero'));
        $response->assertOk();
        $response->assertViewHas('availableImages');
    }

    public function test_category_correctly_lists_facility_images(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $dir = public_path('uploads/facilities');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $testFile1 = $dir . '/test_scan_1.jpg';
        $testFile2 = $dir . '/test_scan_2.PNG';
        $testFile3 = $dir . '/ignore_me.txt';
        file_put_contents($testFile1, 'fake jpg');
        file_put_contents($testFile2, 'fake png');
        file_put_contents($testFile3, 'fake txt');

        try {
            $response = $this->actingAs($superadmin)->get(route('superadmin.settings.gallery'));
            $response->assertOk();
            $availableImages = $response->viewData('availableImages');

            $filenames = array_column($availableImages, 'filename');
            $this->assertContains('test_scan_1.jpg', $filenames);
            $this->assertContains('test_scan_2.PNG', $filenames);
            $this->assertNotContains('ignore_me.txt', $filenames);
        } finally {
            @unlink($testFile1);
            @unlink($testFile2);
            @unlink($testFile3);
        }
    }

    public function test_superadmin_upload_via_ajax_returns_json_response(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
        ]);

        $imageFile = UploadedFile::fake()->image('custom_ajax_hero.jpg', 800, 600)->size(1500);

        $response = $this->actingAs($superadmin)->putJson(route('superadmin.settings.homepage.update'), [
            'editing_section'   => 'hero',
            'hero_media'        => $imageFile,
            'hero_media_enabled'=> '1',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'redirect',
            'media',
        ]);

        $mediaUrl = $response->json('media.url');
        $this->assertNotNull($mediaUrl);
        $fullPath = public_path($mediaUrl);
        $this->assertFileExists($fullPath);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}


