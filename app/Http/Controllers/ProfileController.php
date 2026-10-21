<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $reservations = \App\Models\Reservation::where('user_id', $user->id)
            ->whereNotIn('status', ['denied', 'cancelled', 'expired_unpaid_cancelled'])
            ->orderBy('start_date', 'desc')
            ->get();

        $approved = $reservations->where('status', 'approved')->count();
        $pending = $reservations->where('status', 'pending')->count();
        $completed = $reservations->where('status', 'completed')->count();

        $today = now();
        $upcoming = $reservations->filter(function ($r) use ($today) {
            return $r->end_date->gte($today) && in_array($r->status, ['pending', 'approved']);
        });
        $next = $upcoming->sortBy('start_date')->take(3)->values();

        $totalPeople = (int)$reservations->sum('number_of_people');

        $favorite = null;
        $favCount = 0;
        foreach ($reservations->groupBy('facility_id') as $facId => $items) {
            if ($items->count() > $favCount) {
                $favCount = $items->count();
                $fac = \App\Models\Facility::find($facId);
                $favorite = $fac ? $fac->name : null;
            }
        }

        $recent = $reservations->take(5)->values();

        $summary = [
            'total' => $reservations->count(),
            'approved' => $approved,
            'pending' => $pending,
            'completed' => $completed,
            'total_people' => $totalPeople,
            'favorite_facility' => $favorite,
            'favorite_count' => $favCount,
            'upcoming_next' => $next->map(function ($r) {
                return [
                    'event_title' => $r->event_title,
                    'facility' => $r->facility ? $r->facility->name : null,
                    'start_date' => $r->start_date->format('M d, Y'),
                    'start_time' => $r->start_time,
                    'end_time' => $r->end_time,
                    'date_range' => (\Carbon\Carbon::parse($r->start_date->format('Y-m-d') . ' ' . substr($r->start_time, 11, 5) . ':00')->format('M. d, Y g:i A')
                        . ' – '
                        . \Carbon\Carbon::parse($r->start_date->format('Y-m-d') . ' ' . substr($r->end_time, 11, 5) . ':00')->format('g:i A')),
                    'status' => $r->status,
                    'status_label' => $r->status_label,
                    'people' => (int)($r->number_of_people ?? 0),
                    'ref' => $r->booking_reference,
                ];
            })->toArray(),
            'recent' => $recent->map(function ($r) {
                return [
                    'event_title' => $r->event_title,
                    'facility' => $r->facility ? $r->facility->name : null,
                    'start_date' => $r->start_date->format('M d, Y'),
                    'start_time' => $r->start_time,
                    'end_time' => $r->end_time,
                    'date_range' => (\Carbon\Carbon::parse($r->start_date->format('Y-m-d') . ' ' . substr($r->start_time, 11, 5) . ':00')->format('M. d, Y g:i A')
                        . ' – '
                        . \Carbon\Carbon::parse($r->start_date->format('Y-m-d') . ' ' . substr($r->end_time, 11, 5) . ':00')->format('g:i A')),
                    'status' => $r->status,
                    'status_label' => $r->status_label,
                    'people' => (int)($r->number_of_people ?? 0),
                    'ref' => $r->booking_reference,
                ];
            })->toArray(),
        ];

        return view('profile.edit', [
            'user' => $user,
            'summary' => $summary,
        ]);
    }

    /**
     * Update the user's profile information.
     * Admin Policy: Only admins can update name/email. Guests can only change password.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Only admins and superadmins can update profile information
        if (!$user->isAdminOrAbove()) {
            return Redirect::route('profile.edit')->with('error', 'For security reasons, guests cannot update their personal information. Please contact an administrator for any changes.');
        }

        // Update profile information for admins only
        $validated = $request->validated();
        $user->fill($validated);

        if (isset($validated['first_name']) && isset($validated['last_name'])) {
            $middle = !empty($validated['middle_name']) ? ' ' . $validated['middle_name'] : '';
            $user->name = trim($validated['first_name'] . $middle . ' ' . $validated['last_name']);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update a guest's own contact details (contact_number, personal_address).
     * Name/email remain admin-managed per policy; guests may update how they
     * can be reached and where they live.
     */
    public function updateContact(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'contact_number'  => ['nullable', 'string', 'max:30', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            'personal_address' => ['nullable', 'string', 'max:500'],
        ]);

        // Trim and normalize
        foreach (['contact_number', 'personal_address'] as $field) {
            if (isset($validated[$field])) {
                $value = trim((string)$validated[$field]);
                $user->$field = $value === '' ? null : $value;
            }
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Contact details updated successfully.',
            'contact_number' => $user->contact_number,
            'personal_address' => $user->personal_address,
        ]);
    }

    /**
     * Upload or update the user's profile avatar.
     * Returns JSON for AJAX requests.
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'avatar.max' => 'The profile image must be smaller than 2 MB.',
            'avatar.mimes' => 'Only JPEG, PNG, and WebP images are accepted.',
        ]);

        $user = $request->user();

        // Delete old avatar if it exists (either storage convention).
        $this->deleteAvatarFile($user->avatar);

        // Store + compress the new avatar. Avatars are square-ish display crops,
        // so cap at 512px and re-encode (WebP when available) to keep them tiny.
        $path = app(\App\Services\ImageService::class)->store(
            $request->file('avatar'),
            'uploads/avatars',
            ['maxWidth' => 512, 'maxHeight' => 512, 'quality' => 85, 'format' => 'auto']
        );

        $user->avatar = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated successfully.',
            'avatar_url' => $user->avatarUrl(),
        ]);
    }

    /**
     * Remove the user's profile avatar.
     */
    public function removeAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        $this->deleteAvatarFile($user->avatar);

        $user->avatar = null;
        $user->save();

        // Return the initials for fallback display
        $initial = strtoupper(substr($user->first_name ?? $user->name, 0, 1));

        return response()->json([
            'success' => true,
            'message' => 'Profile photo removed.',
            'initial' => $initial,
        ]);
    }

    /**
     * Delete an avatar file, handling both storage conventions:
     * new uploads in public/uploads/avatars, legacy ones in storage/app/public.
     */
    protected function deleteAvatarFile(?string $path): void
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

    /**
     * Delete the user's account.
     * Handles cascading deletion of related records to avoid foreign key constraint violations.
     * Returns JSON for AJAX requests, redirects for traditional form submissions.
     */
    public function destroy(Request $request)
    {
        // Check if this is an AJAX request
        $isAjax = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        try {
            // Validate password
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
        } catch (ValidationException $e) {
            // If AJAX request, return JSON error
            if ($isAjax) {
                return response()->json([
                    'errors' => $e->errors(),
                    'message' => 'Invalid password'
                ], 422);
            }
            // Otherwise, throw the exception for traditional form handling
            throw $e;
        }

        $user = $request->user();

        try {
            // Delete avatar file if exists
            $this->deleteAvatarFile($user->avatar);

            // Delete all reservations associated with this user
            $reservations = \App\Models\Reservation::where('user_id', $user->id)->get();
            
            foreach ($reservations as $reservation) {
                // Delete slot reservations first (child records)
                $reservation->slotReservations()->delete();
                // Then delete the reservation
                $reservation->delete();
            }

            // Delete audit logs where this user is the admin
            \App\Models\AuditLog::where('admin_id', $user->id)->delete();

            Auth::logout();

            // Finally, delete the user
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // If AJAX request, return JSON success
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account deleted successfully',
                    'redirect' => '/'
                ], 200);
            }

            // Otherwise, redirect for traditional form submission
            return Redirect::to('/');
        } catch (\Exception $e) {
            // If AJAX request, return JSON error
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting your account. Please try again.',
                ], 500);
            }
            // Otherwise, redirect with error for traditional form submission
            return Redirect::route('profile.edit')->with('error', 'An error occurred while deleting your account. Please try again.');
        }
    }
}
