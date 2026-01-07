<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum TicketStatus: string implements HasLabel, HasColor
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Todo => 'To Do',
            self::InProgress => 'In Progress',
            self::Done => 'Done',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Todo => 'gray',
            self::InProgress => 'warning',
            self::Done => 'success',
        };
    }
}
