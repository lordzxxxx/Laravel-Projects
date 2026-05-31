<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'About Us | IMPASUGONG TOURISM'])
    <style>
        @include('client.partials.guest-shell-styles')
    </style>
</head>
<body class="about-portal-page min-h-screen font-sans text-brand-dark antialiased">
    @include('partials.portal-public-nav', [
        'active' => 'about',
        'municipalityName' => config('portals.municipality_name', 'Impasug-ong'),
        'navLayout' => 'minimal',
    ])

    @include('partials.about-us-main')

    @include('partials.portal-public-footer')
</body>
</html>
