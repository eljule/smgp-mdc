<?php

declare(strict_types=1);

namespace App\Models\Itam;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class AssetLoan extends Model
{
    use HasFactory;

    protected $table = 'itam_asset_loans';

    protected $fillable = [
        'loan_code',
        'loan_number',
        'requesting_office',
        'requesting_person_id',
        'borrower_name',
        'start_date',
        'expected_return_date',
        'actual_return_date',
        'event_reason',
        'status',
        'return_condition',
        'technician_name',
        'notes',
    ];

    public function requestingPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'requesting_person_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AssetLoanItem::class, 'loan_id');
    }

    public function asset(): HasOneThrough
    {
        return $this->hasOneThrough(Asset::class, AssetLoanItem::class, 'loan_id', 'id', 'id', 'asset_id');
    }

    public function getApplicantOfficeNameAttribute(): ?string
    {
        return $this->requesting_office;
    }

    public function getApplicantPersonNameAttribute(): ?string
    {
        return $this->borrower_name ?? ($this->requestingPerson ? $this->requestingPerson->full_name : null);
    }

    public function getLoanDateAttribute(): ?string
    {
        return $this->start_date;
    }

    public function getRequestingNameAttribute(): ?string
    {
        return $this->applicant_person_name;
    }

    public function getDepartmentAttribute(): ?string
    {
        return $this->applicant_office_name;
    }
}
