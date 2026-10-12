<?php

declare(strict_types=1);

namespace App\Models\Helpdesk;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAuditLog extends Model
{
    use HasFactory;

    protected $table = 'helpdesk_ticket_audit_logs';

    protected $fillable = [
        'ticket_id',
        'previous_status',
        'new_status',
        'action',
        'performed_by_user_id',
        'performed_by_user_name',
        'notes',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function performedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_user_id');
    }
}
