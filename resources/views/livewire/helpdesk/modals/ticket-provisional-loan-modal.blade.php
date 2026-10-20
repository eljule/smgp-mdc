<!-- MODAL 5: EQUIPO PROVISIONAL EN TICKET                                   -->
<!-- ========================================================================= -->
@if ($isProvisionalModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 animate-scaleUp">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                    🔄 Préstamo de Equipo Provisional ITAM
                </h3>
                <button wire:click="$set('isProvisionalModalOpen', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form wire:submit.prevent="saveProvisionalLoan" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Equipo Informático de Reserva / Préstamo *</label>
                    <select wire:model="selectedProvisionalAssetId" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required>
                        @foreach ($assets->where('status', '!=', 'PRESTADO') as $ast)
                            <option value="{{ $ast->id }}">{{ $ast->computer_code }} - {{ $ast->name }} ({{ $ast->status }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Motivo / Observaciones del Préstamo</label>
                    <textarea wire:model="formProvisionalNotes" rows="3" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" wire:click="$set('isProvisionalModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-bold">
                        Asignar en Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
