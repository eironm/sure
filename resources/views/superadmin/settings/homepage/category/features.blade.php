@extends('layouts.superadmin')

@section('title', 'Homepage Editor — Features')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'homepage'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Features</h1>
        <p class="ph-sub">Highlight what makes the platform valuable.</p>
    </div>
    <div class="page-actions">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost"><i class="bi bi-box-arrow-up-right"></i>View live homepage</a>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.settings.homepage.update') }}" id="homepageForm">
    @csrf
    @method('PUT')

    <input type="hidden" name="editing_section" value="features">
    <input type="hidden" name="feature_cards" id="featureCardsJson">

    <div class="cms-grid cms-grid-single">
        <div class="setting-group-card">
            <div class="setting-group-header">
                <i class="bi bi-grid-fill"></i>
                Features
                <span class="sg-sub">Cards: &ldquo;Why {{ branding('system_name', 'Platform') }}?&rdquo;</span>
            </div>
            <div class="setting-group-body">
                <div class="setting-field">
                    <label class="setting-label" for="setting-feature_title">
                        Section title
                        <span class="setting-key">features.title</span>
                    </label>
                    <input
                        type="text"
                        class="setting-input"
                        id="setting-feature_title"
                        name="feature_title"
                        value="{{ $data['features']['title'] ?? 'Why ' . branding('system_name', 'Platform') . '?' }}"
                    >
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="setting-feature_sub">
                        Subtitle
                        <span class="setting-key">features.subtitle</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-feature_sub"
                        name="feature_sub"
                        rows="2"
                    >{{ $data['features']['sub'] ?? 'Everything our community needs to plan, request, and manage campus facilities — in one place.' }}</textarea>
                </div>

                <hr class="cms-divider">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="setting-label mb-0">
                        Feature cards
                        <span class="setting-key">features.cards</span>
                    </label>
                    <button type="button" class="btn btn-ghost btn-sm" id="addCardBtn" style="border-style:dashed;">
                        <i class="bi bi-plus-lg"></i> Add card
                    </button>
                </div>
                <div id="cardsList" class="d-flex flex-column gap-3"></div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save Features'])
</form>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    function esc(v) {
        return String(v == null ? '' : v).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    var INITIAL = @json($data);
    var ICONS = [
        'bi-calendar2-plus-fill','bi-eye-fill','bi-bell-fill','bi-graph-up-arrow',
        'bi-star-fill','bi-heart-fill','bi-gear-fill','bi-shield-check',
        'bi-lightning-charge-fill','bi-people-fill','bi-building','bi-book-fill'
    ];

    var cards = (INITIAL && INITIAL.features && INITIAL.features.cards) || [
        { icon: 'bi-calendar2-plus-fill', title: 'Organized & reliable', body: 'A reservation experience that is organized, reliable, and accessible for the entire university community.' },
        { icon: 'bi-eye-fill', title: 'Transparent by default', body: 'Fostering transparency and efficient facility management — the standard platform for campus reservations.' },
        { icon: 'bi-bell-fill', title: 'Track every step', body: 'Follow each request step-by-step with real-time status updates and automated reminders for seamless coordination.' },
        { icon: 'bi-graph-up-arrow', title: 'Insights for decision-makers', body: 'Clear logs and resource insights to better support university events, academic functions, and student activities.' }
    ];

    var wrap = document.getElementById('cardsList');
    var jsonField = document.getElementById('featureCardsJson');

    function iconOptions(sel) {
        return ICONS.map(function (ic) {
            return '<option value="' + ic + '"' + (ic === sel ? ' selected' : '') + '>' + ic + '</option>';
        }).join('');
    }

    function renderCards() {
        wrap.innerHTML = '';
        cards.forEach(function (c, i) {
            var div = document.createElement('div');
            div.className = 'repeater-item nested';
            div.innerHTML =
                '<div class="repeater-head">' +
                    '<strong class="small text-muted">Card ' + (i + 1) + '</strong>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm" data-card-del="' + i + '"><i class="bi bi-trash"></i></button>' +
                '</div>' +
                '<div class="row g-2 mt-1">' +
                    '<div class="col-md-3">' +
                        '<label class="form-label" style="font-size:.66rem;">Icon</label>' +
                        '<select class="form-select" data-card-icon="' + i + '">' + iconOptions(c.icon) + '</select>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                        '<label class="form-label" style="font-size:.66rem;">Title</label>' +
                        '<input type="text" class="form-control" value="' + esc(c.title) + '" data-card-title="' + i + '">' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<label class="form-label" style="font-size:.66rem;">Description</label>' +
                        '<textarea class="form-control" rows="2" data-card-body="' + i + '">' + esc(c.body) + '</textarea>' +
                    '</div>' +
                '</div>';
            wrap.appendChild(div);
        });
        syncCards();
    }

    function syncCards() {
        wrap.querySelectorAll('select[data-card-icon]').forEach(function (el) {
            cards[parseInt(el.getAttribute('data-card-icon'), 10)].icon = el.value;
        });
        wrap.querySelectorAll('input[data-card-title]').forEach(function (el) {
            cards[parseInt(el.getAttribute('data-card-title'), 10)].title = el.value;
        });
        wrap.querySelectorAll('textarea[data-card-body]').forEach(function (el) {
            cards[parseInt(el.getAttribute('data-card-body'), 10)].body = el.value;
        });
        jsonField.value = JSON.stringify(cards);
    }

    wrap.addEventListener('input', syncCards);
    wrap.addEventListener('change', syncCards);
    wrap.addEventListener('click', function (e) {
        var del = e.target.closest('[data-card-del]');
        if (!del) return;
        cards.splice(parseInt(del.getAttribute('data-card-del'), 10), 1);
        renderCards();
    });
    document.getElementById('addCardBtn').addEventListener('click', function () {
        cards.push({ icon: 'bi-star-fill', title: '', body: '' });
        renderCards();
    });

    renderCards();
});
</script>
@endpush
