<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UsersByRoleChart extends ChartWidget
{
    protected static ?int $sort = 3;

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

    protected function getType(): string
    {
        return 'doughnut';
    }
}
