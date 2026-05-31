<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Wishlist - Impasugong Accommodations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
        }

        @include('client.partials.top-navbar-styles')

        body {
            font-family: var(--client-nav-font, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif);
            min-height: 100vh;
            background: linear-gradient(135deg, #E8F5E9 0%, #fff 55%, #C8E6C9 100%);
            color: #1f2937;
            padding-top: calc(var(--client-nav-offset) + 16px);
        }
    </style>
</head>
<body>
    @include('client.partials.top-navbar', ['active' => 'wishlist', 'portalDirectory' => true])

    <main class="mx-auto max-w-6xl px-4 pb-16">
        @include('partials.flash-alerts')

        <h1 class="mb-6 text-2xl font-bold text-brand-dark md:text-3xl"><i class="fas fa-heart text-rose-500 mr-2"></i>Your wishlist</h1>

        @if($favorites->isEmpty())
            <div class="rounded-2xl border border-green-100 bg-white/90 p-10 text-center shadow-sm">
                <p class="text-lg font-semibold text-gray-800">No saved listings yet.</p>
                <p class="mt-2 text-sm text-gray-600">Explore verified stays and tap the heart on a listing to save it here.</p>
                <a href="{{ route('portal.accommodations.index') }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">
                    <i class="fas fa-compass"></i> Browse listings
                </a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($favorites as $fav)
                    @php($acc = $fav->accommodation)
                    @continue(!$acc)
                    <article class="overflow-hidden rounded-2xl border border-green-100 bg-white shadow-md">
                        <a href="{{ route('portal.accommodations.show', $acc) }}" class="block aspect-[16/10] bg-gray-100">
                            <x-accommodation-image :accommodation="$acc" class="h-full w-full object-cover hover:opacity-95" />
                        </a>
                        <div class="p-4">
                            <h2 class="line-clamp-2 text-base font-bold text-gray-900">{{ $acc->name }}</h2>
                            <p class="mt-1 line-clamp-2 text-xs text-gray-600">{{ $acc->barangay ?? $acc->address }}</p>
                            <p class="mt-3 font-semibold text-green-700">{{ $acc->formatted_price }} / night</p>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('portal.accommodations.show', $acc) }}" class="inline-flex flex-1 items-center justify-center rounded-lg bg-green-700 py-2 text-xs font-semibold text-white hover:bg-green-800">View</a>
                                <form method="POST" action="{{ route('portal.wishlist.toggle', $acc) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @endif
    </main>
</body>
</html>
