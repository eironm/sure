<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountDeactivated;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

/**
 * Admin User Management.
 * Scope: Admins manage ONLY guest and cashier accounts.
 * SuperAdmin/Admin accounts are not visible or modifiable here.
 */
class UserController extends Controller
{
    /** Roles that admin is allowed to manage */
    const MANAGEABLE_ROLES = ['guest', 'cashier', 'staff'];

    /**
     * Apply an uploaded (or removed) avatar to the target user — same
     * pipeline as the profile page so the photo propagates to every
     * avatar tile automatically.
     */
    private function handleAvatar(Request $request, User $user): void
    {
        // Explicit removal — clear the avatar column and delete the file
        if ($request->boolean('avatar_removed')) {
            $this->deleteAvatarFile($user->avatar);
            $user->avatar = null;
            $user->save();

            return;
        }

        if (!$request->hasFile('avatar')) {
            return;
        }

        $this->deleteAvatarFile($user->avatar);

        $user->avatar = app(ImageService::class)->store(
            $request->file('avatar'),
            'uploads/avatars',
            ['maxWidth' => 512, 'maxHeight' => 512, 'quality' => 85, 'format' => 'auto']
        );
        $user->save();
    }

    /**
     * Delete an avatar file, handling both storage conventions:
     * new uploads in public/uploads/avatars, legacy ones in storage/app/public.
     */
    private function deleteAvatarFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, 'uploads/') && file_exists(public_path($path))) {
            @unlink(public_path($path));
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }
    }

    public function index(Request $request)
    {
        $this->denyStaff();

        $query = User::query();

        // If a specific role is selected (and not 'all')
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roleCounts = self::buildRoleCounts();

        return view('admin.users.index', compact('users', 'roleCounts'));
    }

    /**
     * Per-role account counts for the filter tabs (2 queries instead of 7).
     */
    public static function buildRoleCounts(): array
    {
        $byRole = User::selectRaw('role, COUNT(*) as total')->groupBy('role')->pluck('total', 'role');

        return [
            'all'        => $byRole->sum(),
            'superadmin' => $byRole->get('superadmin', 0),
            'admin'      => $byRole->get('admin', 0),
            'cashier'    => $byRole->get('cashier', 0),
            'staff'      => $byRole->get('staff', 0),
            'guest'      => $byRole->get('guest', 0),
            'inactive'   => User::where('is_active', false)->count(),
        ];
    }

    public function create()
    {
        $this->denyStaff();

        $roles = self::MANAGEABLE_ROLES;
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->denyStaff();

        $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'in:guest,cashier,staff'],
            'client_type' => ['nullable', 'in:internal,external'],
            'contact_number' => ['nullable', 'string', 'max:30', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            // Universal personal identity
            'date_of_birth'    => ['nullable', 'date'],
            'sex'              => ['nullable', 'in:Male,Female'],
            'civil_status'     => ['nullable', 'in:Single,Married,Widowed,Separated'],
            'nationality'      => ['nullable', 'string', 'max:100'],
            'personal_address' => ['nullable', 'string', 'max:1000'],
            // Internal
            'university_email' => ['nullable', 'string', 'email', 'max:255'],
            'university_id' => ['nullable', 'string', 'max:50'],
            'affiliation_type' => ['nullable', 'in:Student,Faculty,Admin/Staff'],
            'college_department' => ['nullable', 'string', 'max:255'],
            // External
            'organization_name' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
            'government_id_type' => ['nullable', 'string', 'max:100'],
            'government_id_number' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $fullName = $request->first_name . ($request->middle_name ? ' ' . $request->middle_name : '') . ' ' . $request->last_name;

        $user = User::create([
            'name'        => $fullName,
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'password'    => $request->password,
            'role'        => $request->role,
            'is_active'   => true,
            'client_type' => $request->client_type,
            'contact_number' => $request->contact_number,
            // Universal personal identity
            'date_of_birth'    => $request->date_of_birth,
            'sex'              => $request->sex,
            'civil_status'     => $request->civil_status,
            'nationality'      => $request->nationality,
            'personal_address' => $request->personal_address,
            // Classification-specific
            'university_email' => $request->university_email,
            'university_id' => $request->university_id,
            'affiliation_type' => $request->affiliation_type,
            'college_department' => $request->college_department,
            'organization_name' => $request->organization_name,
            'billing_address' => $request->billing_address,
            'government_id_type' => $request->government_id_type,
            'government_id_number' => $request->government_id_number,
        ]);

        $this->handleAvatar($request, $user);

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'created',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'details'     => "Admin created user: {$user->email} ({$user->role})",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->denyStaff();
        $this->ensureManageable($user);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->denyStaff();
        $this->ensureManageable($user);

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot edit your own account via the management panel.');
        }

        $roles = self::MANAGEABLE_ROLES;
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->denyStaff();
        $this->ensureManageable($user);

        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Unauthorized self-modification attempt.');
        }

        $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role'        => ['required', 'in:guest,cashier,staff'],
            'client_type' => ['nullable', 'in:internal,external'],
            'contact_number' => ['nullable', 'string', 'max:30', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            // Universal personal identity
            'date_of_birth'    => ['nullable', 'date'],
            'sex'              => ['nullable', 'in:Male,Female'],
            'civil_status'     => ['nullable', 'in:Single,Married,Widowed,Separated'],
            'nationality'      => ['nullable', 'string', 'max:100'],
            'personal_address' => ['nullable', 'string', 'max:1000'],
            // Internal
            'university_email' => ['nullable', 'string', 'email', 'max:255'],
            'university_id' => ['nullable', 'string', 'max:50'],
            'affiliation_type' => ['nullable', 'in:Student,Faculty,Admin/Staff'],
            'college_department' => ['nullable', 'string', 'max:255'],
            // External
            'organization_name' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
            'government_id_type' => ['nullable', 'string', 'max:100'],
            'government_id_number' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $oldRole  = $user->role;
        $oldEmail = $user->email;
        $fullName = $request->first_name . ($request->middle_name ? ' ' . $request->middle_name : '') . ' ' . $request->last_name;

        $userData = [
            'name'        => $fullName,
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'role'        => $request->role,
            'client_type' => $request->client_type,
            'contact_number' => $request->contact_number,
            // Universal personal identity
            'date_of_birth'    => $request->date_of_birth,
            'sex'              => $request->sex,
            'civil_status'     => $request->civil_status,
            'nationality'      => $request->nationality,
            'personal_address' => $request->personal_address,
            // Classification-specific
            'university_email' => $request->university_email,
            'university_id' => $request->university_id,
            'affiliation_type' => $request->affiliation_type,
            'college_department' => $request->college_department,
            'organization_name' => $request->organization_name,
            'billing_address' => $request->billing_address,
            'government_id_type' => $request->government_id_type,
            'government_id_number' => $request->government_id_number,
            'is_active' => $request->boolean('is_active', $user->is_active),
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $userData['password'] = $request->password;
        }

        $user->update($userData);

        $this->handleAvatar($request, $user);

        $changes = [];
        if ($oldEmail !== $user->email) $changes[] = "email → {$user->email}";
        if ($oldRole  !== $user->role)  $changes[] = "role: {$oldRole} → {$user->role}";
        if ($request->filled('password')) $changes[] = "password reset";
        if ($request->file('avatar')) $changes[] = "profile photo updated";
        if ($request->boolean('avatar_removed')) $changes[] = "profile photo removed";

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'updated',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'details'     => "Admin updated user #{$user->id}: " . (empty($changes) ? 'no major changes' : implode(', ', $changes)),
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Toggle active status — admin can only toggle guest accounts.
     */
    public function toggleActive(User $user)
    {
        $this->denyStaff();
        $this->ensureManageable($user);

        $user->update(['is_active' => !$user->is_active]);
        $action = $user->is_active ? 'activated' : 'deactivated';

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => $action,
            'target_type' => 'User',
            'target_id'   => $user->id,
            'details'     => "Admin {$action} guest account #{$user->id} ({$user->email})",
            'ip_address'  => request()->ip(),
        ]);

        // Send activation/deactivation email — never crash the panel if the
        // mail transport is misconfigured; the account change still saves
        // and a warning flash notifies the admin.
        $mailOk = true;
        if ($user->is_active) {
            $mailOk = safe_mail($user->email, new AccountApproved($user));
        } else {
            $mailOk = safe_mail($user->email, new AccountDeactivated($user));
        }

        $response = back()->with('success', "Account has been {$action}.");

        if (!$mailOk) {
            $response = $response->with('warning', 'The account was updated, but the notification email could not be sent. Check the mail configuration.');
        }

        return $response;
    }

    public function destroy(User $user)
    {
        $this->denyStaff();
        $this->ensureManageable($user);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userId    = $user->id;
        $userEmail = $user->email;
        $user->delete();

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'deleted',
            'target_type' => 'User',
            'target_id'   => $userId,
            'details'     => "Admin deleted user: {$userEmail} (ID: #{$userId})",
            'ip_address'  => request()->ip(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /** Guard: abort if the target user is outside admin's scope */
    private function ensureManageable(User $user): void
    {
        if (!in_array($user->role, self::MANAGEABLE_ROLES)) {
            abort(403, 'You do not have permission to manage this account type.');
        }
    }

    /** Guard: staff accounts may not manage users at all */
    private function denyStaff(): void
    {
        if (auth()->user()->isStaff()) {
            abort(403, 'Staff accounts do not have access to user management.');
        }
    }
}
