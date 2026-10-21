<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle }} — {{ $siteName }}</title>
    
    {{-- Google Fonts: Inter & JetBrains Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style id="pageStyle">
        @page {
            size: portrait;
            margin: 0;
        }
    </style>

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #e2e8f0;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            margin: 0;
            padding: 0;
        }

        /* ── Screen Toolbar (Hidden when printing) ── */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 9999;
            background: #0f172a;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .pt-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .pt-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }
        .pt-btn-back {
            background: #1e293b;
            color: #f1f5f9;
            border-color: #334155;
        }
        .pt-btn-back:hover {
            background: #334155;
            color: #ffffff;
        }
        .pt-btn-print {
            background: #059669;
            color: #ffffff;
        }
        .pt-btn-print:hover {
            background: #047857;
        }
        .pt-mode-btn {
            background: transparent;
            color: #94a3b8;
            border: 1px solid #334155;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-size: 0.725rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .pt-mode-btn:hover {
            color: #ffffff;
            border-color: #64748b;
        }
        .pt-mode-btn.active {
            background: #10b981;
            color: #0f172a;
            border-color: #10b981;
        }

        /* Screen Tool Drawer */
        .pt-drawer {
            background: #1e293b;
            border-top: 1px solid #334155;
            padding: 0.65rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            color: #cbd5e1;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .pt-drawer label {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
            font-weight: 600;
        }
        .pt-drawer input[type="checkbox"] {
            cursor: pointer;
        }
        .pt-drawer input[type="text"] {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 4px;
            color: #f1f5f9;
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
        }

        /* ── Printable Paper Container ── */
        .pdf-page-wrap {
            padding: 24px 12px 60px;
            display: flex;
            justify-content: center;
        }

        .pdf-sheet {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            padding: 14mm 16mm 16mm;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.15);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: width 0.2s ease, min-height 0.2s ease;
        }

        .pdf-sheet.landscape {
            width: 297mm;
            min-height: 210mm;
            padding: 12mm 16mm 14mm;
        }

        .pdf-sheet:not(.landscape) .doc-footer-banner-wrap.is-flush {
            width: calc(100% + 32mm) !important;
            margin-left: -16mm !important;
            margin-right: -16mm !important;
            margin-bottom: calc(-16mm + var(--foot-offset-y, 0px)) !important;
            margin-top: auto !important;
        }

        .pdf-sheet.landscape .doc-footer-banner-wrap.is-flush {
            width: calc(100% + 32mm) !important;
            margin-left: -16mm !important;
            margin-right: -16mm !important;
            margin-bottom: calc(-14mm + var(--foot-offset-y, 0px)) !important;
            margin-top: auto !important;
        }

        .pdf-sheet.landscape .doc-header-banner-wrap.is-flush {
            width: calc(100% + 32mm) !important;
            margin-left: -16mm !important;
            margin-right: -16mm !important;
            margin-top: calc(-12mm + var(--hdr-offset-y, 0px)) !important;
            margin-bottom: 0.65rem !important;
        }

        .pdf-sheet.letter-size:not(.landscape) {
            width: 215.9mm;
            min-height: 279.4mm;
        }

        .pdf-sheet.letter-size.landscape {
            width: 279.4mm;
            min-height: 215.9mm;
        }

        #docHeader {
            width: 100%;
        }

        .doc-body-wrap {
            width: 100%;
        }

        /* ── Official Letterhead Rules & Seals ── */
        .doc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.65rem;
        }

        .seal-wrap {
            width: 68px;
            text-align: center;
            flex-shrink: 0;
        }
        .seal-circle {
            position: relative;
            border-radius: 50% !important;
            overflow: hidden !important;
            clip-path: circle(50% at 50% 50%) !important;
            -webkit-clip-path: circle(50% at 50% 50%) !important;
            -webkit-mask-image: -webkit-radial-gradient(white, black);
            mask-image: radial-gradient(white, black);
            isolation: isolate;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            box-sizing: border-box;
            border: none !important;
        }
        .seal-circle.has-ring::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid #0f2b48;
            box-shadow: inset 0 0 0 1.5px #ffffff, inset 0 0 0 2.5px #0f2b48;
            pointer-events: none;
            z-index: 10;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .seal-img {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            border-radius: 50% !important;
            clip-path: circle(50% at 50% 50%) !important;
            -webkit-clip-path: circle(50% at 50% 50%) !important;
            position: relative;
            z-index: 1;
            display: block;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .seal-img-raw {
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }
        .seal-inner-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 1px dashed #0f2b48;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #0f2b48;
            font-size: 1.5rem;
            position: relative;
            z-index: 1;
        }
        .seal-label {
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-top: 3px;
        }

        .doc-header-center {
            text-align: center;
            flex-grow: 1;
        }
        .doc-rep-ph {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #334155;
            margin-bottom: 2px;
        }
        .doc-inst-name {
            font-size: 16.5px;
            font-weight: 900;
            color: #0f2b48;
            letter-spacing: -0.01em;
            line-height: 1.2;
            margin-bottom: 2px;
        }
        .doc-dept-name {
            font-size: 10.5px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 4px;
        }
        
        .doc-header-banner-wrap {
            text-align: center;
            margin-bottom: 0.65rem;
        }
        .doc-header-banner-img {
            max-width: 100%;
            object-fit: contain;
        }
        .doc-rule {
            height: 4px;
            margin: 4px 0 6px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .rule-double {
            border-top: 2px solid #0f2b48;
            border-bottom: 1px solid #0f2b48;
            height: 4px;
            margin: 4px 0 6px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }
        .rule-single {
            border-top: 1.5px solid #0f2b48;
            height: 0;
            margin: 4px 0 6px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }
        .rule-thick {
            border-top: 3px solid #0f2b48;
            height: 0;
            margin: 4px 0 6px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }
        .rule-none {
            display: none;
        }

        .doc-address-line {
            font-size: 8.5px;
            color: #475569;
            line-height: 1.4;
        }

        /* ── Meta Block (Top-right below letterhead) ── */
        .doc-meta-block {
            text-align: right;
            font-size: 8.5px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 0.65rem;
            margin-top: 0.25rem;
        }
        .doc-meta-block strong {
            color: #0f172a;
        }

        /* ── Report Title ── */
        .doc-title {
            text-align: center;
            font-size: 13.5px;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #0f172a;
            margin: 0.2rem 0 0.4rem;
        }
        .doc-subtitle {
            font-size: 9px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.85rem;
            text-align: center;
        }

        /* ── KPI Summary Strip ── */
        .doc-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 0.85rem;
        }
        .doc-kpi-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 10px;
            background: #f8fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .doc-kpi-label {
            font-size: 7.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 2px;
        }
        .doc-kpi-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
        }
        .doc-kpi-sub {
            font-size: 7px;
            color: #64748b;
            margin-top: 1px;
        }

        /* Filter Scope Strip */
        .doc-scope-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 0.75rem;
            font-size: 8px;
            color: #334155;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .doc-scope-bar span strong {
            color: #0f172a;
        }

        /* ── Table Styling ── */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            table-layout: fixed;
            margin-bottom: 0.75rem;
        }
        .doc-table thead th {
            background-color: #0f2b48 !important;
            color: #ffffff !important;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 7.5px;
            letter-spacing: 0.04em;
            padding: 5px 5px;
            border: 1px solid #0f2b48;
            text-align: left;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .doc-table thead th.text-end {
            text-align: right;
        }
        .doc-table tbody td {
            padding: 4px 5px;
            border: 1px solid #cbd5e1;
            color: #1e293b;
            vertical-align: middle;
            font-size: 7.8px;
            line-height: 1.3;
        }
        .doc-table tbody td.text-end {
            text-align: right;
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        .doc-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .doc-table tfoot td {
            padding: 5px;
            border-top: 2px solid #0f2b48;
            border-bottom: 2px solid #0f2b48;
            font-weight: 800;
            background-color: #f8fafc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Column Widths & Wrap Rules (Proportional System) ── */
        .col-idx {
            width: 3.5%;
            text-align: center;
            color: #64748b;
        }
        .col-ref {
            width: 15%;
            white-space: nowrap !important;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            letter-spacing: -0.015em;
        }
        .col-guest {
            width: 17%;
            overflow-wrap: break-word;
        }
        .col-fac {
            width: 16%;
            overflow-wrap: break-word;
        }
        .col-event {
            width: 9.5%;
            white-space: nowrap !important;
        }
        .col-settled {
            width: 10%;
            white-space: nowrap !important;
        }
        .col-method {
            width: 8%;
            text-align: center;
        }
        .col-or {
            width: 11%;
            white-space: nowrap !important;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: -0.015em;
        }
        .col-amt {
            width: 10%;
            text-align: right;
            white-space: nowrap !important;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 800;
        }

        /* ── Portrait Specific Fine-Tuning ── */
        .pdf-sheet:not(.landscape) .doc-table {
            font-size: 7.2px;
        }
        .pdf-sheet:not(.landscape) .doc-table thead th {
            padding: 3.5px 3.5px;
            font-size: 6.8px;
            letter-spacing: 0.03em;
        }
        .pdf-sheet:not(.landscape) .doc-table tbody td {
            padding: 3px 3.5px;
            font-size: 7.2px;
            line-height: 1.25;
        }
        .pdf-sheet:not(.landscape) .col-ref {
            font-size: 7px !important;
        }
        .pdf-sheet:not(.landscape) .col-or {
            font-size: 6.8px !important;
        }
        .pdf-sheet:not(.landscape) .col-event {
            font-size: 7px !important;
        }
        .pdf-sheet:not(.landscape) .col-settled {
            font-size: 7px !important;
        }
        .pdf-sheet:not(.landscape) .col-amt {
            font-size: 7.5px !important;
        }
        .pdf-sheet:not(.landscape) .badge-method {
            padding: 1px 3px;
            font-size: 6.2px;
            letter-spacing: 0.02em;
        }
        .pdf-sheet:not(.landscape) .doc-kpi-grid {
            gap: 6px;
            margin-bottom: 0.65rem;
        }
        .pdf-sheet:not(.landscape) .doc-kpi-card {
            padding: 6px 8px;
        }
        .pdf-sheet:not(.landscape) .doc-kpi-label {
            font-size: 6.8px;
            letter-spacing: 0.03em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pdf-sheet:not(.landscape) .doc-kpi-val {
            font-size: 11.5px;
        }
        .pdf-sheet:not(.landscape) .doc-kpi-sub {
            font-size: 6.2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pdf-sheet:not(.landscape) .doc-scope-bar {
            padding: 3px 6px;
            font-size: 7.2px;
            margin-bottom: 0.6rem;
        }
        .pdf-sheet:not(.landscape) .doc-meta-block {
            font-size: 7.5px;
            line-height: 1.4;
            margin-bottom: 0.45rem;
        }
        .pdf-sheet:not(.landscape) .doc-title {
            font-size: 12.5px;
            margin: 0.15rem 0 0.25rem;
        }
        .pdf-sheet:not(.landscape) .doc-subtitle {
            font-size: 8px;
            margin-bottom: 0.65rem;
        }
        .pdf-sheet:not(.landscape) .signature-section {
            margin-top: 1rem;
            margin-bottom: 0.8rem;
            gap: 24px;
        }
        .pdf-sheet:not(.landscape) .sig-label {
            margin-bottom: 22px;
            font-size: 7.5px;
        }
        .pdf-sheet:not(.landscape) .sig-name {
            font-size: 8.5px;
        }
        .pdf-sheet:not(.landscape) .sig-title {
            font-size: 7.5px;
        }

        /* ── Landscape Fine-Tuning ── */
        .pdf-sheet.landscape .doc-table {
            font-size: 8px;
        }
        .pdf-sheet.landscape .doc-table thead th {
            padding: 5px 6px;
            font-size: 7.8px;
        }
        .pdf-sheet.landscape .doc-table tbody td {
            padding: 4.5px 6px;
            font-size: 7.8px;
            line-height: 1.35;
        }
        .pdf-sheet.landscape .col-ref {
            font-size: 7.6px !important;
        }
        .pdf-sheet.landscape .col-or {
            font-size: 7.5px !important;
        }
        .pdf-sheet.landscape .col-amt {
            font-size: 8.2px !important;
        }

        .badge-method {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }
        .badge-method-cash {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-method-digital {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Dual Signatory Section (Philippine Standard) ── */
        .signature-section {
            margin-top: 1.4rem;
            margin-bottom: 1.2rem;
            font-size: 8.5px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            page-break-inside: avoid;
        }
        .sig-block {
            display: flex;
            flex-direction: column;
        }
        .sig-label {
            color: #334155;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.06em;
        }
        .sig-line {
            width: 100%;
            max-width: 250px;
            border-bottom: 1px solid #0f172a;
            margin-bottom: 3px;
        }
        .sig-name {
            font-weight: 800;
            font-size: 9.5px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .sig-title {
            font-size: 8px;
            color: #475569;
        }
        .sig-subtext {
            font-size: 7.5px;
            color: #94a3b8;
            font-style: italic;
        }

        /* ── Official Footer ── */
        .doc-footer {
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: flex-start;
            justify-content: space-between;
            font-size: 7.5px;
            color: #64748b;
            margin-top: auto;
            width: 100%;
            box-sizing: border-box;
        }
        .footer-left {
            flex: 1 1 0%;
            text-align: left;
            line-height: 1.4;
        }
        .footer-left strong {
            color: #1e293b;
            font-size: 8px;
        }
        .footer-center {
            flex: 1 1 0%;
            text-align: center;
            line-height: 1.4;
        }
        .footer-right {
            flex: 1 1 0%;
            text-align: right;
            line-height: 1.4;
        }

        /* ── Print Media Optimization ── */
        @media print {
            .print-toolbar, .pt-drawer {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .pdf-page-wrap {
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                position: static !important;
            }
            .pdf-sheet {
                width: 100% !important;
                min-height: auto !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                position: static !important;
                background: #ffffff !important;
            }
            .pdf-sheet.landscape {
                width: 100% !important;
                padding: 0 !important;
            }
            #docHeader {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                background: #ffffff !important;
                z-index: 9999 !important;
                margin: 0 !important;
                padding: 6mm 14mm 2mm 14mm !important;
                box-sizing: border-box !important;
            }
            #docFooter {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                background: #ffffff !important;
                z-index: 9999 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
            }
            #docFooter.doc-footer-banner-wrap {
                display: block !important;
                background: transparent !important;
            }
            #docFooter.doc-footer-banner-wrap.is-flush {
                position: fixed !important;
                bottom: var(--foot-offset-y, 0px) !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                z-index: 9999 !important;
            }
            #docFooter.doc-footer-banner-wrap.is-flush img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
            }
            #docFooter.doc-footer-banner-wrap:not(.is-flush) {
                position: fixed !important;
                bottom: var(--foot-offset-y, 0px) !important;
                left: 14mm !important;
                right: 14mm !important;
                width: calc(100% - 28mm) !important;
                margin: 0 !important;
                z-index: 9999 !important;
            }
            .print-foot-spacer,
            .report-canvas-footer-cell.print-foot-spacer {
                display: table-cell !important;
                border: none !important;
                padding: 0 !important;
                background: transparent !important;
            }

            .report-canvas-table {
                width: 100% !important;
                height: 100% !important;
                border-collapse: collapse !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                table-layout: fixed !important;
            }
            .report-canvas-table > thead {
                display: table-header-group !important;
            }
            .report-canvas-table > tfoot {
                display: table-footer-group !important;
            }
            .report-canvas-table > tbody {
                display: table-row-group !important;
            }
            .report-canvas-header-cell {
                padding: 6mm 14mm 3mm 14mm !important;
                border: none !important;
                vertical-align: top !important;
                background: #ffffff !important;
            }
            .report-canvas-body-cell {
                padding: 0 14mm !important;
                border: none !important;
                vertical-align: top !important;
                height: 100% !important;
            }
            .report-canvas-footer-cell {
                padding: 3mm 0 0 0 !important;
                border: none !important;
                vertical-align: bottom !important;
                background: #ffffff !important;
            }
            .report-canvas-table #docHeader {
                position: static !important;
                top: auto !important;
                left: auto !important;
                right: auto !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
                z-index: auto !important;
            }
            .report-canvas-table .doc-header-banner-wrap {
                position: static !important;
                top: auto !important;
                width: 100% !important;
                margin: 0 !important;
            }
            .doc-footer,
            #docFooter.doc-footer {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                justify-content: space-between !important;
                align-items: flex-start !important;
                padding: 4px 14mm 4mm 14mm !important;
                border-top: 1px solid #cbd5e1 !important;
                background: #ffffff !important;
                width: 100% !important;
                margin: 0 !important;
                box-sizing: border-box !important;
            }
            .doc-footer .footer-left,
            #docFooter.doc-footer .footer-left {
                flex: 1 1 0% !important;
                text-align: left !important;
                line-height: 1.4 !important;
            }
            .doc-footer .footer-center,
            #docFooter.doc-footer .footer-center {
                flex: 1 1 0% !important;
                text-align: center !important;
                line-height: 1.4 !important;
            }
            .doc-footer .footer-right,
            #docFooter.doc-footer .footer-right {
                flex: 1 1 0% !important;
                text-align: right !important;
                line-height: 1.4 !important;
            }

            .rule-double, .rule-single, .rule-thick, .doc-rule {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .rule-double {
                border-top-style: solid !important;
                border-bottom-style: solid !important;
            }
            .rule-single {
                border-top-style: solid !important;
                height: 0 !important;
            }
            .rule-thick {
                border-top-width: 3px !important;
                border-top-style: solid !important;
                height: 0 !important;
            }
            .doc-table {
                page-break-inside: auto;
                width: 100% !important;
            }
            .doc-table thead {
                display: table-header-group;
            }
            .doc-table tbody tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .doc-table tfoot {
                display: table-footer-group;
            }
            .signature-section {
                page-break-inside: avoid !important;
                margin-top: 12mm !important;
                margin-bottom: 8mm !important;
            }
            .seal-label {
                display: none !important;
            }
        }
    </style>
