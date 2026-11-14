@php($attachCount = $item->attachments->count())
@if (($item->department->accepts_uploads ?? true) && $attachCount)
    <span class="attach-count">{{ $attachCount }} attached</span>
@endif
