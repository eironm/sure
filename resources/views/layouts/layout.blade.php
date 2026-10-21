<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ branding('system_name', 'Platform') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ brand_favicon() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            {!! brand_css_vars() !!}
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--brand-dark, #0f172a) 0%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Focus Ring (WCAG) ─────────────────────────────── */
        *:focus-visible {
            outline: 3px solid var(--brand-primary);
            outline-offset: 2px;
        }

        .error-container {
            width: 100%;
            max-width: 600px;
            padding: 2rem;
            text-align: center;
        }

        .error-header {
            margin-bottom: 3rem;
        }

        .sure-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .sure-logo img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.25rem;
        }

        .sure-logo-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 2px;
            color: #fff;
        }

        .error-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--brand-primary, #2563eb) 0%, var(--brand-secondary, #7c3aed) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 1rem 0;
            letter-spacing: -2px;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .error-description {
            font-size: 1rem;
            color: #cbd5e1;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .error-icon.error-404 {
            color: var(--brand-primary, #2563eb);
        }

        .error-icon.error-500 {
            color: #ef4444;
        }

        .error-icon.error-403 {
            color: #f97316;
        }

        .error-icon.error-419 {
            color: #eab308;
        }

        .error-icon.error-503 {
            color: var(--brand-secondary, #7c3aed);
        }

        .error-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            color: #fff;
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(var(--brand-primary-rgb), 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(var(--brand-primary-rgb), 0.4);
            color: #fff;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: #fff;
        }

        .error-details {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .error-detail-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .error-detail-item i {
            color: var(--brand-primary, #2563eb);
        }

        .error-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #64748b;
            font-size: 0.85rem;
        }

        .error-footer a {
            color: var(--brand-primary, #2563eb);
            text-decoration: none;
            transition: color 0.2s;
        }

        .error-footer a:hover {
            color: var(--brand-secondary, #7c3aed);
        }

        /* Animated background elements */
        .error-bg-decoration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .error-bg-decoration::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(var(--brand-primary-rgb), 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .error-bg-decoration::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(var(--brand-secondary-rgb), 0.08) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(20px);
            }
        }

        .error-content {
            position: relative;
            z-index: 1;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .error-container {
                padding: 1.5rem;
            }

            .error-code {
                font-size: 3.5rem;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-description {
                font-size: 0.95rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }
    </style>
    @include('layouts.partials.clarity')
</head>
<body>
    <div class="error-bg-decoration" aria-hidden="true"></div>
    <div class="error-container">
        <div class="error-content">
            @yield('content')
        </div>
    </div>
</body>
</html>
