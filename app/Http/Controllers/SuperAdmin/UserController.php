<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\AccountApproved;
use App\Mail\AccountDeactivated;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display all users with optional role filter.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('first_name', 'like', "%$q%")
                    ->orWhere('last_name', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roleCounts = \App\Http\Controllers\Admin\UserController::buildRoleCounts();

        return view('superadmin.users.index', compact('users', 'roleCounts'));
    }

    /**
     * Show the form to create any role.
     */
    public function create()
    {
        // SuperAdmin can create any role except SuperAdmin
        $roles = [User::ROLE_GUEST, User::ROLE_CASHIER, User::ROLE_STAFF, User::ROLE_ADMIN];
        return view('superadmin.users.create', compact('roles'));
    }

    /**
     * Store a new user of any role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'role'        => ['required', 'in:guest,cashier,staff,admin'],
            'is_active'   => ['boolean'],
            'client_type' => ['nullable', 'in:internal,external'],
            'contact_number' => ['nullable', 'string', 'max:30', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            'university_email' => ['nullable', 'string', 'email', 'max:255'],
            'university_id' => ['nullable', 'string', 'max:50'],
            'affiliation_type' => ['nullable', 'in:Student,Faculty,Admin/Staff'],
            'college_department' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
            'government_id_type' => ['nullable', 'string', 'max:100'],
            'government_id_number' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        $fullName = trim("{$validated['first_name']} " . ($validated['middle_name'] ?? '') . " {$validated['last_name']}");

        $user = User::create([
            'name'        => $fullName,
            'first_name'  => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name'   => $validated['last_name'],
            'email'       => $validated['email'],
            'password'    => $validated['password'],
            'role'        => $validated['role'],
            'is_active'   => true, // New accounts always start active
            'client_type' => $validated['client_type'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            // Universal personal identity
            'date_of_birth'    => $validated['date_of_birth'] ?? null,
            'sex'              => $validated['sex'] ?? null,
            'civil_status'     => $validated['civil_status'] ?? null,
            'nationality'      => $validated['nationality'] ?? null,
            'personal_address' => $validated['personal_address'] ?? null,
            // Classification-specific
            'university_email' => $validated['university_email'] ?? null,
            'university_id' => $validated['university_id'] ?? null,
            'affiliation_type' => $validated['affiliation_type'] ?? null,
            'college_department' => $validated['college_department'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'billing_address' => $validated['billing_address'] ?? null,
            'government_id_type' => $validated['government_id_type'] ?? null,
            'government_id_number' => $validated['government_id_number'] ?? null,
        ]);

        // Optional profile photo — same pipeline as the profile page so the
        // photo propagates to every avatar tile automatically.
        $this->syncAvatar($request, $user);

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'created',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'details'     => "SuperAdmin created account: {$user->email} ({$user->role})",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', "Account for {$user->name} created successfully.");
    }

    /**
     * Show edit form for any user.
     */
    public function edit(User $user)
    {
        // Cannot edit yourself via this panel
        if (auth()->id() === $user->id) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Use the Profile page to edit your own account.');
        }

        // SuperAdmin cannot edit other SuperAdmins
        if ($user->role === User::ROLE_SUPERADMIN) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'SuperAdmin accounts cannot be modified via this panel.');
        }

        // SuperAdmin can change user to any role except SuperAdmin
        $roles = [User::ROLE_GUEST, User::ROLE_CASHIER, User::ROLE_STAFF, User::ROLE_ADMIN];
        return view('superadmin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update any user — role, status, password, details.
     */
    public function update(Request $request, User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Unauthorized self-modification.');
        }

        if ($user->role === User::ROLE_SUPERADMIN) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'SuperAdmin accounts cannot be modified.');
        }

        $validated = $request->validate([
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'role'        => ['required', 'in:guest,cashier,staff,admin'],
            'is_active'   => ['sometimes', 'boolean'],
            'client_type' => ['nullable', 'in:internal,external'],
            'contact_number' => ['nullable', 'string', 'max:30', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            'university_email' => ['nullable', 'string', 'email', 'max:255'],
            'university_id' => ['nullable', 'string', 'max:50'],
            'affiliation_type' => ['nullable', 'in:Student,Faculty,Admin/Staff'],
            'college_department' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
            'government_id_type' => ['nullable', 'string', 'max:100'],
            'government_id_number' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        $oldRole   = $user->role;
        $oldEmail  = $user->email;
        $oldStatus = $user->is_active;

        $updateData = [
            'name'        => trim("{$validated['first_name']} " . ($validated['middle_name'] ?? '') . " {$validated['last_name']}"),
            'first_name'  => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name'   => $validated['last_name'],
            'email'       => $validated['email'],
            'role'        => $validated['role'],
            'is_active'   => $request->boolean('is_active', $user->is_active),
            'client_type' => $validated['client_type'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            // Universal personal identity
            'date_of_birth'    => $validated['date_of_birth'] ?? null,
            'sex'              => $validated['sex'] ?? null,
            'civil_status'     => $validated['civil_status'] ?? null,
            'nationality'      => $validated['nationality'] ?? null,
            'personal_address' => $validated['personal_address'] ?? null,
            // Classification-specific
            'university_email' => $validated['university_email'] ?? null,
            'university_id' => $validated['university_id'] ?? null,
            'affiliation_type' => $validated['affiliation_type'] ?? null,
            'college_department' => $validated['college_department'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'billing_address' => $validated['billing_address'] ?? null,
            'government_id_type' => $validated['government_id_type'] ?? null,
            'government_id_number' => $validated['government_id_number'] ?? null,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $updateData['password'] = $request->password;
        }

        $user->update($updateData);

        // Optional profile photo — same pipeline as the profile page.
        $this->syncAvatar($request, $user);

        // Audit
        $changes = [];
        if ($oldEmail  !== $user->email)     $changes[] = "email → {$user->email}";
        if ($oldRole   !== $user->role)      $changes[] = "role: {$oldRole} → {$user->role}";
        if ($oldStatus !== $user->is_active) $changes[] = "status: " . ($user->is_active ? 'activated' : 'deactivated');
        if ($request->filled('password'))    $changes[] = "password reset";

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'updated',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'details'     => "SuperAdmin updated #{$user->id}: " . (empty($changes) ? 'no key changes' : implode(', ', $changes)),
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', "Account for {$user->name} updated.");
    }

    /**
     * Toggle account active/inactive status (SuperAdmin only — no deletes).
     */
    public function toggleActive(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->role === User::ROLE_SUPERADMIN) {
            return back()->with('error', 'SuperAdmin accounts cannot be deactivated from this panel.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $action = $user->is_active ? 'activated' : 'deactivated';

        AuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => $action,
            'target_type' => 'User',
            'target_id'   => $user->id,
            'details'     => "SuperAdmin {$action} account #{$user->id} ({$user->email})",
            'ip_address'  => request()->ip(),
        ]);

        // Send activation/deactivation email — never crash the panel if the
        // mail transport is misconfigured; the account change still saves.
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

    /**
     * Apply an uploaded avatar to the target user (same pipeline as Profile).
     */
    protected function syncAvatar(Request $request, User $user): void
    {
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
}
