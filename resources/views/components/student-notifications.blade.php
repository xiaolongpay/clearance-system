<div class="sn-wrap" id="snWrap">
    <button type="button" class="sn-bell" id="snBell" aria-label="Notifications" aria-haspopup="true" aria-expanded="false" title="Notifications">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8a6 6 0 1 1 12 0c0 4.5 1.6 6.3 2.4 7.1.3.3.1.9-.4.9H4c-.5 0-.7-.6-.4-.9C4.4 14.3 6 12.5 6 8Z"></path><path d="M10 19.5a2.2 2.2 0 0 0 4 0"></path></svg>
        @if ($unseen > 0)
            <span class="sn-badge" id="snBadge"></span>
        @endif
    </button>

    <div class="sn-panel" id="snPanel" role="dialog" aria-label="Notifications">
        <div class="sn-head">Notifications</div>

        @forelse ($items as $item)
            @php($isNew = ! $seenAt || $item->updated_at->gt($seenAt))
            <div class="sn-item {{ $isNew ? 'is-new' : '' }}">
                <span class="sn-mark {{ $item->status }}"></span>
                <div class="sn-text">
                    <div class="sn-title">{{ $item->department?->name ?? 'An office' }} <span class="sn-state {{ $item->status }}">{{ $item->status === 'cleared' ? 'cleared you' : 'rejected' }}</span></div>
                    @if ($item->status === 'rejected' && $item->remarks)
                        <div class="sn-note">{{ \Illuminate\Support\Str::limit($item->remarks, 90) }}</div>
                    @endif
                </div>
                <div class="sn-time">{{ $item->updated_at->diffForHumans(null, true, true) }}</div>
            </div>
        @empty
            <div class="sn-empty">No updates yet</div>
        @endforelse
    </div>
</div>

<style>
    .sn-wrap { position: relative; display: inline-flex; align-items: center; }
    .sn-bell { position: relative; width: 36px; height: 36px; border-radius: 50%; background: transparent; border: 0; color: inherit; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; opacity: .85; transition: opacity .15s, background .15s; }
    .sn-bell:hover, .sn-wrap.open .sn-bell { opacity: 1; background: rgba(128, 128, 128, 0.18); }
    .sn-bell svg { width: 21px; height: 21px; stroke: currentColor; fill: none; stroke-width: 1.7; stroke-linecap: round; stroke-linejoin: round; }
    .sn-badge { position: absolute; top: 6px; right: 7px; width: 9px; height: 9px; border-radius: 50%; background: #EF4444; box-shadow: 0 0 0 2px var(--sn-bg, #0F1B2D); }

    .sn-panel { display: none; position: absolute; top: 44px; right: 0; width: 300px; max-height: 70vh; overflow-y: auto; background: #131F35; color: #F1F5FB; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; box-shadow: 0 12px 32px rgba(0, 0, 0, 0.35); z-index: 1000; text-align: left; }
    .sn-wrap.open .sn-panel { display: block; }
    .sn-head { padding: 13px 16px; font-size: 0.9rem; font-weight: 600; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
    .sn-item { display: flex; align-items: flex-start; gap: 10px; padding: 11px 16px; font-size: 0.85rem; line-height: 1.35; }
    .sn-item + .sn-item { border-top: 1px solid rgba(255, 255, 255, 0.06); }
    .sn-mark { flex-shrink: 0; width: 8px; height: 8px; margin-top: 6px; border-radius: 50%; background: transparent; box-shadow: inset 0 0 0 1.5px #6B7A92; }
    .sn-item.is-new .sn-mark { background: #EF4444; box-shadow: none; }
    .sn-text { flex: 1; min-width: 0; }
    .sn-title { font-weight: 500; }
    .sn-state { font-weight: 400; }
    .sn-state.cleared { color: #4ADE80; }
    .sn-state.rejected { color: #F87171; }
    .sn-note { color: #A9B6CA; font-size: 0.8rem; margin-top: 2px; }
    .sn-time { flex-shrink: 0; font-size: 0.75rem; color: #7F8DA3; padding-top: 1px; }
    .sn-empty { padding: 18px 16px; color: #A9B6CA; font-size: 0.85rem; }

    [data-theme="light"] .sn-badge { box-shadow: 0 0 0 2px var(--sn-bg, #fff); }
    [data-theme="light"] .sn-panel { background: #fff; color: #0F1B2D; border-color: #E3E8F0; box-shadow: 0 12px 32px rgba(15, 27, 45, 0.14); }
    [data-theme="light"] .sn-head { border-color: #EDF0F5; }
    [data-theme="light"] .sn-item + .sn-item { border-color: #F1F3F8; }
    [data-theme="light"] .sn-mark { box-shadow: inset 0 0 0 1.5px #A8B2C3; }
    [data-theme="light"] .sn-state.cleared { color: #15803D; }
    [data-theme="light"] .sn-state.rejected { color: #DC2626; }
    [data-theme="light"] .sn-note, [data-theme="light"] .sn-empty { color: #5B6679; }
    [data-theme="light"] .sn-time { color: #8190A5; }
    @media (max-width: 640px) {
        .sn-wrap { position: static; }
        .sn-panel { position: fixed; top: 70px; left: 50%; right: auto; width: calc(100vw - 32px); max-width: 320px; max-height: 60vh; transform: translateX(-50%); border-radius: 14px; box-shadow: 0 16px 48px rgba(0, 0, 0, 0.45); }
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
