<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('widget_StatsOverview') ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', \App\Models\Project::count())
                ->description('Active projects in the system')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('Total Tickets', \App\Models\Ticket::count())
                ->description('All tickets created')
                ->descriptionIcon('heroicon-m-ticket'),

            Stat::make('Open Tickets', \App\Models\Ticket::where('status', '!=', \App\Enums\TicketStatus::Done)->count())
                ->description('Tickets pending resolution')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Critical Tickets', \App\Models\Ticket::where('priority', \App\Enums\TicketPriority::Critical)->where('status', '!=', \App\Enums\TicketStatus::Done)->count())
                ->description('Urgent attention required')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
