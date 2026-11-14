@if ($item->attachments->isNotEmpty())
    <span class="att-marker" hidden
          data-att="{{ $item->id }}"
          data-student="{{ $item->student->last_name }}, {{ $item->student->first_name }}"
          data-count="{{ $item->attachments->count() }}"
          data-new="{{ $item->attachments->whereNull('viewed_at')->count() }}"></span>
    <div class="att-data" id="att-{{ $item->id }}" hidden>
        <ul class="att-list">
            @foreach ($item->attachments as $attachment)
                <li>
                    <a href="{{ route('uploads.show', $attachment->id) }}" target="_blank" rel="noopener">{{ $attachment->label() }}</a>
                    <span class="att-meta">
                        {{ $attachment->kind === 'link' ? 'Link' : number_format($attachment->size / 1024) . ' KB' }}
                        &middot; {{ $attachment->created_at->diffForHumans(null, true, true) }}
                        @if (is_null($attachment->viewed_at)) &middot; <strong class="att-flag">New</strong> @endif
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
