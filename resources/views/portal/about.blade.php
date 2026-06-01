<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'About Us | IMPASUGONG TOURISM'])
    <style>
        @include('partials.central-portal-shell-styles')
    </style>
</head>
<body class="about-portal-page explore-portal-page min-h-screen font-sans text-gray-800 antialiased">
    @include('partials.portal-public-nav', [
        'active' => 'about',
        'municipalityName' => config('portals.municipality_name', 'Impasug-ong'),
        'navLayout' => 'minimal',
    ])

    @include('partials.about-us-main')

    @include('partials.portal-public-footer')
</body>
</html>
