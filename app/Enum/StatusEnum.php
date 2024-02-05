<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel, HasColor, HasIcon
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::OPEN => 'warning',
            self::CLOSED => 'success',
            self::PENDING => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CLOSED => 'heroicon-o-check-circle',
            self::OPEN => 'heroicon-o-minus-circle',
            self::PENDING => 'heroicon-o-exclamation-circle',
        };
    }
}
