<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    use HasFactory;

    protected $table = 'itam_asset_categories';

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'color',
        'custom_fields_schema',
        'is_active',
    ];

    protected $casts = [
        'custom_fields_schema' => 'array',
        'is_active' => 'boolean',
    ];

    public function getCustomFieldsAttribute(): ?array
    {
        return $this->custom_fields_schema;
    }

    public function setCustomFieldsAttribute($value): void
    {
        $this->attributes['custom_fields_schema'] = is_array($value) ? json_encode($value) : $value;
    }

    public function models(): HasMany
    {
        return $this->hasMany(AssetModel::class, 'category_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'category_id');
    }
}
