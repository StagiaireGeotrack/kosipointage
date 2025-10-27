@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top' => 'dropdown-menu-top',
    default => 'dropdown-menu-end',
};

$width = match ($width) {
    '48' => 'w-48',
    default => $width,
};
@endphp

<div class="dropdown">
    <div data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
        {{ $trigger }}
    </div>

    <div class="dropdown-menu {{ $alignmentClasses }}" style="min-width: {{ $width }}rem;">
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>