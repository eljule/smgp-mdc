<!-- ========================================================================= -->
    <!-- MODAL 1: VINCULAR PERIFÉRICOS & COMPONENTES HIJOS (SRS)                   -->
    <!-- ========================================================================= -->
    @if ($isChildModalOpen && $parentForChildModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#0d4f2f] to-[#105d38] text-white">
                    <div>
                        <h3 class="text-base font-bold">Vincular Periféricos & Componentes Hijos (SRS)</h3>
                        <p class="text-xs text-emerald-200">
                            Equipo Principal: {{ $parentForChildModal->computer_code }} &bull; {{ $parentForChildModal->brand?->name }} {{ $parentForChildModal->model?->name }}
                        </p>
                    </div>
                    <button wire:click="$set('isChildModalOpen', false)" class="text-white/80 hover:text-white text-xl cursor-pointer">&times;</button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Componentes actualmente vinculados -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                            Periféricos Vinculados a este Equipo
                        </h4>
                        @if ($parentForChildModal->childAssets && $parentForChildModal->childAssets->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($parentForChildModal->childAssets as $child)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-lg">{{ $child->category?->icon ?? '🔗' }}</span>
                                            <div>
                                                <span class="font-mono font-bold text-xs text-slate-900 dark:text-white">{{ $child->computer_code }}</span>
                                                <span class="text-[11px] text-slate-500 block">
                                                    {{ $child->brand?->name }} {{ $child->model?->name }} &bull; Serie: {{ $child->serial_number ?: 'S/N' }}
                                                </span>
                                            </div>
                                        </div>
                                        <button type="button"
                                                wire:click="unlinkChildAsset({{ $child->id }})"
                                                class="px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                            Desvincular
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 py-3 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                                Este equipo no cuenta con periféricos ni componentes secundarios asociados.
                            </p>
                        @endif
                    </div>

                    <!-- Vincular Nuevo Componente -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Vincular Nuevo Periférico / Componente Operativo
                        </h4>
                        <div class="flex gap-2">
                            <select wire:model="selectedChildAssetId"
                                    class="flex-1 text-xs px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <option value="">Seleccione un equipo disponible para vincular...</option>
                                @foreach ($candidateChildAssets as $candidate)
                                    <option value="{{ $candidate->id }}">
                                        {{ $candidate->computer_code }} - {{ $candidate->category?->name }}: {{ $candidate->brand?->name }} {{ $candidate->model?->name }} (S/N: {{ $candidate->serial_number ?: 'S/N' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button"
                                    wire:click="linkChildAsset"
                                    class="px-4 py-2 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors cursor-pointer">
                                + Vincular
                            </button>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                    <button type="button"
                            wire:click="$set('isChildModalOpen', false)"
                            class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-300 transition-colors cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
