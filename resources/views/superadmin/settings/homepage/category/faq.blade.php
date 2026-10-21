@extends('layouts.superadmin')

@section('title', 'Homepage Editor — FAQ')

@section('content')

<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'homepage'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">FAQ</h1>
        <p class="ph-sub">Common questions, grouped by topic.</p>
    </div>
    <div class="page-actions">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-ghost"><i class="bi bi-box-arrow-up-right"></i>View live homepage</a>
    </div>
</div>

<form method="POST" action="{{ route('superadmin.settings.homepage.update') }}" id="homepageForm">
    @csrf
    @method('PUT')

    <input type="hidden" name="editing_section" value="faq">
    <input type="hidden" name="faq_groups" id="faqGroupsJson">

    <div class="cms-grid cms-grid-single">
        <div class="setting-group-card">
            <div class="setting-group-header">
                <i class="bi bi-question-circle-fill"></i>
                FAQ
                <span class="sg-sub">Common questions, grouped by topic.</span>
            </div>
            <div class="setting-group-body">
                <div class="setting-field">
                    <label class="setting-label" for="setting-faq_title">
                        Section title
                        <span class="setting-key">faq.title</span>
                    </label>
                    <input
                        type="text"
                        class="setting-input"
                        id="setting-faq_title"
                        name="faq_title"
                        value="{{ $data['faq']['title'] ?? 'Frequently asked questions' }}"
                    >
                </div>

                <div class="setting-field">
                    <label class="setting-label" for="setting-faq_sub">
                        Subtitle
                        <span class="setting-key">faq.subtitle</span>
                    </label>
                    <textarea
                        class="setting-input"
                        id="setting-faq_sub"
                        name="faq_sub"
                        rows="2"
                    >{{ $data['faq']['sub'] ?? 'Everything you need to know about booking campus facilities through ' . branding('system_name', 'Platform') . '.' }}</textarea>
                </div>

                <hr class="cms-divider">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="setting-label mb-0">
                        Question groups
                        <span class="setting-key">faq.groups</span>
                    </label>
                    <button type="button" class="btn btn-ghost btn-sm" id="addGroupBtn" style="border-style:dashed;">
                        <i class="bi bi-plus-lg"></i> Add group
                    </button>
                </div>
                <div id="groupsList" class="d-flex flex-column gap-3"></div>
            </div>
        </div>
    </div>

    @include('partials._cms_save_bar', ['label' => 'Save FAQ'])
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
    var systemName = '{{ str_replace("'", "\\'", branding('system_name', 'Platform')) }}';
    var groups = (INITIAL && INITIAL.faq && INITIAL.faq.groups) || [
        {
            title: 'General information',
            questions: [
                { q: 'What is ' + systemName + '?', a: systemName + ' is an automated web platform designed to streamline, track, and manage university facility and resource bookings across campus, ensuring a transparent process for all users.' },
                { q: 'Who can use the system?', a: 'Students, faculty, administrative staff, recognized student organizations, and authorized external clients across university campuses.' },
                { q: 'What facilities and resources can be reserved?', a: 'Users can reserve authorized campus venues (such as auditoriums, gymnasiums, and grounds) as well as shared event equipment.' }
            ]
        },
        {
            title: 'Reservations &amp; approval process',
            questions: [
                { q: 'How do I submit a campus facility reservation request?', a: 'Log into the portal using your institutional credentials, navigate to the reservation section, select your desired location or equipment, and fill in the event details before submitting for approval.' },
                { q: 'How long does the approval process take?', a: 'Processing times depend on the specific venue and administrative review requirements, but every approval step is updated dynamically on your tracking dashboard.' }
            ]
        },
        {
            title: 'Policies &amp; security',
            questions: [
                { q: 'What is the policy for modifying or cancelling a reservation?', a: 'Cancellations or modifications must be submitted at least 7 days (1 week) prior to your scheduled event date. Late cancellations may result in a temporary suspension of booking privileges.' },
                { q: 'How does ' + systemName + ' handle double-booking and system security?', a: systemName + ' employs automated soft-lock mechanisms to prevent overlapping submission attempts and enforces role-based access secured by institutional single sign-on authentication.' }
            ]
        },
        {
            title: 'Support &amp; assistance',
            questions: [
                { q: 'Who do I contact if I need help planning an event?', a: 'You can reach out directly to the facilities administration team via the contact form below or email support for specialized event requirements.' }
            ]
        }
    ];

    var wrap = document.getElementById('groupsList');
    var jsonField = document.getElementById('faqGroupsJson');

    function groupHTML(g, gi) {
        var qHTML = g.questions.map(function (q, qi) {
            return '<div class="border rounded-2 p-2 mb-2 bg-white">' +
                '<div class="d-flex justify-content-between align-items-center mb-1">' +
                    '<span class="small text-muted">Q' + (qi + 1) + '</span>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm" data-q-del="' + gi + '.' + qi + '"><i class="bi bi-trash"></i></button>' +
                '</div>' +
                '<input type="text" class="form-control form-control-sm mb-1" placeholder="Question" value="' + esc(q.q) + '" data-q-q="' + gi + '.' + qi + '">' +
                '<textarea class="form-control form-control-sm" rows="2" placeholder="Answer" data-q-a="' + gi + '.' + qi + '">' + esc(q.a) + '</textarea>' +
            '</div>';
        }).join('');

        return '<div class="border rounded-3 p-3 bg-light" data-group="' + gi + '">' +
            '<div class="row align-items-center mb-2">' +
                '<div class="col-auto">' +
                    '<strong class="small text-muted">Group ' + (gi + 1) + '</strong>' +
                '</div>' +
                '<div class="col flex-grow-1 me-2">' +
                    '<input type="text" class="form-control form-control-sm" value="' + esc(g.title) + '" data-group-title="' + gi + '" placeholder="Group title (e.g. General information)">' +
                '</div>' +
                '<div class="col-auto">' +
                    '<button type="button" class="btn btn-ghost btn-sm" data-add-q="' + gi + '"><i class="bi bi-plus-lg me-1"></i>Question</button>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm" data-group-del="' + gi + '"><i class="bi bi-trash"></i></button>' +
                '</div>' +
            '</div>' +
            '<div data-group-body="' + gi + '">' + qHTML + '</div>' +
        '</div>';
    }

    function renderGroups() {
        wrap.innerHTML = '';
        groups.forEach(function (g, gi) {
            wrap.insertAdjacentHTML('beforeend', groupHTML(g, gi));
        });
        syncGroups();
    }

    function syncGroups() {
        wrap.querySelectorAll('input[data-group-title]').forEach(function (el) {
            groups[parseInt(el.getAttribute('data-group-title'), 10)].title = el.value;
        });
        wrap.querySelectorAll('input[data-q-q]').forEach(function (el) {
            var parts = el.getAttribute('data-q-q').split('.');
            groups[parts[0]].questions[parts[1]].q = el.value;
        });
        wrap.querySelectorAll('textarea[data-q-a]').forEach(function (el) {
            var parts = el.getAttribute('data-q-a').split('.');
            groups[parts[0]].questions[parts[1]].a = el.value;
        });
        jsonField.value = JSON.stringify(groups);
    }

    wrap.addEventListener('input', syncGroups);
    wrap.addEventListener('click', function (e) {
        var add = e.target.closest('[data-add-q]');
        if (add) {
            var gi = parseInt(add.getAttribute('data-add-q'), 10);
            groups[gi].questions.push({ q: '', a: '' });
            renderGroups();
            return;
        }
        var qdel = e.target.closest('[data-q-del]');
        if (qdel) {
            var parts = qdel.getAttribute('data-q-del').split('.');
            groups[parts[0]].questions.splice(parts[1], 1);
            renderGroups();
            return;
        }
        var gdel = e.target.closest('[data-group-del]');
        if (gdel) {
            groups.splice(parseInt(gdel.getAttribute('data-group-del'), 10), 1);
            renderGroups();
            return;
        }
    });
    document.getElementById('addGroupBtn').addEventListener('click', function () {
        groups.push({ title: '', questions: [] });
        renderGroups();
    });

    document.getElementById('homepageForm').addEventListener('submit', function () {
        syncGroups();
    });

    renderGroups();
});
</script>
@endpush
