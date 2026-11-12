<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard - STI Valencia Clearance System</title>
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

        :root:not([data-theme="dark"]) .navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
        }

        :root:not([data-theme="dark"]) .navbar-brand img {
            filter: none;
        }

        :root:not([data-theme="dark"]) .nav-link {
            color: #374151;
        }

        :root:not([data-theme="dark"]) .nav-link:hover {
            color: #111827;
        }

        :root:not([data-theme="dark"]) .live-datetime {
            color: #6B7280;
        }

        :root:not([data-theme="dark"]) .theme-toggle {
            border-color: #D1D5DB;
            color: #111827;
        }

        :root:not([data-theme="dark"]) .nav-dropdown-menu {
            background-color: #FFFFFF;
            border-color: #E5E7EB;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        :root:not([data-theme="dark"]) .nav-dropdown-menu a {
            color: #374151;
        }

        :root:not([data-theme="dark"]) .hero-panel {
            background-color: #FFFFFF;
            border-right: 1px solid #E5E7EB;
        }

        :root:not([data-theme="dark"]) .welcome-heading {
            color: #6B7280;
        }

        :root:not([data-theme="dark"]) .welcome-period {
            color: #6B7280;
        }

        :root:not([data-theme="dark"]) .welcome-period strong {
            color: #111827;
        }

        html {
            font-size: 15px;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
        }

        /* --- Top navbar --- */

        .navbar {
            background-color: var(--bg-navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 62px;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .navbar-brand img {
            height: 42px;
            width: auto;
            display: block;
            margin-left: 24px;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 4px;
            flex: 1;
            justify-content: center;
        }

        .nav-link {
            position: relative;
            color: #C4CBD8;
            text-decoration: none;
            font-size: 0.87rem;
            font-weight: 500;
            padding: 21px 16px;
            transition: color 0.15s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 16px;
            right: 16px;
            bottom: 0;
            height: 2px;
            background-color: #FFC72C;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.2s ease;
        }

        .nav-link:hover {
            color: #fff;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        .nav-link.active {
            color: #FFC72C;
        }

        .nav-link.active::after {
            transform: scaleX(1);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .live-datetime {
            color: #8B93A3;
            font-size: 0.78rem;
            white-space: nowrap;
        }

        .theme-toggle {
            background: none;
            border: 1px solid rgba(255,255,255,0.2);
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

        .logout-btn {
            background-color: transparent;
            border: 1px solid rgba(220, 38, 38, 0.4);
            color: #F87171;
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.12s;
            white-space: nowrap;
        }

        .logout-btn:hover {
            background-color: rgba(220, 38, 38, 0.15);
        }

        /* --- Dropdown for Techlab Admin group --- */

        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--bg-navy);
            border: 1px solid rgba(255,255,255,0.08);
            border-top: none;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity 0.15s, transform 0.15s, visibility 0.15s;
            border-radius: 0 0 8px 8px;
            overflow: hidden;
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .nav-dropdown-menu a {
            display: block;
            padding: 11px 18px;
            color: #C4CBD8;
            text-decoration: none;
            font-size: 0.84rem;
            border-left: 2px solid transparent;
        }

        .nav-dropdown-menu a:hover {
            background-color: rgba(255,255,255,0.05);
            color: #FFC72C;
            border-left-color: #FFC72C;
        }

        /* --- Split hero: navy panel left, image right --- */

        .hero {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .hero-panel {
            width: 35%;
            background-color: var(--bg-navy);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 48px 40px 0;
            flex-shrink: 0;
        }

        .hero-image {
            width: 65%;
            position: relative;
            overflow: hidden;
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
            display: block;
        }

        .welcome-heading {
            font-size: 1.1rem;
            font-weight: 500;
            color: #C4CBD8;
            margin-bottom: 4px;
        }

        .welcome-heading .user-name {
            display: block;
            font-size: 1.7rem;
            font-weight: 700;
            margin-top: 2px;
            color: #FFC72C;
            text-decoration: none;
            position: relative;
            width: fit-content;
            transition: color 0.15s;
        }

        .welcome-heading .user-name::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -2px;
            height: 2px;
            background-color: #FFC72C;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.2s ease;
        }

        .welcome-heading .user-name:hover::after {
            transform: scaleX(1);
        }

        .welcome-role {
            display: block;
            color: #FFC72C;
            font-size: 0.95rem;
            font-weight: 600;
            margin-top: 6px;
        }

        .welcome-period {
            margin-top: 14px;
            color: #C4CBD8;
            font-size: 0.9rem;
        }

        .welcome-period strong {
            color: #fff;
        }

        /* --- Phone held upright: big greeting on top, the whole photo along the bottom edge --- */

        @media (max-aspect-ratio: 1/1) and (max-width: 1000px) {
            .hero {
                flex-direction: column;
            }

            .hero-panel,
            :root:not([data-theme="dark"]) .hero-panel {
                width: 100%;
                flex: 1;
                justify-content: center;
                padding: 48px 48px;
                border-right: 0;
            }

            [data-theme="dark"] .hero-panel {
                background:
                    radial-gradient(circle at 12% 18%, rgba(255, 199, 44, 0.12), transparent 48%),
                    var(--bg-navy);
            }

            :root:not([data-theme="dark"]) .hero-panel {
                border-bottom: 1px solid #E5E7EB;
            }

            .welcome-heading {
                font-size: 2.5rem;
                margin-bottom: 8px;
            }

            .welcome-heading .user-name {
                font-size: 4.6rem;
                line-height: 1.05;
                margin-top: 6px;
            }

            .welcome-role {
                font-size: 2.6rem;
                margin-top: 16px;
            }

            .welcome-period {
                font-size: 2.2rem;
                line-height: 1.3;
                margin-top: 22px;
            }

            .hero-image {
                width: 100%;
                flex: none;
            }

            .hero-image img {
                height: auto;
                object-fit: contain;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">
            <a href="{{ auth('student')->check() ? route('student.dashboard') : route('staff.dashboard') }}" title="Go to dashboard" style="display:block;"><img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo"></a>
        </div>

        <div class="navbar-links">
            <a href="{{ route('staff.clearance.index') }}" class="nav-link">Clearance Approvals</a>
            <a href="{{ route('account.edit') }}" class="nav-link">Account Settings</a>

            @if (auth('web')->user()->department?->is_registrar)
                <a href="{{ route('registrar.clearance-status') }}" class="nav-link">Full Clearance Status</a>
            @endif

            @if (auth('web')->user()->department?->is_techlab)
                <div class="nav-dropdown">
                    <span class="nav-link">Techlab Admin ▾</span>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('techlab.departments.index') }}">Manage Departments</a>
                        <a href="{{ route('techlab.students.import.create') }}">Import Students</a>
                        <a href="{{ route('techlab.students.index') }}">Manage Students</a>
                        <a href="{{ route('techlab.school-years.index') }}">School Years</a>
                        <a href="{{ route('techlab.audit-logs.index') }}">Audit Log</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="navbar-right">
            <span class="live-datetime" id="liveDateTime"></span>
            <button type="button" class="theme-toggle" id="themeToggle" title="Toggle theme">🌙</button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </div>

    <div class="hero">
        <div class="hero-panel">
            <h1 class="welcome-heading">Welcome, <span class="user-name">{{ auth('web')->user()->name }}</span></h1>
            <div class="welcome-role">{{ auth('web')->user()->department->name ?? 'Unassigned Department' }}</div>
            <div class="welcome-period">Active academic period: <strong>{{ $activeSchoolYear->year_label ?? 'No active school year set' }}</strong></div>
        </div>

        <div class="hero-image">
            <img src="{{ asset('images/1.png') }}" alt="STI Valencia">
        </div>
    </div>

    <script>
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
