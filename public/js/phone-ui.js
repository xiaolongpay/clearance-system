/*
 * phone-ui.js  |  Small helpers for the phone layout of the staff pages.
 *   1. Lets the Techlab Admin menu and the filter menus open when tapped.
 *   2. Keeps the Actions column of a table pinned to the right edge while the table scrolls sideways.
 *   3. Shows a small "swipe sideways" reminder above a table that is wider than the screen.
 * On a computer it does nothing.
 */
(function () {
    var phone = window.matchMedia('(max-width: 820px)');

    // 1) tap menus (phones only; computers keep the hover menus)
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

    // 2) pin the Actions column of a table to the right edge on phones
    document.querySelectorAll('.table-card table').forEach(function (table) {
        var heads = table.querySelectorAll('thead th');
        var last = heads.length ? heads[heads.length - 1].textContent.trim() : '';
        if (/^actions?$/i.test(last)) { table.classList.add('pin-actions'); }
    });

    // 3) swipe reminder for tables wider than the screen
    function updateHints() {
        document.querySelectorAll('.table-card').forEach(function (card) {
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

    window.addEventListener('load', updateHints);
    window.addEventListener('resize', updateHints);
})();
