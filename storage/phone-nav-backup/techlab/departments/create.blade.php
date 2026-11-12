<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Department - STI Valencia Clearance System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        button { font-family: inherit; }
        :root { --bg-page: #F1F3F7; --bg-card: #FFFFFF; --bg-navy: #0F1B2D; --text-main: #111827; --text-muted: #6B7280; --border-color: #E5E7EB; }
        [data-theme="dark"] { --bg-page: #090F1A; --bg-card: #131E30; --bg-navy: #070C15; --text-main: #E5E7EB; --text-muted: #8B93A3; --border-color: #223047; }
        html { font-size: 15px; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; background-color: var(--bg-page); color: var(--text-main); min-height: 100vh; -webkit-font-smoothing: antialiased; }

        .navbar { background-color: var(--bg-navy); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 62px; position: sticky; top: 0; z-index: 20; }
        .navbar-brand img { height: 42px; width: auto; display: block; margin-left: 24px; }
        .navbar-links { display: flex; align-items: center; gap: 4px; flex: 1; justify-content: center; }
        .nav-link { position: relative; color: #C4CBD8; text-decoration: none; font-size: 0.87rem; font-weight: 500; padding: 21px 16px; transition: color 0.15s; }
        .nav-link::after { content: ''; position: absolute; left: 16px; right: 16px; bottom: 0; height: 2px; background-color: #FFC72C; transform: scaleX(0); transition: transform 0.2s ease; }
        .nav-link:hover { color: #fff; }
        .nav-link:hover::after, .nav-link.active::after { transform: scaleX(1); }
        .nav-link.active { color: #FFC72C; }
        .navbar-right { display: flex; align-items: center; gap: 14px; }
        .live-datetime { color: #8B93A3; font-size: 0.78rem; white-space: nowrap; }
        .theme-toggle { background: none; border: 1px solid rgba(255,255,255,0.2); color: #fff; font-size: 0.8rem; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .theme-toggle:hover { border-color: #FFC72C; }
        .logout-btn { background-color: transparent; border: 1px solid rgba(220,38,38,0.4); color: #F87171; padding: 7px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; cursor: pointer; white-space: nowrap; }
        .logout-btn:hover { background-color: rgba(220,38,38,0.15); }

        .nav-dropdown { position: relative; }
        .nav-dropdown-menu { position: absolute; top: 100%; left: 0; background-color: var(--bg-navy); border: 1px solid rgba(255,255,255,0.08); border-top: none; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-6px); transition: opacity 0.15s, transform 0.15s, visibility 0.15s; border-radius: 0 0 8px 8px; overflow: hidden; }
        .nav-dropdown:hover .nav-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .nav-dropdown-menu a { display: block; padding: 11px 18px; color: #C4CBD8; text-decoration: none; font-size: 0.84rem; border-left: 2px solid transparent; }
        .nav-dropdown-menu a:hover, .nav-dropdown-menu a.active { background-color: rgba(255,255,255,0.05); color: #FFC72C; border-left-color: #FFC72C; }

        .main-content { padding: 40px 32px; max-width: 560px; margin: 0 auto; }
        .back-link { display: inline-block; color: var(--text-muted); text-decoration: none; font-size: 0.85rem; margin-bottom: 20px; }
        .back-link:hover { color: #FFC72C; }
        .card { background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 32px 30px; }
        .card h1 { font-size: 1.25rem; margin-bottom: 4px; }
        .card p.sub { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 26px; }
        .error-banner { background-color: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.35); color: #B91C1C; padding: 11px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 500; }
        [data-theme="dark"] .error-banner { color: #F87171; }
        .error-banner p { margin: 2px 0; }

        label { display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 8px; margin-top: 20px; }
        label:first-of-type { margin-top: 0; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 11px 14px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-page); color: var(--text-main); font-size: 0.9rem; font-family: inherit; }
        textarea { resize: vertical; min-height: 120px; line-height: 1.5; }
        input:focus, textarea:focus { outline: none; border-color: #FFC72C; }
        .field-hint, .file-hint { color: var(--text-muted); font-size: 0.78rem; margin-top: 6px; }
        .checkbox-row { display: flex; align-items: center; gap: 10px; font-size: 0.85rem; color: var(--text-main); }
        .checkbox-row input { width: 16px; height: 16px; }

        .signature-preview { margin-bottom: 10px; }
        .signature-preview img { max-height: 50px; max-width: 160px; display: block; padding: 8px 10px; background-color: var(--bg-page); border: 1px solid var(--border-color); border-radius: 8px; }
        .signature-none { color: var(--text-muted); font-size: 0.8rem; }
        .file-input-wrap { border: 2px dashed var(--border-color); border-radius: 8px; padding: 16px; background-color: var(--bg-page); }
        .file-input-wrap input[type="file"] { font-size: 0.83rem; color: var(--text-main); width: 100%; }

        .card button[type="submit"] { width: 100%; margin-top: 28px; padding: 12px; background-color: var(--bg-navy); color: #fff; font-weight: 700; font-size: 0.92rem; border: none; border-radius: 8px; cursor: pointer; }
        [data-theme="dark"] .card button[type="submit"] { background-color: #FFC72C; color: #0F1B2D; }
        .card button[type="submit"]:hover { opacity: 0.9; }
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

            <div class="nav-dropdown">
                <span class="nav-link active">Techlab Admin ▾</span>
                <div class="nav-dropdown-menu">
                    <a href="{{ route('techlab.departments.index') }}" class="active">Manage Departments</a>
                    <a href="{{ route('techlab.students.import.create') }}">Import Students</a>
                    <a href="{{ route('techlab.students.index') }}">Manage Students</a>
                    <a href="{{ route('techlab.school-years.index') }}">School Years</a>
                    <a href="{{ route('techlab.audit-logs.index') }}">Audit Log</a>
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
        <a href="{{ route('techlab.departments.index') }}" class="back-link">&larr; Back to Departments</a>

        <div class="card">
            <h1>Add New Department</h1>
            <p class="sub">Create a department and its staff login account.</p>

            @if ($errors->any())
                <div class="error-banner">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('techlab.departments.store') }}">
                @csrf

                <label for="name">Department Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>

                <label for="officer_name">Officer Name</label>
                <input type="text" name="officer_name" id="officer_name" value="{{ old('officer_name') }}" required>

                <label for="email">Login Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>

                <label for="default_password">Default Password</label>
                <input type="text" name="default_password" id="default_password" required>

                <label for="requirements">Clearance Requirements (shown to students)</label>
                <textarea name="requirements" id="requirements" placeholder="One requirement per line, for example:&#10;Return all borrowed books&#10;Settle library fines">{{ old('requirements') }}</textarea>
                <p class="field-hint">Optional. Students see this when they click the office name on their dashboard. Put one requirement per line.</p>

                <div class="checkbox-row" style="margin-top: 20px;">
                    <input type="checkbox" name="is_techlab" id="is_techlab" value="1">
                    <label for="is_techlab" style="margin: 0; text-transform: none; font-weight: 400; font-size: 0.85rem;">This is the Techlab department (admin powers)</label>
                </div>

                <div class="checkbox-row" style="margin-top: 14px;">
                    <input type="checkbox" name="is_registrar" id="is_registrar" value="1">
                    <label for="is_registrar" style="margin: 0; text-transform: none; font-weight: 400; font-size: 0.85rem;">This is the Registrar (can view full clearance status of all students)</label>
                </div>

                <button type="submit">Create Department</button>
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
