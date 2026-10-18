<div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Trazabilidad de Movimientos & Reasignaciones</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                            {{ $movements->count() }} Registros
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Historial auditable de transferencias entre dependencias, asignaciones iniciales y actas formales en PDF (RF-10).
                    </p>
                </div>
                <button type="button"
                        wire:click="openMovementModal()"
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Registrar Traslado / Asignación</span>
                </button>
            </div>

            <!-- Tabla de Movimientos -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">N° Acta Oficial</th>
                                <th class="py-3 px-4">Activo Informático</th>
                                <th class="py-3 px-4">Tipo Movimiento</th>
                                <th class="py-3 px-4">Origen ➔ Destino</th>
                                <th class="py-3 px-4">Técnico & Custodio</th>
                                <th class="py-3 px-4">Fecha</th>
                                <th class="py-3 px-4 text-right">Documento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($movements as $mov)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-emerald-700 dark:text-emerald-400">
                                        {{ $mov->acta_number ?: 'ACTA-' . $mov->id }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 dark:text-white block">
                                            {{ $mov->asset?->computer_code ?? 'ACTIVO #' . $mov->asset_id }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $mov->asset?->brand?->name }} {{ $mov->asset?->model?->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                            {{ $mov->movement_type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-slate-400 line-through text-[11px] block">
                                            {{ $mov->origin_office_name ?: 'Almacén Central' }}
                                        </span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 block">
                                            ➔ {{ $mov->destination_office_name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-semibold text-slate-800 dark:text-slate-200 block">
                                            {{ $mov->destination_person_name ?: 'Por Asignar' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">
                                            Autorizado: {{ $mov->authorized_by_user_name ?: 'Administrador SIGM' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                        {{ $mov->movement_date ? \Carbon\Carbon::parse($mov->movement_date)->format('d/m/Y') : $mov->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <button type="button"
                                                wire:click="openActaModal('TRANSFERENCIA', {{ $mov->asset_id }}, {{ $mov->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                            <span>📄</span>
                                            <span>Ver Acta PDF</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="7" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">🔄</span>
                                        No hay movimientos registrados. Use el botón "Trasladar" en cualquier activo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
