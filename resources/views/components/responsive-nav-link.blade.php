@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'list-group-item list-group-item-action active border-0'
        : 'list-group-item list-group-item-action border-0';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>