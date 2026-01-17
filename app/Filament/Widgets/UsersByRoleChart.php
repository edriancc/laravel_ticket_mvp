<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UsersByRoleChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('widget_UsersByRoleChart') ?? false;
    }

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected static ?string $heading = 'Users by Role';

    protected function getData(): array
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $labels = [];
        $data = [];
        $colors = [
            '#6366f1', // Indigo
            '#ec4899', // Pink
            '#22c55e', // Green
            '#eab308', // Yellow
            '#f97316', // Orange
        ];

        foreach ($roles as $role) {
            $labels[] = $role->name;
            $data[] = \App\Models\User::role($role->name)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($roles)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): \Filament\Support\RawJs
    {
        $roles = \Spatie\Permission\Models\Role::all()->pluck('id', 'name');
        
        // Manually build JS object with single quotes to avoid conflict with HTML attributes (double quotes)
        $jsObject = '{';
        foreach ($roles as $name => $id) {
            $jsObject .= "'{$name}': '{$id}',";
        }
        $jsObject .= '}';

        return \Filament\Support\RawJs::make(<<<JS
            {
                onClick: (event, elements, chart) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const label = chart.data.labels[index];
                        const roleMap = $jsObject;
                        const roleId = roleMap[label];

                        if (roleId) {
                            const url = new URL('/admin/users', window.location.origin);
                            url.searchParams.set('tableFilters[roles][value]', roleId);
                            window.location.href = url.toString();
                        }
                    }
                }
            }
        JS);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
