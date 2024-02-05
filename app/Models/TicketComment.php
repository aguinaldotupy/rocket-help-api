<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TicketComment extends Pivot
{
    use HasUuids;

    protected $table = 'ticket_comments';

    protected $fillable = [
        'comment',
        'commented_by',
    ];
}
