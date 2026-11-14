@php
    $uploadLabels = ['any' => 'Has attachments', 'new' => 'New attachments'];
@endphp
<div class="filter-dropdown">
    <div class="filter-dropdown-trigger">{{ $uploadLabels[request('uploads')] ?? 'All Attachments' }} &#9662;</div>
    <div class="filter-dropdown-menu">
        <a href="{{ request()->fullUrlWithQuery(['uploads' => null]) }}" class="{{ ! request('uploads') ? 'active' : '' }}">All Attachments</a>
        @foreach ($uploadLabels as $value => $label)
            <a href="{{ request()->fullUrlWithQuery(['uploads' => $value]) }}" class="{{ request('uploads') === $value ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>
