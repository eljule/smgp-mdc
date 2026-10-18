<div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Stock de Insumos & Consumibles TI</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-cyan-100 dark:bg-cyan-900/40 text-cyan-800 dark:text-cyan-300">
                            {{ $supplies->count() }} Artículos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control de tóners por impresora, bobinas y patch cords de cable de red, conectores RJ-45 y pasta térmica (RF-14).
                    </p>
                </div>
                <button type="button"
                        wire:click="openSupplyModal"
                        class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Nuevo Insumo</span>
                </button>
            </div>

            <!-- Banner de Stock Crítico -->
            @if ($criticalSupplies->isNotEmpty())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <h4 class="text-xs font-bold text-rose-800 dark:text-rose-200 uppercase tracking-wider">
                                Alerta de Abastecimiento Crítico
                            </h4>
                            <p class="text-xs text-rose-700 dark:text-rose-300">
                                Hay {{ $criticalSupplies->count() }} insumos con stock igual o inferior a 2 unidades (umbral mínimo SRS).
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-rose-700 dark:text-rose-300 px-3 py-1 bg-white dark:bg-slate-900 rounded-xl border border-rose-300">
                        Atención Inmediata
                    </span>
                </div>
            @endif

            <!-- Cuadrícula de Insumos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($supplies as $sup)
                    @php $isCritical = $sup->stock <= $sup->min_stock; @endphp
                    <div class="p-5 rounded-2xl border bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between space-y-4 {{ $isCritical ? 'border-rose-300 dark:border-rose-800/80 ring-1 ring-rose-400/20' : 'border-slate-200 dark:border-slate-800' }}">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ match($sup->category) {
                                        'TONER_TINTA' => 'Tóner / Impresión',
                                        'CABLEADO_RED' => 'Cableado de Red',
                                        'CONECTORES' => 'Conectores RJ-45',
                                        'PASTA_TERMICA' => 'Pasta Térmica',
                                        'REPUESTOS_HARDWARE' => 'Hardware / Repuesto',
                                        'HERRAMIENTAS' => 'Herramientas TI',
                                        default => $sup->category ?: 'Consumible'
                                    } }}
                                </span>
                                @if ($isCritical)
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400 border border-rose-300">
                                        Stock Crítico
                                    </span>
                                @endif
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">
                                {{ $sup->name }}
                            </h4>
                            <p class="text-[11px] text-slate-400">
                                📍 {{ $sup->location ?: 'Almacén ODT' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block font-semibold">Stock Actual</span>
                                <span class="text-xl font-black {{ $isCritical ? 'text-rose-600' : 'text-slate-900 dark:text-white' }}">
                                    {{ $sup->stock }} <span class="text-xs font-normal text-slate-400">{{ $sup->unit ?: 'UND' }}</span>
                                </span>
                            </div>
                            <button type="button"
                                    wire:click="openSupplyModal({{ $sup->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold text-cyan-700 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-950/40 hover:bg-cyan-100 rounded-xl transition-colors cursor-pointer">
                                Ajustar Stock
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">📦</span>
                        No hay insumos registrados en el almacén TI.
                    </div>
                @endforelse
            </div>
        </div>
