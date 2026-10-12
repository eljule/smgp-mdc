<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Software extends Model
{
    use HasFactory;

    protected $table = 'itam_software';

    protected $fillable = [
        'name',
        'version',
        'developer',
        'license_type',
        'license_key',
        'total_licenses',
        'expiration_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'total_licenses' => 'integer',
        'is_active' => 'boolean',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'itam_asset_software', 'software_id', 'asset_id')
            ->withPivot('license_key_used', 'installed_at')
            ->withTimestamps();
    }
}
