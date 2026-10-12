<?php

declare(strict_types=1);

namespace App\Models\Itam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryAudit extends Model
{
    use HasFactory;

    protected $table = 'itam_inventory_audits';

    protected $fillable = [
        'year',
        'title',
        'status',
        'start_date',
        'close_date',
        'snapshot_data',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'snapshot_data' => 'array',
    ];

    public function verifications(): HasMany
    {
        return $this->hasMany(AuditVerification::class, 'audit_id');
    }
}
