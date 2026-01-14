<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->can('view_all_tickets')) {
            return $query;
        }

        $query->whereHas('project', function ($q) {
            $q->whereHas('users', function ($q2) {
                $q2->where('users.id', auth()->id());
            });
        });

        if (! auth()->user()?->can('view_all_tickets')) {
            $query->where('is_active', true);
        }

        return $query;
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('tickets'),
                Forms\Components\Select::make('status')
                    ->options(\App\Enums\TicketStatus::class)
                    ->required()
                    ->default(\App\Enums\TicketStatus::Todo),
                Forms\Components\Select::make('priority')
                    ->options(\App\Enums\TicketPriority::class)
                    ->required()
                    ->default(\App\Enums\TicketPriority::Medium),
                Forms\Components\Select::make('type')
                    ->options(\App\Enums\TicketType::class)
                    ->required()
                    ->default(\App\Enums\TicketType::Task),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('responsible', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Placeholder::make('assigned_to_avatar')
                    ->label('Current Assignee Avatar')
                    ->content(fn ($record) => $record?->responsible?->getFilamentAvatarUrl() ? new \Illuminate\Support\HtmlString('<img src="' . $record->responsible->getFilamentAvatarUrl() . '" class="w-10 h-10 rounded-full object-cover">') : 'No avatar')
                    ->visible(fn ($record) => $record?->responsible),
                Forms\Components\DatePicker::make('due_date')
                    ->native(false),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->visible(fn () => auth()->user()?->can('view_all_tickets')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->html()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable()
                    ->color(fn ($state, $record) => $state && \Illuminate\Support\Carbon::parse($state)->lt(now()->startOfDay()) && $record->status !== \App\Enums\TicketStatus::Done ? 'danger' : null),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
