<!-- MODAL 6.1: TRASPASO MASIVO DE CARGA TÉCNICA (RF-13 CONTINGENCIAS)        -->
    <!-- ========================================================================= -->
    @if ($isBulkTransferModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 animate-scaleUp border border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center font-bold text-sm">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                                Traspaso Masivo de Carga Técnica
                            </h3>
                            <p class="text-[11px] text-slate-400">
                                Contingencias operativas y descansos médicos (RF-13)
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('isBulkTransferModalOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg cursor-pointer">&times;</button>
                </div>

                <div class="p-3.5 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200/80 dark:border-amber-800/50 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-amber-900 dark:text-amber-200">Técnico de Origen:</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-200/80 dark:bg-amber-900 text-amber-900 dark:text-amber-100">
                            {{ $bulkTicketsCount }} tickets activos
                        </span>
                    </div>
                    <p class="text-sm font-black text-slate-900 dark:text-white">
                        {{ $bulkOriginTechnician }}
                    </p>
                    <p class="text-[11px] text-amber-800 dark:text-amber-300 leading-relaxed">
                        Todos los tickets en estado pendiente o en atención asignados a este técnico serán traspasados para evitar la paralización del servicio municipal.
                    </p>
                </div>

                <form wire:submit.prevent="executeBulkTransfer" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Modo de Distribución de Carga *
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="p-3 rounded-xl border cursor-pointer transition-all flex flex-col justify-between {{ $bulkDestinationMode === 'auto' ? 'border-indigo-600 bg-indigo-50/70 dark:bg-indigo-950/40 dark:border-indigo-500' : 'border-slate-200 dark:border-slate-700' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <input type="radio" value="auto" wire:model.live="bulkDestinationMode" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="font-bold text-slate-800 dark:text-slate-100">⚖️ Auto-Balance</span>
                                </div>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                    Distribución equitativa proporcional entre técnicos con menor carga.
                                </span>
                            </label>

                            <label class="p-3 rounded-xl border cursor-pointer transition-all flex flex-col justify-between {{ $bulkDestinationMode === 'specific' ? 'border-indigo-600 bg-indigo-50/70 dark:bg-indigo-950/40 dark:border-indigo-500' : 'border-slate-200 dark:border-slate-700' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <input type="radio" value="specific" wire:model.live="bulkDestinationMode" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="font-bold text-slate-800 dark:text-slate-100">👤 Técnico Específico</span>
                                </div>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                    Asignar el 100% de los tickets a un único técnico de relevo.
                                </span>
                            </label>
                        </div>
                    </div>

                    @if ($bulkDestinationMode === 'specific')
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Seleccionar Técnico Reemplazante *
                            </label>
                            <select wire:model="bulkTargetTechnician" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200" required>
                                @foreach ($this->getOperationalTechnicians($bulkOriginTechnician) as $tech)
                                    <option value="{{ $tech['name'] }}">
                                        {{ $tech['name'] }} ({{ $tech['activeTickets'] }} tickets activos • {{ $tech['status'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Motivo de la Contingencia *
                        </label>
                        <select wire:model="bulkReason" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 mb-2">
                            <option value="Descanso médico / Licencia de salud">Descanso médico / Licencia de salud</option>
                            <option value="Comisión de servicio fuera de sede">Comisión de servicio fuera de sede</option>
                            <option value="Permiso oficial o vacaciones">Permiso oficial o vacaciones</option>
                            <option value="Reorganización operativa de la jefatura ODT">Reorganización operativa de la jefatura ODT</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isBulkTransferModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold transition-colors shadow-xs flex items-center gap-1.5 cursor-pointer">
                            <span>⇄</span>
                            <span>Ejecutar Traspaso de Carga</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
