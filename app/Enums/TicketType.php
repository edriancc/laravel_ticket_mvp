<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TicketType: string implements HasLabel, HasColor, HasIcon
{
    case Bug = 'bug';
    case Feature = 'feature';
    case Enhancement = 'enhancement';
    case Task = 'task';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Bug => 'Bug',
            self::Feature => 'Feature',
            self::Enhancement => 'Enhancement',
            self::Task => 'Task',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Bug => 'danger',
            self::Feature => 'primary',
            self::Enhancement => 'success',
            self::Task => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Bug => 'heroicon-o-bug-ant',
            self::Feature => 'heroicon-o-sparkles',
            self::Enhancement => 'heroicon-o-arrow-trending-up',
            self::Task => 'heroicon-o-clipboard-document-list',
        };
    }
}
