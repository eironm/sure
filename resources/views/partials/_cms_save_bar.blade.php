{{-- CMS save bar. Use inside the form it submits. --}}
@php $label = $label ?? 'Save'; @endphp
<div class="save-bar">
    <div class="sb-note"><i class="bi bi-info-circle-fill"></i> Nothing is saved until you press the button below.</div>
    <div class="sb-actions">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-lg"></i> {{ $label }}
        </button>
    </div>
</div>