</head>
<body>

{{-- ── Screen Controls Bar ── --}}
<div class="print-toolbar">
    <div class="pt-title">
        <i class="bi bi-file-earmark-check-fill text-success fs-5"></i>
        <span>Official Printable Statement Preview &bull; Cashier Collection Ledger</span>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap" style="display: flex; align-items: center; gap: 0.5rem;">
        {{-- Paper Size Toggle --}}
        <button type="button" class="pt-mode-btn active" id="btnPaperA4" onclick="togglePaperSize('a4')">
            A4
        </button>
        <button type="button" class="pt-mode-btn" id="btnPaperLetter" onclick="togglePaperSize('letter')">
            Letter
        </button>

        {{-- Orientation Toggle --}}
        <div style="border-left: 1px solid #334155; height: 20px; margin: 0 4px;"></div>
        <button type="button" class="pt-mode-btn" id="orientBtn" onclick="toggleOrientation()">
            <i class="bi bi-file-earmark"></i> <span id="orientLabel">Switch to Landscape</span>
        </button>

        {{-- Return, CMS, CSV & Print --}}
        <a href="{{ route('cashier.report', request()->query()) }}" class="pt-btn pt-btn-back">
            <i class="bi bi-arrow-left"></i> Back to Statement
        </a>
        @if(auth()->user()?->isAdmin() || auth()->user()?->isSuperAdmin())
        <a href="{{ route('admin.reports.letterhead-cms') }}" class="pt-btn pt-btn-back" title="Edit Letterhead & Footer CMS">
            <i class="bi bi-sliders"></i> Letterhead CMS
        </a>
        @endif
        <a href="{{ route('cashier.report.export-csv', request()->query()) }}" class="pt-btn pt-btn-back" title="Export this statement to CSV spreadsheet" download>
            <i class="bi bi-file-earmark-spreadsheet text-success"></i> Export CSV
        </a>
        <button type="button" class="pt-btn pt-btn-print" onclick="window.print()">
            <i class="bi bi-printer-fill"></i> Print / Save as PDF
        </button>
    </div>
