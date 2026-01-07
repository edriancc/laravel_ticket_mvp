<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TicketsByStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected static ?string $heading = 'Tickets by Status';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => [
                        \App\Models\Ticket::where('status', \App\Enums\TicketStatus::Todo)->count(),
                        \App\Models\Ticket::where('status', \App\Enums\TicketStatus::InProgress)->count(),
                        \App\Models\Ticket::where('status', \App\Enums\TicketStatus::Done)->count(),
                    ],
                    'backgroundColor' => [
                        '#94a3b8', // Todo (slate-400)
                        '#f59e0b', // In Progress (amber-500)
                        '#10b981', // Done (emerald-500)
                    ],
                ],
            ],
            'labels' => ['To Do', 'In Progress', 'Done'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
