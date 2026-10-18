<div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Órdenes de Mantenimiento Preventivo & Correctivo</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                            {{ $maintenanceOrders->count() }} Órdenes
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control de fallas, diagnósticos técnicos, descargo automático de insumos (RF-15) y emisión de hoja de servicio (RF-16).
                    </p>
                </div>
                <button type="button"
                        wire:click="openMaintenanceModal({{ $assets->first()?->id ?? 1 }})"
                        class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Programar Mantenimiento</span>
                </button>
            </div>

            <!-- Tabla de Órdenes -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">N° Orden</th>
                                <th class="py-3 px-4">Activo</th>
                                <th class="py-3 px-4">Tipo & Prioridad</th>
                                <th class="py-3 px-4">Diagnóstico / Acciones</th>
                                <th class="py-3 px-4">Técnico</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($maintenanceOrders as $ord)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-amber-700 dark:text-amber-400">
                                        {{ $ord->order_number }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 dark:text-white block">
                                            {{ $ord->asset?->computer_code ?? 'ACTIVO #' . $ord->asset_id }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $ord->asset?->brand?->name }} {{ $ord->asset?->model?->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            {{ $ord->type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 max-w-xs">
                                        <span class="text-slate-800 dark:text-slate-200 font-medium block truncate">
                                            {{ $ord->description }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block truncate">
                                            {{ $ord->observations ?: 'Sin observaciones adicionales' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700 dark:text-slate-300">
                                        {{ $ord->assigned_technician_name ?: 'Equipo Técnico ODT' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $ord->status === 'COMPLETADO' ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : 'bg-amber-100 text-amber-700 border-amber-300' }}">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        <button type="button"
                                                wire:click="openActaModal('MANTENIMIENTO', {{ $ord->asset_id }}, null, {{ $ord->id }})"
                                                class="px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                            📄 Hoja de Servicio
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="7" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">🛠️</span>
                                        No hay órdenes de mantenimiento en curso o completadas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
