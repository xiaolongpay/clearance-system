<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Students - STI Valencia Clearance System</title>
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
            padding: 40px 32px;
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

        .format-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 24px 26px;
            margin-bottom: 20px;
        }

        .format-card h2 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .format-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .format-table th,
        .format-table td {
            border: 1px solid var(--border-color);
            padding: 8px 10px;
            text-align: left;
        }

        .format-table th {
            background-color: var(--bg-page);
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--text-muted);
        }

        .format-note {
            margin-top: 14px;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .upload-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 24px 26px;
        }

        .upload-card h2 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .file-input-wrap {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            background-color: var(--bg-page);
        }

        .file-input-wrap input[type="file"] {
            font-size: 0.83rem;
            color: var(--text-main);
        }

        .upload-card button[type="submit"] {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background-color: var(--bg-navy);
            color: #fff;
            font-weight: 700;
            font-size: 0.92rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        [data-theme="dark"] .upload-card button[type="submit"] {
            background-color: #FFC72C;
            color: #0F1B2D;
        }

        .upload-card button[type="submit"]:hover {
            opacity: 0.9;
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
                    <a href="{{ route('techlab.students.import.create') }}" class="active">Import Students</a>
                    <a href="{{ route('techlab.students.index') }}">Manage Students</a>
                    <a href="{{ route('techlab.school-years.index') }}">School Years</a>
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
        <h1 class="page-heading">Import Students</h1>
        <p class="page-sub">Bulk add or update student records via CSV.</p>

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

        <div class="format-card">
            <h2>CSV Format</h2>

            <table class="format-table">
                <thead>
                    <tr>
                        <th>Student_ID</th>
                        <th>Name (Last, First)</th>
                        <th>Middle Initial</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Section</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>370758</td>
                        <td>Villarba, Faith</td>
                        <td>D</td>
                        <td>villarba.370758@valencia.sti.edu.ph</td>
                        <td>BSIS</td>
                        <td>4</td>
                        <td>A</td>
                    </tr>
                </tbody>
            </table>

            <p class="format-note">Imports into the active school year. Matches existing students by Student ID; new accounts default their password to it. Leave Middle Initial blank if the student doesn't have one.</p>
        </div>

        <div class="upload-card">
            <h2>Upload File</h2>

            <form method="POST" action="{{ route('techlab.students.import.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="file-input-wrap">
                    <input type="file" name="csv_file" accept=".csv,.txt" required>
                </div>

                <button type="submit">Import Students</button>
            </form>
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