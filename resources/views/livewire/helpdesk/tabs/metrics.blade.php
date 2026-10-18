<div class="space-y-6 animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">
                <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 block mb-1">
                    Indicadores de Rendimiento y Calidad de Servicio (RNF-01 / RF-13)
                </span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Métricas Operativas de Soporte Técnico
                </h2>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Evaluación de tiempos cronometrados de atención, tasa de resolución municipal y nivel de satisfacción de los usuarios.
                </p>
            </div>

            <!-- 4 KPIs Clave -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tiempo de Respuesta Promedio</span>
                    <div class="text-2xl font-black text-blue-600 dark:text-blue-400">
                        {{ $metrics['averageResponseTimeMinutes'] ?? 1 }} min
                    </div>
                    <p class="text-[11px] text-slate-500">Desde reporte hasta inicio de atención técnica</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tasa de Resolución</span>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        {{ $metrics['resolutionRatePercent'] ?? 33.3 }}%
                    </div>
                    <p class="text-[11px] text-slate-500">Casos resueltos con éxito por el equipo</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Satisfacción del Usuario</span>
                    <div class="text-2xl font-black text-amber-500">
                        ★ {{ $metrics['averageSatisfactionRating'] ?? 5 }} / 5.0
                    </div>
                    <p class="text-[11px] text-slate-500">Calificación en actas de conformidad</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tiempo Promedio de Solución</span>
                    <div class="text-2xl font-black text-purple-600 dark:text-purple-400">
                        {{ $metrics['averageResolutionTimeHours'] ?? 36 }} horas
                    </div>
                    <p class="text-[11px] text-slate-500">Resolución efectiva y cierre definitivo</p>
                </div>
            </div>

            <!-- Desglose por Categoría y Prioridad -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                        Distribución por Categoría de Falla
                    </h3>
                    <div class="space-y-2 text-xs">
                        @foreach (($metrics['byCategory'] ?? ['HARDWARE' => 4, 'SOFTWARE' => 0, 'RED_INTERNET' => 1, 'PERMISOS' => 1, 'OTROS' => 0]) as $cat => $count)
                            @php
                                $total = $metrics['totalTickets'] ?? 6;
                                $pct = $total > 0 ? ($count / $total) * 100 : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $cat }}</span>
                                    <span class="text-slate-500 font-mono font-bold">{{ $count }}</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full"
                                         style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                        Distribución por Nivel de Severidad (Prioridad)
                    </h3>
                    <div class="space-y-2 text-xs">
                        @foreach (($metrics['byPriority'] ?? ['BAJA' => 0, 'MEDIA' => 3, 'ALTA' => 2, 'CRITICA' => 1]) as $prio => $count)
                            @php
                                $total = $metrics['totalTickets'] ?? 6;
                                $pct = $total > 0 ? ($count / $total) * 100 : 0;
                                $barBg = match ($prio) {
                                    'CRITICA' => 'bg-red-600',
                                    'ALTA' => 'bg-amber-500',
                                    'MEDIA' => 'bg-blue-500',
                                    default => 'bg-slate-400',
                                };
                            @endphp
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $prio }}</span>
                                    <span class="text-slate-500 font-mono font-bold">{{ $count }}</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barBg }} rounded-full"
                                         style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
