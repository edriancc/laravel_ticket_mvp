<x-filament-panels::page>
    <div class="mb-4">
        {{ $this->form }}
    </div>

    <div class="flex flex-col md:flex-row gap-4 overflow-x-auto h-full">
        @foreach($statuses as $status)
            <div class="flex-1 bg-gray-100 dark:bg-gray-900 rounded-lg p-4 min-w-[300px]"
                 ondrop="drop(event, '{{ $status->value }}')"
                 ondragover="allowDrop(event)">
                
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ match($status->value) { 'todo' => 'gray', 'in_progress' => 'orange', 'done' => 'green' } }};"></span>
                    {{ $status->getLabel() }}
                </h3>

                <div class="space-y-3 min-h-[500px]">
                    @foreach($tickets->where('status', $status) as $ticket)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow cursor-move border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
                             draggable="true"
                             ondragstart="drag(event, {{ $ticket->id }})">
                            
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex gap-1">
                                    <span class="px-2 py-1 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full dark:text-indigo-300 dark:bg-indigo-900">
                                        {{ Str::upper(Str::limit($ticket->project->name, 3, '')) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
                                          style="
                                            {{ match($ticket->priority?->value) {
                                                'low' => 'background-color: #e5e7eb; color: #1f2937;', // gray-200, gray-800
                                                'medium' => 'background-color: #dbeafe; color: #1e40af;', // blue-100, blue-800
                                                'high' => 'background-color: #fef3c7; color: #92400e;', // amber-100, amber-800
                                                'critical' => 'background-color: #fee2e2; color: #991b1b;', // red-100, red-800
                                                default => ''
                                            } }}
                                          ">
                                        {{ $ticket->priority?->getLabel() ?? 'Medium' }}
                                    </span>
                                    
                                    @if($ticket->type)
                                        <span class="px-2 py-1 text-xs font-bold rounded-full flex items-center gap-1"
                                              style="background-color: rgba(var(--{{ $ticket->type->getColor() }}-500), 0.1); color: rgb(var(--{{ $ticket->type->getColor() }}-600));">
                                            @svg($ticket->type->getIcon(), 'w-3 h-3')
                                            {{ $ticket->type->getLabel() }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400">#{{ $ticket->id }}</span>
                            </div>

                            <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ $ticket->title }}</h4>
                            


                            @if($ticket->due_date)
                                <div class="flex items-center gap-1 mb-2 text-xs font-medium {{ $ticket->due_date < now()->startOfDay() && $ticket->status->value !== 'done' ? 'text-danger-600 dark:text-danger-400 font-bold' : 'text-gray-500 dark:text-gray-400' }}">
                                    <!-- Heroicon: calendar -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                      <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $ticket->due_date->format('M d, Y') }}
                                </div>
                            @endif

                             <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    @if($ticket->responsible)
                                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold" title="{{ $ticket->responsible->name }}">
                                            {{ Str::substr($ticket->responsible->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $ticket->responsible->name }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Unassigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function drag(ev, ticketId) {
            ev.dataTransfer.setData("text/plain", ticketId);
        }

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drop(ev, status) {
            ev.preventDefault();
            var ticketId = ev.dataTransfer.getData("text/plain");
            @this.updateTicketStatus(ticketId, status);
        }
    </script>
</x-filament-panels::page>
