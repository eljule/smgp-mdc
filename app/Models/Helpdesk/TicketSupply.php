<?php

declare(strict_types=1);

namespace App\Models\Helpdesk;

use App\Models\Itam\Supply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketSupply extends Model
{
    use HasFactory;

    protected $table = 'helpdesk_ticket_supplies';

    protected $fillable = [
        'ticket_id',
        'supply_id',
        'supply_code',
        'supply_name',
        'quantity',
        'unit',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'float',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class, 'supply_id');
    }
}