</div>

{{-- Screen Secondary Controls (Toggles & Live Signatory Customization) --}}
<div class="pt-drawer">
    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <span style="font-weight: 700; color: #94a3b8;"><i class="bi bi-sliders me-1"></i> Document Options:</span>
        <label>
            <input type="checkbox" id="chkSummary" checked onchange="toggleSection('secSummary', this.checked)">
            Show KPI Cards
        </label>
        <label>
            <input type="checkbox" id="chkMethod" checked onchange="toggleColumns('col-method', this.checked)">
            Show Payment Method
        </label>
        <label>
            <input type="checkbox" id="chkSig" checked onchange="toggleSection('secSig', this.checked)">
            Show Dual Signatories
        </label>
    </div>

    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
        <span style="font-weight: 700; color: #94a3b8;"><i class="bi bi-pen me-1"></i> Signatories:</span>
        <input type="text" id="inPrepName" value="{{ $preparedByName }}" placeholder="Prepared By" title="Prepared By Name" oninput="updateSignatories()">
        <input type="text" id="inPrepTitle" value="{{ $preparedByTitle }}" placeholder="Title" title="Prepared By Title" oninput="updateSignatories()">
        <input type="text" id="inApprName" value="{{ $verifiedByName }}" placeholder="Verified By" title="Verified By Name" oninput="updateSignatories()">
        <input type="text" id="inApprTitle" value="{{ $verifiedByTitle }}" placeholder="Title" title="Verified By Title" oninput="updateSignatories()">
    </div>
