{{-- Single CMS setting field. Renders one row for the given $setting model row.
     Supported types: text, email, number, url, tel, password, textarea, color,
     boolean (switch), and image (with optional Cropper.js crop flow). --}}
@php
    $isFavicon = ($setting->key ?? '') === 'favicon';
    $needsCrop = in_array($setting->key ?? '', ['system_logo', 'login_background'], true);
    $cropRatio = ($setting->key ?? '') === 'login_background' ? '16:9' : null;
    $accept    = $isFavicon
        ? '.ico,.png,.jpg,.jpeg,.webp,.svg'
        : '.png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml';
    $showHint = $showHint ?? false;
@endphp

<div class="setting-field @if ($setting->type === 'boolean') is-boolean @endif">
    <label class="setting-label" for="setting-{{ $setting->key }}">
        {{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}
        <span class="setting-key">{{ $setting->key }}</span>
    </label>

    @if ($setting->type === 'textarea')
        <textarea
            class="setting-input"
            id="setting-{{ $setting->key }}"
            name="settings[{{ $setting->key }}]"
            rows="3"
        >{{ old("settings.{$setting->key}", $setting->value) }}</textarea>

    @elseif ($setting->type === 'color')
        <div class="color-preview">
            <input
                type="color"
                class="color-swatch"
                id="color-swatch-{{ $setting->key }}"
                value="{{ ($setting->value && preg_match('/^#[0-9a-fA-F]{6}$/', $setting->value)) ? $setting->value : '#000000' }}"
                oninput="document.getElementById('setting-{{ $setting->key }}').value = this.value; window.heroSwatchSync ? window.heroSwatchSync('{{ $setting->key }}', this.value) : null;"
            >
            <input
                type="text"
                class="setting-input color-hex-input"
                id="setting-{{ $setting->key }}"
                name="settings[{{ $setting->key }}]"
                value="{{ old("settings.{$setting->key}", $setting->value) }}"
                placeholder="#000000"
                oninput="document.getElementById('color-swatch-{{ $setting->key }}').value = this.value"
            >
        </div>

    @elseif ($setting->type === 'image')
        @if ($isFavicon)
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                    type="file"
                    class="d-none"
                    id="setting-{{ $setting->key }}"
                    name="settings[{{ $setting->key }}]"
                    accept="{{ $accept }}"
                    onchange="document.getElementById('cms-file-label-{{ $setting->key }}').innerText = this.files.length ? '\u2714 ' + this.files[0].name : 'No file chosen'"
                >
                <label for="setting-{{ $setting->key }}" class="file-choose">
                    <i class="bi bi-upload"></i> Choose File
                </label>
                <span class="file-label" id="cms-file-label-{{ $setting->key }}">No file chosen</span>
            </div>
            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Supported formats: <code>.ico</code>, <code>.png</code>, <code>.svg</code>, <code>.webp</code>, <code>.jpg</code>.</p>

        @elseif ($needsCrop)
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                    type="file"
                    class="d-none"
                    id="raw_input_{{ $setting->key }}"
                    name="settings[{{ $setting->key }}]"
                    accept="{{ $accept }}"
                    data-preview="lv-preview-{{ $setting->key }}"
                    data-target-hidden="cropped_input_{{ $setting->key }}"
                    data-crop-ratio="{{ $cropRatio }}"
                    data-label="{{ $setting->key === 'system_logo' ? 'System Logo' : 'Login Background' }}"
                    onchange="openCmsCropper(this)"
                >
                <input
                    type="file"
                    class="d-none"
                    id="cropped_input_{{ $setting->key }}"
                    name="settings[cropped_input_{{ $setting->key }}]"
                >
                <label for="raw_input_{{ $setting->key }}" class="file-choose">
                    <i class="bi bi-upload"></i> Choose File
                </label>
                @if ($setting->value)
                    <label for="raw_input_{{ $setting->key }}" class="file-choose">
                        <i class="bi bi-crop"></i> Crop &amp; Upload
                    </label>
                @endif
                <span class="file-label" id="cms-file-label-{{ $setting->key }}">
                    {{ $setting->value ? 'Current: ' . $setting->value : 'No file chosen' }}
                </span>
            </div>
            @if ($setting->value)
                <div class="mt-2">
                    <img src="{{ asset($setting->value) }}" alt="Current {{ $setting->key }}"
                         id="lv-preview-{{ $setting->key }}"
                         style="max-height:90px;border-radius:8px;border:1px solid var(--line);">
                </div>
            @endif
            <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>Recommended image types: PNG, JPG, WebP.</p>

        @else
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input
                    type="file"
                    class="d-none"
                    id="setting-{{ $setting->key }}"
                    name="settings[{{ $setting->key }}]"
                    accept="{{ $accept }}"
                    onchange="document.getElementById('cms-file-label-{{ $setting->key }}').innerText = this.files.length ? '\u2714 ' + this.files[0].name : 'No file chosen'"
                >
                <label for="setting-{{ $setting->key }}" class="file-choose">
                    <i class="bi bi-upload"></i> Choose File
                </label>
                <span class="file-label" id="cms-file-label-{{ $setting->key }}">No file chosen</span>
            </div>
            @if ($setting->value)
                <div class="mt-2">
                    <img src="{{ asset($setting->value) }}" alt="Current {{ $setting->key }}"
                         style="max-height:72px;border-radius:6px;border:1px solid var(--line);">
                </div>
            @endif
        @endif

    @elseif ($setting->type === 'boolean')
        <div class="form-check form-switch premium-switch mb-0">
            <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="setting-{{ $setting->key }}"
                name="settings[{{ $setting->key }}]"
                value="1"
                {{ old("settings.{$setting->key}", $setting->value) === '1' ? 'checked' : '' }}
            >
        </div>

    @else
        <input
            type="{{ in_array($setting->type, ['text','email','number','url','tel','password'], true) ? $setting->type : 'text' }}"
            class="setting-input"
            id="setting-{{ $setting->key }}"
            name="settings[{{ $setting->key }}]"
            value="{{ old("settings.{$setting->key}", $setting->value) }}"
            placeholder="{{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}"
        >
    @endif

    @if ($showHint && $setting->type !== 'image' && ! empty($setting->hint))
        <p class="setting-hint mb-0"><i class="bi bi-info-circle me-1"></i>{{ $setting->hint }}</p>
    @endif
</div>
