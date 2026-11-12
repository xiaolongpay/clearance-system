<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Log - STI Valencia Clearance System</title>
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
            --table-header-bg: #0F1B2D;
        }

        [data-theme="dark"] {
            --bg-page: #090F1A;
            --bg-card: #131E30;
            --bg-navy: #070C15;
            --text-main: #E5E7EB;
            --text-muted: #8B93A3;
            --border-color: #223047;
            --table-header-bg: #16233A;
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

        /* --- Top navbar --- */

        .navbar {
            background-color: var(--bg-navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 62px;
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

        .nav-dropdown-menu a.active {
            color: #FFC72C;
            border-left-color: #FFC72C;
        }

        /* --- Main content --- */

        .main-content {
            padding: 36px 32px;
            max-width: 1320px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 22px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .page-heading {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.2px;
            margin-bottom: 3px;
        }

        .page-sub {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* --- Filters --- */

        .filter-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .filter-bar input,
        .filter-bar select {
            background-color: var(--bg-card);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            border-radius: 7px;
            padding: 9px 12px;
            font: inherit;
            font-size: 0.82rem;
            height: 38px;
        }

        .filter-bar input[type="text"] {
            flex: 1 1 240px;
            min-width: 200px;
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: #FFC72C;
        }

        .filter-label {
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .btn-filter {
            background-color: var(--bg-navy);
            color: #fff;
            font-weight: 600;
            border: 1px solid var(--bg-navy);
            padding: 0 18px;
            height: 38px;
            border-radius: 7px;
            font-size: 0.82rem;
            cursor: pointer;
        }

        [data-theme="dark"] .btn-filter {
            background-color: #FFC72C;
            color: #0F1B2D;
            border-color: #FFC72C;
        }

        .reset-link {
            color: var(--text-muted);
            font-size: 0.82rem;
            text-decoration: none;
        }

        .reset-link:hover {
            color: #FFC72C;
        }

        /* --- Table --- */

        .table-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background-color: var(--table-header-bg);
            color: #C4CBD8;
            padding: 12px 18px;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        tbody td {
            padding: 13px 18px;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.83rem;
            vertical-align: top;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background-color: rgba(128, 128, 128, 0.025);
        }

        tbody tr:hover {
            background-color: rgba(255, 199, 44, 0.05);
        }

        .when {
            white-space: nowrap;
            color: var(--text-muted);
        }

        .who strong {
            display: block;
            font-weight: 600;
        }

        .who span {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .details {
            color: var(--text-muted);
            max-width: 360px;
            word-wrap: break-word;
        }

        .badge {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }

        .badge.green { background-color: rgba(22, 163, 74, 0.12); color: #15803D; }
        .badge.red { background-color: rgba(220, 38, 38, 0.12); color: #B91C1C; }
        .badge.amber { background-color: rgba(245, 158, 11, 0.15); color: #B45309; }
        .badge.blue { background-color: rgba(56, 189, 248, 0.15); color: #0369A1; }
        .badge.gold { background-color: rgba(255, 199, 44, 0.18); color: #B8860B; }

        [data-theme="dark"] .badge.green { color: #4ADE80; }
        [data-theme="dark"] .badge.red { color: #F87171; }
        [data-theme="dark"] .badge.amber { color: #FBBF24; }
        [data-theme="dark"] .badge.blue { color: #38BDF8; }
        [data-theme="dark"] .badge.gold { color: #FFC72C; }

        .empty-row {
            text-align: center;
            color: var(--text-muted);
            padding: 40px;
            font-size: 0.88rem;
        }

        /* --- Pagination --- */

        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .pg-buttons {
            display: flex;
            gap: 8px;
        }

        .pg {
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            color: var(--text-main);
            padding: 7px 14px;
            border-radius: 6px;
            font-size: 0.8rem;
            text-decoration: none;
        }

        a.pg:hover {
            border-color: #FFC72C;
        }

        .pg.disabled {
            color: var(--text-muted);
            opacity: 0.5;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/phone-ui.css') }}">
    <script src="{{ asset('js/phone-ui.js') }}" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">
            <a href="{{ auth('student')->check() ? route('student.dashboard') : route('staff.dashboard') }}" title="Go to dashboard" style="display:block;"><img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo"></a>
        </div>

        <div class="navbar-links">
            <a href="{{ route('staff.clearance.index') }}" class="nav-link">Clearance Approvals</a>
            <a href="{{ route('account.edit') }}" class="nav-link">Account Settings</a>

            <div class="nav-dropdown">
                <span class="nav-link active">Techlab Admin ▾</span>
                <div class="nav-dropdown-menu">
                    <a class="menu-item" href="{{ route('techlab.departments.index') }}">Manage Departments</a>
                    <a class="menu-item" href="{{ route('techlab.students.import.create') }}">Import Students</a>
                    <a class="menu-item" href="{{ route('techlab.students.index') }}">Manage Students</a>
                    <a class="menu-item" href="{{ route('techlab.school-years.index') }}">School Years</a>
                    <a class="menu-item active" href="{{ route('techlab.audit-logs.index') }}">Audit Log</a>
                </div>
            </div>
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

    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-heading">Audit Log</h1>
                <p class="page-sub">A permanent record of who did what in the system. Entries cannot be edited or deleted.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="empty-row" style="padding: 12px;">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ route('techlab.audit-logs.index') }}" class="filter-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search a person, student, or detail">

            <select name="action" aria-label="Filter by action">
                <option value="">All actions</option>
                @foreach ($actions as $code => $label)
                    <option value="{{ $code }}" {{ request('action') === $code ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <span class="filter-label">From</span>
            <input type="date" name="from" value="{{ request('from') }}" aria-label="From date">
            <span class="filter-label">To</span>
            <input type="date" name="to" value="{{ request('to') }}" aria-label="To date">

            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('techlab.audit-logs.index') }}" class="reset-link">Reset</a>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Who</th>
                        <th>Action</th>
                        <th>About</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $tone = [
                                'clearance.cleared' => 'green',
                                'clearance.rejected' => 'red',
                                'clearance.reverted' => 'amber',
                                'auth.login_failed' => 'red',
                                'auth.login' => 'blue',
                                'auth.logout' => 'blue',
                            ][$log->action] ?? 'gold';
                        @endphp
                        <tr>
                            <td class="when">{{ $log->created_at->format('M d, Y') }}<br>{{ $log->created_at->format('g:i A') }}</td>
                            <td class="who">
                                <strong>{{ $log->actor_name }}</strong>
                                @if ($log->actor_department)
                                    <span>{{ $log->actor_department }}</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $tone }}">{{ $log->actionLabel() }}</span></td>
                            <td>{{ $log->target_label ?? '-' }}</td>
                            <td class="details">{{ $log->details ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">No entries match your search yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="pagination-wrap">
                <span>Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} entries</span>
                <div class="pg-buttons">
                    @if ($logs->onFirstPage())
                        <span class="pg disabled">Previous</span>
                    @else
                        <a class="pg" href="{{ $logs->previousPageUrl() }}">Previous</a>
                    @endif

                    @if ($logs->hasMorePages())
                        <a class="pg" href="{{ $logs->nextPageUrl() }}">Next</a>
                    @else
                        <span class="pg disabled">Next</span>
                    @endif
                </div>
            </div>
        @endif
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
