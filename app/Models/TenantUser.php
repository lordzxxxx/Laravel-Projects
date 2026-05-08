<?php

namespace App\Models;

use App\Models\Concerns\UsesTenantConnectionWithLandlordFallback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantUser extends Model
{
    use UsesTenantConnectionWithLandlordFallback;

    protected $fillable = [
        'tenant_id',
        'user_id',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

