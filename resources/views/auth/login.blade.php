<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - STI Valencia Clearance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold: #FFC72C;
            --gold-hover: #F0B90B;
            --accent-text: #FFC72C;
            --panel: #0A1424;
            --field: #101D32;
            --field-border: #22334D;
            --text: #FFFFFF;
            --text-soft: #C9D2DF;
            --muted: #8E9BB0;
            --error-bg: rgba(220, 38, 38, 0.14);
            --error-border: #DC2626;
            --error-text: #FCA5A5;
        }

        [data-theme="light"] {
            --accent-text: #A16207;
            --panel: #FFFFFF;
            --field: #F4F6FA;
            --field-border: #D3DAE6;
            --text: #0F1B2D;
            --text-soft: #3F4B5F;
            --muted: #5B6679;
            --error-bg: rgba(220, 38, 38, 0.08);
            --error-border: #DC2626;
            --error-text: #B91C1C;
        }

        html {
            font-size: 15px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: var(--panel);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ---------- Page layout ---------- */

        .login-shell {
            display: flex;
            min-height: 100vh;
            min-height: 100dvh;
        }

        /* ---------- Photo side ---------- */

        .login-photo {
            position: relative;
            flex: 1;
            min-width: 0;
            overflow: hidden;
            background-color: #0F1B2D;
        }

        .login-photo::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/sti_valencia.jpg') }}');
            background-size: cover;
            background-position: center;
            filter: brightness(0.86) saturate(1.05);
        }

        .login-photo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(6, 12, 24, 0.9) 0%, rgba(6, 12, 24, 0.35) 38%, rgba(6, 12, 24, 0) 65%);
        }

        .photo-caption {
            position: absolute;
            z-index: 2;
            left: clamp(32px, 6vw, 96px);
            right: 32px;
            bottom: clamp(44px, 10vh, 90px);
            color: #fff;
        }

        .caption-eyebrow {
            color: var(--gold);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .caption-title {
            font-size: clamp(1.9rem, 2.8vw, 2.7rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .caption-title span {
            color: var(--gold);
        }

        .caption-sub {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 14px;
            font-size: 1rem;
            font-weight: 400;
        }

        .caption-sub::before {
            content: '';
            width: 3px;
            height: 20px;
            border-radius: 2px;
            background: var(--gold);
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.25) inset;
            pointer-events: none;
            transform: translate(-50%, -50%);
            animation: rippleExpand 900ms ease-out forwards;
            z-index: 1;
        }

        @keyframes rippleExpand {
            0% { width: 0; height: 0; opacity: 0.6; }
            100% { width: 160px; height: 160px; opacity: 0; }
        }

        /* ---------- Theme toggle ---------- */

        .theme-btn {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 20;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(15, 27, 45, 0.58);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px);
        }

        .theme-btn:hover {
            border-color: var(--gold);
        }

        .theme-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ---------- Form side ---------- */

        .login-panel {
            position: relative;
            flex: 0 0 36%;
            min-width: 440px;
            max-width: 680px;
            display: flex;
            flex-direction: column;
            padding: 40px clamp(28px, 4vw, 64px) 22px;
            background-color: var(--panel);
            overflow: hidden;
        }

        .login-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 85% 12%, rgba(255, 199, 44, 0.07), transparent 42%),
                repeating-linear-gradient(135deg, rgba(255, 255, 255, 0.022) 0, rgba(255, 255, 255, 0.022) 1px, transparent 1px, transparent 46px);
            pointer-events: none;
        }

        [data-theme="light"] .login-panel::before {
            background:
                radial-gradient(circle at 85% 12%, rgba(255, 199, 44, 0.1), transparent 42%),
                repeating-linear-gradient(135deg, rgba(15, 27, 45, 0.02) 0, rgba(15, 27, 45, 0.02) 1px, transparent 1px, transparent 46px);
        }

        .panel-inner {
            position: relative;
            flex: 1;
            width: 100%;
            max-width: 430px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .eyebrow {
            color: var(--accent-text);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .panel-inner h1 {
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.15;
            margin-bottom: 8px;
        }

        .panel-sub {
            color: var(--text-soft);
            font-size: 0.93rem;
            line-height: 1.5;
            margin-bottom: 26px;
        }

        .error-box {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            color: var(--error-text);
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .error-box p {
            margin: 2px 0;
        }

        .field-label,
        .label-row label {
            display: block;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text);
        }

        .field-label {
            margin-bottom: 8px;
        }

        .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 18px 0 8px;
        }

        .link-btn {
            background: none;
            border: 0;
            padding: 0;
            font: inherit;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent-text);
            cursor: pointer;
        }

        .link-btn:hover {
            text-decoration: underline;
        }

        .field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-icon {
            position: absolute;
            left: 16px;
            width: 18px;
            height: 18px;
            stroke: var(--muted);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            pointer-events: none;
        }

        .field input {
            width: 100%;
            height: 52px;
            padding: 0 46px 0 46px;
            border-radius: 12px;
            border: 1px solid var(--field-border);
            background-color: var(--field);
            color: var(--text);
            font: inherit;
            font-size: 1rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field input::placeholder {
            color: var(--muted);
        }

        .field input:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(255, 199, 44, 0.16);
        }

        .eye-btn {
            position: absolute;
            right: 8px;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 8px;
            background: none;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .eye-btn:hover {
            color: var(--text);
        }

        .eye-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .forgot-note {
            margin-top: 10px;
            padding: 10px 13px;
            border-radius: 10px;
            font-size: 0.82rem;
            line-height: 1.45;
            color: var(--text-soft);
            background: rgba(255, 199, 44, 0.1);
            border: 1px solid rgba(255, 199, 44, 0.35);
        }

        .forgot-note[hidden] {
            display: none;
        }

        .check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            font-size: 0.86rem;
            color: var(--text-soft);
            cursor: pointer;
        }

        .check input {
            width: 18px;
            height: 18px;
            accent-color: var(--gold);
            cursor: pointer;
        }

        .signin-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
            height: 52px;
            margin: 20px auto 0;
            border: 0;
            border-radius: 12px;
            background-color: var(--gold);
            color: #0F1B2D;
            font: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 22px rgba(255, 199, 44, 0.2);
            transition: width 0.35s ease, border-radius 0.35s ease, background-color 0.2s;
        }

        .signin-btn:hover {
            background-color: var(--gold-hover);
        }

        .signin-btn .btn-label {
            display: flex;
            align-items: center;
            gap: 10px;
            transition: opacity 0.15s ease;
        }

        .signin-btn .btn-label svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .signin-btn.is-loading {
            width: 52px;
            border-radius: 50%;
            cursor: default;
            pointer-events: none;
        }

        .signin-btn.is-loading .btn-label {
            opacity: 0;
        }

        .btn-spinner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(15, 27, 45, 0.25);
            border-top-color: #0F1B2D;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.15s ease;
            animation: spin 0.7s linear infinite;
        }

        .signin-btn.is-loading .btn-spinner {
            opacity: 1;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .help {
            margin-top: 18px;
            text-align: center;
            font-size: 0.84rem;
            color: var(--text-soft);
        }

        .help strong {
            color: var(--accent-text);
            font-weight: 800;
        }

        .panel-footer {
            position: relative;
            margin-top: 22px;
            text-align: center;
            font-size: 0.7rem;
            color: var(--muted);
        }

        .panel-footer a {
            color: inherit;
            text-decoration: underline;
        }

        .panel-footer a:hover {
            color: var(--accent-text);
        }

        /* ---------- Phone layout ---------- */

        @media (max-width: 820px) {
            .login-shell {
                flex-direction: column;
            }

            .login-photo {
                flex: none;
                height: 34vh;
                min-height: 230px;
                max-height: 340px;
            }

            .login-photo::after {
                background: linear-gradient(to top, rgba(6, 12, 24, 0.88) 0%, rgba(6, 12, 24, 0.4) 40%, rgba(6, 12, 24, 0) 72%);
            }

            .photo-caption {
                left: 22px;
                right: 22px;
                bottom: 48px;
            }

            .caption-eyebrow {
                font-size: 0.66rem;
                margin-bottom: 6px;
            }

            .caption-title {
                font-size: 2rem;
            }

            .caption-sub {
                margin-top: 10px;
                font-size: 0.9rem;
            }

            .caption-sub::before {
                height: 16px;
            }

            .theme-btn {
                top: 14px;
                right: 14px;
                width: 38px;
                height: 38px;
            }

            .login-panel {
                flex: 1;
                min-width: 0;
                max-width: none;
                width: 100%;
                margin-top: -26px;
                padding: 24px 22px 20px;
                border-radius: 26px 26px 0 0;
                z-index: 2;
            }

            .panel-inner {
                justify-content: flex-start;
            }

            .eyebrow {
                font-size: 0.68rem;
                margin-bottom: 8px;
            }

            .panel-inner h1 {
                font-size: 1.55rem;
                margin-bottom: 6px;
            }

            .panel-sub {
                font-size: 0.88rem;
                margin-bottom: 20px;
            }

            .field input {
                height: 50px;
                font-size: 16px;
            }

            .signin-btn {
                height: 50px;
            }

            .signin-btn.is-loading {
                width: 50px;
            }

            .help {
                margin-top: 16px;
            }

            .panel-footer {
                margin-top: 18px;
            }
        }

        /* very short phones (for example iPhone SE): tighten the spacing so the form fits */
        @media (max-width: 820px) and (max-height: 700px) {
            .login-photo {
                height: 28vh;
                min-height: 190px;
            }

            .photo-caption {
                bottom: 40px;
            }

            .caption-title {
                font-size: 1.6rem;
            }

            .caption-sub {
                margin-top: 6px;
            }

            .panel-inner h1 {
                font-size: 1.4rem;
                margin-bottom: 6px;
            }

            .panel-sub {
                margin-bottom: 14px;
            }

            .label-row {
                margin-top: 16px;
            }

            .check {
                margin-top: 14px;
            }

            .signin-btn {
                margin-top: 18px;
            }

            .help {
                margin-top: 14px;
            }

            .panel-footer {
                margin-top: 14px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .ripple { display: none; }
            .signin-btn, .btn-spinner { transition: none; }
        }
    </style>
</head>
<body>
    <button type="button" class="theme-btn" id="themeToggle" title="Toggle theme" aria-label="Toggle light or dark theme">
        <svg id="themeIcon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path></svg>
    </button>

    <div class="login-shell">
        <section class="login-photo" id="loginPhoto">
            <div class="photo-caption">
                <p class="caption-eyebrow">Welcome to</p>
                <h2 class="caption-title">STI College <span>Valencia</span></h2>
                <p class="caption-sub">Digital Clearance System</p>
            </div>
        </section>

        <main class="login-panel">
            <div class="panel-inner">
                <p class="eyebrow">Digital Clearance System</p>
                <h1>Welcome back</h1>
                <p class="panel-sub">Sign in with your school account to continue.</p>

                @if (session('error'))
                    <div class="error-box" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-box" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" autocomplete="off" id="loginForm">
                    @csrf

                    <label class="field-label" for="email">School email</label>
                    <div class="field">
                        <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                        <input type="text" inputmode="email" name="email" id="email" value="{{ old('email') }}" placeholder="name@valencia.sti.edu.ph" autocomplete="off" autocapitalize="none" spellcheck="false" required>
                    </div>

                    <div class="label-row">
                        <label for="password">Password</label>
                        <button type="button" class="link-btn" id="forgotBtn" aria-expanded="false" aria-controls="forgotNote">Forgot password?</button>
                    </div>
                    <div class="field">
                        <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                        <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="off" required>
                        <button type="button" class="eye-btn" id="passwordToggle" title="Show password" aria-label="Show password">
                            <svg id="eyeIcon" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <p class="forgot-note" id="forgotNote" role="status" hidden>Forgot your password? Ask the Techlab Office to reset it for you.</p>

                    <label class="check">
                        <input type="checkbox" name="remember" id="remember">
                        <span>Keep me signed in on this device</span>
                    </label>

                    <button type="submit" class="signin-btn" id="loginSubmitBtn">
                        <span class="btn-label">
                            Sign in
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path></svg>
                        </span>
                        <span class="btn-spinner"></span>
                    </button>
                </form>

                <p class="help">Need help? Contact the <strong>Techlab Office</strong>.</p>
            </div>

            <p class="panel-footer">Authorized students and staff only &middot; <a href="{{ route('privacy') }}">Privacy Notice</a> &middot; &copy; {{ date('Y') }} STI College Valencia</p>
        </main>
    </div>

    <script>
        // Water-ripple effect over the campus photo (mouse only)
        const loginPhoto = document.getElementById('loginPhoto');
        let lastRipple = 0;

        loginPhoto.addEventListener('mousemove', (e) => {
            const now = Date.now();
            if (now - lastRipple < 90) return;
            lastRipple = now;

            const rect = loginPhoto.getBoundingClientRect();
            const ripple = document.createElement('div');
            ripple.className = 'ripple';
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top = (e.clientY - rect.top) + 'px';
            loginPhoto.appendChild(ripple);

            setTimeout(() => ripple.remove(), 900);
        });

        // Light and dark theme (same saved setting as the other pages)
        const toggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const root = document.documentElement;

        const moonPath = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>';
        const sunPath = '<circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path>';

        const savedTheme = localStorage.getItem('theme') || 'dark';
        root.setAttribute('data-theme', savedTheme);
        themeIcon.innerHTML = savedTheme === 'dark' ? moonPath : sunPath;

        toggleBtn.addEventListener('click', () => {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            themeIcon.innerHTML = next === 'dark' ? moonPath : sunPath;
        });

        // Show or hide the password
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const eyeIcon = document.getElementById('eyeIcon');

        const eyeOpenPath = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle>';
        const eyeClosedPath = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.3 20.3 0 0 1 5.06-6.06M9.9 4.24A10.4 10.4 0 0 1 12 4c7 0 11 8 11 8a20.3 20.3 0 0 1-2.29 3.36M14.12 14.12a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';

        passwordToggle.addEventListener('click', () => {
            const hidden = passwordInput.type === 'password';
            passwordInput.type = hidden ? 'text' : 'password';
            eyeIcon.innerHTML = hidden ? eyeClosedPath : eyeOpenPath;
            const label = hidden ? 'Hide password' : 'Show password';
            passwordToggle.setAttribute('title', label);
            passwordToggle.setAttribute('aria-label', label);
        });

        // Forgot password: passwords are reset by the Techlab Office
        const forgotBtn = document.getElementById('forgotBtn');
        const forgotNote = document.getElementById('forgotNote');

        forgotBtn.addEventListener('click', () => {
            const show = forgotNote.hasAttribute('hidden');
            forgotNote.toggleAttribute('hidden', !show);
            forgotBtn.setAttribute('aria-expanded', show ? 'true' : 'false');
        });

        // Sign-in button turns into a spinner while the login is processed
        const loginForm = document.getElementById('loginForm');
        const loginSubmitBtn = document.getElementById('loginSubmitBtn');

        loginForm.addEventListener('submit', () => {
            loginSubmitBtn.classList.add('is-loading');
        });

        // Focus the email field on computers only (on phones it would open the keyboard straight away)
        if (window.matchMedia('(min-width: 821px)').matches) {
            document.getElementById('email').focus();
        }
    </script>
</body>
</html>