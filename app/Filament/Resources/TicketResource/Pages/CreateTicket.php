<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function afterCreate(): void
    {
        $ticket = $this->record;

        if ($ticket->assigned_to) {
            $recipient = \App\Models\User::find($ticket->assigned_to);

            if ($recipient) {
                \Filament\Notifications\Notification::make()
                    ->title('You have been assigned a new ticket')
                    ->body("Ticket #{$ticket->id}: {$ticket->title}")
                    ->success()
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->url(TicketResource::getUrl('edit', ['record' => $ticket])),
                    ])
                    ->sendToDatabase($recipient);
            }
        }
    }
}
