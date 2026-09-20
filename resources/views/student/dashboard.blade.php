<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - STI Valencia Clearance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-page: #F4F6F9;
            --bg-card: #FFFFFF;
            --bg-navy: #0F1B2D;
            --text-main: #0F1B2D;
            --text-muted: #6B7280;
            --border-color: #9CA3AF;
        }

        [data-theme="dark"] {
            --bg-page: #0A1220;
            --bg-card: #16233A;
            --bg-navy: #0A1220;
            --text-main: #F3F4F6;
            --text-muted: #9CA3AF;
            --border-color: #4B5563;
        }

        html {
            font-size: 16px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
        }

        .topbar {
            background-color: var(--bg-navy);
            color: #fff;
            padding: 20px 40px;
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

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 0.95rem;
        }

        .live-datetime {
            color: #C4CBD8;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .theme-toggle {
            background: none;
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 1.1rem;
            width: 38px;
            height: 38px;
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
            padding: 9px 14px;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.12s;
        }

        .logout-btn:hover {
            background-color: rgba(220, 38, 38, 0.12);
        }

        .account-link {
            color: #E5E7EB;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .account-link:hover {
            color: #FFC72C;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .status-banner {
            background-color: var(--bg-card);
            border-left: 6px solid #FFC72C;
            border-radius: 10px;
            padding: 24px 32px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .status-banner.cleared {
            border-left-color: #16A34A;
        }

        .status-banner h2 {
            font-size: 1.15rem;
            margin-bottom: 6px;
        }

        .status-banner p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .download-btn {
            background-color: #FFC72C;
            color: #0F1B2D;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .download-btn:hover {
            background-color: #F0B90B;
        }

        /* --- Form card, matching the paper clearance layout --- */

        .clearance-card {
            background-color: var(--bg-card);
            border: 2px solid var(--border-color);
            border-radius: 4px;
            padding: 32px 36px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .form-header .logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .form-header .logo-row img {
            height: 40px;
            width: auto;
        }

        .form-header .school-name {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .form-header .address {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-header .clearance-title {
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 6px;
            margin-top: 12px;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table.info-table td {
            border: 1px solid var(--border-color);
            padding: 10px 14px;
            font-size: 0.85rem;
        }

        table.info-table td strong {
            font-weight: 600;
        }

        table.clearance-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.clearance-table th,
        table.clearance-table td {
            border: 1px solid var(--border-color);
            padding: 12px 16px;
            text-align: left;
            font-size: 0.85rem;
        }

        table.clearance-table thead th {
            text-align: center;
            font-weight: 700;
            letter-spacing: 1px;
        }

        table.clearance-table thead .cleared-by-header {
            font-size: 0.9rem;
        }

        .office-col {
            width: 22%;
        }

        table.clearance-table .signature-col {
            width: 30%;
            text-align: center;
        }

        table.clearance-table .date-col {
            width: 15%;
            text-align: center;
        }

        .officer-name {
            color: var(--text-main);
            font-weight: 600;
        }

        .signature-cell {
            height: 42px;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
            mix-blend-mode: multiply;
        }

        [data-theme="dark"] .signature-img {
            mix-blend-mode: screen;
            filter: invert(1);
        }

        .remarks-cell {
            color: #DC2626;
            font-size: 0.75rem;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-left">
            <img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo">
        </div>
        <div class="topbar-right">
            <a href="{{ route('account.edit') }}" class="account-link">Account Settings</a>
            <span class="live-datetime" id="liveDateTime"></span>
            <button type="button" class="theme-toggle" id="themeToggle" title="Toggle theme">🌙</button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="status-banner {{ $isFullyCleared ? 'cleared' : '' }}">
            <div>
                @if ($isFullyCleared)
                    <h2>You are fully cleared!</h2>
                @endif
                <p>Welcome back, {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
            <a href="{{ route('student.clearance-pdf') }}" class="download-btn">Download Clearance (PDF)</a>
        </div>

        <div class="clearance-card">
            <div class="form-header">
                <div class="logo-row">
                    <img src="{{ asset('images/sti_logo.png') }}" alt="STI Logo">
                    <div class="school-name">STI EDUCATION SERVICES GROUP, INC.</div>
                </div>
                <div class="address">Sayre Highway, Poblacion, Valencia City, Bukidnon</div>
                <div class="clearance-title">C L E A R A N C E</div>
            </div>

            <table class="info-table">
                <tr>
                    <td style="width:34%;"><strong>Student ID No.:</strong> {{ $student->student_number }}</td>
                    <td style="width:33%;"><strong>Program/Strand:</strong> {{ $student->course }} {{ $student->year_level }}{{ $student->section }}</td>
                    <td style="width:33%;"><strong>{{ $student->schoolYear->year_label ?? 'SY' }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Last Name:</strong> {{ $student->last_name }}</td>
                    <td><strong>First Name:</strong> {{ $student->first_name }}</td>
                    <td><strong>Middle Initial:</strong> {{ $student->middle_initial }}</td>
                </tr>
            </table>

            @if ($totalDepartments === 0)
                <p style="padding: 24px; color: var(--text-muted); font-size: 0.85rem;">No clearance items have been set up for your school year yet.</p>
            @else
                <table class="clearance-table">
                    <thead>
                        <tr>
                            <th class="office-col" rowspan="2">OFFICE</th>
                            <th class="cleared-by-header" colspan="3">CLEARED BY</th>
                        </tr>
                        <tr>
                            <th>NAME</th>
                            <th class="signature-col">SIGNATURE</th>
                            <th class="date-col">DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clearanceItems as $item)
                            <tr>
                                <td>{{ $item->department->name }}</td>
                                <td>
                                    <span class="officer-name">{{ $item->department->officer_name }}</span>
                                    @if ($item->status === 'rejected' && $item->remarks)
                                        <div class="remarks-cell">Rejected: {{ $item->remarks }}</div>
                                    @endif
                                </td>
                                <td class="signature-col">
                                    @if ($item->status === 'cleared' && $item->department->signature_path)
                                        <div class="signature-cell">
                                            <img class="signature-img" src="{{ asset('storage/' . $item->department->signature_path) }}" alt="Signature">
                                        </div>
                                    @endif
                                </td>
                                <td class="date-col">
                                    @if ($item->status === 'cleared' && $item->signed_at)
                                        {{ $item->signed_at->format('m/d/Y') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
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