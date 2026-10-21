{{--
    Renders a single whitelabel legal document with dynamic language tabs.

    Expected variables:
      $doc  : short document key ('terms', 'privacy', 'data_collection')
      $slug : unique slug used for element ids ('tos', 'privacy', 'data')

    Languages are defined per-university via the CMS and rendered from Markdown.
--}}
@php
    $languages = legal_doc($doc);
@endphp

@if (empty($languages))
    <div class="legal-scroll-body h-100 w-100 lang-scroller" data-parent="{{ $slug }}">
        <p class="text-muted">This document has not been configured yet.</p>
    </div>
@else
    {{-- Language toggle (only shown when more than one language exists) --}}
    @if (count($languages) > 1)
        <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom bg-light legal-lang-bar">
            <span class="small fw-bold text-muted"><i class="bi bi-translate me-1"></i>Language</span>
            <ul class="nav nav-pills nav-pills-sm flex-wrap gap-1" role="tablist">
                @foreach ($languages as $i => $lang)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-1 px-3 {{ $i === 0 ? 'active' : '' }}"
                                data-bs-toggle="pill"
                                data-bs-target="#scroll-{{ $slug }}-{{ $lang['code'] }}"
                                type="button" role="tab">{{ $lang['label'] }}</button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tab-content flex-grow-1 overflow-hidden" style="background:#fff;">
        @foreach ($languages as $i => $lang)
            <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }} h-100"
                 id="scroll-{{ $slug }}-{{ $lang['code'] }}" role="tabpanel">
                <div class="legal-scroll-body legal-markdown h-100 w-100 lang-scroller" data-parent="{{ $slug }}">
                    <p class="text-muted small mb-3">
                        <strong>Effective Date:</strong> {{ date('F d, Y') }} &nbsp;|&nbsp;
                        <strong>{{ branding('system_name', 'Platform') }}</strong>
                    </p>
                    {!! $lang['html'] !!}
                </div>
            </div>
        @endforeach
    </div>
@endif
