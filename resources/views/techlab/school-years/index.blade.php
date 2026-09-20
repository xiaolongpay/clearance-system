<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School Years - STI Valencia Clearance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        button {
            font-family: inherit;
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
            max-width: 760px;
            margin: 0 auto;
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
            margin-bottom: 26px;
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

        /* --- Add new card --- */

        .add-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 22px 24px;
            margin-bottom: 24px;
        }

        .add-card h2 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .add-form-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .add-form-group {
            flex: 1;
            min-width: 160px;
        }

        .add-form-group label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .add-form-group input[type="text"],
        .add-form-group select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 7px;
            background-color: var(--bg-page);
            color: var(--text-main);
            font-size: 0.85rem;
            font-family: inherit;
        }

        .add-form-group input:focus,
        .add-form-group select:focus {
            outline: none;
            border-color: #FFC72C;
        }

        .btn-primary {
            background-color: var(--bg-navy);
            color: #fff;
            font-weight: 600;
            border: 1px solid var(--bg-navy);
            padding: 9px 20px;
            border-radius: 7px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: opacity 0.12s;
            white-space: nowrap;
        }

        .btn-primary:hover {
            opacity: 0.85;
        }

        [data-theme="dark"] .btn-primary {
            background-color: #FFC72C;
            color: #0F1B2D;
            border-color: #FFC72C;
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
            vertical-align: middle;
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

        .year-label {
            font-weight: 600;
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

        .badge.active {
            border-color: #86EFAC;
            color: #15803D;
        }

        .badge.inactive {
            border-color: #D1D5DB;
            color: #6B7280;
        }

        [data-theme="dark"] .badge.active { border-color: #166534; color: #4ADE80; }
        [data-theme="dark"] .badge.inactive { border-color: #4B5563; color: #9CA3AF; }

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

        .action-btn:hover {
            background-color: rgba(255, 199, 44, 0.08);
            border-color: #FFC72C;
        }

        .action-btn.danger:hover {
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
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo">
        </div>

        <div class="navbar-links">
            <a href="{{ route('staff.dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('staff.clearance.index') }}" class="nav-link">Clearance Approvals</a>
            <a href="{{ route('account.edit') }}" class="nav-link">Account Settings</a>

            <div class="nav-dropdown">
                <span class="nav-link active">Techlab Admin ▾</span>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('techlab.departments.index') }}">Manage Departments</a>
                    <a href="{{ route('techlab.students.import.create') }}">Import Students</a>
                    <a href="{{ route('techlab.students.index') }}">Manage Students</a>
                    <a href="{{ route('techlab.school-years.index') }}" class="active">School Years</a>
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
        <h1 class="page-heading">School Years</h1>
        <p class="page-sub">Create academic periods and set which one is currently active.</p>

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

        <div class="add-card">
            <h2>Add School Year</h2>

            <form method="POST" action="{{ route('techlab.school-years.store') }}">
                @csrf

                <div class="add-form-row">
                    <div class="add-form-group">
                        <label for="year_label">School Year</label>
                        <input type="text" name="year_label" id="year_label" placeholder="e.g. 2027-2028" required>
                    </div>

                    <div class="add-form-group">
                        <label for="semester">Semester</label>
                        <select name="semester" id="semester" required>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary">Add</button>
                </div>
            </form>
        </div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>School Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schoolYears as $schoolYear)
                        <tr>
                            <td><span class="year-label">{{ $schoolYear->year_label }}</span></td>
                            <td>
                                @if ($schoolYear->is_active)
                                    <span class="badge active">Active</span>
                                @else
                                    <span class="badge inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if (! $schoolYear->is_active)
                                    <form method="POST" action="{{ route('techlab.school-years.activate', $schoolYear) }}" style="display:inline;" onsubmit="return confirm('Activate {{ $schoolYear->year_label }}? This will start a fresh clearance cycle for all students.');">
                                        @csrf
                                        <button type="submit" class="action-btn">Activate</button>
                                    </form>

                                    <form method="POST" action="{{ route('techlab.school-years.destroy', $schoolYear) }}" style="display:inline;" onsubmit="return confirm('Delete {{ $schoolYear->year_label }} and all its clearance records? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-row">No school years yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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