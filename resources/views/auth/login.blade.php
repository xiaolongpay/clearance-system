<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - STI Valencia Clearance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 12px;
        }

        :root {
            --bg-dark: #0F1B2D;
            --bg-darker: #0A1220;
            --input-bg: #1A2942;
            --input-border: #2E3F5C;
            --text-main: #fff;
            --text-muted: #9CA3AF;
            --label-color: #D1D5DB;
        }

        [data-theme="light"] {
            --bg-dark: #FFFFFF;
            --bg-darker: #FFFFFF;
            --input-bg: #F9FAFB;
            --input-border: #D1D5DB;
            --text-main: #0F1B2D;
            --text-muted: #4B5563;
            --label-color: #374151;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 1.1rem;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            border-color: #FFC72C;
        }

        .main {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .image-side {
            width: 70%;
            position: relative;
            overflow: hidden;
        }

        .image-side::before {
            content: '';
            position: absolute;
            inset: -10px;
            background-image: url('{{ asset('images/sti_valencia.jpg') }}');
            background-size: cover;
            background-position: center 15%;
            filter: blur(1px) brightness(0.6) contrast(1.05);
        }

        .image-side::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 27, 45, 0.9), rgba(15, 27, 45, 0.2));
        }

        .image-caption {
            position: absolute;
            bottom: 70px;
            left: 56px;
            color: #fff;
            z-index: 2;
            max-width: 85%;
        }

        .image-caption h2 {
            font-size: 2.6rem;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.15;
        }

        .image-caption span.accent {
            color: #FFC72C;
        }

        .image-caption p {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.55);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.25) inset;
            pointer-events: none;
            transform: translate(-50%, -50%);
            animation: rippleExpand 900ms ease-out forwards;
            z-index: 3;
        }

        @keyframes rippleExpand {
            0% {
                width: 0px;
                height: 0px;
                opacity: 0.6;
            }
            100% {
                width: 160px;
                height: 160px;
                opacity: 0;
            }
        }

        .form-side {
            width: 30%;
            position: relative;
            overflow: hidden;
            background-color: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .form-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 15% 20%, rgba(255, 199, 44, 0.12), transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(255, 199, 44, 0.08), transparent 50%),
                repeating-linear-gradient(135deg, rgba(255,255,255,0.025) 0px, rgba(255,255,255,0.025) 1px, transparent 1px, transparent 46px);
            pointer-events: none;
        }

        [data-theme="light"] .form-side::before {
            background:
                radial-gradient(circle at 15% 20%, rgba(255, 199, 44, 0.10), transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(15, 27, 45, 0.04), transparent 50%),
                repeating-linear-gradient(135deg, rgba(15,27,45,0.02) 0px, rgba(15,27,45,0.02) 1px, transparent 1px, transparent 46px);
        }

        .form-side::after {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            top: -120px;
            right: -140px;
            background: radial-gradient(circle, rgba(255, 199, 44, 0.15), transparent 70%);
            filter: blur(10px);
            pointer-events: none;
        }

        .form-box {
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }

        .form-box h1 {
            color: var(--text-main);
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .form-box .accent {
            color: #FFC72C;
        }

        .form-box p.subtitle {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 28px;
        }

        .error-box {
            background-color: rgba(220, 38, 38, 0.15);
            border: 1px solid #DC2626;
            color: #FCA5A5;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        .error-box p {
            margin: 3px 0;
        }

        label {
            display: block;
            color: var(--label-color);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
            margin-top: 18px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 0.95rem;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #FFC72C;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input[type="password"],
        .password-wrap input[type="text"] {
            padding-right: 44px;
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

        .remember-row {
            display: flex;
            align-items: center;
            margin-top: 16px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .remember-row input {
            margin-right: 10px;
            width: 15px;
            height: 15px;
        }

        button[type="submit"] {
            width: 100%;
            margin-top: 26px;
            padding: 14px;
            background-color: #FFC72C;
            color: #0F1B2D;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #F0B90B;
        }
    </style>
</head>
<body>
    <button type="button" class="theme-toggle" id="themeToggle" title="Toggle theme">🌙</button>

    <div class="main">
        <div class="image-side" id="imageSide">
            <div class="image-caption">
                <h2>STI College <span class="accent">Valencia</span></h2>
                <p>Digital Clearance System</p>
            </div>
        </div>

        <div class="form-side">
            <div class="form-box">
                <h1>Sign <span class="accent">In</span></h1>
                <p class="subtitle">Enter your credentials to continue</p>

                @if (session('error'))
                    <div class="error-box">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" autocomplete="off">
                    @csrf

                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="new-email" spellcheck="false" required autofocus>

                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input type="password" name="password" id="password" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" id="passwordToggle" title="Show password" aria-label="Show password">
                            <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>

                    <div class="remember-row">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember" style="margin: 0; text-transform: none; font-weight: 400; font-size: 0.85rem;">Remember me</label>
                    </div>

                    <button type="submit">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const imageSide = document.getElementById('imageSide');
        let lastRipple = 0;

        imageSide.addEventListener('mousemove', (e) => {
            const now = Date.now();
            if (now - lastRipple < 90) return;
            lastRipple = now;

            const rect = imageSide.getBoundingClientRect();
            const ripple = document.createElement('div');
            ripple.className = 'ripple';
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top = (e.clientY - rect.top) + 'px';
            imageSide.appendChild(ripple);

            setTimeout(() => ripple.remove(), 900);
        });

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

        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const eyeIcon = document.getElementById('eyeIcon');

        const eyeOpenPath = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path><circle cx="12" cy="12" r="3"></circle>';
        const eyeClosedPath = '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.3 20.3 0 0 1 5.06-6.06M9.9 4.24A10.4 10.4 0 0 1 12 4c7 0 11 8 11 8a20.3 20.3 0 0 1-2.29 3.36M14.12 14.12a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';

        passwordToggle.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.innerHTML = isPassword ? eyeClosedPath : eyeOpenPath;
            passwordToggle.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
        });
    </script>
</body>
</html>