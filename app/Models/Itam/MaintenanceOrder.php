<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceOrder extends Model
{
    use HasFactory;

    protected $table = 'itam_maintenance_orders';

    protected $fillable = [
        'code',
        'order_number',
        'asset_id',
        'type',
        'status',
        'priority',
        'scheduled_date',
        'completed_date',
        'technician_name',
        'diagnosis',
        'activities_performed',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'total_cost' => 'float',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function suppliesUsed(): HasMany
    {
        return $this->hasMany(MaintenanceSupply::class, 'maintenance_order_id');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->diagnosis ?? $this->activities_performed ?? ($this->attributes['description'] ?? null);
    }

    public function getObservationsAttribute(): ?string
    {
        return $this->notes ?? ($this->attributes['observations'] ?? null);
    }

    public function getAssignedTechnicianNameAttribute(): ?string
    {
        return $this->technician_name ?? ($this->attributes['assigned_technician_name'] ?? 'Equipo Técnico ODT');
    }
}
