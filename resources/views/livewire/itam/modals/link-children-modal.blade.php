<!-- ========================================================================= -->
<!-- MODAL 1: VINCULAR PERIFÉRICOS & COMPONENTES HIJOS (SRS)                   -->
<!-- ========================================================================= -->
@if ($isChildModalOpen && $parentForChildModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6">
        <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp flex flex-col max-h-[90vh]">
            <!-- Header con Gradiente Municipal -->
            <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#146b40] text-white shrink-0 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-xl shadow-inner border border-white/20 shrink-0">
                        🔗
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-bold tracking-tight text-white">Vincular Periféricos & Componentes Hijos</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-emerald-500/30 text-emerald-100 border border-emerald-400/30">SRS</span>
                        </div>
                        <p class="text-xs text-emerald-100/90 flex items-center gap-1.5 mt-0.5">
                            <span class="font-semibold text-white">Equipo Principal:</span>
                            <span class="font-mono bg-black/25 px-2 py-0.5 rounded text-[11px] font-bold text-emerald-200">{{ $parentForChildModal->computer_code }}</span>
                            <span>&bull;</span>
                            <span>{{ $parentForChildModal->brand?->name }} {{ $parentForChildModal->model?->name }}</span>
                        </p>
                    </div>
                </div>
                <button type="button"
                        wire:click="$set('isChildModalOpen', false)"
                        class="text-white/70 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors cursor-pointer text-xl leading-none"
                        title="Cerrar modal">&times;</button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="p-6 space-y-6 overflow-y-auto flex-1">
                <!-- Sección 1: Componentes actualmente vinculados -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Periféricos Vinculados a este Equipo
                            </h4>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($parentForChildModal->childAssets && $parentForChildModal->childAssets->isNotEmpty()) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                {{ $parentForChildModal->childAssets ? $parentForChildModal->childAssets->count() : 0 }} vinculados
                            </span>
                        </div>
                        @if ($parentForChildModal->childAssets && $parentForChildModal->childAssets->isNotEmpty())
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 hidden sm:inline">Mismo custodio y oficina asignados</span>
                        @endif
                    </div>

                    @if ($parentForChildModal->childAssets && $parentForChildModal->childAssets->isNotEmpty())
                        <div class="max-h-60 overflow-y-auto pr-1 space-y-2">
                            @foreach ($parentForChildModal->childAssets as $child)
                                <div class="group flex items-center justify-between p-3 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 hover:border-emerald-300 dark:hover:border-emerald-600/40 hover:shadow-2xs transition-all duration-150">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-lg bg-white dark:bg-slate-700/80 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-lg shadow-2xs shrink-0 group-hover:scale-105 transition-transform">
                                            {{ $child->category?->icon ?? '🔗' }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-xs text-slate-900 dark:text-white tracking-tight">{{ $child->computer_code }}</span>
                                                @if ($child->category)
                                                    <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 bg-slate-200/70 dark:bg-slate-700/60 px-1.5 py-0.5 rounded">
                                                        {{ $child->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $child->brand?->name }} {{ $child->model?->name }}</span>
                                                <span class="text-slate-400 dark:text-slate-500">&bull;</span>
                                                <span>Serie: <span class="font-mono text-slate-600 dark:text-slate-300">{{ $child->serial_number ?: 'S/N' }}</span></span>
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            wire:click="unlinkChildAsset({{ $child->id }})"
                                            wire:loading.attr="disabled"
                                            class="shrink-0 ml-3 inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:text-white hover:bg-rose-600 dark:hover:bg-rose-600/90 rounded-lg transition-colors cursor-pointer border border-rose-200 dark:border-rose-900/40 hover:border-transparent">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Desvincular</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 px-4 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-2 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Este equipo no cuenta con periféricos asociados</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Usa el buscador inferior para asociar mouse, teclado, monitor, estabilizador, etc.</p>
                        </div>
                    @endif
                </div>

                <!-- Sección 2: Vincular Nuevo Componente con Datalist -->
                <div class="pt-5 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Vincular Nuevo Periférico / Componente Operativo
                        </h4>
                        <span class="text-[11px] text-slate-400 hidden sm:inline">Equipos operativos sin equipo padre</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center">
                        <div class="relative flex-1 min-w-0">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text"
                                   list="candidate-child-assets-datalist"
                                   wire:model.live.debounce.250ms="selectedChildAssetInput"
                                   wire:keydown.enter.prevent="linkChildAsset"
                                   placeholder="Buscar por código (ej. MDC-TI-...), categoría, modelo o serie..."
                                   class="w-full text-xs pl-10 pr-8 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none shadow-2xs">
                            @if (!empty($selectedChildAssetInput))
                                <button type="button"
                                        wire:click="$set('selectedChildAssetInput', '')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer text-xs"
                                        title="Limpiar campo">
                                    ✕
                                </button>
                            @endif
                            <datalist id="candidate-child-assets-datalist">
                                @foreach ($candidateChildAssets as $candidate)
                                    <option value="[{{ $candidate->computer_code }}] {{ $candidate->category?->name }}: {{ $candidate->brand?->name }} {{ $candidate->model?->name }} (S/N: {{ $candidate->serial_number ?: 'S/N' }})"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <button type="button"
                                wire:click="linkChildAsset"
                                wire:loading.attr="disabled"
                                class="shrink-0 px-4 py-2.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer disabled:opacity-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>+ Vincular</span>
                        </button>
                    </div>

                    @if ($selectedChildAssetId)
                        @php
                            $resolvedCandidate = $candidateChildAssets->firstWhere('id', $selectedChildAssetId);
                        @endphp
                        @if ($resolvedCandidate)
                            <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200 text-xs animate-fadeIn">
                                <span class="text-base shrink-0">{{ $resolvedCandidate->category?->icon ?? '✅' }}</span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold font-mono text-emerald-900 dark:text-emerald-100">{{ $resolvedCandidate->computer_code }}</span>
                                        <span class="text-[10px] bg-emerald-200/80 dark:bg-emerald-900/60 text-emerald-900 dark:text-emerald-200 px-1.5 py-0.5 rounded font-medium">
                                            {{ $resolvedCandidate->category?->name }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-emerald-700 dark:text-emerald-300 truncate mt-0.5">
                                        {{ $resolvedCandidate->brand?->name }} {{ $resolvedCandidate->model?->name }} &bull; Serie: {{ $resolvedCandidate->serial_number ?: 'S/N' }}
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold bg-emerald-600 text-white px-2 py-0.5 rounded-full shrink-0">
                                    Listo para asociar
                                </span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3.5 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between shrink-0">
                <span class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:inline">
                    Al vincular, el periférico adoptará automáticamente la oficina y custodio del equipo principal.
                </span>
                <button type="button"
                        wire:click="$set('isChildModalOpen', false)"
                        class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors cursor-pointer ml-auto">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endif