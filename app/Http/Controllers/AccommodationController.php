<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ListsClientAccommodations;
use App\Models\Accommodation;
use App\Models\Tenant;
use App\Models\User;
use App\Support\AccommodationAvailability;
use App\Support\PortalDetector;
use Database\Seeders\RbacCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;

class AccommodationController extends Controller
{
    use ListsClientAccommodations;

    /**
     * Display a listing of accommodations for clients.
     */
    public function index(Request $request)
    {
        if (Tenant::checkCurrent()) {
            $target = '/dashboard';
            if ($request->getQueryString()) {
                $target .= '?'.$request->getQueryString();
            }

            return redirect($target);
        }

        $accommodations = $this->paginatedClientAccommodations($request);
        $portalDirectory = PortalDetector::isPublicPortal($request) || ! Tenant::checkCurrent();

        return view('client.accommodations.index', compact('accommodations', 'portalDirectory'));
    }

    /**
     * Display accommodation details.
     */
    public function show(Request $request, Accommodation $accommodation)
    {
        $currentTenant = Tenant::current();

        if ($currentTenant && (int) $accommodation->tenant_id !== (int) $currentTenant->id) {
            abort(404);
        }

        if (! $currentTenant) {
            abort_unless($this->isApprovedMunicipalityListing($accommodation), 404);
        }

        $accommodation->load(['owner', 'bookings' => function ($query) {
            $query->whereIn('status', ['pending', 'confirmed', 'paid'])
                ->where('check_out_date', '>=', now()->toDateString());
        }]);

        $amenities = is_array($accommodation->amenities) ? $accommodation->amenities : [];
        $images = is_array($accommodation->images) ? $accommodation->images : [];
        $portalDirectory = PortalDetector::isPublicPortal($request) || ! Tenant::checkCurrent();

        $availabilityAccommodations = collect([$accommodation]);
        $availabilityEventsByAccommodation = AccommodationAvailability::eventsForAccommodationIds(
            [(int) $accommodation->id],
            (int) $accommodation->tenant_id,
            null
        );

        return view('client.accommodations.show', compact(
            'accommodation',
            'amenities',
            'images',
            'portalDirectory',
            'availabilityAccommodations',
            'availabilityEventsByAccommodation'
        ));
    }

    /**
     * Show the form for creating a new accommodation (Owner only).
     */
    public function create(Request $request)
    {
        $this->authorize('create', Accommodation::class);

        [$tenant, $_ownerId] = $this->resolveManagedTenantAndOwner($request);

        if (! $tenant && $request->user()?->isOwner()) {
            $tenant = $request->user()->ensureTenant();
        }

        if (! $tenant) {
            return redirect()
                ->route('owner.dashboard')
                ->with('error', 'We could not determine your business (tenant). Open the dashboard and try again, or contact support.');
        }

        $currentCount = Accommodation::query()->where('tenant_id', $tenant->id)->count();
        $maxListings = $tenant->maxListings();
        $canCreate = $tenant->canCreateAccommodation($currentCount);
        $availableFeatures = $tenant->getAvailableFeatures();

        $businessStatus = $tenant->businessStatusParts();

        return view('owner.accommodations.create', compact('canCreate', 'currentCount', 'maxListings', 'availableFeatures', 'businessStatus'));
    }

    /**
     * Store a newly created accommodation.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Accommodation::class);

        [$tenant, $ownerId] = $this->resolveManagedTenantAndOwner($request);

        if (! $tenant || ! $ownerId) {
            return back()->withErrors([
                'name' => 'Unable to resolve tenant ownership for this listing.',
            ])->withInput();
        }

        $currentCount = Accommodation::query()->where('tenant_id', $tenant->id)->count();

        if (! $tenant->canCreateAccommodation($currentCount)) {
            return back()->withErrors([
                'name' => 'You have reached your plan limit or your subscription is inactive. Upgrade your plan to add more properties.',
            ])->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:traveller-inn,airbnb,daily-rental',
            'description' => 'required|string',
            'address' => 'required|string',
            'barangay' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'amenities' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! is_array($value) && ! is_string($value)) {
                        $fail('The amenities field must be a valid list.');
                    }
                },
            ],
            'house_rules' => 'nullable|string',
            'check_in_instructions' => 'nullable|string',
            'primary_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['owner_id'] = $ownerId;
        $validated['tenant_id'] = $tenant->id;
        $validated['max_guests'] = (int) $validated['max_guests'];
        $validated['is_available'] = true;
        $validated['is_verified'] = true;
        $validated['amenities'] = $this->normalizeAmenities($request->input('amenities', []));

        $galleryImages = [];

        // Handle cover image upload
        if ($request->hasFile('primary_image')) {
            $validated['primary_image'] = $request->file('primary_image')->store('accommodations', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $galleryImages[] = $imageFile->store('accommodations', 'public');
            }
        }

        if (! empty($galleryImages)) {
            $validated['images'] = $galleryImages;
        }

        if (empty($validated['primary_image']) && ! empty($galleryImages)) {
            $validated['primary_image'] = $galleryImages[0];
        }

        $accommodation = Accommodation::create($validated);

        return redirect('/owner/accommodations')
            ->with('success', 'Accommodation listed successfully! It is now visible to tenant clients.');
    }

    /**
     * Show the form for editing an accommodation.
     */
    public function edit(Accommodation $accommodation)
    {
        $this->authorize('update', $accommodation);

        return view('owner.accommodations.edit', compact('accommodation'));
    }

