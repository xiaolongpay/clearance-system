<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        .requirements-hint {
            color: var(--text-muted);
            font-size: 0.78rem;
            margin-bottom: 10px;
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

        /* --- Clickable office name --- */

        .office-link {
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            color: var(--text-main);
            text-align: left;
            cursor: pointer;
            text-decoration: underline;
            text-decoration-style: dotted;
            text-underline-offset: 4px;
            text-decoration-color: var(--text-muted);
            transition: color 0.12s;
        }

        .office-link:hover,
        .office-link:focus-visible {
            color: #B8860B;
            text-decoration-color: #FFC72C;
            outline: none;
        }

        [data-theme="dark"] .office-link:hover,
        [data-theme="dark"] .office-link:focus-visible {
            color: #FFC72C;
        }

        /* --- Requirements popup --- */

        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(8, 14, 25, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.18s ease, visibility 0.18s ease;
            z-index: 100;
        }

        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-top: 4px solid #FFC72C;
            border-radius: 10px;
            width: 100%;
            max-width: 460px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 24px 26px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
            transform: translateY(10px);
            transition: transform 0.18s ease;
        }

        .modal-overlay.open .modal-box {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 4px;
        }

        .modal-title {
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .modal-close {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--text-main);
            border-color: #FFC72C;
        }

        .modal-close svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .modal-subtitle {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .modal-body {
            font-size: 0.9rem;
            line-height: 1.6;
            white-space: pre-line;
            word-wrap: break-word;
        }

        .modal-body.empty {
            color: var(--text-muted);
            font-style: italic;
        }

        /* =====================================================
           Phone layout: the clearance form stays a whole paper
           sheet (same four columns), scaled down to fit the screen
           ===================================================== */

        @media (max-width: 640px) {
            .topbar {
                padding: 10px 12px;
                gap: 8px;
            }

            .topbar-left img {
                height: 30px;
                margin-left: 0;
            }

            .topbar-right {
                gap: 8px;
            }

            .live-datetime {
                display: none;
            }

            .account-link {
                font-size: 0.78rem;
            }

            .theme-toggle {
                width: 32px;
                height: 32px;
                font-size: 0.95rem;
            }

            .logout-btn {
                padding: 7px 10px;
                font-size: 0.75rem;
            }

            .container {
                margin: 14px auto 28px;
                padding: 0 10px;
            }

            .status-banner {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 16px;
                margin-bottom: 14px;
                border-left-width: 5px;
            }

            .status-banner h2 {
                font-size: 1.05rem;
                margin-bottom: 4px;
            }

            .status-banner p {
                font-size: 0.85rem;
            }

            .download-btn {
                text-align: center;
                padding: 12px;
            }

            .clearance-card {
                padding: 14px 10px 16px;
            }

            .form-header {
                margin-bottom: 14px;
            }

            .form-header .logo-row {
                gap: 8px;
            }

            .form-header .logo-row img {
                height: 26px;
            }

            .form-header .school-name {
                font-size: 0.68rem;
                letter-spacing: 0.2px;
            }

            .form-header .address {
                font-size: 0.56rem;
            }

            .form-header .clearance-title {
                font-size: 0.72rem;
                letter-spacing: 4px;
                margin-top: 8px;
            }

            table.info-table {
                margin-bottom: 14px;
            }

            table.info-table td {
                padding: 6px 7px;
                font-size: 0.68rem;
                line-height: 1.3;
            }

            .requirements-hint {
                font-size: 0.66rem;
                margin-bottom: 8px;
            }

            table.clearance-table th,
            table.clearance-table td {
                padding: 6px 5px;
                font-size: 0.68rem;
                line-height: 1.3;
                word-wrap: break-word;
            }

            table.clearance-table thead th {
                font-size: 0.6rem;
                letter-spacing: 0.3px;
            }

            table.clearance-table thead .cleared-by-header {
                font-size: 0.66rem;
            }

            .office-col {
                width: 31%;
            }

            table.clearance-table .signature-col {
                width: 27%;
            }

            table.clearance-table .date-col {
                width: 19%;
            }

            .office-link {
                padding: 4px 0;
                line-height: 1.3;
                overflow-wrap: anywhere;
            }

            .signature-cell {
                height: 30px;
            }

            .modal-overlay {
                padding: 14px;
            }

            .modal-box {
                padding: 20px 18px;
            }
        }

        /* very small phones (360px wide and under): a little tighter so nothing splits mid-word */
        @media (max-width: 360px) {
            .topbar-left img {
                height: 26px;
            }

            .topbar-right {
                gap: 6px;
            }

            .account-link {
                font-size: 0.72rem;
                white-space: nowrap;
            }

            .logout-btn {
                padding: 6px 8px;
                font-size: 0.7rem;
                white-space: nowrap;
            }

            .clearance-card {
                padding: 12px 6px 14px;
            }

            table.info-table td {
                padding: 5px 4px;
                font-size: 0.62rem;
            }

            table.clearance-table th,
            table.clearance-table td {
                padding: 5px 3px;
                font-size: 0.62rem;
            }

            table.clearance-table thead th {
                font-size: 0.54rem;
                letter-spacing: 0;
            }

            .office-col {
                width: 29%;
            }

            table.clearance-table .signature-col {
                width: 24%;
            }

            table.clearance-table .date-col {
                width: 25%;
            }

            .signature-cell {
                height: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ auth('student')->check() ? route('student.dashboard') : route('staff.dashboard') }}" title="Go to dashboard" style="display:block;"><img src="{{ asset('images/sti_val_logo.png') }}" alt="STI Valencia Logo"></a>
        </div>
        <div class="topbar-right">
            <a href="{{ route('account.edit') }}" class="account-link">Account Settings</a>
            <span class="live-datetime" id="liveDateTime"></span>
            <x-student-notifications />
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
                <p class="requirements-hint">Click an office name to see what you need to submit or settle to get cleared.</p>

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
                                <td>
                                    <button type="button"
                                            class="office-link"
                                            data-office="{{ $item->department->name }}"
                                            data-requirements="{{ $item->department->requirements }}">
                                        {!! str_replace('/', '/<wbr>', e($item->department->name)) !!}
                                    </button>
                                </td>
                                <td>
                                    <span class="officer-name">{{ $item->department->officer_name }}</span>
                                </td>
                                <td class="signature-col">
                                    @if ($item->status === 'cleared' && $item->department->signature_path)
                                        <div class="signature-cell">
                                            <img class="signature-img" src="{{ $item->department->signatureDataUri() }}" alt="Signature">
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

    <div class="modal-overlay" id="requirementsModal" aria-hidden="true">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle"></h3>
                <button type="button" class="modal-close" id="modalClose" aria-label="Close">
                    <svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="modal-subtitle">Requirements for clearance</div>
            <div class="modal-body" id="modalBody"></div>
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

        // --- Requirements popup ---
        const modal = document.getElementById('requirementsModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const modalClose = document.getElementById('modalClose');
        let lastFocused = null;

        function openModal(office, requirements) {
            lastFocused = document.activeElement;
            modalTitle.textContent = office;

            const text = (requirements || '').trim();
            if (text) {
                modalBody.textContent = text;
                modalBody.classList.remove('empty');
            } else {
                modalBody.textContent = 'No requirements have been listed for this office yet.';
                modalBody.classList.add('empty');
            }

            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            modalClose.focus();
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
            if (lastFocused) lastFocused.focus();
        }

        document.querySelectorAll('.office-link').forEach((btn) => {
            btn.addEventListener('click', () => {
                openModal(btn.dataset.office, btn.dataset.requirements);
            });
        });

        modalClose.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
        });
    </script>
</body>
</html>