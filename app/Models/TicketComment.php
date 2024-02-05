<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TicketComment extends Pivot
{
    use HasUuids;

    protected $table = 'ticket_comments';

    protected $fillable = [
        'comment',
        'commented_by',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function commentedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commented_by');
    }
}
