<!-- Action Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white border-0" id="modalHeader">
                <h5 class="modal-title" id="deleteConfirmationLabel">
                    <i class="bi bi-exclamation-triangle" id="modalIcon"></i> <span id="modalTitle">Confirm Action</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0">
                    <strong id="modalMainText">Are you sure you want to perform this action?</strong>
                </p>
                <p class="text-muted small mt-2 mb-0" id="modalSubText">
                    This action can be undone later. Please confirm if you want to proceed.
                </p>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn" id="confirmDeleteBtn">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof pendingDeleteForm === 'undefined') {
        var pendingDeleteForm = null;
    }

    /**
     * @param event - The trigger event
     * @param itemLabel - The name of the item (e.g. 'User Account')
     * @param actionType - 'delete', 'toggle', or 'warning'
     * @param customMessage - (Optional) Specific message like 'deactivate' or 'update'
     */
    function showDeleteConfirmation(event, itemLabel = 'item', actionType = 'delete', customMessage = null) {
        event.preventDefault();
        pendingDeleteForm = event.target.closest('form');
        
        const header = document.getElementById('modalHeader');
        const title = document.getElementById('modalTitle');
        const icon = document.getElementById('modalIcon');
        const mainText = document.getElementById('modalMainText');
        const subText = document.getElementById('modalSubText');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        // Reset classes
        header.classList.remove('bg-danger', 'bg-primary', 'bg-warning', 'text-dark');
        confirmBtn.classList.remove('btn-danger', 'btn-primary', 'btn-warning');
        
        if (actionType === 'delete') {
            header.classList.add('bg-danger');
            title.textContent = 'Confirm Deletion';
            icon.className = 'bi bi-trash-fill me-2';
            const actionVerb = customMessage || 'delete';
            mainText.innerHTML = `Are you sure you want to <span class="text-danger">${actionVerb}</span> this <strong>${itemLabel}</strong>?`;
            subText.textContent = 'This action cannot be undone and will permanently remove the data.';
            confirmBtn.textContent = (customMessage ? customMessage.charAt(0).toUpperCase() + customMessage.slice(1) : 'Delete') + ' Permanently';
            confirmBtn.classList.add('btn-danger');
        } else if (actionType === 'toggle') {
            header.classList.add('bg-primary');
            title.textContent = 'Confirm Status Change';
            icon.className = 'bi bi-info-circle-fill me-2';
            const actionVerb = customMessage || 'update';
            mainText.innerHTML = `Are you sure you want to <span class="text-primary">${actionVerb}</span> this <strong>${itemLabel}</strong>?`;
            subText.textContent = 'This change will take effect immediately but can be reversed later.';
            confirmBtn.textContent = (customMessage ? customMessage.charAt(0).toUpperCase() + customMessage.slice(1) : 'Update Status');
            confirmBtn.classList.add('btn-primary');
        } else {
            header.classList.add('bg-warning', 'text-dark');
            title.textContent = 'Warning';
            icon.className = 'bi bi-exclamation-triangle-fill me-2';
            mainText.innerHTML = customMessage || `Are you sure you want to proceed with this <strong>${itemLabel}</strong>?`;
            subText.textContent = 'Please confirm if you want to perform this action.';
            confirmBtn.textContent = 'Confirm Action';
            confirmBtn.classList.add('btn-warning');
        }
        
        confirmBtn.className += ' px-4 rounded-pill fw-bold shadow-sm';

        const modalEl = document.getElementById('deleteConfirmationModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    /**
     * Perform the pending action as a genuine POST submission. This keeps the
     * request a real POST even when the SPA navigation layer flakes out (a
     * network blip or an aborted SPA fetch would otherwise fall back to a full
     * GET navigation on the action URL — which is exactly how the
     * "Method Not Allowed" error appeared on toggle buttons).
     */
    function submitPendingForm() {
        if (!pendingDeleteForm) return;
        const form = pendingDeleteForm;
        pendingDeleteForm = null;
        form.submit();
    }

    onPageLoad(function() {
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                if (!pendingDeleteForm) return;

                const modalEl = document.getElementById('deleteConfirmationModal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }

                // Try the SPA path first, but never let it silently eat the
                // action. If spaNavigate is missing, or the fetch it performs
                // throws (network drop mid-request), fall back to a native
                // POST submit so the action still executes exactly once.
                const formData = new FormData(pendingDeleteForm);
                const action = pendingDeleteForm.getAttribute('action') || window.location.href;
                const method = (pendingDeleteForm.getAttribute('method') || 'POST').toUpperCase();

                if (window.spaNavigate) {
                    const formCopy = pendingDeleteForm;
                    try {
                        window.spaNavigate(action, {
                            method: method,
                            body: formData
                        }, false);

                        // Watch for an unresolved SPA request (network drop
                        // mid-fetch) and recover with a real POST submit
                        // instead of letting the SPA layer silently die.
                        if (formCopy && formCopy.parentElement) {
                            setTimeout(function() {
                                if (formCopy && formCopy.parentElement) {
                                    submitPendingForm();
                                }
                            }, 4500);
                        }
                    } catch (e) {
                        console.error('SPA confirm failed, submitting natively:', e);
                        submitPendingForm();
                    }
                } else {
                    submitPendingForm();
                }
            };
        }
    });
</script>
