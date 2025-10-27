@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active bg-primary bg-opacity-10 text-primary fw-medium border-start border-primary border-4'
            : 'nav-link text-secondary fw-medium border-start border-4 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} style="transition: all 0.15s ease-in-out;">
    {{ $slot }}
</a>