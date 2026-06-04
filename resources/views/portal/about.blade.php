<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'About Impasug-ong Tourism | IMPASUGONG TOURISM'])
</head>
<body class="about-portal-page explore-portal-page min-h-[100dvh] font-sans text-gray-800 antialiased">
    @include('partials.portal-public-nav', [
        'active' => 'about',
        'municipalityName' => config('portals.municipality_name', 'Impasug-ong'),
        'navLayout' => 'minimal',
    ])

    @include('partials.about-us-main')

    @include('partials.portal-public-footer')
</body>
</html>
