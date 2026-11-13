<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Notice - STI Valencia Clearance System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --gold: #FFC72C;
            --accent-text: #FFC72C;
            --page: #0A1424;
            --bar: #070E1B;
            --card: #101D32;
            --border: #22334D;
            --text: #FFFFFF;
            --text-soft: #C9D2DF;
            --muted: #8E9BB0;
        }

        [data-theme="light"] {
            --accent-text: #A16207;
            --page: #F4F6F9;
            --bar: #0F1B2D;
            --card: #FFFFFF;
            --border: #D8DEE8;
            --text: #0F1B2D;
            --text-soft: #3F4B5F;
            --muted: #5B6679;
        }

        html {
            font-size: 15px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: var(--page);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---------- Top bar ---------- */

        .topbar {
            background-color: var(--bar);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--gold);
            color: #0F1B2D;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-name {
            font-size: 0.8rem;
            line-height: 1.25;
            font-weight: 600;
        }

        .brand-name strong {
            display: block;
            color: var(--gold);
            font-weight: 800;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .back-link {
            color: #E5E7EB;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .back-link:hover {
            color: var(--gold);
        }

        .theme-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .theme-btn:hover {
            border-color: var(--gold);
        }

        .theme-btn svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ---------- Content ---------- */

        main {
            flex: 1;
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
            padding: 36px 20px 48px;
        }

        .eyebrow {
            color: var(--accent-text);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .lead {
            color: var(--text-soft);
            font-size: 0.97rem;
            margin-bottom: 24px;
        }

        .summary {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-left: 4px solid var(--gold);
            border-radius: 10px;
            padding: 18px 22px;
            margin-bottom: 32px;
        }

        .summary h2 {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--accent-text);
            margin-bottom: 8px;
        }

        .summary ul {
            padding-left: 18px;
            color: var(--text-soft);
        }

        .summary li {
            margin: 4px 0;
        }

        section.block {
            margin-bottom: 30px;
        }

        section.block h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--gold);
            color: #0F1B2D;
            font-size: 0.75rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        section.block p,
        section.block li {
            color: var(--text-soft);
        }

        section.block p {
            margin-bottom: 10px;
        }

        section.block ul {
            padding-left: 20px;
            margin-bottom: 10px;
        }

        section.block li {
            margin: 4px 0;
        }

        .data-list {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .data-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 16px;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
        }

        .data-row:last-child {
            border-bottom: 0;
        }

        .data-row dt {
            font-weight: 600;
            color: var(--text);
        }

        .data-row dd {
            color: var(--text-soft);
        }

        .note {
            font-size: 0.88rem;
            color: var(--muted);
        }

        .contact-box {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px 20px;
        }

        .contact-box strong {
            color: var(--accent-text);
        }

        a.inline-link {
            color: var(--accent-text);
            font-weight: 600;
        }

        /* ---------- Footer ---------- */

        footer {
            text-align: center;
            padding: 18px 20px 24px;
            font-size: 0.75rem;
            color: var(--muted);
            border-top: 1px solid var(--border);
        }

        /* ---------- Phone ---------- */

        @media (max-width: 640px) {
            .topbar {
                padding: 10px 14px;
            }

            .brand-name {
                display: none;
            }

            main {
                padding: 24px 16px 36px;
            }

            h1 {
                font-size: 1.45rem;
            }

            .data-row {
                grid-template-columns: 1fr;
                gap: 2px;
                padding: 12px 16px;
            }

            .summary {
                padding: 16px 18px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a href="{{ route('login') }}" class="brand" aria-label="STI College Valencia, back to sign in">
            <span class="brand-mark">STI</span>
            <span class="brand-name">STI College<strong>Valencia</strong></span>
        </a>

        <div class="top-actions">
            <a href="{{ route('login') }}" class="back-link">Back to sign in</a>
            <button type="button" class="theme-btn" id="themeToggle" title="Toggle theme" aria-label="Toggle light or dark theme">
                <svg id="themeIcon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path></svg>
            </button>
        </div>
    </header>

    <main>
        <p class="eyebrow">Privacy Notice</p>
        <h1>How the Digital Clearance System handles your information</h1>
        <p class="lead">This notice explains what information the STI College Valencia Digital Clearance System keeps, why, and who can see it. The system is designed to follow the principles of the Data Privacy Act of 2012 (Republic Act No. 10173): being open about what we collect, collecting only for a clear purpose, and collecting no more than needed.</p>

        <div class="summary">
            <h2>In short</h2>
            <ul>
                <li>We keep only the details needed to process your clearance.</li>
                <li>Your information is used for clearance and for email notices about it, and for nothing else.</li>
                <li>Only your own account, the offices that clear you, the Registrar, and the Techlab Office can see your record.</li>
                <li>Emails are sent through Google's Gmail service, so Google handles those messages.</li>
                <li>You can ask the Techlab Office to correct your details at any time.</li>
            </ul>
        </div>

        <section class="block">
            <h2><span class="num">1</span> What information we keep</h2>
            <dl class="data-list">
                <div class="data-row">
                    <dt>Students</dt>
                    <dd>Student number, last name, first name, middle initial, school email, course, year level, and section.</dd>
                </div>
                <div class="data-row">
                    <dt>Clearance records</dt>
                    <dd>For each office: whether you are cleared, the date, and the officer who signed. If an office cannot clear you, it may record a short reason, which is sent to you by email.</dd>
                </div>
                <div class="data-row">
                    <dt>Office staff</dt>
                    <dd>Name, school email, the office they handle, and the signature image used on student clearance forms.</dd>
                </div>
                <div class="data-row">
                    <dt>While you sign in</dt>
                    <dd>A password (kept only in scrambled form), a session record that includes your IP address and browser type while you are signed in, and a short-lived count of failed sign-in attempts.</dd>
                </div>
            </dl>
            <p class="note">The system does not ask for health, family, financial, or other sensitive information. Your browser also remembers your light or dark theme choice on your own device.</p>
        </section>

        <section class="block">
            <h2><span class="num">2</span> Why we keep it</h2>
            <ul>
                <li>To record which offices have cleared you for the school year.</li>
                <li>To show you your clearance status and what each office requires.</li>
                <li>To produce your downloadable clearance form.</li>
                <li>To email you when an office clears you or cannot clear you yet.</li>
                <li>To keep your account secure.</li>
            </ul>
            <p>The system does not sell your information and does not use it for advertising.</p>
        </section>

        <section class="block">
            <h2><span class="num">3</span> Where the information comes from</h2>
            <p>Student details are loaded by the Techlab Office from records prepared by STI College Valencia. You do not need to enter them yourself. The system is not connected to the school's other student systems.</p>
        </section>

        <section class="block">
            <h2><span class="num">4</span> Who can see it</h2>
            <ul>
                <li><strong>You</strong> can see your own record only.</li>
                <li><strong>Each office</strong> can see the students it needs to clear: name, student number, course, section, and clearance status for that office.</li>
                <li><strong>The Registrar</strong> can see every student's clearance status across all offices.</li>
                <li><strong>The Techlab Office</strong> manages accounts, student imports, and school years.</li>
            </ul>
        </section>

        <section class="block">
            <h2><span class="num">5</span> Who else handles it</h2>
            <p>Clearance emails are delivered through Google's Gmail service, so the message (your name, the office, the result, and any reason given) passes through Google. The system runs on the Vercel hosting service, and its data is stored in the TiDB Cloud database service. Both work on the school's behalf, and the data is kept in Singapore. We do not share your information with anyone else.</p>
        </section>

        <section class="block">
            <h2><span class="num">6</span> How we protect it</h2>
            <ul>
                <li>Passwords are stored in encrypted (hashed) form, so staff cannot read them.</li>
                <li>New accounts start with a default password that must be changed at first sign-in.</li>
                <li>Repeated wrong passwords temporarily block sign-in.</li>
                <li>Each person can only open the pages that match their role.</li>
            </ul>
            <p class="note">No system can promise perfect security. Please keep your password private and sign out on shared computers.</p>
        </section>

        <section class="block">
            <h2><span class="num">7</span> How long we keep it</h2>
            <p>Clearance records stay with the school year they belong to, so past school years remain available. How long records are kept overall follows the policy of STI College Valencia. Details that were imported by mistake can be removed by the Techlab Office.</p>
        </section>

        <section class="block">
            <h2><span class="num">8</span> Your rights and how to use them</h2>
            <p>Under the Data Privacy Act, you may ask to know what is kept about you, ask for wrong details to be corrected, and raise a concern about how your information is used. To do any of these, contact the Techlab Office. If you are not satisfied, you may take your concern to the National Privacy Commission at <a class="inline-link" href="https://www.privacy.gov.ph" target="_blank" rel="noopener noreferrer">privacy.gov.ph</a>.</p>
        </section>

        <section class="block">
            <h2><span class="num">9</span> Contact</h2>
            <div class="contact-box">
                For questions about this notice or your information, contact the <strong>Techlab Office</strong>, STI College Valencia, Sayre Highway, Poblacion, Valencia City, Bukidnon.
            </div>
        </section>
    </main>

    <footer>
        Last updated: November 2026 &middot; &copy; {{ date('Y') }} STI College Valencia
    </footer>

    <script>
        // Light and dark theme (same saved setting as the other pages)
        const toggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const root = document.documentElement;

        const moonPath = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"></path>';
        const sunPath = '<circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path>';

        const savedTheme = localStorage.getItem('theme') || 'dark';
        root.setAttribute('data-theme', savedTheme);
        themeIcon.innerHTML = savedTheme === 'dark' ? moonPath : sunPath;

        toggleBtn.addEventListener('click', () => {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            themeIcon.innerHTML = next === 'dark' ? moonPath : sunPath;
        });
    </script>
</body>
</html>