    /**
     * Update an accommodation.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        $this->authorize('update', $accommodation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:traveller-inn,airbnb,daily-rental',
            'description' => 'required|string',
            'address' => 'required|string',
            'barangay' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'amenities' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (! is_array($value) && ! is_string($value)) {
                        $fail('The amenities field must be a valid list.');
                    }
                },
            ],
            'house_rules' => 'nullable|string',
            'check_in_instructions' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'primary_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['amenities'] = $this->normalizeAmenities($request->input('amenities', []));
        $validated['max_guests'] = (int) $validated['max_guests'];
        $validated['is_available'] = $request->has('is_available');

        // Handle cover image upload
        if ($request->hasFile('primary_image')) {
            // Delete old image if exists
            if ($accommodation->primary_image) {
                Storage::disk('public')->delete($accommodation->primary_image);
            }
            $validated['primary_image'] = $request->file('primary_image')->store('accommodations', 'public');
        }

        // Handle gallery images upload (replaces existing gallery when provided)
        if ($request->hasFile('images')) {
            $oldGalleryImages = is_array($accommodation->images) ? $accommodation->images : [];
            foreach ($oldGalleryImages as $oldImagePath) {
                Storage::disk('public')->delete((string) $oldImagePath);
            }

            $galleryImages = [];
            foreach ($request->file('images') as $imageFile) {
                $galleryImages[] = $imageFile->store('accommodations', 'public');
            }

            $validated['images'] = $galleryImages;

            if (! isset($validated['primary_image']) && ! empty($galleryImages)) {
                $validated['primary_image'] = $galleryImages[0];
            }
        }

        $accommodation->update($validated);

        return redirect('/owner/accommodations')
            ->with('success', 'Accommodation updated successfully!');
    }

    /**
     * Remove an accommodation.
     */
    public function destroy(Accommodation $accommodation)
    {
        $this->authorize('delete', $accommodation);

        // Delete images
        if ($accommodation->primary_image) {
            Storage::disk('public')->delete($accommodation->primary_image);
        }

        $galleryImages = is_array($accommodation->images) ? $accommodation->images : [];
        foreach ($galleryImages as $imagePath) {
            Storage::disk('public')->delete((string) $imagePath);
        }

        $accommodation->delete();

        return redirect('/owner/accommodations')
            ->with('success', 'Accommodation deleted successfully!');
    }

    /**
     * Display owner's accommodations.
     */
    public function ownerIndex(Request $request)
    {
        $this->assertTenantAdminHasAnyPermission($request, [
            User::PERM_ACCOMMODATIONS_CREATE,
            User::PERM_ACCOMMODATIONS_UPDATE,
            User::PERM_ACCOMMODATIONS_DELETE,
        ]);

        $user = $request->user();
        $currentTenant = Tenant::current();

        if ($user->isAdmin() && $currentTenant && (int) $user->tenant_id === (int) $currentTenant->id) {
            $accommodations = Accommodation::query()
                ->where('tenant_id', $currentTenant->id)
                ->withCount('bookings')
                ->latest()
                ->paginate(10);
        } else {
            $tenantId = $user->tenant_id;

            $accommodations = $user
                ->accommodations()
                ->when($tenantId, fn ($query) => $query->where('tenant_id', $tenantId))
                ->withCount('bookings')
                ->latest()
                ->paginate(10);
        }

        $tenantForLimits = $currentTenant;
        if (! $tenantForLimits && $user->isOwner()) {
            $tenantForLimits = $user->tenant ?? $user->ownedTenant;
        }

        $businessStatus = null;
        $canCreateListing = false;

        if ($tenantForLimits) {
            $totalForTenant = Accommodation::query()->where('tenant_id', $tenantForLimits->id)->count();
            $businessStatus = $tenantForLimits->businessStatusParts();
            $canCreateListing = $tenantForLimits->canCreateAccommodation($totalForTenant);
        }

        $availabilityAccommodations = collect();
        $availabilityEventsByAccommodation = [];

        if ($tenantForLimits) {
            if ($user->isAdmin() && $currentTenant && (int) $user->tenant_id === (int) $currentTenant->id) {
                $availabilityAccommodations = Accommodation::query()
                    ->where('tenant_id', $currentTenant->id)
                    ->orderBy('name')
                    ->get(['id', 'name', 'type']);
                $ids = $availabilityAccommodations->pluck('id')->all();
                $availabilityEventsByAccommodation = AccommodationAvailability::eventsForAccommodationIds(
                    $ids,
                    (int) $currentTenant->id,
                    null
                );
            } else {
                $tenantId = $user->tenant_id;
                $availabilityAccommodations = $user
                    ->accommodations()
                    ->when($tenantId, fn ($query) => $query->where('tenant_id', $tenantId))
                    ->orderBy('name')
                    ->get(['id', 'name', 'type']);
                $ids = $availabilityAccommodations->pluck('id')->all();
                $availabilityEventsByAccommodation = AccommodationAvailability::eventsForAccommodationIds(
                    $ids,
                    null,
                    (int) $user->id
                );
            }
        }

        return view('owner.accommodations.index', compact(
            'accommodations',
            'businessStatus',
            'canCreateListing',
            'availabilityAccommodations',
            'availabilityEventsByAccommodation'
        ));
    }

