<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supply extends Model
{
    use HasFactory;

    protected $table = 'itam_supplies';

    protected $fillable = [
        'code',
        'name',
        'category',
        'stock',
        'min_stock',
        'unit',
        'unit_cost',
        'location',
        'compatible_models',
        'notes',
    ];

    protected $casts = [
        'stock' => 'integer',
        'min_stock' => 'integer',
        'unit_cost' => 'float',
    ];

    public function maintenanceSupplies(): HasMany
    {
        return $this->hasMany(MaintenanceSupply::class, 'supply_id');
    }
}
