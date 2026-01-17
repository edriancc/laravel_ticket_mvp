<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

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
                ->descriptionIcon('heroicon-m-ticket')
                ->url(\App\Filament\Resources\TicketResource::getUrl()),

            Stat::make('Open Tickets', \App\Models\Ticket::where('status', '!=', \App\Enums\TicketStatus::Done)->count())
                ->description('Tickets pending resolution')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(\App\Filament\Resources\TicketResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'values' => [
                                \App\Enums\TicketStatus::Todo->value,
                                \App\Enums\TicketStatus::InProgress->value
                            ]
                        ]
                    ]
                ])),

            Stat::make('Critical Tickets', \App\Models\Ticket::where('priority', \App\Enums\TicketPriority::Critical)->where('status', '!=', \App\Enums\TicketStatus::Done)->count())
                ->description('Urgent attention required')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->url(\App\Filament\Resources\TicketResource::getUrl('index', [
                    'tableFilters' => [
                        'priority' => [
                            'values' => [\App\Enums\TicketPriority::Critical->value]
                        ],
                        'status' => [
                            'values' => [
                                \App\Enums\TicketStatus::Todo->value,
                                \App\Enums\TicketStatus::InProgress->value
                            ]
                        ]
                    ]
                ])),
        ];
    }
}
