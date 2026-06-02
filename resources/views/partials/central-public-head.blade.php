@php
    $pageTitle = $pageTitle ?? 'IMPASUGONG TOURISM | Impasugong Accommodations';
    $faviconStem = $faviconStem ?? 'love';
@endphp
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
@include('partials.navigation-stability-meta')
@include('partials.appearance-boot')
<title>{{ $pageTitle }}</title>
@include('partials.favicon-links', ['faviconStem' => $faviconStem])
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
@include('partials.typography-system')
@include('partials.ui-button-styles')
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
