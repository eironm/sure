

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'passwordId' => 'password',
    'confirmId'  => 'password_confirmation',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'passwordId' => 'password',
    'confirmId'  => 'password_confirmation',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="pwd-strength-container" id="pwdStrength_<?php echo e($passwordId); ?>">
    
    <div class="pwd-bar-wrapper">
        <div class="pwd-bar-track">
            <div class="pwd-bar-segment" data-level="1"></div>
            <div class="pwd-bar-segment" data-level="2"></div>
            <div class="pwd-bar-segment" data-level="3"></div>
            <div class="pwd-bar-segment" data-level="4"></div>
        </div>
        <span class="pwd-bar-label">—</span>
    </div>

    
    <div class="pwd-checklist">
        <div class="pwd-check-item" data-rule="length">
            <i class="bi bi-x-circle"></i>
            <span>At least 8 characters</span>
        </div>
        <div class="pwd-check-item" data-rule="uppercase">
            <i class="bi bi-x-circle"></i>
            <span>One uppercase letter (A–Z)</span>
        </div>
        <div class="pwd-check-item" data-rule="lowercase">
            <i class="bi bi-x-circle"></i>
            <span>One lowercase letter (a–z)</span>
        </div>
        <div class="pwd-check-item" data-rule="number">
            <i class="bi bi-x-circle"></i>
            <span>One number (0–9)</span>
        </div>
        <div class="pwd-check-item" data-rule="symbol">
            <i class="bi bi-x-circle"></i>
            <span>One special character (!@#$...)</span>
        </div>
        <?php if($confirmId): ?>
        <div class="pwd-check-item" data-rule="match">
            <i class="bi bi-x-circle"></i>
            <span>Passwords match</span>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="pwd-hibp-notice">
        <i class="bi bi-shield-lock"></i>
        <span>Your password will be checked against known data breaches for your security.</span>
    </div>
</div>

<style>
    .pwd-strength-container {
        margin-top: 0.6rem;
        margin-bottom: 0.75rem;
    }

    /* ── Strength Bar ── */
    .pwd-bar-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .pwd-bar-track {
        display: flex;
        gap: 4px;
        flex: 1;
        height: 6px;
    }

    .pwd-bar-segment {
        flex: 1;
        border-radius: 999px;
        background: #e2e8f0;
        transition: background 0.3s ease;
    }

    .pwd-bar-label {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        min-width: 52px;
        text-align: right;
        transition: color 0.3s ease;
    }

    /* Strength levels */
    .pwd-strength-container[data-strength="1"] .pwd-bar-segment[data-level="1"] { background: #ef4444; }
    .pwd-strength-container[data-strength="1"] .pwd-bar-label { color: #ef4444; }

    .pwd-strength-container[data-strength="2"] .pwd-bar-segment[data-level="1"],
    .pwd-strength-container[data-strength="2"] .pwd-bar-segment[data-level="2"] { background: #f59e0b; }
    .pwd-strength-container[data-strength="2"] .pwd-bar-label { color: #f59e0b; }

    .pwd-strength-container[data-strength="3"] .pwd-bar-segment[data-level="1"],
    .pwd-strength-container[data-strength="3"] .pwd-bar-segment[data-level="2"],
    .pwd-strength-container[data-strength="3"] .pwd-bar-segment[data-level="3"] { background: #3b82f6; }
    .pwd-strength-container[data-strength="3"] .pwd-bar-label { color: #3b82f6; }

    .pwd-strength-container[data-strength="4"] .pwd-bar-segment { background: #10b981; }
    .pwd-strength-container[data-strength="4"] .pwd-bar-label { color: #10b981; }

    /* ── Checklist ── */
    .pwd-checklist {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.3rem 1rem;
        margin-bottom: 0.6rem;
    }

    .pwd-check-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #94a3b8;
        transition: color 0.2s ease;
    }

    .pwd-check-item i {
        font-size: 0.85rem;
        transition: color 0.2s ease;
        flex-shrink: 0;
    }

    .pwd-check-item.pass {
        color: #10b981;
    }

    .pwd-check-item.pass i {
        color: #10b981;
    }

    .pwd-check-item.fail {
        color: #ef4444;
    }

    .pwd-check-item.fail i {
        color: #ef4444;
    }

    /* ── HIBP Notice ── */
    .pwd-hibp-notice {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.45rem 0.75rem;
    }

    .pwd-hibp-notice i {
        color: var(--brand-primary, #2563eb);
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    @media (max-width: 576px) {
        .pwd-checklist {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
(function () {
    const containerId = 'pwdStrength_<?php echo e($passwordId); ?>';
    const passwordId  = '<?php echo e($passwordId); ?>';
    const confirmId   = '<?php echo e($confirmId); ?>';

    function init() {
        const container = document.getElementById(containerId);
        const pwdInput  = document.getElementById(passwordId);
        const cfmInput  = confirmId ? document.getElementById(confirmId) : null;

        if (!container || !pwdInput) return;

        const label     = container.querySelector('.pwd-bar-label');
        const checks    = container.querySelectorAll('.pwd-check-item');

        const labels = ['—', 'Weak', 'Fair', 'Good', 'Strong'];

        function evaluate() {
            const val = pwdInput.value;
            const cfmVal = cfmInput ? cfmInput.value : '';

            const rules = {
                length:    val.length >= 8,
                uppercase: /[A-Z]/.test(val),
                lowercase: /[a-z]/.test(val),
                number:    /\d/.test(val),
                symbol:    /[^A-Za-z0-9]/.test(val),
                match:     confirmId ? (val.length > 0 && val === cfmVal) : true,
            };

            // Update checklist
            checks.forEach(item => {
                const rule = item.dataset.rule;
                const icon = item.querySelector('i');
                if (rules[rule]) {
                    item.classList.add('pass');
                    item.classList.remove('fail');
                    icon.className = 'bi bi-check-circle-fill';
                } else {
                    item.classList.remove('pass');
                    if (val.length > 0) {
                        item.classList.add('fail');
                    } else {
                        item.classList.remove('fail');
                    }
                    icon.className = 'bi bi-x-circle';
                }
            });

            // Calculate strength (0-4)
            if (val.length === 0) {
                container.removeAttribute('data-strength');
                label.textContent = '—';
                return;
            }

            let score = 0;
            if (rules.length)    score++;
            if (rules.uppercase && rules.lowercase) score++;
            if (rules.number)    score++;
            if (rules.symbol)    score++;

            // Bonus for length
            if (val.length >= 12 && score < 4) score = Math.min(score + 1, 4);

            // Penalty: if fewer than 3 basic rules pass, cap at 2
            const basicPasses = [rules.length, rules.uppercase && rules.lowercase, rules.number, rules.symbol].filter(Boolean).length;
            if (basicPasses < 3 && score > 2) score = 2;

            container.setAttribute('data-strength', score);
            label.textContent = labels[score];
        }

        pwdInput.addEventListener('input', evaluate);
        if (cfmInput) cfmInput.addEventListener('input', evaluate);

        // Initial eval in case of autofill
        evaluate();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/components/password-strength-meter.blade.php ENDPATH**/ ?>