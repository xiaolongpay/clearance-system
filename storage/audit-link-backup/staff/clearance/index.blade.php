<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clearance Approvals - {{ $department->name }}</title>
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

        /* --- Main content --- */

        .main-content {
            padding: 36px 32px;
            max-width: 1320px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 26px;
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

        .success-banner {
            background-color: rgba(22, 163, 74, 0.08);
            border: 1px solid rgba(22, 163, 74, 0.35);
            color: #15803D;
            padding: 11px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        [data-theme="dark"] .success-banner {
            color: #4ADE80;
        }

        /* --- Filter toolbar --- */

        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
            align-items: center;
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 10px 12px;
        }

        .filter-bar input[type="text"] {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 7px;
            background-color: var(--bg-page);
            color: var(--text-main);
            font-size: 0.83rem;
            font-family: inherit;
            min-width: 190px;
            flex: 1;
        }

        .filter-bar input[type="text"]:focus {
            outline: none;
            border-color: #FFC72C;
        }

        .btn-filter {
            background-color: var(--bg-navy);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 9px 18px;
            border-radius: 7px;
            font-size: 0.82rem;
            cursor: pointer;
            transition: opacity 0.12s;
        }

        .btn-filter:hover {
            opacity: 0.85;
        }

        .reset-link {
            color: var(--text-muted);
            font-size: 0.82rem;
            text-decoration: none;
            padding: 0 6px;
        }

        .reset-link:hover {
            color: #FFC72C;
        }

        .filter-dropdown {
            position: relative;
        }

        .filter-dropdown-trigger {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 7px;
            background-color: var(--bg-page);
            color: var(--text-main);
            font-size: 0.83rem;
            font-family: inherit;
            cursor: default;
            white-space: nowrap;
            display: inline-block;
        }

        .filter-dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 4px;
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            min-width: 160px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity 0.15s, transform 0.15s, visibility 0.15s;
            z-index: 15;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .filter-dropdown:hover .filter-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .filter-dropdown-menu a {
            display: block;
            padding: 9px 14px;
            color: var(--text-main);
            text-decoration: none;
            font-size: 0.83rem;
        }

        .filter-dropdown-menu a:hover {
            background-color: rgba(255, 199, 44, 0.1);
            color: #B8860B;
        }

        .filter-dropdown-menu a.active {
            color: #FFC72C;
            font-weight: 600;
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
            font-size: 0.85rem;
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

        .student-name {
            font-weight: 600;
        }

        .student-id {
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid transparent;
        }

        .badge.pending {
            background-color: transparent;
            border-color: #D1D5DB;
            color: #6B7280;
        }

        .badge.cleared {
            background-color: transparent;
            border-color: #86EFAC;
            color: #15803D;
        }

        .badge.rejected {
            background-color: transparent;
            border-color: #FCA5A5;
            color: #B91C1C;
        }

        [data-theme="dark"] .badge.pending { border-color: #4B5563; color: #9CA3AF; }
        [data-theme="dark"] .badge.cleared { border-color: #166534; color: #4ADE80; }
        [data-theme="dark"] .badge.rejected { border-color: #7F1D1D; color: #F87171; }

        .action-btn {
            border: 1px solid var(--border-color);
            background-color: transparent;
            color: var(--text-main);
            padding: 5px 11px;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            margin-right: 6px;
            transition: background-color 0.12s, border-color 0.12s;
        }

        .btn-clear:hover {
            background-color: rgba(22, 163, 74, 0.08);
            border-color: #16A34A;
            color: #15803D;
        }

        .btn-reject:hover {
            background-color: rgba(220, 38, 38, 0.08);
            border-color: #DC2626;
            color: #B91C1C;
        }

        .empty-row {
            text-align: center;
            color: var(--text-muted);
            padding: 40px;
            font-size: 0.88rem;
        }

        .pagination-wrap {
            margin-top: 18px;
            font-size: 0.82rem;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/phone-scaled.css') }}">
    <script src="{{ asset('js/phone-scaled.js') }}" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">
            <a href="{{ auth('student')->check() ? route('student.dashboard') : route('staff.dashboard') }}" title="Go to dashboard" style="display:block;"><img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo"></a>
        </div>

        <div class="navbar-links">
            <a href="{{ route('staff.clearance.index') }}" class="nav-link active">Clearance Approvals</a>
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

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-heading">Clearance Approvals</h1>
            <p class="page-sub">{{ $department->name }} &middot; {{ $activeSchoolYear->year_label ?? 'No active school year' }}</p>
        </div>

        @if (session('success'))
            <div class="success-banner">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('staff.clearance.index') }}" class="filter-bar">
            <input type="text" name="search" placeholder="Search name or student ID" value="{{ request('search') }}">

            <div class="filter-dropdown">
                <div class="filter-dropdown-trigger">{{ ucfirst(request('status')) ?: 'All Statuses' }} ▾</div>
                <div class="filter-dropdown-menu">
                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="{{ !request('status') ? 'active' : '' }}">All Statuses</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="{{ request('status') === 'pending' ? 'active' : '' }}">Pending</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'cleared']) }}" class="{{ request('status') === 'cleared' ? 'active' : '' }}">Cleared</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" class="{{ request('status') === 'rejected' ? 'active' : '' }}">Rejected</a>
                </div>
            </div>

            <div class="filter-dropdown">
                <div class="filter-dropdown-trigger">{{ request('course') ?: 'All Courses' }} ▾</div>
                <div class="filter-dropdown-menu">
                    <a href="{{ request()->fullUrlWithQuery(['course' => null]) }}" class="{{ !request('course') ? 'active' : '' }}">All Courses</a>
                    <a href="{{ request()->fullUrlWithQuery(['course' => 'BSIS']) }}" class="{{ request('course') === 'BSIS' ? 'active' : '' }}">BSIS</a>
                    <a href="{{ request()->fullUrlWithQuery(['course' => 'BSTM']) }}" class="{{ request('course') === 'BSTM' ? 'active' : '' }}">BSTM</a>
                    <a href="{{ request()->fullUrlWithQuery(['course' => 'ABM']) }}" class="{{ request('course') === 'ABM' ? 'active' : '' }}">ABM</a>
                    <a href="{{ request()->fullUrlWithQuery(['course' => 'HUMSS']) }}" class="{{ request('course') === 'HUMSS' ? 'active' : '' }}">HUMSS</a>
                </div>
            </div>

            <div class="filter-dropdown">
                <div class="filter-dropdown-trigger">
                    @php
                        $yearLabels = ['1' => '1st Year', '2' => '2nd Year', '3' => '3rd Year', '4' => '4th Year', '11' => 'Grade 11', '12' => 'Grade 12'];
                    @endphp
                    {{ $yearLabels[request('year_level')] ?? 'All Years' }} ▾
                </div>
                <div class="filter-dropdown-menu">
                    <a href="{{ request()->fullUrlWithQuery(['year_level' => null]) }}" class="{{ !request('year_level') ? 'active' : '' }}">All Years</a>
                    @foreach ($yearLabels as $value => $label)
                        <a href="{{ request()->fullUrlWithQuery(['year_level' => $value]) }}" class="{{ request('year_level') === $value ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <div class="filter-dropdown">
                <div class="filter-dropdown-trigger">{{ request('section') ?: 'All Sections' }} ▾</div>
                <div class="filter-dropdown-menu">
                    <a href="{{ request()->fullUrlWithQuery(['section' => null]) }}" class="{{ !request('section') ? 'active' : '' }}">All Sections</a>
                    <a href="{{ request()->fullUrlWithQuery(['section' => 'A']) }}" class="{{ request('section') === 'A' ? 'active' : '' }}">A</a>
                    <a href="{{ request()->fullUrlWithQuery(['section' => 'B']) }}" class="{{ request('section') === 'B' ? 'active' : '' }}">B</a>
                    <a href="{{ request()->fullUrlWithQuery(['section' => 'C']) }}" class="{{ request('section') === 'C' ? 'active' : '' }}">C</a>
                </div>
            </div>

            <button type="submit" class="btn-filter">Search</button>
            <a href="{{ route('staff.clearance.index') }}" class="reset-link">Reset</a>
        </form>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course & Section</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clearanceItems as $item)
                        <tr>
                            <td>
                                <div class="student-name">{{ $item->student->last_name }}, {{ $item->student->first_name }}</div>
                                <div class="student-id">{{ $item->student->student_number }}</div>
                            </td>
                            <td>{{ $item->student->course }} {{ $item->student->year_level }}{{ $item->student->section }}</td>
                            <td><span class="badge {{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                            <td>{{ $item->remarks ?? '—' }}</td>
                            <td>
                                @if ($item->status === 'pending')
                                    <form method="POST" action="{{ route('staff.clearance.clear', $item) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-clear">Mark Cleared</button>
                                    </form>

                                    <form method="POST" action="{{ route('staff.clearance.reject', $item) }}" style="display:inline;" onsubmit="return promptReject(event, this);">
                                        @csrf
                                        <input type="hidden" name="remarks" class="reject-remarks">
                                        <button type="submit" class="action-btn btn-reject">Reject</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('staff.clearance.revert', $item) }}" style="display:inline;" onsubmit="return confirm('Revert this student back to Pending?');">
                                        @csrf
                                        <button type="submit" class="action-btn">Undo</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">No students found matching these filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $clearanceItems->links() }}
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

        function promptReject(event, form) {
            const reason = prompt('Reason for rejection:');
            if (!reason) {
                event.preventDefault();
                return false;
            }
            form.querySelector('.reject-remarks').value = reason;
            return true;
        }
    </script>
</body>
</html>