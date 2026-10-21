<!-- ========================================================================= -->
<!-- MODAL 5: EQUIPO PROVISIONAL EN TICKET                                   -->
<!-- ========================================================================= -->
@if ($isProvisionalModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 animate-scaleUp">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🔄</span>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">
                            Préstamo de Equipo Provisional ITAM
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            Asignar equipo de reserva temporal para este ticket
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="$set('isProvisionalModalOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg cursor-pointer">&times;</button>
            </div>

            @php
                $availableLoanAssets = isset($loanableAssets) ? $loanableAssets : $assets->where('is_loanable', true)->where('status', 'OPERATIVO');
            @endphp

            <form wire:submit.prevent="saveProvisionalLoan" class="space-y-4 text-xs">
                @if ($availableLoanAssets->isEmpty())
                    <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-900 dark:text-amber-200 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-xs text-amber-800 dark:text-amber-300">
                            <span>⚠️</span>
                            <span>No hay equipos disponibles para préstamo</span>
                        </div>
                        <p class="text-[11px] leading-relaxed text-amber-700 dark:text-amber-400">
                            Todos los equipos destinados a préstamo están actualmente cedidos o no se han marcado activos como aptos para préstamo (<span class="font-mono font-semibold">is_loanable</span>) en el catálogo de ITAM.
                        </p>
                    </div>
                @else
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                            Equipo Informático de Reserva / Préstamo <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="selectedProvisionalAssetId"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                required>
                            <option value="">-- Seleccione un equipo disponible --</option>
                            @foreach ($availableLoanAssets as $ast)
                                <option value="{{ $ast->id }}">
                                    {{ $ast->computer_code }} - {{ $ast->brand?->name }} {{ $ast->model?->name }} ({{ $ast->category?->name ?? 'Equipo TI' }})
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-slate-400 mt-1 block">
                            Solo se muestran equipos operativos marcados como disponibles para préstamo.
                        </span>
                    </div>
                @endif

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Motivo / Observaciones del Préstamo
                    </label>
                    <textarea wire:model="formProvisionalNotes"
                              rows="3"
                              placeholder="Ej. Se entrega laptop de contingencia mientras se repara la PC averiada..."
                              class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                            wire:click="$set('isProvisionalModalOpen', false)"
                            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold transition cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                            @if ($availableLoanAssets->isEmpty()) disabled @endif
                            class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold transition shadow-xs cursor-pointer flex items-center gap-1.5">
                        <span>🔄</span>
                        <span>Asignar en Préstamo</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
