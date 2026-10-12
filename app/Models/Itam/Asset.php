<?php

declare(strict_types=1);

namespace App\Models\Itam;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'itam_assets';

    protected $fillable = [
        'computer_code',
        'patrimonial_code',
        'serial_number',
        'category_id',
        'brand_id',
        'model_id',
        'parent_asset_id',
        'color',
        'status',
        'physical_condition',
        'office',
        'assigned_person_id',
        'acquisition_date',
        'supplier',
        'warranty_end_date',
        'is_loanable',
        'specifications',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_loanable' => 'boolean',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(AssetBrand::class, 'brand_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(AssetModel::class, 'model_id');
    }

    public function parentAsset(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_asset_id');
    }

    public function childAssets(): HasMany
    {
        return $this->hasMany(self::class, 'parent_asset_id');
    }

    public function assignedPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'assigned_person_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(AssetMovement::class, 'asset_id');
    }

    public function maintenanceOrders(): HasMany
    {
        return $this->hasMany(MaintenanceOrder::class, 'asset_id');
    }

    public function software(): BelongsToMany
    {
        return $this->belongsToMany(Software::class, 'itam_asset_software', 'asset_id', 'software_id')
            ->withPivot('license_key_used', 'installed_at')
            ->withTimestamps();
    }
}
