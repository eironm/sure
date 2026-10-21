<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'contact_number',
    'id' => 'contact_number',
    'value' => '',
    'required' => false,
    'theme' => 'admin', // 'admin', 'profile', 'reserve', 'bootstrap'
    'inputClass' => '',
    'selectClass' => '',
    'placeholder' => null,
    'helper' => null,
    'errorName' => null,
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
    'name' => 'contact_number',
    'id' => 'contact_number',
    'value' => '',
    'required' => false,
    'theme' => 'admin', // 'admin', 'profile', 'reserve', 'bootstrap'
    'inputClass' => '',
    'selectClass' => '',
    'placeholder' => null,
    'helper' => null,
    'errorName' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $errKey = $errorName ?? $name;
    $countries = [
        ['code' => 'PH', 'name' => 'Philippines', 'dial' => '+63', 'flag' => '🇵🇭', 'digits' => [10], 'sample' => '917 123 4567'],
        ['code' => 'US', 'name' => 'United States', 'dial' => '+1', 'flag' => '🇺🇸', 'digits' => [10], 'sample' => '202 555 0199'],
        ['code' => 'CA', 'name' => 'Canada', 'dial' => '+1', 'flag' => '🇨🇦', 'digits' => [10], 'sample' => '416 555 0199'],
        ['code' => 'GB', 'name' => 'United Kingdom', 'dial' => '+44', 'flag' => '🇬🇧', 'digits' => [10], 'sample' => '7911 123456'],
        ['code' => 'AU', 'name' => 'Australia', 'dial' => '+61', 'flag' => '🇦🇺', 'digits' => [9], 'sample' => '412 345 678'],
        ['code' => 'SG', 'name' => 'Singapore', 'dial' => '+65', 'flag' => '🇸🇬', 'digits' => [8], 'sample' => '8123 4567'],
        ['code' => 'JP', 'name' => 'Japan', 'dial' => '+81', 'flag' => '🇯🇵', 'digits' => [10], 'sample' => '90 1234 5678'],
        ['code' => 'MY', 'name' => 'Malaysia', 'dial' => '+60', 'flag' => '🇲🇾', 'digits' => [9, 10], 'sample' => '12 345 6789'],
        ['code' => 'AE', 'name' => 'United Arab Emirates', 'dial' => '+971', 'flag' => '🇦🇪', 'digits' => [9], 'sample' => '50 123 4567'],
        ['code' => 'SA', 'name' => 'Saudi Arabia', 'dial' => '+966', 'flag' => '🇸🇦', 'digits' => [9], 'sample' => '50 123 4567'],
        ['code' => 'KR', 'name' => 'South Korea', 'dial' => '+82', 'flag' => '🇰🇷', 'digits' => [10], 'sample' => '10 1234 5678'],
        ['code' => 'HK', 'name' => 'Hong Kong', 'dial' => '+852', 'flag' => '🇭🇰', 'digits' => [8], 'sample' => '9123 4567'],
        ['code' => 'TW', 'name' => 'Taiwan', 'dial' => '+886', 'flag' => '🇹🇼', 'digits' => [9], 'sample' => '912 345 678'],
        ['code' => 'CN', 'name' => 'China', 'dial' => '+86', 'flag' => '🇨🇳', 'digits' => [11], 'sample' => '138 0013 8000'],
        ['code' => 'IN', 'name' => 'India', 'dial' => '+91', 'flag' => '🇮🇳', 'digits' => [10], 'sample' => '98765 43210'],
        ['code' => 'ID', 'name' => 'Indonesia', 'dial' => '+62', 'flag' => '🇮🇩', 'digits' => [10, 11], 'sample' => '812 3456 7890'],
        ['code' => 'TH', 'name' => 'Thailand', 'dial' => '+66', 'flag' => '🇹🇭', 'digits' => [9], 'sample' => '81 234 5678'],
        ['code' => 'VN', 'name' => 'Vietnam', 'dial' => '+84', 'flag' => '🇻🇳', 'digits' => [9], 'sample' => '91 234 5678'],
        ['code' => 'NZ', 'name' => 'New Zealand', 'dial' => '+64', 'flag' => '🇳🇿', 'digits' => [9], 'sample' => '21 123 4567'],
        ['code' => 'QA', 'name' => 'Qatar', 'dial' => '+974', 'flag' => '🇶🇦', 'digits' => [8], 'sample' => '3312 3456'],
        ['code' => 'KW', 'name' => 'Kuwait', 'dial' => '+965', 'flag' => '🇰🇼', 'digits' => [8], 'sample' => '9123 4567'],
        ['code' => 'BH', 'name' => 'Bahrain', 'dial' => '+973', 'flag' => '🇧🇭', 'digits' => [8], 'sample' => '3912 3456'],
        ['code' => 'OM', 'name' => 'Oman', 'dial' => '+968', 'flag' => '🇴🇲', 'digits' => [8], 'sample' => '9123 4567'],
        ['code' => 'DE', 'name' => 'Germany', 'dial' => '+49', 'flag' => '🇩🇪', 'digits' => [10, 11], 'sample' => '151 12345678'],
        ['code' => 'FR', 'name' => 'France', 'dial' => '+33', 'flag' => '🇫🇷', 'digits' => [9], 'sample' => '6 12 34 56 78'],
        ['code' => 'IT', 'name' => 'Italy', 'dial' => '+39', 'flag' => '🇮🇹', 'digits' => [10], 'sample' => '312 345 6789'],
        ['code' => 'ES', 'name' => 'Spain', 'dial' => '+34', 'flag' => '🇪🇸', 'digits' => [9], 'sample' => '612 34 56 78'],
        ['code' => 'NL', 'name' => 'Netherlands', 'dial' => '+31', 'flag' => '🇳🇱', 'digits' => [9], 'sample' => '6 12345678'],
        ['code' => 'CH', 'name' => 'Switzerland', 'dial' => '+41', 'flag' => '🇨🇭', 'digits' => [9], 'sample' => '78 123 45 67'],
        ['code' => 'BR', 'name' => 'Brazil', 'dial' => '+55', 'flag' => '🇧🇷', 'digits' => [11], 'sample' => '11 91234 5678'],
        ['code' => 'MX', 'name' => 'Mexico', 'dial' => '+52', 'flag' => '🇲🇽', 'digits' => [10], 'sample' => '55 1234 5678'],
    ];

    // Initial parsing
    $initialRaw = trim((string)($value ?? ''));
    $matchedCountry = 'PH';
    $matchedDigits = '';

    if ($initialRaw !== '') {
        $clean = preg_replace('/[^\d+]/', '', $initialRaw);
        if (str_starts_with($clean, '09') && strlen($clean) === 11) {
            $matchedCountry = 'PH';
            $matchedDigits = substr($clean, 1); // strip leading 0 -> 10 digits
        } elseif (str_starts_with($clean, '+63') || str_starts_with($clean, '63')) {
            $matchedCountry = 'PH';
            $stripped = str_starts_with($clean, '+63') ? substr($clean, 3) : substr($clean, 2);
            $matchedDigits = ltrim($stripped, '0');
        } elseif (str_starts_with($clean, '+')) {
            // Find longest matching dial code
            $bestMatch = null;
            $longestLen = 0;
            foreach ($countries as $c) {
                if (str_starts_with($clean, $c['dial']) && strlen($c['dial']) > $longestLen) {
                    $bestMatch = $c['code'];
                    $longestLen = strlen($c['dial']);
                }
            }
            if ($bestMatch) {
                $matchedCountry = $bestMatch;
                $matchedDigits = substr($clean, $longestLen);
            } else {
                $matchedDigits = substr($clean, 1);
            }
        } else {
            // Assume PH if 10 digits starting with 9
            if (strlen($clean) === 10 && str_starts_with($clean, '9')) {
                $matchedCountry = 'PH';
                $matchedDigits = $clean;
            } elseif (strlen($clean) === 11 && str_starts_with($clean, '0')) {
                $matchedCountry = 'PH';
                $matchedDigits = substr($clean, 1);
            } else {
                $matchedDigits = $clean;
            }
        }
    }

    $uuid = 'intl_phone_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $id) . '_' . substr(md5($name . $id), 0, 6);
