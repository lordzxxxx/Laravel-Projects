<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.central-public-head', ['pageTitle' => 'About Impasug-ong Tourism | IMPASUGONG TOURISM'])
</head>
<body
    class="flex min-h-screen flex-col font-sans text-brand-dark antialiased bg-cover bg-center bg-fixed"
    style="background-image: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 50%, rgba(27, 94, 32, 0.1) 100%), url('/COMMUNAL.jpg');"
>
    @include('partials.central-public-nav', ['active' => 'about'])

    @include('partials.about-us-main')

    @include('partials.central-public-footer')
</body>
</html>
