<?php

declare(strict_types=1);

namespace App\Models\Itam;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMovement extends Model
{
    use HasFactory;

    protected $table = 'itam_asset_movements';

    protected $fillable = [
        'acta_number',
        'asset_id',
        'from_office',
        'to_office',
        'from_person_id',
        'to_person_id',
        'movement_date',
        'movement_type',
        'reason',
        'technician_name',
        'notes',
        'include_children_in_transfer',
    ];

    protected $casts = [
        'include_children_in_transfer' => 'boolean',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function fromPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'from_person_id');
    }

    public function toPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'to_person_id');
    }

    public function getOriginOfficeNameAttribute(): ?string
    {
        return $this->from_office;
    }

    public function getDestinationOfficeNameAttribute(): ?string
    {
        return $this->to_office;
    }

    public function getDestinationPersonNameAttribute(): ?string
    {
        return $this->toPerson ? $this->toPerson->full_name : ($this->attributes['destination_person_name'] ?? null);
    }

    public function getAuthorizedByUserNameAttribute(): ?string
    {
        return $this->technician_name ?? 'Soporte TI';
    }
}
