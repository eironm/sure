{{-- Official Letterhead Component (Word-Style Builder / Banner from CMS) --}}
@php
    $hdrMode = branding('report_header_mode', 'builder');
    $hdrBanner = branding('report_header_image');
    $hdrBannerH = branding('report_header_image_height', '90');

    $showLeftLogo = branding('report_header_left_logo_show', '1') == '1';
    $leftLogoPath = branding('report_header_left_logo');
    $leftLogoSize = (int) branding('report_header_left_logo_size', '60');
    $leftLogoShape = branding('report_header_left_logo_shape', 'circle');

    $showRightLogo = branding('report_header_right_logo_show', '1') == '1';
    $rightLogoPath = branding('report_header_right_logo');
    $rightLogoSize = (int) branding('report_header_right_logo_size', '60');
    $rightLogoShape = branding('report_header_right_logo_shape', 'circle');

    $ruleStyle = branding('report_header_divider_style', 'double');
    $ruleColor = branding('report_header_divider_color', '#0f2b48');

    $hLine1 = branding('report_header_line1', 'Republic of the Philippines');
    $hLine2 = branding('report_header_line2', branding('institution_name', 'STATE UNIVERSITY SYSTEM'));
    $hLine3 = branding('report_header_line3', branding('office_name', 'OFFICE OF PHYSICAL PLANT & AUXILIARY SERVICES'));
    $hLine4 = branding('report_header_line4', branding('campus_address', 'University Boulevard, Main Campus, Metro Manila, Philippines'));
    $hLine5 = branding('report_header_line5', 'Tel: (02) 8911-2000 • Web: ' . url('/'));
@endphp

@if($hdrMode === 'image' && $hdrBanner)
    @php
        $hdrFit = branding('report_header_image_fit', 'contain');
        $hdrEdge = branding('report_header_edge', 'flush');
        $hdrOffsetY = (int) branding('report_header_offset_y', '0');
        $hdrPosX = (float) branding('report_header_image_pos_x', '0');
        $hdrPosY = (float) branding('report_header_image_pos_y', '0');
        $hdrScale = (float) branding('report_header_image_scale', '1');
        $isHdrFlush = $hdrEdge === 'flush';
    @endphp
    <div class="doc-header-banner-wrap {{ $isHdrFlush ? 'is-flush' : '' }}" style="height: {{ $hdrBannerH }}px; overflow: hidden; position: relative; --hdr-offset-y: {{ $hdrOffsetY }}px; {{ $isHdrFlush ? 'width: calc(100% + 32mm); margin-left: -16mm; margin-right: -16mm; margin-top: calc(-14mm + ' . $hdrOffsetY . 'px); margin-bottom: 0.65rem;' : 'width: 100%; margin-top: ' . $hdrOffsetY . 'px; margin-bottom: 0.65rem;' }}">
        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; transform: translate({{ $hdrPosX }}px, {{ $hdrPosY }}px) scale({{ $hdrScale }}); transform-origin: center center;">
            <img src="{{ asset($hdrBanner) }}" alt="Letterhead" class="doc-header-banner-img" style="width: 100%; height: 100%; {{ $hdrFit === 'width' ? 'object-fit: cover;' : 'object-fit: contain;' }} display: block; max-width: none; max-height: none;">
        </div>
    </div>
@else
    <div class="doc-header">
        {{-- Left Seal / Logo --}}
        @if($showLeftLogo)
            <div class="seal-wrap" style="width: {{ max($leftLogoSize, 64) }}px;">
                @if($leftLogoShape === 'none')
                    <img src="{{ $leftLogoPath ? asset($leftLogoPath) : asset('assets/logo-dark.png') }}" class="seal-img-raw" style="max-width: {{ $leftLogoSize }}px; max-height: {{ $leftLogoSize }}px;">
                @else
                    <div class="seal-circle has-ring" style="width: {{ $leftLogoSize }}px; height: {{ $leftLogoSize }}px;">
                        @if($leftLogoPath)
                            <img src="{{ asset($leftLogoPath) }}" class="seal-img" alt="Left Seal">
                        @else
                            <div class="seal-inner-circle">
                                <i class="bi bi-bank"></i>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="seal-label">OFFICIAL</div>
            </div>
        @endif

        {{-- Center Official Header --}}
        <div class="doc-header-center">
            @if($hLine1)
                <div class="doc-rep-ph" style="font-size: {{ branding('report_header_line1_font_size', '9.5px') }}; font-weight: {{ branding('report_header_line1_font_weight', '700') }}; color: {{ branding('report_header_line1_color', '#334155') }}; text-transform: {{ branding('report_header_line1_transform', 'uppercase') }}; letter-spacing: 0.12em;">
                    {{ $hLine1 }}
                </div>
            @endif

            @if($hLine2)
                <div class="doc-inst-name" style="font-size: {{ branding('report_header_line2_font_size', '16.5px') }}; font-weight: {{ branding('report_header_line2_font_weight', '900') }}; color: {{ branding('report_header_line2_color', '#0f2b48') }}; text-transform: {{ branding('report_header_line2_transform', 'none') }}; line-height: 1.2; margin: 2px 0;">
                    {{ $hLine2 }}
                </div>
            @endif

            @if($hLine3)
                <div class="doc-dept-name" style="font-size: {{ branding('report_header_line3_font_size', '10.5px') }}; font-weight: {{ branding('report_header_line3_font_weight', '600') }}; color: {{ branding('report_header_line3_color', '#475569') }}; text-transform: {{ branding('report_header_line3_transform', 'none') }}; margin-bottom: 3px;">
                    {{ $hLine3 }}
                </div>
            @endif

            @if($ruleStyle !== 'none')
                <div class="rule-{{ $ruleStyle }}" style="border-color: {{ $ruleColor }} !important; border-top-color: {{ $ruleColor }} !important; border-bottom-color: {{ $ruleColor }} !important; background-color: {{ $ruleColor }} !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;"></div>
            @endif

            @if($hLine4)
                <div class="doc-address-line" style="font-size: {{ branding('report_header_line4_font_size', '8.5px') }}; font-weight: {{ branding('report_header_line4_font_weight', '400') }}; color: {{ branding('report_header_line4_color', '#475569') }};">
                    {{ $hLine4 }}
                </div>
            @endif

            @if($hLine5)
                <div class="doc-address-line" style="font-size: {{ branding('report_header_line5_font_size', '8.5px') }}; font-weight: {{ branding('report_header_line5_font_weight', '400') }}; color: {{ branding('report_header_line5_color', '#475569') }};">
                    {{ $hLine5 }}
                </div>
            @endif
        </div>

        {{-- Right Seal / Auxiliary Seal --}}
        @if($showRightLogo)
            <div class="seal-wrap" style="width: {{ max($rightLogoSize, 64) }}px;">
                @if($rightLogoShape === 'none')
                    <img src="{{ $rightLogoPath ? asset($rightLogoPath) : asset('assets/logo-dark.png') }}" class="seal-img-raw" style="max-width: {{ $rightLogoSize }}px; max-height: {{ $rightLogoSize }}px;">
                @else
                    <div class="seal-circle has-ring" style="width: {{ $rightLogoSize }}px; height: {{ $rightLogoSize }}px;">
                        @if($rightLogoPath)
                            <img src="{{ asset($rightLogoPath) }}" class="seal-img" alt="Right Seal">
                        @else
                            <div class="seal-inner-circle">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="seal-label">SEAL</div>
            </div>
        @endif
    </div>
@endif
