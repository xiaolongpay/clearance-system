@if ($item->attachments->isNotEmpty())
    @php($newAttachments = $item->attachments->whereNull('viewed_at')->count())
    <button type="button" class="att-link" data-att="{{ $item->id }}" data-student="{{ $item->student->last_name }}, {{ $item->student->first_name }}">
        Attachments ({{ $item->attachments->count() }})@if ($newAttachments) <span class="att-new">New</span>@endif
    </button>
    <div class="att-data" id="att-{{ $item->id }}" hidden>
        <ul class="att-list">
            @foreach ($item->attachments as $attachment)
                <li>
                    <a href="{{ route('uploads.show', $attachment->id) }}" target="_blank" rel="noopener">{{ $attachment->label() }}</a>
                    <span class="att-meta">
                        {{ $attachment->kind === 'link' ? 'Link' : number_format($attachment->size / 1024) . ' KB' }}
                        &middot; {{ $attachment->created_at->diffForHumans(null, true, true) }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
