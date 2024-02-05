<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case PENDING = 'pending';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::CLOSED => 'Closed',
            self::PENDING => 'Pending',
        };
    }
}
