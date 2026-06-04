<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Edit Accommodation - ImpaStay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @include('owner.partials.owner-page-fonts')
        * { box-sizing: border-box; }
        :root {
            @include('partials.tenant-theme-css-vars')
            --red-light: #fee2e2; --red-dark: #991b1b;
        }
        .owner-edit-panel {
            width: 100%;
            max-width: min(56rem, 100%);
            margin: 0 auto;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        .section-title {
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--gray-700, #374151);
            margin: 1.25rem 0 0.75rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
        }
        .section-title:first-of-type { border-top: none; padding-top: 0; margin-top: 0; }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .full { grid-column: 1 / -1; }
        label { display: block; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.35rem; color: var(--gray-700, #374151); }
        input, select, textarea {
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 0.5rem;
            padding: 0.5625rem 0.75rem;
            font-size: 0.8125rem;
            font-family: inherit;
        }
        textarea { min-height: 6.5rem; resize: vertical; }
        .error-list { background: var(--red-light); color: var(--red-dark); border: 1px solid #fecaca; border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem; font-size: 0.8125rem; }
        .hint { color: var(--gray-500, #6b7280); font-size: 0.75rem; margin-top: 0.25rem; }
        .check-row { display: flex; align-items: center; gap: 0.5rem; }
        .check-row input { width: auto; }
        .check-row label { margin: 0; }
        .amenities-grid { display: grid; gap: 0.5rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .amenity-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 500; }
        .amenity-item input { width: auto; }
        .actions { margin-top: 1.25rem; display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap; }
        @media (max-width: 768px) {
            .grid, .amenities-grid { grid-template-columns: 1fr; }
        }
        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page font-sans antialiased text-gray-800">
    @include('owner.partials.top-navbar', ['active' => 'accommodations'])

    <main class="main-content with-owner-nav owner-app-main">
        <header class="owner-page-top">
            <div class="owner-page-hero owner-page-hero--flush">
                <p class="owner-page-hero__eyebrow">Listings</p>
                <h1 class="owner-page-hero__title">Edit Accommodation</h1>
                <p class="owner-page-hero__lede">Update photos, pricing, availability, and amenities for this listing.</p>
            </div>
            <a href="/owner/accommodations" class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to listings
            </a>
        </header>

        <div class="owner-page-body owner-edit-panel">
        @if ($errors->any())
            <div class="error-list">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/owner/accommodations/{{ $accommodation->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="section-title">
                <i class="fas fa-info-circle"></i> Property Information
            </div>

            <div class="grid">
                <div>
                    <label for="name">Property Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $accommodation->name) }}" required>
                </div>

                <div>
                    <label for="type">Property Type *</label>
                    <select id="type" name="type" required>
                        <option value="traveller-inn" @selected(old('type', $accommodation->type) === 'traveller-inn')>Traveller-Inn</option>
                        <option value="airbnb" @selected(old('type', $accommodation->type) === 'airbnb')>Airbnb</option>
                        <option value="daily-rental" @selected(old('type', $accommodation->type) === 'daily-rental')>Daily Rental</option>
                    </select>
                </div>

                <div class="full">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required>{{ old('description', $accommodation->description) }}</textarea>
                </div>

                <div>
                    <label for="address">Address *</label>
                    <input id="address" name="address" type="text" value="{{ old('address', $accommodation->address) }}" required>
                </div>

                <div>
                    <label for="barangay">Barangay *</label>
                    <input id="barangay" name="barangay" type="text" value="{{ old('barangay', $accommodation->barangay) }}" required>
                </div>
            </div>

            <div class="section-title">
                <i class="fas fa-tag"></i> Pricing
            </div>

            <div class="grid">
                <div>
                    <label for="price_per_night">Price per Night (₱) *</label>
                    <input id="price_per_night" name="price_per_night" type="number" min="0" step="0.01" value="{{ old('price_per_night', $accommodation->price_per_night) }}" required>
                </div>

                <div>
                    <label for="price_per_day">Price per Day (₱)</label>
                    <input id="price_per_day" name="price_per_day" type="number" min="0" step="0.01" value="{{ old('price_per_day', $accommodation->price_per_day) }}">
                    <div class="hint">Optional: If different from nightly rate</div>
                </div>
            </div>

            <div class="section-title">
                <i class="fas fa-bed"></i> Details
            </div>

            <div class="grid">
                <div>
                    <label for="bedrooms">Bedrooms</label>
                    <input id="bedrooms" name="bedrooms" type="number" min="0" value="{{ old('bedrooms', $accommodation->bedrooms) }}">
                </div>

                <div>
                    <label for="bathrooms">Bathrooms</label>
                    <input id="bathrooms" name="bathrooms" type="number" min="0" value="{{ old('bathrooms', $accommodation->bathrooms) }}">
                </div>

                <div>
                    <label for="max_guests">Max Guests *</label>
                    <input id="max_guests" name="max_guests" type="number" min="1" value="{{ old('max_guests', $accommodation->max_guests ?? 1) }}" required>
                </div>

                <div>
                    <label for="primary_image">Primary Image</label>
                    <input id="primary_image" name="primary_image" type="file" accept="image/*">
                    <div class="hint">Leave empty to keep the current image.</div>
                </div>

                <div>
                    <label for="images">Room Photos (Multiple)</label>
                    <input id="images" name="images[]" type="file" accept="image/*" multiple>
                    <div class="hint">Upload up to 10 photos. Uploading new photos replaces the current gallery.</div>
                </div>

                <div class="full">
                    <label>Basic Amenities</label>
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
                    @endphp
                    <div class="amenities-grid">
                        @foreach($basicAmenities as $amenity)
                            <label class="amenity-item">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity }}" {{ in_array($amenity, $selectedAmenities, true) ? 'checked' : '' }}>
                                <span>{{ $amenity }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="hint">Select all amenities available in this property.</div>
                </div>

                <div class="full">
                    <label for="house_rules">House Rules</label>
                    <textarea id="house_rules" name="house_rules">{{ old('house_rules', $accommodation->house_rules) }}</textarea>
                </div>

                <div class="full">
                    <label for="check_in_instructions">Check-in Instructions</label>
                    <textarea id="check_in_instructions" name="check_in_instructions">{{ old('check_in_instructions', $accommodation->check_in_instructions) }}</textarea>
                </div>

                <div class="full check-row">
                    <input id="is_available" name="is_available" type="checkbox" value="1" @checked(old('is_available', $accommodation->is_available))>
                    <label for="is_available" style="margin: 0;">Set as available</label>
                </div>
            </div>

            <div class="actions">
                <a href="/owner/accommodations" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200"><i class="fas fa-xmark"></i> Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[var(--brand-primary,#457359)] px-4 py-2 text-sm font-semibold text-white hover:opacity-90"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
        </div>
    </main>
</body>
</html>
