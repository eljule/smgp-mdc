<!-- MODAL 6: REASIGNAR / TRASPASAR TICKET A TÉCNICO (RF-13)                  -->
    <!-- ========================================================================= -->
    @if ($isReassignModalOpen)
        @php
            $currTicket = \App\Models\Helpdesk\Ticket::find($reassignTicketId);
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 animate-scaleUp border border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 flex items-center justify-center font-bold text-sm">
                            ⇄
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                                Traspasar / Reasignar Ticket
                            </h3>
                            <p class="text-[11px] text-slate-400">
                                Ticket #{{ $currTicket?->ticket_number ?? $reassignTicketId }} — Rebalanceo de Carga ODT
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('isReassignModalOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg cursor-pointer">&times;</button>
                </div>

                @if ($currTicket)
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200/80 dark:border-slate-700/60 space-y-1 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Asunto:</span>
                            <span class="text-slate-500 font-mono text-[11px]">{{ $currTicket->status }}</span>
                        </div>
                        <p class="text-slate-800 dark:text-slate-200 font-medium truncate">{{ $currTicket->subject }}</p>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 border-t border-slate-200/50 dark:border-slate-700/50">
                            <span>Técnico actual: <strong class="text-slate-700 dark:text-slate-300">{{ $currTicket->assigned_technician_display ?: ($currTicket->assigned_technician_name ?: 'Sin asignar') }}</strong></span>
                            <span>Oficina: <strong>{{ $currTicket->office?->name ?? 'MDC' }}</strong></span>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="saveReassign" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Seleccionar Nuevo Técnico Destino *
                        </label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            @forelse ($operationalTechnicians as $opTech)
                                <label class="flex items-center justify-between p-2.5 rounded-xl border transition-all cursor-pointer {{ $reassignTechnicianName === $opTech['name'] ? 'border-indigo-600 bg-indigo-50/60 dark:bg-indigo-950/40 dark:border-indigo-500' : 'border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/40' }}">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio"
                                               name="reassignTechnicianChoice"
                                               value="{{ $opTech['name'] }}"
                                               wire:model.live="reassignTechnicianName"
                                               class="text-indigo-600 focus:ring-indigo-500">
                                        <div>
                                            <span class="font-bold text-slate-800 dark:text-slate-100 block">{{ $opTech['name'] }}</span>
                                            <span class="text-[10px] text-slate-400">Soporte Técnico Informático</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $opTech['status'] === 'DISPONIBLE' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : ($opTech['status'] === 'OCUPADO' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300') }}">
                                            {{ $opTech['activeTickets'] }} activos • {{ $opTech['status'] }}
                                        </span>
                                    </div>
                                </label>
                            @empty
                                <div class="text-slate-400 italic p-3 text-center">No hay otros técnicos registrados.</div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Motivo / Causa del Traspaso *</label>
                        <textarea wire:model="reassignNotes" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200" placeholder="Ej: Especialidad en redes, técnico actual en salida a campo, etc." required></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isReassignModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition-colors shadow-xs flex items-center gap-1.5 cursor-pointer">
                            <span>⇄</span>
                            <span>Confirmar Traspaso</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
