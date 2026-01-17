<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyAssignedTickets extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Ticket::query()
                    ->where('assigned_to', auth()->id())
                    ->where('status', '!=', \App\Enums\TicketStatus::Done)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state && \Illuminate\Support\Carbon::parse($state)->lt(now()->startOfDay()) ? 'danger' : null),
            ])
            ->paginated(false)
            ->defaultSort('due_date', 'asc');
    }
}
