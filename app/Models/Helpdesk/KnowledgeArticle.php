<?php

declare(strict_types=1);

namespace App\Models\Helpdesk;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeArticle extends Model
{
    use HasFactory;

    protected $table = 'helpdesk_knowledge_articles';

    protected $fillable = [
        'title',
        'category',
        'summary',
        'symptoms',
        'solution_steps',
        'tags',
        'source_ticket_id',
        'source_ticket_number',
        'author_technician_id',
        'author_technician_name',
        'views_count',
        'helpful_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'views_count' => 'integer',
        'helpful_count' => 'integer',
    ];

    public function sourceTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'source_ticket_id');
    }

    public function authorTechnician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_technician_id');
    }
}
