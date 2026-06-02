@props([
    'accommodation',
    'alt' => null,
    'fallback' => null,
])

@php
    $altText = $alt ?? ($accommodation->name ?? 'Accommodation photo');
    $src = $accommodation->primary_image_url ?? asset('COMMUNAL.jpg');
    $fallbackSrc = $fallback ?? asset('COMMUNAL.jpg');
@endphp

<img
    src="{{ $src }}"
    alt="{{ $altText }}"
    {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
    loading="lazy"
    decoding="async"
    onerror="this.onerror=null;this.src='{{ $fallbackSrc }}';"
>
