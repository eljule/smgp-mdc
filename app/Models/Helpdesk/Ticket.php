<?php

declare(strict_types=1);

namespace App\Models\Helpdesk;

use App\Models\Itam\Asset;
use App\Models\Office;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'helpdesk_tickets';

    protected $fillable = [
        'ticket_number',
        'applicant_id',
        'applicant_name',
        'applicant_phone',
        'applicant_email',
        'office_id',
        'office_name',
        'asset_id',
        'asset_computer_code',
        'asset_category',
        'category',
        'priority',
        'subject',
        'description',
        'evidence_photo_url',
        'status',
        'assigned_technician_id',
        'assigned_technician_name',
        'assigned_technician_phone',
        'started_at',
        'resolved_at',
        'closed_at',
        'user_conformity',
        'user_rating',
        'user_feedback',
        'is_locked',
        'locked_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'user_conformity' => 'boolean',
        'user_rating' => 'integer',
        'is_locked' => 'boolean',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'applicant_id');
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assignedTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function technicalDetail(): HasOne
    {
        return $this->hasOne(TicketTechnicalDetail::class, 'ticket_id');
    }

    public function supplies(): HasMany
    {
        return $this->hasMany(TicketSupply::class, 'ticket_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(TicketLoan::class, 'ticket_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(TicketAuditLog::class, 'ticket_id');
    }

    public function histories(): HasMany
    {
        return $this->auditLogs();
    }
}
