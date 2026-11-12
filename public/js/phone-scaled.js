/*
 * phone-scaled.js  |  Small helpers for the staff pages on a phone.
 *   1. Lets the Techlab Admin menu and the filter menus open when tapped.
 *   2. Keeps the Actions column of a list pinned to the right edge while it scrolls sideways.
 *   3. Picks the largest text size at which every column of every list still fits the screen.
 *   4. Shows a small "swipe sideways" reminder only if a list is still wider than the screen.
 * On a computer it does nothing.
 */
(function () {
    var phone = window.matchMedia('(max-aspect-ratio: 1/1) and (max-width: 1000px)');

    // 1) tap menus
    function closeAll(except) {
        document.querySelectorAll('.nav-dropdown.open, .filter-dropdown.open').forEach(function (el) {
            if (el !== except) { el.classList.remove('open'); }
        });
    }

    document.addEventListener('click', function (event) {
        if (!phone.matches) { return; }

        var trigger = event.target.closest('.nav-dropdown > .nav-link, .filter-dropdown-trigger');

        if (trigger) {
            var box = trigger.parentElement;
            var willOpen = !box.classList.contains('open');
            closeAll(box);
            box.classList.toggle('open', willOpen);
            event.preventDefault();
            return;
        }

        if (!event.target.closest('.nav-dropdown-menu, .filter-dropdown-menu')) { closeAll(null); }
    });

    // 2) long email addresses may wrap anywhere; everything else breaks only at spaces
    document.querySelectorAll('.table-card table').forEach(function (table) {
        var heads = Array.prototype.map.call(table.querySelectorAll('thead th'), function (th) { return th.textContent.trim(); });
        var emailAt = heads.findIndex(function (h) { return /^e-?mail/i.test(h); });
        if (emailAt < 0) { return; }
        table.querySelectorAll('tbody tr').forEach(function (row) {
            var cell = row.children[emailAt];
            if (cell && !cell.hasAttribute('colspan')) {
                cell.classList.add('wrap-any');
                // allow a line break only after the "@" and the dots
                if (cell.children.length === 0) {
                    cell.textContent = cell.textContent.trim().replace(/([@.])/g, '$1\u200b');
                }
            }
        });
    });

    // 2b) pin the Actions column
    document.querySelectorAll('.table-card table').forEach(function (table) {
        var heads = table.querySelectorAll('thead th');
        var last = heads.length ? heads[heads.length - 1].textContent.trim() : '';
        if (/^actions?$/i.test(last)) { table.classList.add('pin-actions'); }
    });

    // 3) make the page as readable as possible while every column of every list still fits the screen
    function fitToScreen() {
        var main = document.querySelector('.main-content');
        if (!main) { return; }

        if (!phone.matches) { main.style.zoom = ''; return; }

        var cards = document.querySelectorAll('.table-card');
        var zoom = 2.4;
        main.style.zoom = zoom;

        function overflows() {
            return Array.prototype.some.call(cards, function (card) { return card.scrollWidth > card.clientWidth + 1; });
        }

        while (zoom > 1.5 && overflows()) {
            zoom = Math.round((zoom - 0.05) * 100) / 100;
            main.style.zoom = zoom;
        }
    }

    // 4) swipe reminder (only needed if a list is still wider than the screen)
    function updateHints() {
        document.querySelectorAll('.table-card, .format-card').forEach(function (card) {
            var hint = card.previousElementSibling;
            var isHint = hint && hint.classList && hint.classList.contains('swipe-hint');
            var needed = phone.matches && card.scrollWidth > card.clientWidth + 4;

            if (needed && !isHint) {
                var p = document.createElement('p');
                p.className = 'swipe-hint';
                p.textContent = 'Swipe sideways to see all columns \u2192';
                card.parentNode.insertBefore(p, card);
            } else if (!needed && isHint) {
                hint.remove();
            }
        });
    }

    function refresh() { fitToScreen(); updateHints(); }

    window.addEventListener('load', refresh);
    window.addEventListener('resize', refresh);
    window.addEventListener('orientationchange', refresh);
})();
