<section class="profile-danger-surface">
    <style>
        .profile-danger-surface {
            display: grid;
            gap: 1.25rem;
        }

        .profile-danger-summary {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem 1.35rem;
            border-radius: 16px;
            border: 1px solid rgba(239, 68, 68, 0.12);
            background: linear-gradient(135deg, #fff 0%, #fef2f2 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-danger-summary::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #ef4444, #dc2626);
        }

        .profile-danger-summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .profile-danger-summary h2 {
            font-size: 1rem;
            font-weight: 800;
            color: #991b1b;
            margin: 0 0 0.25rem;
        }

        .profile-danger-summary p {
            font-size: 0.84rem;
            line-height: 1.55;
            color: #b91c1c;
            margin: 0;
            opacity: 0.88;
        }

        .profile-danger-action {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            width: fit-content;
            padding: 0.85rem 2rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 0.88rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-danger-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.28);
        }

        .profile-danger-modal {
            padding: 1.5rem;
        }

        .profile-danger-modal-head {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .profile-danger-modal-badge {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.35rem;
        }

        .profile-danger-modal-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.3rem;
        }

        .profile-danger-modal-copy {
            font-size: 0.86rem;
            line-height: 1.55;
            color: #64748b;
            margin: 0;
        }

        .profile-danger-alert {
            display: none;
            align-items: center;
            gap: 0.5rem;
            margin: 0 auto 1rem;
            padding: 0.9rem 1rem;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.18);
            color: #b91c1c;
            font-size: 0.84rem;
            font-weight: 700;
            text-align: left;
        }

        .profile-danger-alert.show {
            display: flex;
        }

        .profile-danger-field {
            max-width: 360px;
            margin: 0 auto 1.35rem;
        }

        .profile-danger-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.45rem;
        }

        .profile-danger-input {
            width: 100%;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 0.8rem 1rem;
            font-size: 0.92rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-danger-input:focus {
            outline: none;
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .profile-danger-modal-actions {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .profile-danger-cancel {
            padding: 0.8rem 1.4rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            color: #475569;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .profile-danger-submit {
            padding: 0.8rem 1.4rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 0.84rem;
            font-weight: 800;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-danger-submit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.28);
        }
    </style>

    <div class="profile-danger-summary">
        <div class="profile-danger-summary-icon">
            <i class="bi bi-shield-exclamation"></i>
        </div>
        <div>
            <h2><?php echo e(__('Delete Account')); ?></h2>
            <p><?php echo e(__('This permanently removes the account and all associated data. Export or save anything you want to keep before continuing.')); ?></p>
        </div>
    </div>

    <button
        type="button"
        class="profile-danger-action"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <i class="bi bi-trash3-fill"></i>
        <?php echo e(__('Delete Account')); ?>

    </button>

    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'confirm-user-deletion','show' => false,'focusable' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'confirm-user-deletion','show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'focusable' => true]); ?>
        <form id="guestDeleteAccountForm" class="p-0">
            <?php echo csrf_field(); ?>
            <?php echo method_field('delete'); ?>

            <div class="profile-danger-modal">
                <div class="profile-danger-modal-head">
                    <div class="profile-danger-modal-badge">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <h3 class="profile-danger-modal-title"><?php echo e(__('Confirm permanent deletion')); ?></h3>
                        <p class="profile-danger-modal-copy">
                            <?php echo e(__('This action cannot be undone. Enter your password to confirm and permanently remove your account.')); ?>

                        </p>
                    </div>
                </div>

                <div id="guestDeleteErrorMessage" class="profile-danger-alert" role="alert">
                    <i class="bi bi-exclamation-circle"></i>
                    <span id="guestDeleteErrorText"></span>
                </div>

                <div class="profile-danger-field">
                    <label for="guestDeletePassword" class="profile-danger-label"><?php echo e(__('Password')); ?></label>
                    <input
                        id="guestDeletePassword"
                        name="password"
                        type="password"
                        class="profile-danger-input"
                        placeholder="<?php echo e(__('Enter your password')); ?>"
                        required
                    />
                </div>

                <div class="profile-danger-modal-actions">
                    <button type="button" class="profile-danger-cancel" x-on:click="$dispatch('close')">
                        <?php echo e(__('Cancel')); ?>

                    </button>
                    <button type="button" id="guestDeleteSubmitBtn" class="profile-danger-submit">
                        <i class="bi bi-trash3-fill me-1"></i><?php echo e(__('Delete Permanently')); ?>

                    </button>
                </div>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

    <script>
        document.getElementById('guestDeleteSubmitBtn').addEventListener('click', async function (e) {
            e.preventDefault();

            const passwordInput = document.getElementById('guestDeletePassword');
            const errorDiv = document.getElementById('guestDeleteErrorMessage');
            const errorText = document.getElementById('guestDeleteErrorText');
            const btn = this;
            const password = passwordInput.value;

            errorDiv.classList.remove('show');

            if (!password) {
                errorText.textContent = '<?php echo e(__("Please enter your password")); ?>';
                errorDiv.classList.add('show');
                passwordInput.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><?php echo e(__("Deleting...")); ?>';

            try {
                const response = await fetch('<?php echo e(route("profile.destroy")); ?>', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password: password }),
                });

                const data = await response.json();

                if (response.ok) {
                    window.location.href = data.redirect || '/';
                    return;
                }

                if (response.status === 422) {
                    errorText.textContent = data.errors?.password?.[0] || '<?php echo e(__("Invalid password. Please try again.")); ?>';
                    errorDiv.classList.add('show');
                    passwordInput.value = '';
                    passwordInput.focus();
                    return;
                }

                errorText.textContent = data.message || '<?php echo e(__("An error occurred. Please try again.")); ?>';
                errorDiv.classList.add('show');
            } catch (error) {
                console.error('Error:', error);
                errorText.textContent = '<?php echo e(__("An error occurred. Please try again.")); ?>';
                errorDiv.classList.add('show');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash3-fill me-1"></i><?php echo e(__("Delete Permanently")); ?>';
            }
        });

        document.getElementById('guestDeletePassword').addEventListener('input', function () {
            document.getElementById('guestDeleteErrorMessage').classList.remove('show');
        });
    </script>
</section>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/profile/partials/delete-user-form.blade.php ENDPATH**/ ?>