<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Project;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use Illuminate\Support\Collection;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;

class KanbanBoard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Kanban Board';
    protected static ?string $title = 'Project Kanban';
    protected static string $view = 'filament.pages.kanban-board';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('page_KanbanBoard') ?? false;
    }

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill();
        
        $projects = $this->getProjects();
        if ($projects->isNotEmpty()) {
            $this->form->fill([
                'project_ids' => $projects->pluck('id')->toArray(),
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_ids')
                    ->label('Select Projects')
                    ->multiple()
                    ->options($this->getProjects()->pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(function () {
                        // Just trigger re-render
                    }),
            ])
            ->statePath('data');
    }

    public function getProjects(): Collection
    {
        if (auth()->user()->can('view_all_projects')) {
            return Project::all();
        }
        return auth()->user()->projects;
    }

    public function getViewData(): array
    {
        $statuses = TicketStatus::cases();
        
        $tickets = collect();
        
        $selectedProjectIds = $this->data['project_ids'] ?? [];

        if (!empty($selectedProjectIds)) {
            $query = Ticket::with(['project', 'responsible'])
                ->whereIn('project_id', $selectedProjectIds);
            
             // Apply access control for non-admins (active only)
            if (! auth()->user()->can('view_all_tickets')) {
                 $query->where('is_active', true);
            }
            
            $tickets = $query->get();
        }

        return [
            'statuses' => $statuses,
            'tickets' => $tickets,
        ];
    }

    public function updateTicketStatus($ticketId, $status)
    {
        $ticket = Ticket::find($ticketId);
        
        // Authorization check
        if (! $ticket) return;
        
        if (! auth()->user()->can('view_all_tickets')) {
            // Check if user belongs to project
            if (! auth()->user()->projects->contains($ticket->project_id)) {
                 return;
            }
        }

        $ticket->status = TicketStatus::tryFrom($status);
        $ticket->save();
        
        // Notification
        \Filament\Notifications\Notification::make()
            ->title('Status updated')
            ->success()
            ->send();
    }
}
