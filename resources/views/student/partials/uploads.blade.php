@php
    $uploadStoreUrl = route('student.uploads.store', ['clearanceItem' => '__ID__']);
    $uploadDeleteUrl = route('student.uploads.destroy', ['upload' => '__ID__']);
@endphp

<style>
    .up-panel { display: none; }
    .up-panel.show { display: block; }
    .up-head { margin: 18px 0 8px; padding-top: 16px; border-top: 1px solid var(--border-color); font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted); }
    .attach-count { display: block; margin-top: 4px; font-size: 0.75em; color: var(--text-muted); }
    .up-remark { font-size: 0.82rem; padding: 8px 10px; border-radius: 8px; background: rgba(220, 38, 38, 0.1); color: #DC2626; margin-bottom: 12px; }
    [data-theme="dark"] .up-remark { color: #F87171; }
    .up-list { list-style: none; margin: 0 0 14px; padding: 0; }
    .up-list li { display: flex; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--border-color); font-size: 0.85rem; }
    .up-list li:last-child { border-bottom: 0; }
    .up-list a { flex: 1; min-width: 0; color: var(--text-main); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .up-size { color: var(--text-muted); font-size: 0.75rem; flex-shrink: 0; }
    .up-remove { background: none; border: 0; color: #DC2626; font-size: 0.78rem; cursor: pointer; padding: 2px 4px; }
    .up-none { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 14px; }
    .up-form label { display: block; font-size: 0.78rem; font-weight: 600; margin: 12px 0 5px; }
    .up-form input[type="file"], .up-form input[type="url"] { width: 100%; padding: 9px 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-page); color: var(--text-main); font: inherit; font-size: 0.85rem; }
    .up-hint { font-size: 0.72rem; color: var(--text-muted); margin-top: 4px; }
    .up-open { font-family: inherit; margin-top: 14px; width: 100%; background: transparent; color: var(--text-main); border: 1.5px dashed var(--border-color); border-radius: 8px; padding: 12px; font-weight: 600; font-size: 0.9rem; cursor: pointer; }
    .up-open:hover { border-color: #FFC72C; color: #B8860B; }
    [data-theme="dark"] .up-open:hover { color: #FFC72C; }
    .up-submit { font-family: inherit; margin-top: 16px; width: 100%; background: #FFC72C; color: #0F1B2D; border: 0; border-radius: 8px; padding: 11px; font-weight: 700; font-size: 0.88rem; cursor: pointer; }
    .up-submit:disabled { opacity: 0.6; cursor: default; }
    .up-error { display: none; margin-top: 10px; font-size: 0.82rem; color: #DC2626; }
    [data-theme="dark"] .up-error { color: #F87171; }
    .up-locked { font-size: 0.85rem; color: var(--text-muted); }
    /* 16px stops iPhones from zooming in when a field is tapped */
    @media (max-width: 640px) { .up-form input[type="file"], .up-form input[type="url"] { font-size: 16px; } .up-list li { padding: 11px 0; } .up-remove { padding: 8px 6px; } }
</style>

<div class="modal-overlay" id="uploadModal" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="upTitle">
        <div class="modal-header">
            <h3 class="modal-title" id="upTitle"></h3>
            <button type="button" class="modal-close" id="upClose" aria-label="Close">
                <svg viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="modal-subtitle">Requirements for clearance</div>
        <div class="modal-body" id="upReq"></div>

        @foreach ($clearanceItems as $uploadItem)
            @continue(! ($uploadItem->department->accepts_uploads ?? true))
            <div class="up-panel" data-panel="{{ $uploadItem->id }}" data-office="{{ $uploadItem->department->name }}" data-req="{{ $uploadItem->department->requirements }}" data-locked="{{ $uploadItem->status === 'cleared' ? 1 : 0 }}">
                @if ($uploadItem->status === 'rejected' && $uploadItem->remarks)
                    <div class="up-remark">Rejected: {{ $uploadItem->remarks }}</div>
                @endif

                @if ($uploadItem->attachments->isNotEmpty())
                    <div class="up-head">Your attachments</div>
                    <ul class="up-list">
                        @foreach ($uploadItem->attachments as $attachment)
                            <li>
                                <a href="{{ route('uploads.show', $attachment->id) }}" target="_blank" rel="noopener" title="{{ $attachment->label() }}">{{ $attachment->label() }}</a>
                                @if ($attachment->kind === 'file')
                                    <span class="up-size">{{ number_format($attachment->size / 1024) }} KB</span>
                                @else
                                    <span class="up-size">Link</span>
                                @endif
                                @if ($uploadItem->status !== 'cleared')
                                    <button type="button" class="up-remove" data-remove="{{ $attachment->id }}">Remove</button>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach

        <button type="button" class="up-open" id="upOpen">Upload here</button>

        <form class="up-form" id="upForm" novalidate style="display:none;">
            <label for="upFile">Attach a file</label>
            <input type="file" id="upFile" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,image/*">
            <div class="up-hint">JPG, PNG, PDF, DOC or DOCX, up to 2 MB. Photos are shrunk for you.</div>

            <label for="upLink">Or paste a link (for a video)</label>
            <input type="url" id="upLink" placeholder="https://drive.google.com/...">
            <div class="up-hint">Upload the video to Google Drive or YouTube first, and make sure anyone with the link can view it.</div>

            <button type="submit" class="up-submit" id="upSubmit">Send</button>
            <div class="up-error" id="upError"></div>
        </form>
        <div class="up-locked" id="upLocked" style="display:none;">This office has cleared you, so attachments are closed.</div>
    </div>
</div>

<script>
    (function () {
        var overlay = document.getElementById('uploadModal');
        if (!overlay) { return; }

        var STORE_URL = @json($uploadStoreUrl);
        var DELETE_URL = @json($uploadDeleteUrl);
        var CSRF = @json(csrf_token());

        var title = document.getElementById('upTitle');
        var reqBox = document.getElementById('upReq');
        var form = document.getElementById('upForm');
        var fileInput = document.getElementById('upFile');
        var linkInput = document.getElementById('upLink');
        var submit = document.getElementById('upSubmit');
        var errorBox = document.getElementById('upError');
        var locked = document.getElementById('upLocked');
        var openBtn = document.getElementById('upOpen');
        var currentId = null;

        function showError(message) {
            errorBox.textContent = message;
            errorBox.style.display = message ? 'block' : 'none';
        }

        function openFor(id) {
            currentId = id;
            showError('');
            form.reset();

            var isLocked = false;
            document.querySelectorAll('.up-panel').forEach(function (panel) {
                var match = panel.getAttribute('data-panel') === String(id);
                panel.classList.toggle('show', match);

                if (match) {
                    title.textContent = panel.getAttribute('data-office');
                    isLocked = panel.getAttribute('data-locked') === '1';

                    var requirements = (panel.getAttribute('data-req') || '').trim();
                    reqBox.textContent = requirements || 'No requirements have been listed for this office yet.';
                    reqBox.classList.toggle('empty', !requirements);
                }
            });

            // The fields stay hidden until the student taps "Upload here"
            form.style.display = 'none';
            openBtn.style.display = isLocked ? 'none' : 'block';
            locked.style.display = isLocked ? 'block' : 'none';
            overlay.classList.add('open');
            overlay.setAttribute('aria-hidden', 'false');
        }

        function closeIt() {
            overlay.classList.remove('open');
            overlay.setAttribute('aria-hidden', 'true');
        }

        // Clicking an office name opens this popup (requirements and attachments together),
        // for every office that takes attachments. The other offices keep the plain popup.
        document.addEventListener('click', function (event) {
            var button = event.target.closest ? event.target.closest('.office-link[data-item]') : null;

            if (!button) { return; }
            if (!document.querySelector('.up-panel[data-panel="' + button.getAttribute('data-item') + '"]')) { return; }

            event.preventDefault();
            event.stopPropagation();
            openFor(button.getAttribute('data-item'));
        }, true);

        openBtn.addEventListener('click', function () {
            openBtn.style.display = 'none';
            form.style.display = 'block';
            fileInput.focus();
        });

        document.getElementById('upClose').addEventListener('click', closeIt);
        overlay.addEventListener('click', function (event) { if (event.target === overlay) { closeIt(); } });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && overlay.classList.contains('open')) { closeIt(); }
        });

        // Shrink photos in the browser so phone pictures fit under the 2 MB limit
        function shrinkImage(file) {
            return new Promise(function (resolve) {
                if (!/^image\/(jpeg|png)$/.test(file.type)) { resolve(file); return; }

                var reader = new FileReader();
                reader.onerror = function () { resolve(file); };
                reader.onload = function () {
                    var img = new Image();
                    img.onerror = function () { resolve(file); };
                    img.onload = function () {
                        var max = 1600;
                        var scale = Math.min(1, max / Math.max(img.width, img.height));
                        var canvas = document.createElement('canvas');
                        canvas.width = Math.round(img.width * scale);
                        canvas.height = Math.round(img.height * scale);
                        var ctx = canvas.getContext('2d');
                        ctx.fillStyle = '#fff';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        canvas.toBlob(function (blob) {
                            if (!blob) { resolve(file); return; }
                            var name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
                            resolve(new File([blob], name, { type: 'image/jpeg' }));
                        }, 'image/jpeg', 0.82);
                    };
                    img.src = reader.result;
                };
                reader.readAsDataURL(file);
            });
        }

        function send(url, body) {
            return fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: body
            }).then(function (response) {
                return response.json().catch(function () { return {}; }).then(function (data) {
                    if (response.ok) { return data; }
                    var message = data.message || 'Something went wrong. Please try again.';
                    if (data.errors) { message = Object.values(data.errors)[0][0]; }
                    if (response.status === 419) { message = 'Your session expired. Please refresh the page and try again.'; }
                    throw new Error(message);
                });
            });
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            showError('');

            var file = fileInput.files[0];
            var link = linkInput.value.trim();

            if (!file && !link) { showError('Choose a file or paste a link first.'); return; }

            submit.disabled = true;
            submit.textContent = 'Sending...';

            var ready = file ? shrinkImage(file) : Promise.resolve(null);

            ready.then(function (finalFile) {
                if (finalFile && finalFile.size > 2 * 1024 * 1024) {
                    throw new Error('The file is too large. The limit is 2 MB.');
                }

                var data = new FormData();
                if (finalFile) { data.append('file', finalFile, finalFile.name); }
                if (link) { data.append('link', link); }

                return send(STORE_URL.replace('__ID__', currentId), data);
            }).then(function () {
                window.location.reload();
            }).catch(function (error) {
                showError(error.message);
                submit.disabled = false;
                submit.textContent = 'Send';
            });
        });

        document.querySelectorAll('[data-remove]').forEach(function (button) {
            button.addEventListener('click', function () {
                if (!confirm('Remove this attachment?')) { return; }

                var data = new FormData();
                data.append('_method', 'DELETE');

                send(DELETE_URL.replace('__ID__', button.getAttribute('data-remove')), data)
                    .then(function () { window.location.reload(); })
                    .catch(function (error) { showError(error.message); });
            });
        });
    })();
</script>
