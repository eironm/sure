@extends('layouts.superadmin')

@section('title', 'Legal Documents')

@section('content')
<div class="branding-layout">
@include('partials.superadmin-branding-nav', ['page' => 'legal'])
<div class="branding-main">

<div class="page-header">
    <div>
        <h1 class="ph-title">Legal Documents</h1>
        <p class="ph-sub">Manage the Terms of Service, Privacy Policy, and Data Collection Notice guests see during signup.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('superadmin.dashboard') }}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i>Dashboard</a>
    </div>
</div>

<div class="alert alert-info d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-info-circle-fill flex-shrink-0"></i>
    <div>Add as many languages as you need per document, write in <strong>Markdown</strong>, and see the <strong>live preview</strong> beside each editor. Empty languages are discarded on save.</div>
</div>

<form action="{{ route('superadmin.settings.legal.update') }}" method="POST" id="legalForm">
    @csrf
    @method('PUT')

    @foreach ($documents as $docKey => $doc)
        <div class="card mb-4" data-doc="{{ $docKey }}" id="doc-{{ $docKey }}">
            <div class="card-head">
                <div class="ch-title"><i class="bi bi-file-earmark-text-fill"></i> {{ $doc['label'] }}</div>
                <span class="badge badge-neutral"><span data-lang-count-for="{{ $docKey }}">{{ count($doc['languages']) ?: 1 }}</span> language(s)</span>
            </div>
            <div class="card-body">
                <div class="lang-list" data-doc="{{ $docKey }}">
                    @forelse ($doc['languages'] as $i => $lang)
                        @include('superadmin.settings._legal_lang', [
                            'docKey' => $docKey, 'i' => $i,
                            'code' => $lang['code'], 'label' => $lang['label'], 'body' => $lang['body'],
                        ])
                    @empty
                        @include('superadmin.settings._legal_lang', [
                            'docKey' => $docKey, 'i' => 0, 'code' => '', 'label' => '', 'body' => '',
                        ])
                    @endforelse
                </div>
                <button type="button" class="btn btn-ghost btn-sm" data-add-lang="{{ $docKey }}" style="border-style:dashed;">
                    <i class="bi bi-plus-lg"></i> Add Language
                </button>
            </div>
        </div>
    @endforeach

    @include('partials._cms_save_bar', ['label' => 'Save Legal Documents'])
</form>

{{-- Template for a new language block (cloned by JS) --}}
<template id="langBlockTemplate">
    @include('superadmin.settings._legal_lang', [
        'docKey' => '__DOC__', 'i' => '__I__', 'code' => '', 'label' => '', 'body' => '',
    ])
</template>

</div><!-- /branding-main -->
</div><!-- /branding-layout -->

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked@12.0.2/marked.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /* ── Scroll-spy: bookmark the document card currently in view ── */
    var docLinks = document.querySelectorAll('.branding-subnav a.bsn-scroll');
    var docCards = document.querySelectorAll('[id^="doc-"]');
    if (docLinks.length && docCards.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                var link = document.querySelector('.branding-subnav a.bsn-scroll[data-doc="' + entry.target.id + '"]');
                if (link && entry.isIntersecting) { docLinks.forEach(function (item) { item.classList.remove('bsn-scroll-active'); }); link.classList.add('bsn-scroll-active'); }
            });
        }, { root: null, rootMargin: '-16% 0px -62% 0px', threshold: 0 });
        docLinks.forEach(function (link) { link.addEventListener('click', function () { docLinks.forEach(function (item) { item.classList.remove('bsn-scroll-active'); }); link.classList.add('bsn-scroll-active'); }); });
        if (docLinks[0]) docLinks[0].classList.add('bsn-scroll-active');
        docCards.forEach(function (card) { observer.observe(card); });
    }

    /* Tiny built-in Markdown fallback — preview still renders headings, bold, and lists even if the marked CDN fails to load. */
    function mdFallback(txt) {
        if (!txt) return '';
        var html = String(txt)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/^### (.+)$/gm, '<h3>$1</h3>')
            .replace(/^## (.+)$/gm, '<h2>$1</h2>')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/^- (.+)$/gm, '<li>$1</li>')
            .replace(/((?:<li>.*<\/li>\n?)+)/g, '<ul>$1</ul>')
            .replace(/(^|\n)(?!<[hlu]|<\/)/g, '$1<p>')
            .replace(/(?!<\/)(<p>)/g, '$1')
            .replace(/([^>])\n/g, '$1<br>');
        html = html.replace(/<p>\s*(<h[23]|<ul|<\/ul>)/g, '$1')
                   .replace(/(<\/h[23]>|<\/ul>)\s*(<p>)/g, '$1');
        return html;
    }

    function md(txt) {
        try {
            if (window.marked && marked.parse) return marked.parse(txt || '');
        } catch (e) { /* fall through */ }
        return mdFallback(txt || '');
    }

    function wirePreview(block) {
        var ta = block.querySelector('textarea');
        var pv = block.querySelector('.md-preview');
        if (!ta || !pv) return;
        var render = function () { pv.innerHTML = md(ta.value); };
        ta.addEventListener('input', render);
        render();
    }

    function updateCount(docKey) {
        var list = document.querySelector('.lang-list[data-doc="' + docKey + '"]');
        var counter = document.querySelector('[data-lang-count-for="' + docKey + '"]');
        if (list && counter) counter.textContent = list.querySelectorAll('.lang-block').length;
    }

    /* Wire existing blocks */
    document.querySelectorAll('.lang-block').forEach(wirePreview);

    /* Remove language */
    document.addEventListener('click', function (e) {
        var rm = e.target.closest('.btn-remove-lang');
        if (!rm) return;
        var block = rm.closest('.lang-block');
        var list  = block.closest('.lang-list');
        var docKey = list.dataset.doc;
        if (list.querySelectorAll('.lang-block').length <= 1) {
            /* Keep at least one (clear instead of remove) */
            block.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
            var pv = block.querySelector('.md-preview');
            if (pv) pv.innerHTML = '';
        } else {
            block.remove();
        }
        updateCount(docKey);
    });

    /* Add language */
    var template = document.getElementById('langBlockTemplate');
    document.querySelectorAll('[data-add-lang]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var docKey = btn.dataset.addLang;
            var list   = document.querySelector('.lang-list[data-doc="' + docKey + '"]');
            var index  = list.querySelectorAll('.lang-block').length;
            var html = template.innerHTML
                .replaceAll('__DOC__', docKey)
                .replaceAll('__I__', index);
            var wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            var block = wrap.firstElementChild;
            list.appendChild(block);
            wirePreview(block);
            updateCount(docKey);
            var first = block.querySelector('input');
            if (first) first.focus();
        });
    });
});
</script>
@endpush