?>

<div class="intl-phone-wrapper intl-theme-<?php echo e($theme); ?>" id="wrap_<?php echo e($uuid); ?>" data-countries='<?php echo json_encode($countries, 15, 512) ?>'>
    
    <input type="hidden" name="<?php echo e($name); ?>" id="<?php echo e($id); ?>" value="<?php echo e($initialRaw); ?>" <?php echo e($required ? 'required' : ''); ?> class="intl-phone-canonical">

    <div class="intl-phone-group <?php $__errorArgs = [$errKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-group <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        
        <div class="intl-phone-select-wrap">
            <select class="intl-phone-select <?php echo e($selectClass); ?>" id="sel_<?php echo e($uuid); ?>" aria-label="Country Dial Code">
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c['code']); ?>" data-dial="<?php echo e($c['dial']); ?>" data-digits="<?php echo e(implode(',', $c['digits'])); ?>" data-sample="<?php echo e($c['sample']); ?>" <?php echo e($matchedCountry === $c['code'] ? 'selected' : ''); ?>>
                        <?php echo e($c['flag']); ?> <?php echo e($c['dial']); ?> (<?php echo e($c['code']); ?>)
                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="intl-phone-input-wrap">
            <input type="tel"
                   id="num_<?php echo e($uuid); ?>"
                   class="intl-phone-input <?php echo e($inputClass); ?> <?php $__errorArgs = [$errKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e($matchedDigits); ?>"
                   placeholder="<?php echo e($placeholder ?? '917 123 4567'); ?>"
                   inputmode="numeric"
                   autocomplete="tel-national"
                   <?php echo e($required ? 'required' : ''); ?>>
        </div>
    </div>

    
    <div class="intl-phone-hint-row">
        <span class="intl-phone-hint" id="hint_<?php echo e($uuid); ?>">
            <?php echo e($helper ?? 'Select country and enter digits'); ?>

        </span>
        <span class="intl-phone-counter" id="cnt_<?php echo e($uuid); ?>">
            <span class="intl-cnt-current">0</span>/<span class="intl-cnt-max">10</span> digits
        </span>
    </div>

    <?php $__errorArgs = [$errKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="intl-phone-error-msg">
            <i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<?php if (! $__env->hasRenderedOnce('93cc9939-9896-498c-86c2-0c3bfd0ae80f')): $__env->markAsRenderedOnce('93cc9939-9896-498c-86c2-0c3bfd0ae80f'); ?>
<style>
    .intl-phone-wrapper {
        width: 100%;
        position: relative;
    }
    .intl-phone-group {
        display: flex;
        align-items: stretch;
        width: 100%;
        border-radius: 8px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .intl-phone-select-wrap {
        flex: 0 0 auto;
        width: 120px;
        min-width: 110px;
    }
    .intl-phone-select {
        width: 100%;
        height: 100%;
        min-height: 42px;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right: none !important;
        font-weight: 600;
        font-size: 0.86rem;
        cursor: pointer;
        padding-left: 0.65rem;
        padding-right: 1.8rem;
        background-position: right 0.55rem center;
        background-size: 11px 9px;
    }
    .intl-phone-input-wrap {
        flex: 1 1 auto;
        position: relative;
    }
    .intl-phone-input {
        width: 100%;
        height: 100%;
        min-height: 42px;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        font-size: 0.90rem;
        font-weight: 500;
        letter-spacing: 0.02em;
    }
    .intl-phone-hint-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.35rem;
        font-size: 0.73rem;
        color: #64748b;
        gap: 0.5rem;
    }
    .intl-phone-hint {
        font-weight: 500;
    }
    .intl-phone-counter {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        white-space: nowrap;
        padding: 0.1rem 0.45rem;
        border-radius: 4px;
        background: #f1f5f9;
        color: #475569;
        transition: all 0.2s ease;
    }
    .intl-phone-counter.is-valid {
        background: #dcfce7;
        color: #166534;
    }
    .intl-phone-counter.is-warn {
        background: #fef3c7;
        color: #92400e;
    }
    .intl-phone-error-msg {
        display: flex;
        align-items: center;
        color: #dc2626;
        font-size: 0.76rem;
        font-weight: 600;
        margin-top: 0.35rem;
    }

    /* ── Theme Adaptations ── */
    /* Admin Theme (matches .acct-input) */
    .intl-theme-admin .intl-phone-select,
    .intl-theme-admin .intl-phone-input {
        padding: 0.62rem 0.85rem;
        color: #0f172a;
        background-color: #fff;
        border: 1.5px solid #e2e8f0;
        font-family: inherit;
        line-height: 1.5;
        transition: border-color .15s, box-shadow .15s;
    }
    .intl-theme-admin .intl-phone-select {
        border-radius: 8px 0 0 8px;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        background-size: 11px 9px;
    }
    .intl-theme-admin .intl-phone-input {
        border-radius: 0 8px 8px 0;
    }
    .intl-theme-admin .intl-phone-select:focus,
    .intl-theme-admin .intl-phone-input:focus {
        outline: none;
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13,148,136,0.10);
        z-index: 2;
    }
    .intl-theme-admin .intl-phone-group.is-invalid-group .intl-phone-select,
    .intl-theme-admin .intl-phone-group.is-invalid-group .intl-phone-input {
        border-color: #fca5a5;
    }

    /* Profile Theme (matches .pf-ctrl) */
    .intl-theme-profile .intl-phone-select,
    .intl-theme-profile .intl-phone-input {
        padding: 0.62rem 0.85rem;
        color: #0f172a;
        background-color: #fff;
        border: 1.5px solid #e2e8f0;
        font-family: inherit;
        line-height: 1.5;
        transition: border-color .15s, box-shadow .15s;
    }
    .intl-theme-profile .intl-phone-select {
        border-radius: 8px 0 0 8px;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        background-size: 11px 9px;
    }
    .intl-theme-profile .intl-phone-input {
        border-radius: 0 8px 8px 0;
    }
    .intl-theme-profile .intl-phone-select:focus,
    .intl-theme-profile .intl-phone-input:focus {
        outline: none;
        border-color: var(--brand-primary, #2563eb);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        z-index: 2;
    }

    /* Reserve Theme (matches .rw-input) */
    .intl-theme-reserve .intl-phone-select,
    .intl-theme-reserve .intl-phone-input {
        padding: .75rem 1rem;
        border: 1.5px solid var(--rw-border, #cbd5e1);
        color: var(--rw-text, #0f172a);
        background-color: #fff;
        transition: border-color .2s, box-shadow .2s;
    }
    .intl-theme-reserve .intl-phone-select {
        border-radius: .7rem 0 0 .7rem;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 12px 10px;
    }
    .intl-theme-reserve .intl-phone-input {
        border-radius: 0 .7rem .7rem 0;
    }
    .intl-theme-reserve .intl-phone-select:focus,
    .intl-theme-reserve .intl-phone-input:focus {
        outline: none;
        border-color: var(--rw-navy, #0f172a);
        box-shadow: 0 0 0 4px rgba(13,27,42,.08);
        z-index: 2;
    }
    .intl-theme-reserve .intl-phone-input.invalid,
    .intl-theme-reserve .intl-phone-select.invalid {
        border-color: var(--rw-bad, #dc2626);
        box-shadow: 0 0 0 4px rgba(220,38,38,.08);
    }

    /* Bootstrap Theme (matches .form-control) */
    .intl-theme-bootstrap .intl-phone-select,
    .intl-theme-bootstrap .intl-phone-input {
        padding: 0.5rem 0.75rem;
        font-size: 0.92rem;
        border: 1px solid #ced4da;
        background-color: #fff;
        line-height: 1.5;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .intl-theme-bootstrap .intl-phone-select {
        border-radius: 0.375rem 0 0 0.375rem;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        background-size: 11px 9px;
    }
    .intl-theme-bootstrap .intl-phone-input {
        border-radius: 0 0.375rem 0.375rem 0;
    }
    .intl-theme-bootstrap .intl-phone-select:focus,
    .intl-theme-bootstrap .intl-phone-input:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        z-index: 2;
    }
</style>
<script>
    (function () {
        if (window.__intlPhoneInit) return;
        window.__intlPhoneInit = true;

        function formatPhoneDigits(digits) {
            if (digits.length <= 4) return digits;
            if (digits.length <= 7) return digits.slice(0, 3) + ' ' + digits.slice(3);
            if (digits.length <= 10) return digits.slice(0, 3) + ' ' + digits.slice(3, 6) + ' ' + digits.slice(6);
            return digits.slice(0, 3) + ' ' + digits.slice(3, 7) + ' ' + digits.slice(7);
        }

        window.initIntlPhoneInput = function(wrapperEl) {
            if (!wrapperEl || wrapperEl.dataset.initialized === 'true') return;
            wrapperEl.dataset.initialized = 'true';

            const select = wrapperEl.querySelector('.intl-phone-select');
            const input = wrapperEl.querySelector('.intl-phone-input');
            const hidden = wrapperEl.querySelector('.intl-phone-canonical');
            const hint = wrapperEl.querySelector('.intl-phone-hint');
            const counter = wrapperEl.querySelector('.intl-phone-counter');
            const currentSpan = counter ? counter.querySelector('.intl-cnt-current') : null;
            const maxSpan = counter ? counter.querySelector('.intl-cnt-max') : null;

            let countryData = {};
            try {
                const list = JSON.parse(wrapperEl.dataset.countries || '[]');
                list.forEach(c => countryData[c.code] = c);
            } catch (e) {
                countryData = {};
            }

            function getActiveCountry() {
                const opt = select.options[select.selectedIndex];
                const code = opt ? opt.value : 'PH';
                return countryData[code] || {
                    code: 'PH', dial: '+63', digits: [10], sample: '917 123 4567', name: 'Philippines'
                };
            }

            function sync() {
                const country = getActiveCountry();
                const optDigits = country.digits || [10];
                const maxDigits = Math.max(...optDigits);
                const minDigits = Math.min(...optDigits);

                let rawVal = input.value.replace(/\D/g, '');

                if (country.code === 'PH' && rawVal.startsWith('09') && rawVal.length > 10) {
                    rawVal = rawVal.slice(1);
                } else if (rawVal.startsWith('0') && rawVal.length > maxDigits) {
                    rawVal = rawVal.slice(1);
                }

                if (rawVal.length > maxDigits) {
                    rawVal = rawVal.slice(0, maxDigits);
                }

                input.value = formatPhoneDigits(rawVal);
                input.maxLength = maxDigits + 4;
                input.placeholder = country.sample || ('e.g. ' + maxDigits + ' digits');

                if (rawVal.length > 0) {
                    hidden.value = country.dial + rawVal;
                } else {
                    hidden.value = '';
                }

                hidden.dispatchEvent(new Event('input', { bubbles: true }));
                hidden.dispatchEvent(new Event('change', { bubbles: true }));

                if (currentSpan) currentSpan.textContent = rawVal.length;
                if (maxSpan) maxSpan.textContent = minDigits === maxDigits ? maxDigits : (minDigits + '-' + maxDigits);

                if (hint) {
                    const digitDesc = minDigits === maxDigits ? (maxDigits + ' digits') : (minDigits + ' to ' + maxDigits + ' digits');
                    hint.textContent = country.name + ' (' + country.dial + '): requires ' + digitDesc + ' (e.g. ' + country.sample + ')';
                }

                if (counter) {
                    counter.classList.remove('is-valid', 'is-warn');
                    if (rawVal.length === 0) {
                        // empty
                    } else if (optDigits.includes(rawVal.length)) {
                        counter.classList.add('is-valid');
                    } else {
                        counter.classList.add('is-warn');
                    }
                }
            }

            input.addEventListener('paste', function(e) {
                const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
                const cleanPasted = pasted.replace(/[^\d+]/g, '');

                if (cleanPasted.startsWith('+')) {
                    const opts = Array.from(select.options);
                    opts.sort((a, b) => (b.getAttribute('data-dial') || '').length - (a.getAttribute('data-dial') || '').length);
                    for (const opt of opts) {
                        const dial = opt.getAttribute('data-dial');
                        if (dial && cleanPasted.startsWith(dial)) {
                            select.value = opt.value;
                            e.preventDefault();
                            input.value = cleanPasted.slice(dial.length);
                            sync();
                            return;
                        }
                    }
                }
            });

            select.addEventListener('change', function() {
                sync();
                input.focus();
            });

            input.addEventListener('input', sync);

            // Support external programmatic value updates
            hidden.addEventListener('external:sync', function() {
                const val = (hidden.value || '').trim();
                if (!val) {
                    input.value = '';
                    sync();
                    return;
                }
                const clean = val.replace(/[^\d+]/g, '');
                let matchedCode = null;
                let matchedDigits = '';
                if (clean.startsWith('+')) {
                    const opts = Array.from(select.options);
                    opts.sort((a, b) => (b.getAttribute('data-dial') || '').length - (a.getAttribute('data-dial') || '').length);
                    for (const opt of opts) {
                        const dial = opt.getAttribute('data-dial');
                        if (dial && clean.startsWith(dial)) {
                            matchedCode = opt.value;
                            matchedDigits = clean.slice(dial.length);
                            break;
                        }
                    }
                } else if (clean.startsWith('09') && clean.length === 11) {
                    matchedCode = 'PH';
                    matchedDigits = clean.slice(1);
                }
                if (matchedCode) {
                    select.value = matchedCode;
                    input.value = matchedDigits;
                } else {
                    input.value = clean;
                }
                sync();
            });

            sync();
        };

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.intl-phone-wrapper').forEach(window.initIntlPhoneInput);
        });
    })();
</script>
<?php endif; ?>

<script>
    (function () {
        var el = document.getElementById('wrap_<?php echo e($uuid); ?>');
        if (el && window.initIntlPhoneInput) {
            window.initIntlPhoneInput(el);
        }
    })();
</script>
<?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/components/international-phone-input.blade.php ENDPATH**/ ?>