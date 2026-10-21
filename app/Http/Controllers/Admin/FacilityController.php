<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityImage;
use App\Models\FacilityCategory;
use App\Models\FacilityTag;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::with('images', 'category', 'tags')->withCount('timeSlots');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('location', 'like', "%$q%");
            });
        }

        if (in_array($request->availability, ['available', 'unavailable'], true)) {
            $query->where('is_available', $request->availability === 'available');
        }

        $facilities = $query->latest()->paginate(9)->withQueryString();

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        $categories = FacilityCategory::all();
        $tags = FacilityTag::all();
        return view('admin.facilities.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'amenities' => 'nullable|string',
            'operating_hours' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'terms_conditions' => 'nullable|string',
            'category_id' => 'nullable|exists:facility_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:facility_tags,id',
            'draft_token' => 'nullable|string|max:64',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:png,jpg,jpeg|max:5120', // 5MB max
        ]);

        // Normalize the availability checkbox (unchecked = not submitted).
        $validated['is_available'] = $request->has('is_available');
        $draftToken = $request->input('draft_token');
        unset($validated['draft_token']);

        return DB::transaction(function () use ($request, $validated, $draftToken) {
            $facility = Facility::create($validated);

            if ($request->has('tags')) {
                $facility->tags()->attach($request->tags);
            }

            // 1) Claim any images already uploaded as drafts (instant-upload flow).
            if ($draftToken) {
                FacilityImage::where('draft_token', $draftToken)
                    ->whereNull('facility_id')
                    ->update([
                        'facility_id' => $facility->id,
                        'draft_token' => null,
                    ]);
            }

            // 2) Fallback: handle any images posted the classic (synchronous) way.
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $this->uploadAndCompressImage($imageFile);

                    FacilityImage::create([
                        'facility_id' => $facility->id,
                        'image_path' => $path,
                        'is_featured' => false,
                    ]);
                }
            }

            // 3) Guarantee exactly one featured image survives.
            $this->ensureFeaturedImage($facility->id);

            AuditLog::create([
                'admin_id' => auth()->id(),
                'action' => 'created',
                'target_type' => 'Facility',
                'target_id' => $facility->id,
                'details' => "Admin created facility: {$facility->name}",
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('admin.facilities.index')->with('success', 'Facility created successfully!');
        });
    }

    public function edit(Facility $facility)
    {
        $facility->load('images', 'category', 'tags');
        $categories = FacilityCategory::all();
        $tags = FacilityTag::all();
        return view('admin.facilities.edit', compact('facility', 'categories', 'tags'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'amenities' => 'nullable|string',
            'operating_hours' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'terms_conditions' => 'nullable|string',
            'category_id' => 'nullable|exists:facility_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:facility_tags,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:png,jpg,jpeg|max:5120',
        ]);

        $validated['is_available'] = $request->has('is_available');

        return DB::transaction(function () use ($request, $facility, $validated) {
            $facility->update($validated);

            if ($request->has('tags')) {
                $facility->tags()->sync($request->tags);
            } else {
                $facility->tags()->detach();
            }

            // Fallback for classic synchronous uploads; instant uploads are
            // already attached directly to the facility via uploadImage().
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $path = $this->uploadAndCompressImage($imageFile);

                    FacilityImage::create([
                        'facility_id' => $facility->id,
                        'image_path' => $path,
                        'is_featured' => false,
                    ]);
                }
            }

            $this->ensureFeaturedImage($facility->id);

            AuditLog::create([
                'admin_id' => auth()->id(),
                'action' => 'updated',
                'target_type' => 'Facility',
                'target_id' => $facility->id,
                'details' => "Admin updated facility: {$facility->name}",
                'ip_address' => $request->ip(),
            ]);

            return redirect()->route('admin.facilities.edit', $facility->id)->with('success', 'Facility updated successfully!');
        });
    }

    /**
     * Instant / draft image upload (AJAX).
     *
     * Called the moment an image is cropped, so photos are persisted before the
     * facility form is submitted. On Create, images are stored against a
     * client-generated draft_token and claimed once the facility is saved. On
     * Edit, images are attached directly to the facility.
     *
     * Returns JSON describing the stored image so the client can render it.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
            'facility_id' => 'nullable|exists:facilities,id',
            'draft_token' => 'nullable|string|max:64',
        ]);

        if (!$request->filled('facility_id') && !$request->filled('draft_token')) {
            return response()->json([
                'ok'      => false,
                'message' => 'A facility or draft token is required.',
            ], 422);
        }

        $facilityId = $request->input('facility_id');
        $draftToken = $facilityId ? null : $request->input('draft_token');

        $path = $this->uploadAndCompressImage($request->file('image'));

        // Feature this image automatically if it is the first for its owner.
        $isFirst = $facilityId
            ? !FacilityImage::where('facility_id', $facilityId)->exists()
            : !FacilityImage::where('draft_token', $draftToken)->exists();

        $nextOrder = ($facilityId
            ? FacilityImage::where('facility_id', $facilityId)->max('sort_order')
            : FacilityImage::where('draft_token', $draftToken)->max('sort_order')) ?? -1;

        $image = FacilityImage::create([
            'facility_id' => $facilityId,
            'draft_token' => $draftToken,
            'image_path'  => $path,
            'is_featured' => $isFirst,
            'sort_order'  => $nextOrder + 1,
        ]);

        return response()->json([
            'ok'    => true,
            'image' => [
                'id'          => $image->id,
                'url'         => $image->url,
                'is_featured' => $image->is_featured,
                'sort_order'  => $image->sort_order,
            ],
        ]);
    }

    /**
     * Persist a new gallery order (AJAX). Expects an ordered array of image ids.
     */
    public function reorderImages(Request $request)
    {
        $data = $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer',
        ]);

        DB::transaction(function () use ($data) {
            foreach (array_values($data['order']) as $position => $imageId) {
                FacilityImage::where('id', $imageId)->update(['sort_order' => $position]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function setFeaturedImage(Request $request, FacilityImage $image)
    {
        DB::transaction(function () use ($image) {
            $scope = $image->facility_id
                ? FacilityImage::where('facility_id', $image->facility_id)
                : FacilityImage::where('draft_token', $image->draft_token);

            (clone $scope)->update(['is_featured' => false]);
            $image->update(['is_featured' => true]);

            if ($image->facility_id) {
                AuditLog::create([
                    'admin_id' => auth()->id(),
                    'action' => 'updated',
                    'target_type' => 'Facility',
                    'target_id' => $image->facility_id,
                    'details' => "Admin set a new featured image for facility ID: #{$image->facility_id}",
                    'ip_address' => request()->ip(),
                ]);
            }
        });

        if ($this->wantsJson($request)) {
            return response()->json(['ok' => true, 'featured_id' => $image->id]);
        }

        return redirect()->route('admin.facilities.edit', $image->facility_id)->with('success', 'Featured image updated!');
    }

    public function deleteImage(Request $request, FacilityImage $image)
    {
        $facilityId  = $image->facility_id;
        $draftToken  = $image->draft_token;
        $wasFeatured = $image->is_featured;

        DB::transaction(function () use ($image, $facilityId, $draftToken, $wasFeatured) {
            $this->deleteImageFile($image->image_path);
            $image->delete();

            // Promote a replacement featured image within the same owner scope.
            if ($wasFeatured) {
                $next = $facilityId
                    ? FacilityImage::where('facility_id', $facilityId)->orderBy('sort_order')->first()
                    : FacilityImage::where('draft_token', $draftToken)->orderBy('sort_order')->first();
                if ($next) {
                    $next->update(['is_featured' => true]);
                }
            }

            if ($facilityId) {
                AuditLog::create([
                    'admin_id' => auth()->id(),
                    'action' => 'updated',
                    'target_type' => 'Facility',
                    'target_id' => $facilityId,
                    'details' => "Admin deleted an image from facility ID: #{$facilityId}" . ($wasFeatured ? " (was featured)" : ""),
                    'ip_address' => request()->ip(),
                ]);
            }
        });

        if ($this->wantsJson($request)) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.facilities.edit', $facilityId)->with('success', 'Image deleted successfully!');
    }

    /**
     * Ensure a facility has exactly one featured image.
     */
    protected function ensureFeaturedImage(int $facilityId): void
    {
        $hasFeatured = FacilityImage::where('facility_id', $facilityId)->where('is_featured', true)->exists();
        if ($hasFeatured) {
            return;
        }
        $first = FacilityImage::where('facility_id', $facilityId)->orderBy('sort_order')->orderBy('id')->first();
        if ($first) {
            $first->update(['is_featured' => true]);
        }
    }

    protected function wantsJson(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson() || $request->boolean('ajax');
    }

    public function destroy(Facility $facility)
    {
        $facilityName = $facility->name;
        $facilityId = $facility->id;

        return DB::transaction(function () use ($facility, $facilityName, $facilityId) {
            // Delete all associated images from storage
            foreach ($facility->images as $image) {
                $this->deleteImageFile($image->image_path);
            }

            $facility->delete();

            AuditLog::create([
                'admin_id' => auth()->id(),
                'action' => 'deleted',
                'target_type' => 'Facility',
                'target_id' => $facilityId,
                'details' => "Admin deleted facility: {$facilityName}",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->route('admin.facilities.index')->with('success', 'Facility deleted successfully!');
        });
    }

    /**
     * Upload and compress a facility gallery image.
     *
     * Gallery images are already 16:9 (1280x720) from the client cropper, so we
     * cap at 1600px and re-encode (WebP when available) to shrink file size.
     */
    protected function uploadAndCompressImage($file)
    {
        return app(\App\Services\ImageService::class)->store($file, 'uploads/facilities', [
            'maxWidth'  => 1600,
            'maxHeight' => 900,
            'quality'   => 82,
            'format'    => 'auto',
        ]);
    }

    /**
     * Delete an image file, checking both storage locations:
     * new uploads live in public/uploads/..., legacy ones in storage/app/public/...
     */
    protected function deleteImageFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'uploads/') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        } elseif (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
