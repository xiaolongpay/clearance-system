<div class="sn-wrap" id="snWrap">
    <button type="button" class="sn-bell" id="snBell" aria-label="Clearance updates" aria-haspopup="true" aria-expanded="false" title="Clearance updates">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        @if ($unseen > 0)
            <span class="sn-badge" id="snBadge">{{ $unseen > 9 ? '9+' : $unseen }}</span>
        @endif
    </button>

    <div class="sn-panel" id="snPanel" role="dialog" aria-label="Clearance updates">
        <div class="sn-head">Clearance updates</div>

        @forelse ($items as $item)
            @php($isNew = ! $seenAt || $item->updated_at->gt($seenAt))
            <div class="sn-item {{ $isNew ? 'is-new' : '' }}">
                <span class="sn-mark {{ $item->status }}"></span>
                <div class="sn-text">
                    <div class="sn-title"><strong>{{ $item->department?->name ?? 'An office' }}</strong>: {{ $item->status === 'cleared' ? 'Cleared' : 'Rejected' }}</div>
                    @if ($item->status === 'rejected' && $item->remarks)
                        <div class="sn-note">{{ \Illuminate\Support\Str::limit($item->remarks, 90) }}</div>
                    @endif
                    <div class="sn-time">{{ $item->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        @empty
            <div class="sn-empty">No updates yet. When an office clears you or rejects your clearance, it will show here.</div>
        @endforelse
    </div>
</div>

<style>
    .sn-wrap { position: relative; display: inline-flex; align-items: center; }
    .sn-bell { position: relative; width: 36px; height: 36px; border-radius: 50%; background: transparent; border: 1px solid rgba(255, 255, 255, 0.3); color: #fff; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; }
    .sn-bell:hover, .sn-wrap.open .sn-bell { border-color: #FFC72C; }
    .sn-bell svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .sn-badge { position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9px; background: #DC2626; color: #fff; font-size: 0.68rem; font-weight: 700; line-height: 18px; text-align: center; box-shadow: 0 0 0 2px #070E1B; }
    .sn-panel { display: none; position: absolute; top: 46px; right: 0; width: 320px; max-height: 70vh; overflow-y: auto; background: #101D32; color: #fff; border: 1px solid #22334D; border-radius: 10px; box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35); z-index: 1000; text-align: left; }
    .sn-wrap.open .sn-panel { display: block; }
    .sn-head { padding: 12px 16px; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #FFC72C; border-bottom: 1px solid #22334D; }
    .sn-item { display: flex; gap: 10px; padding: 11px 16px; border-bottom: 1px solid #22334D; font-size: 0.86rem; line-height: 1.35; }
    .sn-item:last-child { border-bottom: 0; }
    .sn-item.is-new { background: rgba(255, 199, 44, 0.1); }
    .sn-mark { flex-shrink: 0; width: 9px; height: 9px; margin-top: 5px; border-radius: 50%; background: #8E9BB0; }
    .sn-mark.cleared { background: #16A34A; }
    .sn-mark.rejected { background: #DC2626; }
    .sn-note { color: #C9D2DF; margin-top: 2px; }
    .sn-time { color: #8E9BB0; font-size: 0.75rem; margin-top: 2px; }
    .sn-empty { padding: 16px; color: #C9D2DF; font-size: 0.86rem; }
    [data-theme="light"] .sn-panel { background: #fff; color: #0F1B2D; border-color: #D8DEE8; box-shadow: 0 10px 28px rgba(15, 27, 45, 0.18); }
    [data-theme="light"] .sn-head, [data-theme="light"] .sn-item { border-color: #D8DEE8; }
    [data-theme="light"] .sn-head { color: #A16207; }
    [data-theme="light"] .sn-item.is-new { background: #FFF7DB; }
    [data-theme="light"] .sn-note, [data-theme="light"] .sn-empty { color: #3F4B5F; }
    [data-theme="light"] .sn-time { color: #5B6679; }
    @media (max-width: 640px) {
        .sn-wrap { position: static; }
        .sn-panel { position: fixed; top: 64px; left: 10px; right: 10px; width: auto; max-width: 380px; margin-left: auto; }
    }
</style>

<script>
    (function () {
        var wrap = document.getElementById('snWrap');
        if (!wrap) { return; }

        var bell = document.getElementById('snBell');
        var marked = false;

        function close() {
            wrap.classList.remove('open');
            bell.setAttribute('aria-expanded', 'false');
        }

        bell.addEventListener('click', function (event) {
            event.stopPropagation();

            var open = wrap.classList.toggle('open');
            bell.setAttribute('aria-expanded', open ? 'true' : 'false');

            var badge = document.getElementById('snBadge');

            // Opening the bell marks the results as seen, so the red badge goes away
            if (open && badge && !marked) {
                marked = true;

                fetch('{{ \Illuminate\Support\Facades\Route::has('student.notifications.seen') ? route('student.notifications.seen') : '#' }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                }).then(function (response) {
                    if (response.ok) { badge.remove(); } else { marked = false; }
                }).catch(function () { marked = false; });
            }
        });

        document.addEventListener('click', function (event) {
            if (!wrap.contains(event.target)) { close(); }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { close(); }
        });
    })();
</script>
