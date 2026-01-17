<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TicketsByStatusChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('widget_TicketsByStatusChart') ?? false;
    }

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

    protected function getOptions(): \Filament\Support\RawJs
    {
        return \Filament\Support\RawJs::make(<<<JS
            {
                onClick: (event, elements, chart) => {
                    if (! elements.length) return;

                    const index = elements[0].index;
                    const label = chart.data.labels[index];
                    const statusMap = {
                        'To Do': 'todo',
                        'In Progress': 'in_progress',
                        'Done': 'done'
                    };
                    const status = statusMap[label];

                    if (status) {
                        const url = new URL('/admin/tickets', window.location.origin);
                        url.searchParams.set('tableFilters[status][values][0]', status);
                        window.location.href = url.toString();
                    }
                },
            }
        JS);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
