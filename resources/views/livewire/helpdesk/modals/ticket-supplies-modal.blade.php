<!-- MODAL 4: INSUMOS DE ALMACÉN EN TICKET                                     -->
    <!-- ========================================================================= -->
    @if ($isSuppliesModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 animate-scaleUp">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        Consumo de Insumos / Repuestos ITAM
                    </h3>
                    <button wire:click="$set('isSuppliesModalOpen', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form wire:submit.prevent="saveTicketSupply" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Insumo del Almacén *</label>
                        <select wire:model="selectedSupplyId" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required>
                            @foreach ($supplies as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }} (Stock: {{ $sup->stock }} {{ $sup->unit }}s)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Cantidad Consumida *</label>
                        <input type="number" wire:model="formSupplyQuantity" min="1" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 font-bold" required>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Detalle de Instalación</label>
                        <input type="text" wire:model="formSupplyNotes" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isSuppliesModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold">
                            Descontar de Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
