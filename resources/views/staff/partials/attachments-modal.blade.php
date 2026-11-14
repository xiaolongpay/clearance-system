<style>
    .att-name { cursor: pointer; border-bottom: 1px dotted currentColor; display: inline-block; }
    .att-name:hover { color: #B8860B; }
    [data-theme="dark"] .att-name:hover { color: #FFC72C; }
    .att-chip { display: inline-flex; align-items: center; gap: 4px; margin-left: 6px; padding: 0 6px; border-radius: 8px; border: 1px solid var(--border-color); color: var(--text-muted); font-size: 0.68rem; font-weight: 600; vertical-align: middle; }
    .att-chip.has-new::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #EF4444; }
    .att-flag { color: #EF4444; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.3px; }

    .att-overlay { position: fixed; inset: 0; background: rgba(8, 14, 25, 0.6); display: none; align-items: center; justify-content: center; padding: 20px; z-index: 100; }
    .att-overlay.open { display: flex; }
    .att-box { background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border-color); border-radius: 12px; width: 100%; max-width: 440px; max-height: 80vh; overflow-y: auto; padding: 20px 22px; box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3); }
    .att-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 4px; }
    .att-title { font-size: 1rem; font-weight: 700; }
    .att-sub { color: var(--text-muted); font-size: 0.78rem; margin-bottom: 12px; }
    .att-close { background: none; border: 1px solid var(--border-color); color: var(--text-muted); width: 28px; height: 28px; border-radius: 50%; cursor: pointer; flex-shrink: 0; }
    .att-list { list-style: none; margin: 0; padding: 0; }
    .att-list li { padding: 10px 0; border-bottom: 1px solid var(--border-color); font-size: 0.85rem; }
    .att-list li:last-child { border-bottom: 0; }
    .att-list a { color: var(--text-main); word-break: break-all; font-weight: 500; }
    .att-meta { display: block; color: var(--text-muted); font-size: 0.74rem; margin-top: 2px; }
</style>

<div class="att-overlay" id="attOverlay" aria-hidden="true">
    <div class="att-box" role="dialog" aria-modal="true" aria-labelledby="attTitle">
        <div class="att-head">
            <div class="att-title" id="attTitle"></div>
            <button type="button" class="att-close" id="attClose" aria-label="Close">&times;</button>
        </div>
        <div class="att-sub">Attachments from this student. Opening one marks it as seen.</div>
        <div id="attBody"></div>
    </div>
</div>

<script>
    (function () {
        var overlay = document.getElementById('attOverlay');
        if (!overlay) { return; }

        var title = document.getElementById('attTitle');
        var body = document.getElementById('attBody');

        function openIt(id, student) {
            var source = document.getElementById('att-' + id);
            title.textContent = student;
            body.innerHTML = source ? source.innerHTML : '';
            overlay.classList.add('open');
            overlay.setAttribute('aria-hidden', 'false');
        }

        function closeIt() {
            overlay.classList.remove('open');
            overlay.setAttribute('aria-hidden', 'true');
        }

        // Only students who attached something get a clickable name.
        document.querySelectorAll('.att-marker').forEach(function (marker) {
            var cell = marker.closest('td');
            var name = cell ? cell.querySelector('.student-name') : null;
            if (!name) { return; }

            var id = marker.getAttribute('data-att');
            var student = marker.getAttribute('data-student');
            var fresh = parseInt(marker.getAttribute('data-new'), 10) > 0;

            name.classList.add('att-name');
            name.setAttribute('role', 'button');
            name.setAttribute('tabindex', '0');
            name.setAttribute('title', 'View attachments');

            var chip = document.createElement('span');
            chip.className = 'att-chip' + (fresh ? ' has-new' : '');
            chip.textContent = marker.getAttribute('data-count');
            name.appendChild(chip);

            name.addEventListener('click', function () { openIt(id, student); });
            name.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openIt(id, student); }
            });
        });

        document.getElementById('attClose').addEventListener('click', closeIt);
        overlay.addEventListener('click', function (event) { if (event.target === overlay) { closeIt(); } });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { closeIt(); }
        });
    })();
</script>
