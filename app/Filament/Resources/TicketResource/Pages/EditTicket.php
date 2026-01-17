<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function afterSave(): void
    {
        $ticket = $this->record;

        // Notify if assigned user changed/is set (always notify for now to test)
        if ($ticket->wasChanged('assigned_to') && $ticket->assigned_to) {
            \Filament\Notifications\Notification::make()
                ->title('You have been assigned a ticket')
                ->body("Ticket #{$ticket->id}: {$ticket->title}")
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->url(TicketResource::getUrl('edit', ['record' => $ticket])),
                ])
                ->sendToDatabase($ticket->responsible);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
