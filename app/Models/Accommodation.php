<?php

namespace App\Models;

use App\Models\Concerns\UsesTenantConnectionForTenantData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accommodation extends Model
{
    use HasFactory;
    use UsesTenantConnectionForTenantData;

    protected $fillable = [
        'owner_id',
        'tenant_id',
        'name',
        'type',
        'description',
        'address',
        'barangay',
        'price_per_night',
        'price_per_day',
        'bedrooms',
        'bathrooms',
        'max_guests',
        'amenities',
        'images',
        'primary_image',
        'latitude',
        'longitude',
        'house_rules',
        'check_in_instructions',
        'rating',
        'total_reviews',
        'is_available',
        'is_verified',
        'is_featured',
        'available_from',
    ];

    protected $casts = [
        'amenities' => 'array',
        'images' => 'array',
        'price_per_night' => 'decimal:2',
        'price_per_day' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'available_from' => 'datetime',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        $query->where('is_available', true);

        // In tenant app context, owner/admin listings should be visible immediately.
        if (! Tenant::checkCurrent()) {
            $query->where('is_verified', true);
        }

        return $query;
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->available();
    }

    public function scopeInBarangay($query, $barangay)
    {
        return $query->where('barangay', 'like', '%'.$barangay.'%');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Central public directory: listings from approved, provisioned municipal hosts.
     */
    public function scopeForCentralMunicipalityDirectory($query)
    {
        $approvedTenantIds = Tenant::query()
            ->where('onboarding_status', Tenant::ONBOARDING_APPROVED)
            ->where('database_provisioned', true)
            ->pluck('id');

        return $query->whereIn('tenant_id', $approvedTenantIds);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '₱'.number_format($this->price_per_night, 0, '.', ',');
    }

    public function getPrimaryImageUrlAttribute()
    {
        if ($this->primary_image) {
            $url = $this->publicStorageAssetUrl($this->primary_image);
            if ($url !== null) {
                return $url;
            }
        }

        $images = $this->images;
        if (is_array($images) && count($images) > 0) {
            $url = $this->publicStorageAssetUrl((string) $images[0]);
            if ($url !== null) {
                return $url;
            }
        }

        return asset('COMMUNAL.jpg');
    }

    /**
     * Absolute URL for a file on the public disk (uploads). Handles full URLs,
     * legacy leading slashes, and optional "storage/" prefix so images resolve
     * on tenant hosts, HTTPS, and non-root APP_URL deployments.
     */
    public function publicStorageAssetUrl(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        $path = str_replace('\\', '/', trim((string) $path));

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return asset('storage/'.$path);
    }

    /**
     * Gallery paths resolved to URLs (for client/owner galleries).
     *
     * @return array<int, string>
     */
    public function galleryImageUrls(): array
    {
        $raw = is_array($this->images) ? $this->images : [];
        $urls = [];
        foreach ($raw as $item) {
            if (! is_string($item) || trim($item) === '') {
                continue;
            }
            $u = $this->publicStorageAssetUrl($item);
            if ($u !== null) {
                $urls[] = $u;
            }
        }

        if ($this->primary_image) {
            $primary = $this->publicStorageAssetUrl($this->primary_image);
            if ($primary !== null) {
                array_unshift($urls, $primary);
            }
        }

        return array_values(array_unique($urls));
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'traveller-inn' => 'Traveller-Inn',
            'airbnb' => 'Airbnb',
            'daily-rental' => 'Daily Rental',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    // Methods
    public function calculateTotalPrice($checkIn, $checkOut, $guests = 1)
    {
        $nights = $checkIn->diffInDays($checkOut);
        $basePrice = $this->price_per_night * $nights;

        // Add guest surcharge if超过max_guests
        $extraGuests = max(0, $guests - $this->max_guests);
        $guestSurcharge = $extraGuests * 200; // ₱200 per extra guest

        return $basePrice + $guestSurcharge;
    }

    public function isBooked($checkIn, $checkOut)
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'paid'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->exists();
    }
}