</div>

{{-- ── Printable Document Sheet ── --}}
<div class="pdf-page-wrap">
    <div class="pdf-sheet" id="printSheet">

        {{-- ── Report Canvas Table ── --}}
        <table class="report-canvas-table" role="presentation">

        {{-- ── THEAD: Repeating Official Letterhead Header ── --}}
        <thead class="report-canvas-header">
            <tr>
                <td class="report-canvas-header-cell">
                    <div id="docHeader" class="doc-header-fixed-wrap">
                        <x-report-letterhead />
                    </div>
                </td>
            </tr>
        </thead>

        {{-- ── TBODY: Statement Ledger Body ── --}}
        <tbody class="report-canvas-body">
            <tr>
                <td class="report-canvas-body-cell">
                    <div class="doc-body-wrap">

                        {{-- Top-Right Meta Block --}}
                        <div class="doc-meta-block">
                            <div>Document Ref: <strong>{{ $docReference }}</strong></div>
                            <div>Date Generated: <strong>{{ now()->format('F d, Y h:i A') }}</strong></div>
                            <div>Classification: <strong>OFFICIAL FINANCIAL RECORD / AUDIT TRAIL</strong></div>
                        </div>

                        {{-- Report Title & Scope Subtitle --}}
                        <div class="doc-title">{{ $reportTitle }}</div>
                        <div class="doc-subtitle">
                            Reporting Period: {{ strtoupper($dateRangeLabel) }} &bull; Scope: {{ $selectedCashier ? strtoupper($selectedCashier->name) : 'ALL CASHIERS' }}
                        </div>

                        {{-- Summary KPI Cards --}}
                        <div class="doc-kpi-grid" id="secSummary">
                            <div class="doc-kpi-card">
                                <div class="doc-kpi-label">Settled Transactions</div>
                                <div class="doc-kpi-val">{{ number_format($totalCount) }}</div>
                                <div class="doc-kpi-sub">Total verified bookings</div>
                            </div>
                            <div class="doc-kpi-card">
                                <div class="doc-kpi-label">Total Revenue Collected</div>
                                <div class="doc-kpi-val" style="color: #059669;">&#8369;{{ number_format($totalAmount, 2) }}</div>
                                <div class="doc-kpi-sub">Gross settled sum</div>
                            </div>
                            <div class="doc-kpi-card">
                                <div class="doc-kpi-label">Cash (Over-The-Counter)</div>
                                <div class="doc-kpi-val">&#8369;{{ number_format($cashCollected, 2) }}</div>
                                <div class="doc-kpi-sub">{{ number_format($cashCount) }} OTC settlements</div>
                            </div>
                            <div class="doc-kpi-card">
                                <div class="doc-kpi-label">Digital (PayMongo Gateway)</div>
                                <div class="doc-kpi-val">&#8369;{{ number_format($digitalCollected, 2) }}</div>
                                <div class="doc-kpi-sub">{{ number_format($digitalCount) }} online transactions</div>
                            </div>
                        </div>

                        {{-- Scope Filter Bar --}}
                        <div class="doc-scope-bar">
                            <span><strong>Period:</strong> {{ $dateRangeLabel }}</span>
                            <span><strong>Facility:</strong> {{ $selectedFacility ? $selectedFacility->name : 'All Facilities' }}</span>
                            <span><strong>Method:</strong> {{ ucfirst($paymentMethod) }}</span>
                            <span><strong>Cashier Scope:</strong> {{ $selectedCashier ? $selectedCashier->name : 'All Cashiers' }}</span>
                        </div>

                        {{-- Detailed Statement Ledger Table --}}
                        <table class="doc-table">
                            <thead>
                                <tr>
                                    <th class="col-idx">#</th>
                                    <th class="col-ref">Ref #</th>
                                    <th class="col-guest">Client / Guest</th>
                                    <th class="col-fac">Facility &amp; Category</th>
                                    <th class="col-event">Event Date</th>
                                    <th class="col-settled">Settled Date</th>
                                    <th class="col-method">Method</th>
                                    <th class="col-or">OR / Gateway Ref</th>
                                    <th class="col-amt">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $i => $r)
                                    <tr>
                                        <td class="col-idx">{{ $i + 1 }}</td>
                                        <td class="col-ref">#{{ $r->booking_reference ?? $r->id }}</td>
                                        <td class="col-guest">
                                            <div style="font-weight: 700; color: #0f172a;">{{ $r->user->name ?? 'Guest User' }}</div>
                                            <div style="font-size: 6.8px; color: #64748b;">{{ $r->user->email ?? 'N/A' }}</div>
                                        </td>
                                        <td class="col-fac">
                                            <div style="font-weight: 600; color: #0f172a;">{{ $r->facility->name ?? 'Facility Removed' }}</div>
                                            <div style="font-size: 6.8px; color: #64748b;">{{ $r->facility->category->name ?? 'Campus System' }}</div>
                                        </td>
                                        <td class="col-event">
                                            {{ $r->start_date ? \Carbon\Carbon::parse($r->start_date)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="col-settled">
                                            @if($r->cashier_signed_at)
                                                <div>{{ \Carbon\Carbon::parse($r->cashier_signed_at)->format('M d, Y') }}</div>
                                                <div style="font-size: 6.5px; color: #64748b;">{{ \Carbon\Carbon::parse($r->cashier_signed_at)->format('h:i A') }}</div>
                                            @elseif($r->updated_at)
                                                <div>{{ $r->updated_at->format('M d, Y') }}</div>
                                                <div style="font-size: 6.5px; color: #64748b;">{{ $r->updated_at->format('h:i A') }}</div>
                                            @else
                                                <span style="color: #94a3b8;">N/A</span>
                                            @endif
                                        </td>
                                        <td class="col-method">
                                            @if($r->payment_method === 'cash')
                                                <span class="badge-method badge-method-cash"><i class="bi bi-cash me-1"></i>Cash OTC</span>
                                            @elseif($r->payment_method === 'paymongo')
                                                <span class="badge-method badge-method-digital"><i class="bi bi-wallet2 me-1"></i>PayMongo</span>
                                            @else
                                                <span class="badge-method">{{ ucfirst($r->payment_method ?? 'Unknown') }}</span>
                                            @endif
                                        </td>
                                        <td class="col-or">
                                            @if($r->cash_receipt_number)
                                                <span style="font-weight: 700; color: #0f172a;">{{ $r->cash_receipt_number }}</span>
                                            @elseif($r->payment_method === 'paymongo' && $r->payment_id)
                                                <span style="font-size: 6.8px; color: #0284c7;">{{ \Illuminate\Support\Str::limit(str_replace('cs_test_', '', $r->payment_id), 12, '') }}</span>
                                            @else
                                                <span style="color: #94a3b8; font-style: italic;">Pending</span>
                                            @endif
                                        </td>
                                        <td class="col-amt">
                                            &#8369;{{ number_format($r->total_price ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 24px; color: #64748b; font-style: italic;">
                                            No payment records found matching the specified parameters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" style="text-transform: uppercase; font-size: 7.5px; letter-spacing: 0.05em;">
                                        Total Cashier Collections ({{ number_format($totalCount) }} Transactions)
                                    </td>
                                    <td class="col-method"></td>
                                    <td style="text-align: right; font-size: 7.5px; text-transform: uppercase;">Sum:</td>
                                    <td class="col-amt" style="font-size: 9.2px; color: #0f2b48;">
                                        &#8369;{{ number_format($totalAmount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- Dual Signatory Section --}}
                        <div class="signature-section" id="secSig">
                            <div class="sig-block">
                                <div class="sig-label">Prepared &amp; Processed By:</div>
                                <div class="sig-line"></div>
                                <div class="sig-name" id="sigPrepName">{{ $preparedByName }}</div>
                                <div class="sig-title" id="sigPrepTitle">{{ $preparedByTitle }}</div>
                                <div class="sig-subtext">Cashiering &amp; Disbursements Unit</div>
                            </div>
                            <div class="sig-block">
                                <div class="sig-label">Certified &amp; Verified By:</div>
                                <div class="sig-line"></div>
                                <div class="sig-name" id="sigApprName">{{ $verifiedByName }}</div>
                                <div class="sig-title" id="sigApprTitle">{{ $verifiedByTitle }}</div>
                                <div class="sig-subtext">Office of Financial Management &amp; Accounting</div>
                            </div>
                        </div>

                    </div>
                </td>
            </tr>
        </tbody>

        @php
            $footMode = branding('report_footer_mode', 'builder');
            $footBanner = branding('report_footer_image');
            $footBannerH = branding('report_footer_image_height', '50');
            $footSpacerHeight = ($footMode === 'image' && $footBanner) ? max((int)$footBannerH + 10, 50) . 'px' : '22mm';
        @endphp

        {{-- ── TFOOT: Repeating spacer on every page so table rows never overlap the fixed footer ── --}}
        <tfoot class="report-canvas-footer">
            <tr class="print-foot-spacer">
                <td class="report-canvas-footer-cell print-foot-spacer" style="display: table-cell !important; height: {{ $footSpacerHeight }} !important; border: none !important; padding: 0 !important; background: transparent !important;"></td>
            </tr>
        </tfoot>

        </table>

        {{-- ── Official Repeating Footer: Outside table so position: fixed repeats reliably across all printed pages ── --}}
        <x-report-footer />

    </div>
</div>

<script>
    let isLandscape = false;
    let paperSize = 'a4';

    function toggleOrientation() {
        const sheet = document.getElementById('printSheet');
        const pageStyle = document.getElementById('pageStyle');
        const orientLabel = document.getElementById('orientLabel');
        isLandscape = !isLandscape;

        if (isLandscape) {
            sheet.classList.add('landscape');
            pageStyle.innerHTML = `@page { size: landscape; margin: 0; }`;
            orientLabel.textContent = 'Switch to Portrait';
        } else {
            sheet.classList.remove('landscape');
            pageStyle.innerHTML = `@page { size: portrait; margin: 0; }`;
            orientLabel.textContent = 'Switch to Landscape';
        }
    }

    function togglePaperSize(size) {
        const sheet = document.getElementById('printSheet');
        const btnA4 = document.getElementById('btnPaperA4');
        const btnLetter = document.getElementById('btnPaperLetter');
        paperSize = size;

        if (size === 'letter') {
            sheet.classList.add('letter-size');
            btnLetter.classList.add('active');
            btnA4.classList.remove('active');
        } else {
            sheet.classList.remove('letter-size');
            btnA4.classList.add('active');
            btnLetter.classList.remove('active');
        }
    }

    function toggleSection(sectionId, isVisible) {
        const sec = document.getElementById(sectionId);
        if (sec) {
            sec.style.display = isVisible ? '' : 'none';
        }
    }

    function toggleColumns(colClass, isVisible) {
        const cells = document.querySelectorAll('.' + colClass);
        cells.forEach(el => {
            el.style.display = isVisible ? '' : 'none';
        });
    }

    function updateSignatories() {
        const inPrepName  = document.getElementById('inPrepName').value;
        const inPrepTitle = document.getElementById('inPrepTitle').value;
        const inApprName  = document.getElementById('inApprName').value;
        const inApprTitle = document.getElementById('inApprTitle').value;

        const sigPrepName  = document.getElementById('sigPrepName');
        const sigPrepTitle = document.getElementById('sigPrepTitle');
        const sigApprName  = document.getElementById('sigApprName');
        const sigApprTitle = document.getElementById('sigApprTitle');

        if (sigPrepName)  sigPrepName.textContent  = inPrepName || '____________________';
        if (sigPrepTitle) sigPrepTitle.textContent = inPrepTitle || 'Processing Cashier';
        if (sigApprName)  sigApprName.textContent  = inApprName || '____________________';
        if (sigApprTitle) sigApprTitle.textContent = inApprTitle || 'Finance Officer';
    }
</script>

</body>
</html>
