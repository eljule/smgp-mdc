<?php

declare(strict_types=1);

namespace App\Models\Helpdesk;

use App\Models\Itam\Asset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketLoan extends Model
{
    use HasFactory;

    protected $table = 'helpdesk_ticket_loans';

    protected $fillable = [
        'ticket_id',
        'temporary_asset_id',
        'temporary_asset_code',
        'temporary_asset_name',
        'damaged_asset_id',
        'damaged_asset_code',
        'status',
        'loan_date',
        'return_date',
        'notes',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function temporaryAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'temporary_asset_id');
    }

    public function damagedAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'damaged_asset_id');
    }
}