    /**
     * Resolve tenant and owner account for owner-management actions.
     */
    private function resolveManagedTenantAndOwner(Request $request): array
    {
        $user = $request->user();
        $currentTenant = Tenant::current();

        if ($user->isOwner()) {
            $tenant = $user->ensureTenant();

            return [$tenant, $user->id];
        }

        if ($user->isAdmin() && $currentTenant && (int) $user->tenant_id === (int) $currentTenant->id) {
            $ownerId = (int) ($currentTenant->owner_user_id ?? 0);

            return [$currentTenant, $ownerId > 0 ? $ownerId : null];
        }

        return [null, null];
    }

    /**
     * Toggle accommodation availability.
     */
    public function toggleAvailability(Accommodation $accommodation)
    {
        $this->authorize('update', $accommodation);

        $accommodation->update(['is_available' => ! $accommodation->is_available]);

        $status = $accommodation->is_available ? 'available' : 'unavailable';

        return back()->with('success', "Accommodation is now {$status}.");
    }

    /**
     * Normalize amenities input from either array fields or textarea text.
     */
    private function normalizeAmenities($amenities): array
    {
        if (is_string($amenities)) {
            $amenities = preg_split('/\r\n|\r|\n|,/', $amenities) ?: [];
        }

        if (! is_array($amenities)) {
            return [];
        }

        return collect($amenities)
            ->flatMap(function ($item) {
                if (! is_string($item)) {
                    return [];
                }

                return preg_split('/\r\n|\r|\n|,/', $item) ?: [];
            })
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $permissions
     */
    private function assertTenantAdminHasAnyPermission(Request $request, array $permissions): void
    {
        $user = $request->user();
        $tenant = Tenant::current();

        if (! $tenant || ! $user || ! $user->isAdmin()) {
            return;
        }

        if ((int) ($user->tenant_id ?? 0) !== (int) $tenant->id) {
            return;
        }

        $allowed = collect($permissions)->contains(fn (string $permission): bool => $user->hasPermission($permission));
        if (! $allowed) {
            RbacCatalog::ensurePermissionsExist();
            RbacCatalog::ensureRolesAndGrantPermissions();
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user->syncRbacFromLegacyRole();
            $user->syncEffectiveTenantPermissions($tenant);
            $user->refresh();
            $allowed = collect($permissions)->contains(fn (string $permission): bool => $user->hasPermission($permission));
        }

        abort_unless($allowed, 403);
    }

    private function isApprovedMunicipalityListing(Accommodation $accommodation): bool
    {
        if (! $accommodation->is_available || ! $accommodation->is_verified) {
            return false;
        }

        $tenant = $accommodation->tenant;

        if (! $tenant instanceof Tenant) {
            return false;
        }

        return (string) $tenant->onboarding_status === Tenant::ONBOARDING_APPROVED
            && (bool) $tenant->database_provisioned
            && $this->matchesCentralMunicipalityDirectory($accommodation);
    }

    private function matchesCentralMunicipalityDirectory(Accommodation $accommodation): bool
    {
        $municipality = (string) config('portals.municipality_name', 'Impasug-ong');
        $term = strtolower(str_replace('-', '', $municipality));

        foreach (['address', 'barangay', 'description'] as $col) {
            $hay = strtolower((string) ($accommodation->{$col} ?? ''));
            if ($hay !== '' && (str_contains($hay, strtolower($municipality)) || str_contains($hay, $term))) {
                return true;
            }
        }

        return false;
    }
}
