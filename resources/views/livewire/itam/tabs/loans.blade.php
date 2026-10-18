<div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Cesión Temporal & Reservas de Equipos</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300">
                            {{ $loans->count() }} Préstamos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control de cañones multimedia, laptops de contingencia y parlantes prestados a dependencias municipales con alertas de retraso (RF-19).
                    </p>
                </div>
                <button type="button"
                        wire:click="openLoanModal"
                        class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Registrar Préstamo / Reserva</span>
                </button>
            </div>

            <!-- Alerta de Retrasos -->
            @php
                $delayedCount = $loans->where('status', 'ACTIVO')->filter(fn ($l) => $l->expected_return_date && \Carbon\Carbon::parse($l->expected_return_date)->isPast())->count();
            @endphp
            @if ($delayedCount > 0)
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⏰</span>
                        <div>
                            <h4 class="text-xs font-bold text-amber-800 dark:text-amber-200 uppercase tracking-wider">
                                Alertas de Devolución Retrasada
                            </h4>
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                Hay {{ $delayedCount }} préstamos que han sobrepasado la fecha fin estimada de retorno.
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-amber-800 dark:text-amber-300 px-3 py-1 bg-white dark:bg-slate-900 rounded-xl border border-amber-300">
                        Notificar a Dependencia
                    </span>
                </div>
            @endif

            <!-- Tabla de Préstamos -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">N° Préstamo</th>
                                <th class="py-3 px-4">Dependencia & Solicitante</th>
                                <th class="py-3 px-4">Equipos Cedidos</th>
                                <th class="py-3 px-4">Período</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($loans as $ln)
                                @php
                                    $isDelayed = $ln->status === 'ACTIVO' && $ln->expected_return_date && \Carbon\Carbon::parse($ln->expected_return_date)->isPast();
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-indigo-700 dark:text-indigo-400">
                                        {{ $ln->loan_number }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 dark:text-white block">
                                            {{ $ln->applicant_office_name }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            👤 {{ $ln->applicant_person_name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 block">
                                            {{ $ln->asset?->computer_code ?? 'Equipo Institucional' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $ln->asset?->brand?->name }} {{ $ln->asset?->model?->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-mono text-[11px]">
                                        <span class="block text-slate-600 dark:text-slate-300">
                                            {{ $ln->loan_date ? \Carbon\Carbon::parse($ln->loan_date)->format('d/m/Y') : '' }}
                                        </span>
                                        <span class="text-[10px] {{ $isDelayed ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                                            Retorno: {{ $ln->expected_return_date ? \Carbon\Carbon::parse($ln->expected_return_date)->format('d/m/Y') : 'Indefinido' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $ln->status === 'ACTIVO' ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-emerald-100 text-emerald-700 border-emerald-300' }}">
                                                {{ $ln->status }}
                                            </span>
                                            @if ($isDelayed)
                                                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-rose-500 text-white animate-pulse">
                                                    DEMORADO
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        @if ($ln->status === 'ACTIVO')
                                            <button type="button"
                                                    wire:click="returnLoan({{ $ln->id }})"
                                                    class="px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 rounded-lg transition-colors cursor-pointer">
                                                ✓ Registrar Retorno
                                            </button>
                                        @endif
                                        <button type="button"
                                                wire:click="openActaModal('PRESTAMO', {{ $ln->asset_id ?? 1 }}, null, null, {{ $ln->id }})"
                                                class="px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                            📄 Acta
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="6" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">⏱️</span>
                                        No hay préstamos activos o registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
