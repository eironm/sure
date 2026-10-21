<!-- Custom Alert Component -->
<div id="customAlert" class="modal-overlay" style="display: none; z-index: 3000; background: rgba(0,0,0,0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); position: fixed; top: 0; left: 0; width: 100%; height: 100%; align-items: center; justify-content: center;" onclick="if(event.target === this) closeAlert(false)">
    <div class="modal-container" style="max-width: 450px; background: white; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); overflow: hidden; transform: scale(0.9); transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div class="p-5 text-center">
            <div class="mb-3">
                <i id="alertIcon" class="bi bi-info-circle text-primary" style="font-size: 3.5rem;"></i>
            </div>
            <h4 id="alertTitle" class="fw-bold mb-2">Alert</h4>
            <p id="alertMessage" class="text-muted mb-4">Message goes here.</p>
            
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" id="alertConfirmBtn" class="btn btn-primary rounded-pill px-4 fw-bold">OK</button>
                <button type="button" id="alertCancelBtn" class="btn btn-outline-secondary rounded-pill px-4 fw-bold d-none">Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
    #customAlert.active {
        display: flex !important;
        opacity: 1;
    }
    #customAlert.active .modal-container {
        transform: scale(1) !important;
    }

    /* ── Brand overrides for alert dialog ──────────────────── */
    /* Ensure the confirm button and icons honour the CMS branding */
    #customAlert .btn-primary {
        background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed)) !important;
        border: none !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb, 37,99,235), 0.3);
        transition: opacity 0.2s, transform 0.2s;
    }
    #customAlert .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    #customAlert .text-primary {
        color: var(--brand-primary, #2563eb) !important;
    }
    #customAlert .modal-container {
        border-top: 4px solid var(--brand-primary, #2563eb);
    }
</style>

<script>
    function showAlert(options) {
        const modal = document.getElementById('customAlert');
        const title = document.getElementById('alertTitle');
        const message = document.getElementById('alertMessage');
        const icon = document.getElementById('alertIcon');
        const confirmBtn = document.getElementById('alertConfirmBtn');
        const cancelBtn = document.getElementById('alertCancelBtn');

        if (!modal) return;

        // Set Content
        title.innerText = options.title || 'Alert';
        // Support HTML formatting in messages
        if (options.isHtml) {
            message.innerHTML = options.message || '';
        } else {
            message.innerText = options.message || '';
        }
        
        // Icon and Color
        const type = options.type || 'info';
        const colorClass = type === 'error' ? 'danger' : (type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'primary'));
        
        icon.className = 'bi ' + (options.icon || (type === 'error' ? 'bi-x-circle' : (type === 'success' ? 'bi-check-circle' : (type === 'warning' ? 'bi-exclamation-triangle' : 'bi-info-circle'))));
        icon.className += ' text-' + colorClass;
        
        // Button Styling
        confirmBtn.className = 'btn rounded-pill px-4 fw-bold btn-' + colorClass;
        confirmBtn.innerText = options.confirmText || 'OK';

        if (options.showCancel) {
            cancelBtn.classList.remove('d-none');
            cancelBtn.innerText = options.cancelText || 'Cancel';
        } else {
            cancelBtn.classList.add('d-none');
        }

        // ABSOLUTE FIX: Clear and set event handlers directly
        confirmBtn.onclick = function() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            if (options.onConfirm) options.onConfirm();
        };

        cancelBtn.onclick = function() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            if (options.onCancel) options.onCancel();
        };
        
        // Show Modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeAlert(isConfirm) {
        const modal = document.getElementById('customAlert');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    // Automatically show session messages if they exist
    onPageLoad(function() {
        <?php if(session('success')): ?>
            showAlert({
                title: 'Success!',
                message: "<?php echo e(session('success')); ?>",
                type: 'success'
            });
        <?php endif; ?>

        <?php if(session('error')): ?>
            showAlert({
                title: 'Error',
                message: "<?php echo e(session('error')); ?>",
                type: 'error'
            });
        <?php endif; ?>

        <?php if(isset($errors) && $errors->any()): ?>
            showAlert({
                title: 'Validation Error',
                message: "<?php echo e($errors->first()); ?>",
                type: 'error'
            });
        <?php endif; ?>
    });
</script>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/components/custom-alert.blade.php ENDPATH**/ ?>