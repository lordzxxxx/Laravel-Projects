@php
    /**
     * Prefer generated squares: public/favicons/{stem}-{size}.png (npm run favicons:generate).
     * Largest sizes first helps UAs pick sharper icons without changing artwork.
     * Fallback: single $href — duplicate <link>s with sizes hint (same asset).
     */
    $faviconStem = $faviconStem ?? null;
    $href = $href ?? null;
@endphp
@if ($faviconStem)
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicons/'.$faviconStem.'-512.png') }}">
    <link rel="icon" type="image/png" sizes="256x256" href="{{ asset('favicons/'.$faviconStem.'-256.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/'.$faviconStem.'-192.png') }}">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('favicons/'.$faviconStem.'-128.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/'.$faviconStem.'-96.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicons/'.$faviconStem.'-64.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicons/'.$faviconStem.'-48.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/'.$faviconStem.'-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/'.$faviconStem.'-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/'.$faviconStem.'-180.png') }}" sizes="180x180">
@elseif (!empty($href))
    <link rel="icon" type="image/png" sizes="512x512" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="256x256" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="128x128" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $href }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $href }}">
    <link rel="apple-touch-icon" href="{{ $href }}" sizes="180x180">
@endif
