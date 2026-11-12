<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Settings - STI Valencia Clearance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-page: #F1F3F7;
            --bg-card: #FFFFFF;
            --bg-navy: #0F1B2D;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border-color: #E5E7EB;
        }

        [data-theme="dark"] {
            --bg-page: #090F1A;
            --bg-card: #131E30;
            --bg-navy: #070C15;
            --text-main: #E5E7EB;
            --text-muted: #8B93A3;
            --border-color: #223047;
        }

        html {
            font-size: 15px;
        }

        body {
            font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

         .topbar {
            background-color: var(--bg-navy);
            color: #fff;
            padding: 0 32px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-left img {
            height: 42px;
            width: auto;
            display: block;
            margin-left: 24px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .live-datetime {
            font-size: 0.78rem;
            color: #C4CBD8;
            white-space: nowrap;
        }

        .theme-toggle {
            background: none;
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
            font-size: 0.8rem;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .theme-toggle:hover {
            border-color: #FFC72C;
        }

        .container {
            max-width: 480px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-block;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .back-link:hover {
            color: #FFC72C;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 36px 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .card h1 {
            font-size: 1.3rem;
            margin-bottom: 4px;
        }

        .card p.sub {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 28px;
        }

        .success-banner {
            background-color: rgba(22, 163, 74, 0.08);
            border: 1px solid rgba(22, 163, 74, 0.35);
            color: #15803D;
            padding: 11px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        [data-theme="dark"] .success-banner {
            color: #4ADE80;
        }

        .error-banner {
            background-color: rgba(220, 38, 38, 0.08);
            border: 1px solid rgba(220, 38, 38, 0.35);
            color: #B91C1C;
            padding: 11px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        [data-theme="dark"] .error-banner {
            color: #F87171;
        }

        .error-banner p {
            margin: 2px 0;
        }

        label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
            margin-top: 20px;
        }

        label:first-of-type {
            margin-top: 0;
        }

        input[type="password"],
        input[type="text"].password-field {
            width: 100%;
            padding: 11px 44px 11px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background-color: var(--bg-page);
            color: var(--text-main);
            font-size: 0.9rem;
            font-family: inherit;
        }

        input[type="password"]:focus,
        input[type="text"].password-field:focus {
            outline: none;
            border-color: #FFC72C;
        }

        .password-wrap {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .password-toggle:hover {
            color: var(--text-main);
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        button[type="submit"] {
            width: 100%;
            margin-top: 28px;
            padding: 12px;
            background-color: #FFC72C;
            color: #0F1B2D;
            font-weight: 700;
            font-size: 0.92rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #F0B90B;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/phone-account.css') }}">
</head>
<body>
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ auth('student')->check() ? route('student.dashboard') : route('staff.dashboard') }}" title="Go to dashboard" style="display:block;"><img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo"></a>
        </div>
        <div class="header-right">
            <span class="live-datetime" id="liveDateTime"></span>
            <button type="button" class="theme-toggle" id="themeToggle" title="Toggle theme">🌙</button>
        </div>
    </div>

    <div class="container">
        <a href="{{ $isStudent ? route('student.dashboard') : route('staff.dashboard') }}" class="back-link">&larr; Back to Dashboard</a>

        <div class="card">
            <h1>Account Settings</h1>
            <p class="sub">Update your password below.</p>

            @if (session('success'))
                <div class="success-banner">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error-banner">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('account.update') }}">
                @csrf

                <label for="current_password">Current Password</label>
                <div class="password-wrap">
                    <input type="password" name="current_password" id="current_password" required>
                    <button type="button" class="password-toggle" data-target="current_password" title="Show password" aria-label="Show password">
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <label for="new_password">New Password</label>
                <div class="password-wrap">
                    <input type="password" name="new_password" id="new_password" required>
                    <button type="button" class="password-toggle" data-target="new_password" title="Show password" aria-label="Show password">
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <label for="new_password_confirmation">Retype New Password</label>
                <div class="password-wrap">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
                    <button type="button" class="password-toggle" data-target="new_password_confirmation" title="Show password" aria-label="Show password">
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <button type="submit">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        const eyeOpenPath = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle>';
        const eyeClosedPath = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.3 20.3 0 0 1 5.06-6.06M9.9 4.24A10.4 10.4 0 0 1 12 4c7 0 11 8 11 8a20.3 20.3 0 0 1-2.29 3.36M14.12 14.12a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';

        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const icon = btn.querySelector('.eye-icon');
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.innerHTML = isPassword ? eyeClosedPath : eyeOpenPath;
                btn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
            });
        });

        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' };
            document.getElementById('liveDateTime').textContent = now.toLocaleString('en-US', options);
        }
        updateDateTime();
        setInterval(updateDateTime, 1000 * 30);

        const toggleBtn = document.getElementById('themeToggle');
        const root = document.documentElement;

        const savedTheme = localStorage.getItem('theme') || 'dark';
        root.setAttribute('data-theme', savedTheme);
        toggleBtn.textContent = savedTheme === 'dark' ? '🌙' : '☀️';

        toggleBtn.addEventListener('click', () => {
            const current = root.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            toggleBtn.textContent = next === 'dark' ? '🌙' : '☀️';
        });
    </script>
</body>
</html>