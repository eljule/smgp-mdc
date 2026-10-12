<?php

declare(strict_types=1);

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketTechnicalDetail extends Model
{
    use HasFactory;

    protected $table = 'helpdesk_ticket_technical_details';

    protected $fillable = [
        'ticket_id',
        'confirmed_category',
        'real_diagnosis',
        'solution_applied',
        'pause_reason',
        'is_definitive_decommission',
        'decommission_act_number',
        'decommission_reason',
        'decommission_destination',
    ];

    protected $casts = [
        'is_definitive_decommission' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
