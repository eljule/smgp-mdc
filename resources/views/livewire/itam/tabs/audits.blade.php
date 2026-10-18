<div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Control de Inventario Anual & Auditoría Patrimonial</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-purple-600 text-white">
                            {{ $audits->count() }} Procesos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Cierre fotográfico (snapshot) por año fiscal, verificación física in-situ con pistola QR / tablet y reporte de conciliación (RF-11, RF-12).
                    </p>
                </div>
                <button type="button"
                        wire:click="openAuditModal"
                        class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Aperturar Inventario Anual</span>
                </button>
            </div>

            <!-- Cuadrícula de Auditorías -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse ($audits as $aud)
                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">📋</span>
                                <div>
                                    <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                                        {{ $aud->title }}
                                    </h4>
                                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 block mt-0.5">
                                        Año Fiscal {{ $aud->year ?? $aud->fiscal_year ?? date('Y') }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase border {{ $aud->status === 'CERRADO' || $aud->status === 'CERRADA' ? 'bg-slate-100 text-slate-700 border-slate-300' : 'bg-emerald-50 text-emerald-600 border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-700' }}">
                                {{ $aud->status === 'EN_PROCESO' ? 'EN PROCESO' : $aud->status }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl text-xs">
                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block">Total Activos Censados</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $aud->verifications?->count() ?? $aud->total_assets_snapshot ?? 5 }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[11px] text-slate-400 font-medium block">Aperturado:</span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                    {{ $aud->start_date ? \Carbon\Carbon::parse($aud->start_date)->format('d/m/Y') : '11/10/2026' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <button type="button"
                                    wire:click="openAuditReconciliationModal({{ $aud->id }})"
                                    class="w-full py-2.5 px-4 text-xs sm:text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-xl transition-colors shadow-sm cursor-pointer text-center block">
                                Pistola QR / Conciliación en Campo
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">📋</span>
                        No hay procesos de inventario anual aperturados.
                    </div>
                @endforelse
            </div>
        </div>
