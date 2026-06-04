@php
    $basicAmenities = [
        'WiFi',
        'Air Conditioning',
        'Kitchen',
        'Parking',
        'TV',
        'Hot Shower',
        'Refrigerator',
        'Toiletries',
    ];
    $amenitiesValue = old('amenities', $accommodation->amenities ?? []);
    if (is_string($amenitiesValue)) {
        $amenitiesValue = preg_split('/\r\n|\r|\n|,/', $amenitiesValue) ?: [];
    }
    $selectedAmenities = collect($amenitiesValue)
        ->map(fn ($item) => trim((string) $item))
        ->filter()
        ->values()
        ->all();
    $galleryUrls = $accommodation->galleryImageUrls();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Edit {{ $accommodation->name }} — ImpaStay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @include('owner.partials.owner-page-fonts')
        :root {
            @include('partials.tenant-theme-css-vars')
        }

        body.owner-nav-page.owner-accommodation-edit .owner-edit-main {
            flex: 1 1 auto;
            min-height: 0;
        }

        .owner-edit-workspace {
            flex: 1 1 auto;
            min-height: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .owner-edit-form {
            flex: 1 1 auto;
            min-height: 0;
            display: flex;
            flex-direction: column;
            gap: clamp(0.75rem, 1.25vw, 1.25rem);
        }

        .owner-edit-form__grid {
            flex: 1 1 auto;
            min-height: 0;
            width: 100%;
        }

        .owner-edit-section {
            background: var(--app-surface-bg, rgba(255, 255, 255, 0.94));
            border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            padding: clamp(1rem, 2vw, 1.5rem);
        }

        .owner-edit-section__title {
            margin: 0 0 clamp(0.85rem, 1.5vw, 1.25rem);
            font-family: var(--app-font-display, inherit);
            font-size: clamp(0.9375rem, 1.5vw, 1.0625rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--chrome-icon-color, var(--brand-800, #34543f));
        }

        .owner-edit-section__title i {
            margin-right: 0.4rem;
            opacity: 0.85;
        }

        .owner-edit-preview {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .owner-edit-preview__media {
            position: relative;
            aspect-ratio: 4 / 3;
            border-radius: 0.625rem;
            overflow: hidden;
            background: var(--gray-100, #f1f5f9);
            border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
        }

        .owner-edit-preview__media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .owner-edit-preview__meta {
            display: grid;
            gap: 0.5rem;
        }

        .owner-edit-preview__row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            font-size: 0.8125rem;
            color: var(--gray-600, #4b5563);
        }

        .owner-edit-preview__row strong {
            color: var(--gray-900, #0f172a);
            font-weight: 600;
        }

        .owner-edit-status {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .owner-edit-status.is-active {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
        }

        .owner-edit-status.is-inactive {
            background: rgba(148, 163, 184, 0.2);
            color: #475569;
        }

        .owner-edit-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(4.5rem, 1fr));
            gap: 0.4rem;
        }

        .owner-edit-gallery img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .owner-edit-field input[type="file"] {
            font-size: 0.8125rem;
        }

        .owner-edit-amenity {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.1));
            background: rgba(248, 250, 252, 0.9);
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--gray-700, #374151);
            cursor: pointer;
            transition: border-color 0.12s ease, background 0.12s ease;
        }

        .owner-edit-amenity:has(input:checked) {
            border-color: rgba(69, 115, 89, 0.35);
            background: rgba(236, 253, 245, 0.85);
        }

        .owner-edit-amenity input {
            width: 1rem;
            height: 1rem;
            accent-color: var(--brand-700, #457359);
        }

        .owner-edit-availability {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.85rem 1rem;
            border-radius: 0.625rem;
            border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.1));
            background: rgba(248, 250, 252, 0.9);
        }

        .owner-edit-availability input {
            width: 1.125rem;
            height: 1.125rem;
            accent-color: var(--brand-700, #457359);
        }

        .owner-edit-availability label {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-800, #1f2937);
            cursor: pointer;
        }

        .owner-edit-actions {
            flex-shrink: 0;
            margin-top: auto;
            padding: clamp(0.85rem, 1.5vw, 1rem) clamp(1rem, 2vw, 1.25rem);
            border-radius: 0.75rem;
            border: 1px solid rgba(69, 115, 89, 0.15);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        }

        @media (min-width: 1024px) {
            .owner-edit-aside {
                align-self: start;
                position: sticky;
                top: calc(var(--owner-content-offset, 6rem) + 0.5rem);
            }
        }

        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page owner-accommodation-edit min-h-screen text-gray-800">
    @include('owner.partials.top-navbar', ['active' => 'accommodations'])

    <main class="main-content with-owner-nav owner-app-main owner-edit-main">
        @include('partials.flash-alerts')

        <header class="owner-page-top">
            <div class="owner-page-hero owner-page-hero--flush">
                <p class="owner-page-hero__eyebrow">Listings</p>
                <h1 class="owner-page-hero__title">{{ $accommodation->name }}</h1>
                <p class="owner-page-hero__lede">Update photos, pricing, availability, and amenities for this listing.</p>
            </div>
            <a href="{{ route('owner.accommodations.index', [], false) }}" class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to listings
            </a>
        </header>

        <div class="owner-page-body owner-edit-workspace">
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-900 shadow-sm" role="alert">
                    <strong class="mb-2 block text-sm font-semibold"><i class="fas fa-exclamation-circle mr-1" aria-hidden="true"></i> Please fix the following:</strong>
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('owner.accommodations.update', ['accommodation' => $accommodation->id], false) }}"
                method="POST"
                enctype="multipart/form-data"
                class="owner-edit-form"
            >
                @csrf
                @method('PUT')

                <div class="owner-edit-form__grid grid gap-6 lg:grid-cols-3">
                    <section class="owner-edit-section">
                        <h2 class="owner-edit-section__title"><i class="fas fa-info-circle" aria-hidden="true"></i> Property information</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2 owner-edit-field">
                                <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Property name *</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $accommodation->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>

                            <div class="sm:col-span-2 owner-edit-field">
                                <label for="type" class="mb-2 block text-sm font-semibold text-gray-700">Property type *</label>
                                <select id="type" name="type" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                                    <option value="traveller-inn" @selected(old('type', $accommodation->type) === 'traveller-inn')>Traveller inn</option>
                                    <option value="airbnb" @selected(old('type', $accommodation->type) === 'airbnb')>Airbnb</option>
                                    <option value="daily-rental" @selected(old('type', $accommodation->type) === 'daily-rental')>Daily rental</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2 owner-edit-field">
                                <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">Description *</label>
                                <textarea id="description" name="description" required class="min-h-[7.5rem] w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">{{ old('description', $accommodation->description) }}</textarea>
                            </div>

                            <div class="owner-edit-field">
                                <label for="address" class="mb-2 block text-sm font-semibold text-gray-700">Address *</label>
                                <input id="address" name="address" type="text" value="{{ old('address', $accommodation->address) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>

                            <div class="owner-edit-field">
                                <label for="barangay" class="mb-2 block text-sm font-semibold text-gray-700">Barangay *</label>
                                <input id="barangay" name="barangay" type="text" value="{{ old('barangay', $accommodation->barangay) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>
                        </div>
                    </section>

                    <section class="owner-edit-section">
                        <h2 class="owner-edit-section__title"><i class="fas fa-tag" aria-hidden="true"></i> Pricing &amp; capacity</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="owner-edit-field">
                                <label for="price_per_night" class="mb-2 block text-sm font-semibold text-gray-700">Price per night (₱) *</label>
                                <input id="price_per_night" name="price_per_night" type="number" min="0" step="0.01" value="{{ old('price_per_night', $accommodation->price_per_night) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>

                            <div class="owner-edit-field">
                                <label for="price_per_day" class="mb-2 block text-sm font-semibold text-gray-700">Price per day (₱)</label>
                                <input id="price_per_day" name="price_per_day" type="number" min="0" step="0.01" value="{{ old('price_per_day', $accommodation->price_per_day) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                                <p class="mt-1 text-xs text-gray-500">Optional if different from nightly rate</p>
                            </div>

                            <div class="owner-edit-field">
                                <label for="bedrooms" class="mb-2 block text-sm font-semibold text-gray-700">Bedrooms</label>
                                <input id="bedrooms" name="bedrooms" type="number" min="0" value="{{ old('bedrooms', $accommodation->bedrooms) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>

                            <div class="owner-edit-field">
                                <label for="bathrooms" class="mb-2 block text-sm font-semibold text-gray-700">Bathrooms</label>
                                <input id="bathrooms" name="bathrooms" type="number" min="0" value="{{ old('bathrooms', $accommodation->bathrooms) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>

                            <div class="sm:col-span-2 owner-edit-field">
                                <label for="max_guests" class="mb-2 block text-sm font-semibold text-gray-700">Max guests *</label>
                                <input id="max_guests" name="max_guests" type="number" min="1" value="{{ old('max_guests', $accommodation->max_guests ?? 1) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">
                            </div>
                        </div>
                    </section>

                    <aside class="owner-edit-aside owner-edit-section lg:row-span-2" aria-label="Listing preview">
                        <h2 class="owner-edit-section__title"><i class="fas fa-image" aria-hidden="true"></i> Listing preview</h2>
                        <div class="owner-edit-preview">
                            <div class="owner-edit-preview__media">
                                <img src="{{ $accommodation->primary_image_url }}" alt="">
                            </div>
                            <div class="owner-edit-preview__meta">
                                <div class="owner-edit-preview__row">
                                    <span>Type</span>
                                    <strong>{{ ucfirst(str_replace('-', ' ', $accommodation->type)) }}</strong>
                                </div>
                                <div class="owner-edit-preview__row">
                                    <span>Nightly rate</span>
                                    <strong>₱{{ number_format($accommodation->price_per_night, 0, '.', ',') }}</strong>
                                </div>
                                <div class="owner-edit-preview__row">
                                    <span>Status</span>
                                    <span class="owner-edit-status {{ $accommodation->is_available ? 'is-active' : 'is-inactive' }}">
                                        {{ $accommodation->is_available ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            @if(count($galleryUrls) > 1)
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Gallery</p>
                                <div class="owner-edit-gallery">
                                    @foreach(array_slice($galleryUrls, 0, 6) as $url)
                                        <img src="{{ $url }}" alt="">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </aside>

                    <section class="owner-edit-section lg:col-span-3">
                        <h2 class="owner-edit-section__title"><i class="fas fa-camera" aria-hidden="true"></i> Photos</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="owner-edit-field">
                                <label for="primary_image" class="mb-2 block text-sm font-semibold text-gray-700">Primary image</label>
                                <input id="primary_image" name="primary_image" type="file" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to keep the current image.</p>
                            </div>
                            <div class="owner-edit-field">
                                <label for="images" class="mb-2 block text-sm font-semibold text-gray-700">Room photos</label>
                                <input id="images" name="images[]" type="file" accept="image/*" multiple class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm">
                                <p class="mt-1 text-xs text-gray-500">Uploading new photos replaces the current gallery.</p>
                            </div>
                        </div>
                    </section>

                    <section class="owner-edit-section lg:col-span-3">
                        <h2 class="owner-edit-section__title"><i class="fas fa-star" aria-hidden="true"></i> Amenities &amp; rules</h2>
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div>
                                <p class="mb-2 text-sm font-semibold text-gray-700">Basic amenities</p>
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    @foreach($basicAmenities as $amenity)
                                        <label class="owner-edit-amenity">
                                            <input type="checkbox" name="amenities[]" value="{{ $amenity }}" {{ in_array($amenity, $selectedAmenities, true) ? 'checked' : '' }}>
                                            <span>{{ $amenity }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="owner-edit-field">
                                    <label for="house_rules" class="mb-2 block text-sm font-semibold text-gray-700">House rules</label>
                                    <textarea id="house_rules" name="house_rules" class="min-h-[6.875rem] w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">{{ old('house_rules', $accommodation->house_rules) }}</textarea>
                                </div>
                                <div class="owner-edit-field">
                                    <label for="check_in_instructions" class="mb-2 block text-sm font-semibold text-gray-700">Check-in instructions</label>
                                    <textarea id="check_in_instructions" name="check_in_instructions" class="min-h-[6.875rem] w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100">{{ old('check_in_instructions', $accommodation->check_in_instructions) }}</textarea>
                                </div>
                                <div class="owner-edit-availability">
                                    <input id="is_available" name="is_available" type="checkbox" value="1" @checked(old('is_available', $accommodation->is_available))>
                                    <label for="is_available">Listing is available to guests</label>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="owner-edit-actions">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <a href="{{ route('owner.accommodations.index', [], false) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200">
                            <i class="fas fa-times" aria-hidden="true"></i> Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800">
                            <i class="fas fa-check" aria-hidden="true"></i> Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
