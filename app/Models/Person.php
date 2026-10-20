<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Itam\Asset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    use HasFactory;

    protected $table = 'core_people';

    protected $fillable = [
        'type',
        'document_type',
        'document_number',
        'first_name',
        'paternal_surname',
        'maternal_surname',
        'email',
        'phone',
        'address',
        'position',
        'office',
        'labor_condition',
        'entry_date',
        'business_name',
        'allows_online_access',
        'labor_status',
        'departure_date',
        'cessation_reason',
        'is_active',
    ];

    protected $casts = [
        'allows_online_access' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'person_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'assigned_person_id');
    }

            public function getFullNameAttribute(): string
    {
        $fullName = trim("{$this->first_name} {$this->paternal_surname} {$this->maternal_surname}");
        if (empty($fullName) && ! empty($this->business_name)) {
            return $this->business_name;
        }

        return $fullName;
    }
}