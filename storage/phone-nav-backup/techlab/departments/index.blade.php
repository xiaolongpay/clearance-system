<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Departments - STI Valencia Clearance System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        button { font-family: inherit; }
        :root { --bg-page: #F1F3F7; --bg-card: #FFFFFF; --bg-navy: #0F1B2D; --text-main: #111827; --text-muted: #6B7280; --border-color: #E5E7EB; --table-header-bg: #0F1B2D; }
        [data-theme="dark"] { --bg-page: #090F1A; --bg-card: #131E30; --bg-navy: #070C15; --text-main: #E5E7EB; --text-muted: #8B93A3; --border-color: #223047; --table-header-bg: #16233A; }
        html { font-size: 15px; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; background-color: var(--bg-page); color: var(--text-main); min-height: 100vh; -webkit-font-smoothing: antialiased; }

        /* --- Top navbar --- */
        .navbar { background-color: var(--bg-navy); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; height: 62px; position: sticky; top: 0; z-index: 20; }
        .navbar-brand img { height: 42px; width: auto; display: block; margin-left: 24px; }
        .navbar-links { display: flex; align-items: center; gap: 4px; flex: 1; justify-content: center; }
        .nav-link { position: relative; color: #C4CBD8; text-decoration: none; font-size: 0.87rem; font-weight: 500; padding: 21px 16px; transition: color 0.15s; }
        .nav-link::after { content: ''; position: absolute; left: 16px; right: 16px; bottom: 0; height: 2px; background-color: #FFC72C; transform: scaleX(0); transform-origin: center; transition: transform 0.2s ease; }
        .nav-link:hover { color: #fff; }
        .nav-link:hover::after, .nav-link.active::after { transform: scaleX(1); }
        .nav-link.active { color: #FFC72C; }
        .navbar-right { display: flex; align-items: center; gap: 14px; }
        .live-datetime { color: #8B93A3; font-size: 0.78rem; white-space: nowrap; }
        .theme-toggle { background: none; border: 1px solid rgba(255,255,255,0.2); color: #fff; font-size: 0.8rem; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .theme-toggle:hover { border-color: #FFC72C; }
        .logout-btn { background-color: transparent; border: 1px solid rgba(220,38,38,0.4); color: #F87171; padding: 7px 14px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; cursor: pointer; white-space: nowrap; transition: background-color 0.12s; }
        .logout-btn:hover { background-color: rgba(220,38,38,0.15); }

        .nav-dropdown { position: relative; }
        .nav-dropdown-menu { position: absolute; top: 100%; left: 0; background-color: var(--bg-navy); border: 1px solid rgba(255,255,255,0.08); border-top: none; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-6px); transition: opacity 0.15s, transform 0.15s, visibility 0.15s; border-radius: 0 0 8px 8px; overflow: hidden; }
        .nav-dropdown:hover .nav-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .nav-dropdown-menu a { display: block; padding: 11px 18px; color: #C4CBD8; text-decoration: none; font-size: 0.84rem; border-left: 2px solid transparent; }
        .nav-dropdown-menu a:hover, .nav-dropdown-menu a.active { background-color: rgba(255,255,255,0.05); color: #FFC72C; border-left-color: #FFC72C; }

        /* --- Main content --- */
        .main-content { padding: 36px 32px; max-width: 1320px; margin: 0 auto; }
        .page-header { margin-bottom: 26px; display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .page-heading { font-size: 1.35rem; font-weight: 700; letter-spacing: -0.2px; margin-bottom: 3px; }
        .page-sub { color: var(--text-muted); font-size: 0.85rem; }
        .btn-primary { background-color: var(--bg-navy); color: #fff; font-weight: 600; border: 1px solid var(--bg-navy); padding: 9px 18px; border-radius: 7px; font-size: 0.82rem; cursor: pointer; text-decoration: none; display: inline-block; transition: opacity 0.12s; }
        .btn-primary:hover { opacity: 0.85; }
        [data-theme="dark"] .btn-primary { background-color: #FFC72C; color: #0F1B2D; border-color: #FFC72C; }
        .success-banner { background-color: rgba(22,163,74,0.08); border: 1px solid rgba(22,163,74,0.35); color: #15803D; padding: 11px 16px; border-radius: 8px; margin-bottom: 18px; font-size: 0.85rem; font-weight: 500; }
        [data-theme="dark"] .success-banner { color: #4ADE80; }

        /* --- Table (desktop) --- */
        .table-card { background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        table { width: 100%; border-collapse: collapse; }
        thead th { background-color: var(--table-header-bg); color: #C4CBD8; padding: 12px 18px; text-align: left; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; }
        tbody td { padding: 13px 18px; border-bottom: 1px solid var(--border-color); font-size: 0.85rem; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:nth-child(even) { background-color: rgba(128,128,128,0.025); }
        tbody tr:hover { background-color: rgba(255,199,44,0.05); }
        .dept-name { font-weight: 600; }
        .signature-thumb { max-height: 32px; max-width: 90px; }
        .signature-none { color: var(--text-muted); font-size: 0.78rem; }
        .badge-techlab { display: inline-block; background-color: rgba(255,199,44,0.15); color: #B8860B; font-size: 0.68rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.4px; margin-right: 4px; }
        [data-theme="dark"] .badge-techlab { color: #FFC72C; }
        .badge-registrar { display: inline-block; background-color: rgba(56,189,248,0.15); color: #0369A1; font-size: 0.68rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.4px; }
        [data-theme="dark"] .badge-registrar { color: #38BDF8; }
        .action-btn { border: 1px solid var(--border-color); background-color: transparent; color: var(--text-main); padding: 5px 11px; border-radius: 5px; font-size: 0.75rem; font-weight: 500; cursor: pointer; margin-right: 6px; text-decoration: none; display: inline-block; transition: background-color 0.12s, border-color 0.12s; }
        .action-btn:hover { background-color: rgba(255,199,44,0.08); border-color: #FFC72C; }
        .action-btn.danger:hover { background-color: rgba(220,38,38,0.08); border-color: #DC2626; color: #B91C1C; }
        .empty-row { text-align: center; color: var(--text-muted); padding: 40px; font-size: 0.88rem; }
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
        <div class="page-header">
            <div>
                <h1 class="page-heading">Manage Departments</h1>
                <p class="page-sub">Add offices, assign officers, and upload signatures &middot; {{ $activeSchoolYear->year_label ?? 'No active school year' }}</p>
            </div>
            <a href="{{ route('techlab.departments.create') }}" class="btn-primary">+ Add New Department</a>
        </div>

        @if (session('success'))
            <div class="success-banner">{{ session('success') }}</div>
        @endif

        <div class="table-card dept-card">
            <table class="dept-table">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Officer</th>
                        <th>Email</th>
                        <th>Signature</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $department)
                        <tr>
                            <td class="dept-cell">
                                <div class="dept-name">{{ $department->name }}</div>
                                @if ($department->is_techlab)
                                    <span class="badge-techlab">Techlab</span>
                                @endif
                                @if ($department->is_registrar)
                                    <span class="badge-registrar">Registrar</span>
                                @endif
                            </td>
                            <td>{{ $department->officer_name }}</td>
                            <td class="email-cell">{{ $department->email }}</td>
                            <td>
                                @if ($department->signature_path)
                                    <img src="{{ asset('storage/' . $department->signature_path) }}" alt="Signature" class="signature-thumb">
                                @else
                                    <span class="signature-none">None</span>
                                @endif
                            </td>
                            <td class="actions-cell">
                                <a href="{{ route('techlab.departments.edit', $department) }}" class="action-btn">Edit</a>

                                <form method="POST" action="{{ route('techlab.departments.reset-password', $department) }}" style="display:inline;" onsubmit="return confirm('Reset password for {{ $department->name }} back to default?');">
                                    @csrf
                                    <button type="submit" class="action-btn">Reset Password</button>
                                </form>

                                <form method="POST" action="{{ route('techlab.departments.destroy', $department) }}" style="display:inline;" onsubmit="return confirm('Delete this department?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">No departments yet.</td>
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
