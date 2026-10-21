<?php $__env->startSection('title', 'Edit Account — ' . ($user->first_name ?? $user->name) . ' ' . ($user->last_name ?? '')); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Compact profile avatar and accessible account switch for the edit form. */
    .acct-avatar-preview { width: 72px; height: 72px; border-radius: 50%; flex: 0 0 72px; overflow: hidden; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
    .acct-avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
    .acct-switch { display: inline-flex; align-items: center; gap: .65rem; cursor: pointer; position: relative; }
    .acct-switch input[type="checkbox"] { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
    .acct-switch .track { width: 38px; height: 22px; border-radius: 99px; background: #cbd5e1; position: relative; transition: background .2s; flex: 0 0 auto; }
    .acct-switch .track::after { content: ''; position: absolute; top: 3px; left: 3px; width: 16px; height: 16px; border-radius: 50%; background: #fff; transition: transform .2s; box-shadow: 0 1px 2px rgba(15,23,42,.18); }
    .acct-switch input[type="checkbox"]:checked + .track { background: var(--brand-primary, #f97316); }
    .acct-switch input[type="checkbox"]:checked + .track::after { transform: translateX(16px); }
    .acct-switch .lbl { font-size: .82rem; font-weight: 700; color: var(--ink-1, #0f172a); }
    .acct-switch .lbl small { display: block; font-weight: 500; font-size: .72rem; color: var(--ink-3, #64748b); }
</style>
<div class="sa-page-center">

    <div class="page-header" style="margin-bottom: 1.25rem;">
        <div>
            <h1 class="ph-title">Edit Account</h1>
            <p class="sa-page-subnote"><span style="font-family:'JetBrains Mono',monospace;color:var(--ink-2);">#<?php echo e($user->id); ?></span> &middot; <?php echo e($user->first_name ?? $user->name); ?> <?php echo e($user->last_name ?? ''); ?> <i class="bi bi-circle-fill" style="font-size:.4rem;color:var(--good);"></i> <?php echo e(ucfirst($user->role)); ?> <i class="bi bi-circle-fill" style="font-size:.4rem;color:<?php echo e($user->is_active ? 'var(--good)' : 'var(--bad)'); ?>;"></i> <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?></p>
        </div>
        <div class="page-actions">
            <a href="<?php echo e(route('superadmin.users.index')); ?>" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Back</a>
        </div>
    </div>

    <form id="acctForm" method="POST" action="<?php echo e(route('superadmin.users.update', $user)); ?>" enctype="multipart/form-data" novalidate>
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-person-lines-fill"></i> Who They Are
                <small>Identity &amp; login</small>
            </div>

            <div class="field">
                <label class="form-label">Profile Photo <span class="opt">(optional)</span></label>
                <div style="display: flex; align-items: center; gap: 1rem; margin-top: .35rem;">
                    <div class="acct-avatar-preview" id="acctAvatarPreview" style="<?php echo e($user->avatar && $user->avatarUrl() ? '' : 'display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f172a,#334155);color:rgba(255,255,255,.55);'); ?>">
                        <?php if($user->avatar && $user->avatarUrl()): ?>
                            <img src="<?php echo e($user->avatarUrl()); ?>" alt="Current profile photo">
                        <?php else: ?>
                            <i class="bi bi-person-fill" style="font-size:2.2rem;"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="acctAvatarInput" class="file-choose" style="cursor:pointer;"><i class="bi bi-camera-fill"></i><?php echo e($user->avatar ? 'Replace Photo' : 'Upload Photo'); ?></label>
                        <button type="button" class="file-choose ms-1" id="acctAvatarRemove"><i class="bi bi-x-lg"></i>Remove</button>
                        <div class="form-hint">JPEG / PNG / WebP, up to 2 MB. Optional.</div>
                        <input type="file" id="acctAvatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" style="display:none;">
                        <input type="hidden" id="acctAvatarRemoved" name="avatar_removed" value="">
                        <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="field-row cols-3">
                <div class="field">
                    <label for="first_name" class="form-label">First Name <span class="req">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="first_name" name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" placeholder="e.g. Juan" required>
                    <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="field">
                    <label for="middle_name" class="form-label">Middle Name <span class="opt">(optional)</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['middle_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="middle_name" name="middle_name" value="<?php echo e(old('middle_name', $user->middle_name)); ?>" placeholder="e.g. Santos">
                    <?php $__errorArgs = ['middle_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="field">
                    <label for="last_name" class="form-label">Last Name <span class="req">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="last_name" name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" placeholder="e.g. Dela Cruz" required>
                    <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="field">
                <label for="email" class="form-label">Email Address <span class="req">*</span></label>
                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" placeholder="e.g. jdelacruz@university.edu.ph" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-hint"><i class="bi bi-info-circle"></i> Used as the login credential.</div>
            </div>

            <div class="field-row cols-2">
                <div class="field">
                    <label class="form-label">Contact Number <span class="opt">(optional)</span></label>
                    <?php if (isset($component)) { $__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.international-phone-input','data' => ['name' => 'contact_number','id' => 'contact_number','value' => old('contact_number', $user->contact_number),'theme' => 'bootstrap']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('international-phone-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'contact_number','id' => 'contact_number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('contact_number', $user->contact_number)),'theme' => 'bootstrap']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f)): ?>
<?php $attributes = $__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f; ?>
<?php unset($__attributesOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f)): ?>
<?php $component = $__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f; ?>
<?php unset($__componentOriginalcf75d37fc45c1bde8a9ae3ace5a58e5f); ?>
<?php endif; ?>
                </div>
                <div class="field">
                    <label for="date_of_birth" class="form-label">Date of Birth <span class="opt">(optional)</span></label>
                    <input type="date" class="form-control <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="date_of_birth" name="date_of_birth" value="<?php echo e(old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '')); ?>">
                    <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-key-fill"></i> Access Level
                <small>Role, status &amp; password</small>
            </div>

            <div class="field">
                <label class="form-label">Role <span class="req">*</span></label>
                <div class="role-grid">
                    <?php $__currentLoopData = ['guest' => ['bi bi-person', 'Guest'], 'cashier' => ['bi bi-cash-coin', 'Cashier'], 'staff' => ['bi bi-briefcase', 'Staff'], 'admin' => ['bi bi-shield-lock', 'Admin']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => [$icon, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="role-option">
                            <input type="radio" name="role" value="<?php echo e($slug); ?>" <?php echo e(old('role', $user->role) === $slug ? 'checked' : ''); ?>>
                            <span class="role-option-chip"><i class="<?php echo e($icon); ?>" aria-hidden="true"></i><?php echo e($label); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="field">
                <?php
                    $acctActive = old('is_active') !== null ? (int) old('is_active') === 1 : (bool) $user->is_active;
                ?>
                <label class="acct-switch" id="activeSwitch">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="activeToggle" name="is_active" value="1" <?php echo e($acctActive ? 'checked' : ''); ?>>
                    <span class="track"></span>
                    <span class="lbl">Account Active<small id="activeSwitchNote"><?php echo e($acctActive ? 'On — user can log in' : 'Off — login blocked'); ?></small></span>
                </label>
            </div>
            <script>document.addEventListener('DOMContentLoaded', function () { var t = document.getElementById('activeToggle'); var n = document.getElementById('activeSwitchNote'); if (t && n) { t.addEventListener('change', function () { n.textContent = t.checked ? 'On — user can log in' : 'Off — login blocked'; }); } });</script>

            <div class="field-row cols-2">
                <div class="field">
                    <label for="password" class="form-label">New Password <span class="opt">(leave blank to keep)</span></label>
                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password" placeholder="Enter only to reset">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php echo $__env->make('components.password-strength-meter', [
                        'passwordId' => 'password',
                        'confirmId'  => 'password_confirmation',
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="field">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password_confirmation" name="password_confirmation" placeholder="Re-type the new password">
                    <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-hint" style="margin-top:.45rem;"><i class="bi bi-info-circle"></i> Leaving both fields empty keeps the current password.</div>
        </div>

        
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-tag-fill"></i> Client Profile
                <small>Optional classification</small>
            </div>

            <div class="field-row" x-data="{ clientType: '<?php echo e(old('client_type', $user->client_type ?? '')); ?>' }">
                <div class="field">
                    <label for="client_type" class="form-label">Client Type <span class="opt">(optional)</span></label>
                    <select id="client_type" name="client_type" class="form-control <?php $__errorArgs = ['client_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" x-model="clientType">
                        <option value="" :selected="clientType === ''">Not Specified</option>
                        <option value="internal" :selected="clientType === 'internal'">Internal (University)</option>
                        <option value="external" :selected="clientType === 'external'">External (General Public)</option>
                    </select>
                    <?php $__errorArgs = ['client_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div x-show="clientType === 'internal'" x-cloak>
                    <div class="callout ok">
                        <div class="callout-call"><i class="bi bi-building"></i>Internal Client Details</div>
                        <div class="field-row cols-2" style="margin-bottom: 1rem;">
                            <div class="field">
                                <label for="university_email" class="form-label">University Email</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['university_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="university_email" name="university_email" value="<?php echo e(old('university_email', $user->university_email)); ?>">
                                <?php $__errorArgs = ['university_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="field">
                                <label for="affiliation_type" class="form-label">Affiliation Type</label>
                                <select id="affiliation_type" name="affiliation_type" class="form-control <?php $__errorArgs = ['affiliation_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="" <?php echo e(old('affiliation_type', $user->affiliation_type) == '' ? 'selected' : ''); ?>>Select Type</option>
                                    <option value="Student" <?php echo e(old('affiliation_type', $user->affiliation_type) == 'Student' ? 'selected' : ''); ?>>Student</option>
                                    <option value="Faculty" <?php echo e(old('affiliation_type', $user->affiliation_type) == 'Faculty' ? 'selected' : ''); ?>>Faculty</option>
                                    <option value="Staff" <?php echo e(old('affiliation_type', $user->affiliation_type) == 'Staff' ? 'selected' : ''); ?>>Staff</option>
                                </select>
                                <?php $__errorArgs = ['affiliation_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="field">
                            <label for="college_department" class="form-label">College / Department</label>
                            <select id="college_department" name="college_department" class="form-control <?php $__errorArgs = ['college_department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="" <?php echo e(old('college_department', $user->college_department) == '' ? 'selected' : ''); ?>>Select Department</option>
                                <option value="College of Engineering" <?php echo e(old('college_department', $user->college_department) == 'College of Engineering' ? 'selected' : ''); ?>>College of Engineering</option>
                                <option value="College of Education" <?php echo e(old('college_department', $user->college_department) == 'College of Education' ? 'selected' : ''); ?>>College of Education</option>
                                <option value="College of Arts and Sciences" <?php echo e(old('college_department', $user->college_department) == 'College of Arts and Sciences' ? 'selected' : ''); ?>>College of Arts and Sciences</option>
                                <option value="College of Business and Accountancy" <?php echo e(old('college_department', $user->college_department) == 'College of Business and Accountancy' ? 'selected' : ''); ?>>College of Business and Accountancy</option>
                                <option value="College of Information and Communications Technology" <?php echo e(old('college_department', $user->college_department) == 'College of Information and Communications Technology' ? 'selected' : ''); ?>>College of ICT</option>
                                <option value="College of Architecture and Fine Arts" <?php echo e(old('college_department', $user->college_department) == 'College of Architecture and Fine Arts' ? 'selected' : ''); ?>>College of Architecture &amp; Fine Arts</option>
                                <option value="College of Nursing" <?php echo e(old('college_department', $user->college_department) == 'College of Nursing' ? 'selected' : ''); ?>>College of Nursing</option>
                                <option value="School of Graduate Studies" <?php echo e(old('college_department', $user->college_department) == 'School of Graduate Studies' ? 'selected' : ''); ?>>School of Graduate Studies</option>
                                <option value="Administration / General Staff" <?php echo e(old('college_department', $user->college_department) == 'Administration / General Staff' ? 'selected' : ''); ?>>Administration / General Staff</option>
                            </select>
                            <?php $__errorArgs = ['college_department'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div x-show="clientType === 'external'" x-cloak>
                    <div class="callout info">
                        <div class="callout-call"><i class="bi bi-globe2"></i>External Client Details</div>
                        <div class="field-row cols-2" style="margin-bottom: 1rem;">
                            <div class="field">
                                <label for="organization_name" class="form-label">Organization / Company</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="organization_name" name="organization_name" value="<?php echo e(old('organization_name', $user->organization_name)); ?>">
                                <?php $__errorArgs = ['organization_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="field">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['billing_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="billing_address" name="billing_address" value="<?php echo e(old('billing_address', $user->billing_address)); ?>">
                                <?php $__errorArgs = ['billing_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="field-row cols-2">
                            <div class="field">
                                <label for="government_id_type" class="form-label">Government ID Type</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['government_id_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="government_id_type" name="government_id_type" value="<?php echo e(old('government_id_type', $user->government_id_type)); ?>" placeholder="e.g. Passport, UMID">
                                <?php $__errorArgs = ['government_id_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="field">
                                <label for="government_id_number" class="form-label">Government ID Number</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['government_id_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="government_id_number" name="government_id_number" value="<?php echo e(old('government_id_number', $user->government_id_number)); ?>">
                                <?php $__errorArgs = ['government_id_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="fieldset">
            <div class="fieldset-title">
                <i class="bi bi-person-badge-fill"></i> Extra Details
                <label class="toggle-row" style="margin: 0; padding: .5rem .75rem;">
                    <input type="checkbox" id="extraToggle" style="margin-right:.65rem;">
                    <span class="tl">Expand <small>sex, civil status, nationality, address</small></span>
                </label>
            </div>
            <div id="extraBody" style="display:none; padding: 1rem 0 0;">
                <div class="field-row cols-2" style="margin-bottom: 1rem;">
                    <div class="field">
                        <label for="sex" class="form-label">Sex <span class="opt">(optional)</span></label>
                        <select id="sex" name="sex" class="form-control <?php $__errorArgs = ['sex'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="" <?php echo e(old('sex', $user->sex) == '' ? 'selected' : ''); ?>>Not Specified</option>
                            <option value="Male" <?php echo e(old('sex', $user->sex) == 'Male' ? 'selected' : ''); ?>>Male</option>
                            <option value="Female" <?php echo e(old('sex', $user->sex) == 'Female' ? 'selected' : ''); ?>>Female</option>
                        </select>
                        <?php $__errorArgs = ['sex'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="field">
                        <label for="civil_status" class="form-label">Civil Status <span class="opt">(optional)</span></label>
                        <select id="civil_status" name="civil_status" class="form-control <?php $__errorArgs = ['civil_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="" <?php echo e(old('civil_status', $user->civil_status) == '' ? 'selected' : ''); ?>>Not Specified</option>
                            <option value="Single" <?php echo e(old('civil_status', $user->civil_status) == 'Single' ? 'selected' : ''); ?>>Single</option>
                            <option value="Married" <?php echo e(old('civil_status', $user->civil_status) == 'Married' ? 'selected' : ''); ?>>Married</option>
                            <option value="Widowed" <?php echo e(old('civil_status', $user->civil_status) == 'Widowed' ? 'selected' : ''); ?>>Widowed</option>
                            <option value="Separated" <?php echo e(old('civil_status', $user->civil_status) == 'Separated' ? 'selected' : ''); ?>>Separated</option>
                        </select>
                        <?php $__errorArgs = ['civil_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="field-row cols-2">
                    <div class="field">
                        <label for="nationality" class="form-label">Nationality <span class="opt">(optional)</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nationality" name="nationality" value="<?php echo e(old('nationality', $user->nationality)); ?>" placeholder="e.g. Filipino">
                        <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="field">
                        <label for="personal_address" class="form-label">Personal Address <span class="opt">(optional)</span></label>
                        <input type="text" class="form-control <?php $__errorArgs = ['personal_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="personal_address" name="personal_address" value="<?php echo e(old('personal_address', $user->personal_address)); ?>">
                        <?php $__errorArgs = ['personal_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><i class="bi bi-exclamation-circle"></i><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <div class="save-bar">
        <div class="sb-note"><i class="bi bi-info-circle-fill"></i> Changes take effect immediately.</div>
        <div class="sb-actions">
            <a href="<?php echo e(route('superadmin.users.index')); ?>" class="btn btn-ghost"><i class="bi bi-x-lg"></i>Cancel</a>
            <button type="submit" form="acctForm" class="btn btn-primary"><i class="bi bi-check2"></i>Save Changes</button>
        </div>
    </div>
</div>

<script>
(function acctAvatar() {
    var input = document.getElementById('acctAvatarInput');
    var preview = document.getElementById('acctAvatarPreview');
    var remove = document.getElementById('acctAvatarRemove');
    if (!input || !preview || !remove) return;
    var fallback = preview.innerHTML;
    function showFile(file) {
        if (!file) { preview.innerHTML = fallback; return; }
        var reader = new FileReader();
        reader.onload = function () {
            preview.innerHTML = '<img src="' + reader.result + '" alt="Profile photo preview">';
        };
        reader.readAsDataURL(file);
    }
    input.addEventListener('change', function () {
        showFile(this.files && this.files[0] ? this.files[0] : null);
    });
    remove.addEventListener('click', function () {
        input.value = '';
        showFile(null);
        var hidden = document.getElementById('acctAvatarRemoved');
        if (hidden) hidden.value = '1';
    });
})();
(function acctExtra() {
    var toggle = document.getElementById('extraToggle');
    var body = document.getElementById('extraBody');
    if (!toggle || !body) return;
    toggle.addEventListener('change', function () {
        body.style.display = toggle.checked ? '' : 'none';
    });
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.superadmin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/superadmin/users/edit.blade.php ENDPATH**/ ?>