<div class="space-y-6 animate-fadeIn">
            <!-- Banner Superior -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Dashboard Ejecutivo de Indicadores TI (KPIs)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Semáforos operativos, alertas de inventario crítico, vencimiento de garantías y cumplimiento de retornos.
                </p>
            </div>

            <!-- 4 Tarjetas de Indicadores Principales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">DISPONIBILIDAD TI</span>
                    <div class="text-3xl font-black text-emerald-600 mt-1">
                        {{ $metrics['disponibilidad'] }}%
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Equipos en estado operativo</p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">INSUMOS CRÍTICOS</span>
                    <div class="text-3xl font-black mt-1 {{ $metrics['criticalSuppliesCount'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        {{ $metrics['criticalSuppliesCount'] }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Con stock menor a 2 unidades</p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">MANTENIMIENTOS PENDIENTES</span>
                    <div class="text-3xl font-black text-amber-600 mt-1">
                        {{ $metrics['pendingMaintenance'] }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">En taller o programados</p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">PRÉSTAMOS RETRASADOS</span>
                    <div class="text-3xl font-black mt-1 {{ $metrics['delayedLoans'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        {{ $metrics['delayedLoans'] }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Vencidos sin retorno</p>
                </div>
            </div>

            <!-- Gráficos de Distribución con Barras de Progreso -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Desglose por Categoría -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                        Distribución de Activos por Categoría
                    </h4>
                    <div class="space-y-3">
                        @foreach ($metrics['categories'] as $cat)
                            @php
                                $cnt = $cat->assets_count;
                                $pct = ($metrics['totalAssets'] > 0) ? round(($cnt / $metrics['totalAssets']) * 100) : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                        <span>{{ $cat->icon ?: '💻' }}</span>
                                        <span>{{ $cat->name }}</span>
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">
                                        {{ $cnt }} ({{ $pct }}%)
                                    </span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         style="width: {{ $pct }}%; background-color: {{ $cat->color ?? '#10b981' }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desglose por Estado -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                        Estado Operativo del Parque Informático
                    </h4>
                    <div class="space-y-3">
                        @foreach ($metrics['statuses'] as $st)
                            @php
                                $cnt = $st['count'];
                                $pct = ($metrics['totalAssets'] > 0) ? round(($cnt / $metrics['totalAssets']) * 100) : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">
                                        {{ $st['label'] }}
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">
                                        {{ $cnt }} ({{ $pct }}%)
                                    </span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         style="width: {{ $pct }}%; background-color: {{ $st['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
