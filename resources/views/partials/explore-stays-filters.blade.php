@php
    $formAction = $formAction ?? route('portal.accommodations.index');
    $idPrefix = $idPrefix ?? '';
    $exploreFiltersActive = collect(['type', 'min_price', 'max_price', 'guests', 'search'])
        ->contains(fn (string $key) => filled(request($key)));
    $activeFilterCount = collect(['type', 'min_price', 'max_price', 'guests', 'search'])
        ->filter(fn (string $key) => filled(request($key)))
        ->count();
    $listingPerPage = (int) request('per_page', 12);
    if (! in_array($listingPerPage, [5, 12], true)) {
        $listingPerPage = 12;
    }
@endphp
<form action="{{ $formAction }}" method="GET" class="explore-stays-filters">
    <input type="hidden" name="per_page" value="{{ $listingPerPage }}" class="explore-stays-filters__per-page" data-mobile-value="5" data-desktop-value="12">
    <details class="explore-stays-filters__details" @if($exploreFiltersActive) open @endif>
        <summary class="explore-stays-filters__summary">
            <span class="explore-stays-filters__summary-label">
                <i class="fas fa-sliders-h" aria-hidden="true"></i>
                Search &amp; filters
            </span>
            @if($activeFilterCount > 0)
                <span class="explore-stays-filters__badge">{{ $activeFilterCount }} active</span>
            @endif
            <i class="fas fa-chevron-down explore-stays-filters__chevron" aria-hidden="true"></i>
        </summary>
        <div class="explore-stays-filters__grid">
            <div class="explore-stays-field explore-stays-field--type">
                <label for="{{ $idPrefix }}filter-type">Type</label>
                <select id="{{ $idPrefix }}filter-type" name="type">
                    <option value="">All</option>
                    <option value="traveller-inn" {{ request('type') == 'traveller-inn' ? 'selected' : '' }}>Traveller-Inn</option>
                    <option value="airbnb" {{ request('type') == 'airbnb' ? 'selected' : '' }}>Airbnb</option>
                    <option value="daily-rental" {{ request('type') == 'daily-rental' ? 'selected' : '' }}>Daily Rental</option>
                </select>
            </div>
            <div class="explore-stays-field explore-stays-field--min">
                <label for="{{ $idPrefix }}filter-min-price">Min price</label>
                <input type="number" id="{{ $idPrefix }}filter-min-price" name="min_price" placeholder="₱ 0" value="{{ request('min_price') }}">
            </div>
            <div class="explore-stays-field explore-stays-field--max">
                <label for="{{ $idPrefix }}filter-max-price">Max price</label>
                <input type="number" id="{{ $idPrefix }}filter-max-price" name="max_price" placeholder="₱ 10,000" value="{{ request('max_price') }}">
            </div>
            <div class="explore-stays-field explore-stays-field--guests">
                <label for="{{ $idPrefix }}filter-guests">Guests</label>
                <select id="{{ $idPrefix }}filter-guests" name="guests">
                    <option value="">Any</option>
                    <option value="1" {{ request('guests') == '1' ? 'selected' : '' }}>1 guest</option>
                    <option value="2" {{ request('guests') == '2' ? 'selected' : '' }}>2 guests</option>
                    <option value="3" {{ request('guests') == '3' ? 'selected' : '' }}>3 guests</option>
                    <option value="4" {{ request('guests') == '4' ? 'selected' : '' }}>4 guests</option>
                    <option value="5" {{ request('guests') == '5' ? 'selected' : '' }}>5+ guests</option>
                </select>
            </div>
            <div class="explore-stays-field explore-stays-field--search">
                <label for="{{ $idPrefix }}filter-search">Search</label>
                <input type="text" id="{{ $idPrefix }}filter-search" name="search" placeholder="Property name, location…" value="{{ request('search') }}" aria-label="Search properties">
            </div>
            <div class="explore-stays-field explore-stays-field--submit">
                <label class="sr-only" for="{{ $idPrefix }}filter-submit">Search</label>
                <button type="submit" id="{{ $idPrefix }}filter-submit" class="explore-stays-search-btn">Search</button>
            </div>
        </div>
    </details>
</form>
