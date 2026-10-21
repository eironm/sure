<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ branding('system_name', 'Platform') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ brand_favicon() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            {!! brand_css_vars() !!}
        }
        body {
            background: linear-gradient(135deg, var(--brand-dark, #0f172a) 0%, #1e293b 100%);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dashboard-card {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .welcome-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
            color: var(--brand-dark, #0f172a);
        }
        .btn-custom {
            background: linear-gradient(135deg, var(--brand-primary, #2563eb), var(--brand-secondary, #7c3aed));
            color: #fff;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-block;
            margin: 0.625rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(var(--brand-primary-rgb),0.3);
            color: #fff;
        }
        *:focus-visible {
            outline: 3px solid var(--brand-primary);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <img src="{{ brand_logo() }}" width="60" height="60" alt="{{ branding('system_name', 'Platform') }} Logo" class="mb-3" style="border-radius: 0.375rem;">
        <div class="welcome-text">Welcome, {{ Auth::user()->name }}!</div>
        <p class="text-muted">You are logged in as a <strong>{{ Auth::user()->role }}</strong>.</p>
        
        <div class="mt-4">
            <a href="{{ route('profile.edit') }}" class="btn-custom">Manage Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-custom">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
