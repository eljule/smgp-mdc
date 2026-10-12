<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditVerification extends Model
{
    use HasFactory;

    protected $table = 'itam_audit_verifications';

    protected $fillable = [
        'audit_id',
        'asset_id',
        'expected_office',
        'found_office',
        'status',
        'verified_by',
        'notes',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(InventoryAudit::class, 'audit_id');
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
