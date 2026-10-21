<section>
    <style>
        /* ═══════════════════════════════════════════════════════════
           Update Password Form — Redesigned
           ═══════════════════════════════════════════════════════════ */

        .pwd-header {
            margin-bottom: 2rem;
        }

        .pwd-header-row {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .pwd-header-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb, 37,99,235), 0.2);
        }

        .pwd-header h2 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.3rem;
            letter-spacing: -0.02em;
        }

        .pwd-header p {
            font-size: 0.83rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }

        /* History Notice */
        .pwd-reuse-notice {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%);
            border: 1px solid #fde68a;
            position: relative;
            overflow: hidden;
        }

        .pwd-reuse-notice::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #f59e0b, #d97706);
            border-radius: 4px 0 0 4px;
        }

        .pwd-reuse-notice-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(245, 158, 11, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #d97706;
            font-size: 1rem;
        }

        .pwd-reuse-notice span {
            font-size: 0.82rem;
            color: #92400e;
            font-weight: 600;
            line-height: 1.55;
        }

        /* Form Groups */
        .pwd-field {
            margin-bottom: 1.5rem;
        }

        .pwd-label {
            display: block;
            font-weight: 700;
            color: #334155;
            font-size: 0.78rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .pwd-input-wrap {
            position: relative;
        }

        .pwd-input {
            width: 100%;
            padding: 0.75rem 3rem 0.75rem 1.1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #0f172a;
            background: #fff;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            line-height: 1.5;
        }

        .pwd-input:focus {
            outline: none;
            border-color: var(--brand-primary, #2563eb);
            box-shadow: 0 0 0 4px rgba(var(--brand-primary-rgb, 37,99,235), 0.1);
        }

        .pwd-input.is-invalid {
            border-color: #fca5a5;
            background: #fef2f2;
        }

        .pwd-input.is-invalid:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .pwd-eye-btn {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.05rem;
            padding: 0.3rem 0.4rem;
            border-radius: 8px;
            cursor: pointer;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .pwd-eye-btn:hover {
            color: #334155;
            background: #f1f5f9;
        }

        .pwd-field-error {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            color: #ef4444;
            font-size: 0.78rem;
            margin-top: 0.4rem;
            font-weight: 600;
        }

        .pwd-field-error::before {
            content: '\F623';
            font-family: 'bootstrap-icons';
            font-size: 0.85rem;
        }

        /* Submit */
        .pwd-btn-update {
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            color: #fff;
            border: none;
            padding: 0.8rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
        }

        .pwd-btn-update::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .pwd-btn-update:hover::before {
            opacity: 1;
        }

        .pwd-btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(var(--brand-primary-rgb, 37,99,235), 0.35);
        }

        .pwd-btn-update:active {
            transform: translateY(0);
        }

        .pwd-success-msg {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #6ee7b7;
            border-radius: 10px;
            color: #065f46;
            font-weight: 600;
            font-size: 0.82rem;
            animation: pwdSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes pwdSlideIn {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>

    <div class="pwd-header">
        <div class="pwd-header-row">
            <div class="pwd-header-icon">
                <i class="bi bi-key-fill"></i>
            </div>
            <div>
                <h2>Update Password</h2>
                <p>Use a strong, unique password you haven't used on other sites or previously on this account.</p>
            </div>
        </div>
    </div>

    
    <div class="pwd-reuse-notice">
        <div class="pwd-reuse-notice-icon">
            <i class="bi bi-clock-history"></i>
        </div>
        <span>For security reasons, you cannot reuse any password you've used before on this account. Each password change must be a completely new password.</span>
    </div>

    <form method="post" action="<?php echo e(route('password.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>

        
        <div class="pwd-field">
            <label for="update_password_current_password" class="pwd-label">Current Password</label>
            <div class="pwd-input-wrap">
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="pwd-input <?php echo e($errors->updatePassword->has('current_password') ? 'is-invalid' : ''); ?>"
                    autocomplete="current-password"
                    placeholder="Enter your current password"
                />
                <button type="button" class="pwd-eye-btn" aria-label="Show password" onclick="togglePwdVis(this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <?php if($errors->updatePassword->has('current_password')): ?>
                <span class="pwd-field-error"><?php echo e($errors->updatePassword->first('current_password')); ?></span>
            <?php endif; ?>
        </div>

        
        <div class="pwd-field">
            <label for="update_password_password" class="pwd-label">New Password</label>
            <div class="pwd-input-wrap">
                <input
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="pwd-input <?php echo e($errors->updatePassword->has('password') ? 'is-invalid' : ''); ?>"
                    autocomplete="new-password"
                    placeholder="Create a new strong password"
                />
                <button type="button" class="pwd-eye-btn" aria-label="Show password" onclick="togglePwdVis(this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <?php if($errors->updatePassword->has('password')): ?>
                <span class="pwd-field-error"><?php echo e($errors->updatePassword->first('password')); ?></span>
            <?php endif; ?>

            
            <?php echo $__env->make('components.password-strength-meter', [
                'passwordId' => 'update_password_password',
                'confirmId'  => 'update_password_password_confirmation',
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <div class="pwd-field">
            <label for="update_password_password_confirmation" class="pwd-label">Confirm New Password</label>
            <div class="pwd-input-wrap">
                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="pwd-input"
                    autocomplete="new-password"
                    placeholder="Repeat your new password"
                />
                <button type="button" class="pwd-eye-btn" aria-label="Show password" onclick="togglePwdVis(this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-top: 2.25rem;">
            <button type="submit" class="pwd-btn-update">
                <i class="bi bi-shield-check"></i>
                Update Password
            </button>

            <?php if(session('status') === 'password-updated'): ?>
                <div class="pwd-success-msg">
                    <i class="bi bi-check-circle-fill"></i>
                    Password updated successfully.
                </div>
            <?php endif; ?>
        </div>
    </form>

    <script>
        function togglePwdVis(btn) {
            const input = btn.parentElement.querySelector('input');
            const icon = btn.querySelector('i');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        }
    </script>
</section>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/profile/partials/update-password-form.blade.php ENDPATH**/ ?>