<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetBrand extends Model
{
    use HasFactory;

    protected $table = 'itam_asset_brands';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(AssetModel::class, 'brand_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'brand_id');
    }
}
