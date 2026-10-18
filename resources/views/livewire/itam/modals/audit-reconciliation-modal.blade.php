<!-- ========================================================================= -->
    <!-- MODAL: AUDITORÍA & CONCILIACIÓN FÍSICA EN CAMPO (SRS RF-11, RF-12)        -->
    <!-- ========================================================================= -->
    @if ($isAuditReconciliationModalOpen && $selectedAuditForModal)
        @php
            $verifs = $selectedAuditForModal->verifications ?? collect();
            $totalExpected = $verifs->count();
            $conciliados = $verifs->where('status', 'CONCILIADO')->count();
            $traslados = $verifs->where('status', 'TRASLADADO_NO_AUTORIZADO')->count();
            $noHabidos = $verifs->whereIn('status', ['NO_HABIDO', 'FALTANTE'])->count();
            $verifiedTotal = $conciliados + $traslados;
            $progressPct = $totalExpected > 0 ? (int) round(($verifiedTotal / $totalExpected) * 100) : 0;

            $filteredVerifs = $verifs->filter(function ($v) use ($auditFilterStatus) {
                if ($auditFilterStatus === 'ALL') return true;
                if ($auditFilterStatus === 'PENDIENTE') return $v->status === 'PENDIENTE';
                if ($auditFilterStatus === 'CONCILIADO') return $v->status === 'CONCILIADO';
                if ($auditFilterStatus === 'TRASLADADO_NO_AUTORIZADO') return $v->status === 'TRASLADADO_NO_AUTORIZADO';
                if ($auditFilterStatus === 'NO_HABIDO') return in_array($v->status, ['NO_HABIDO', 'FALTANTE']);
                return true;
            });
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden max-h-[92vh] flex flex-col animate-scaleUp">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 bg-slate-900 text-white flex-shrink-0 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📋</span>
                        <div>
                            <h2 class="text-base font-bold text-white">
                                Auditoría & Conciliación Física: {{ $selectedAuditForModal->title }}
                            </h2>
                            <p class="text-xs text-slate-300">
                                Año Fiscal {{ $selectedAuditForModal->year ?? $selectedAuditForModal->fiscal_year ?? 2026 }} — Estado: {{ $selectedAuditForModal->status }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isAuditReconciliationModalOpen', false)" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto space-y-5 flex-1">
                    <!-- 5 KPI Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div class="bg-slate-50 border border-slate-200 dark:bg-slate-800/60 dark:border-slate-700 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">TOTAL ESPERADOS</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalExpected }}</p>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/30 dark:border-emerald-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">CONCILIADOS</p>
                            <p class="text-2xl font-black text-emerald-700 dark:text-emerald-300 mt-1">{{ $conciliados }}</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 dark:bg-amber-950/30 dark:border-amber-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider">TRASLADOS NO AUT.</p>
                            <p class="text-2xl font-black text-amber-700 dark:text-amber-300 mt-1">{{ $traslados }}</p>
                        </div>
                        <div class="bg-rose-50 border border-rose-200 dark:bg-rose-950/30 dark:border-rose-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-rose-700 dark:text-rose-300 uppercase tracking-wider">NO HABIDOS / FALT.</p>
                            <p class="text-2xl font-black text-rose-700 dark:text-rose-300 mt-1">{{ $noHabidos }}</p>
                        </div>
                        <div class="bg-indigo-50 border border-indigo-200 dark:bg-indigo-950/30 dark:border-indigo-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">PROGRESO FÍSICO</p>
                            <p class="text-2xl font-black text-indigo-700 dark:text-indigo-300 mt-1">{{ $progressPct }}%</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Avance de Verificación Física en Campo</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $progressPct }}% completado</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-teal-500 to-indigo-600 rounded-full transition-all duration-500"
                                 style="width: {{ $progressPct }}%"></div>
                        </div>
                    </div>

                    <!-- Quick Scanner Box (Tablet / QR / Barcode) -->
                    @if ($selectedAuditForModal->status === 'EN_PROCESO')
                        <div class="p-4 bg-slate-900 text-white rounded-2xl shadow-inner space-y-3 border border-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-base">📱</span>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">
                                        LEVANTAMIENTO RÁPIDO EN CAMPO (TABLET / LECTOR DE CÓDIGO DE BARRAS / QR)
                                    </h4>
                                </div>
                                <span class="text-[11px] text-slate-400">RNF-04 Optimizado para tablets</span>
                            </div>

                            <form wire:submit.prevent="registerAuditVerification" class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 items-center">
                                <input
                                    type="text"
                                    placeholder="Escanear Código (MDC-TI-PC-0001)"
                                    wire:model="auditScanCode"
                                    class="px-3 py-2 text-xs bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-indigo-400 font-mono focus:outline-none"
                                />
                                <input
                                    type="text"
                                    placeholder="Oficina donde fue hallado..."
                                    wire:model="auditFoundOffice"
                                    class="px-3 py-2 text-xs bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                />
                                <select
                                    wire:model="auditVerificationStatus"
                                    class="px-3 py-2 text-xs bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                >
                                    <option value="CONCILIADO">Hallado en su Oficina (Conciliado)</option>
                                    <option value="TRASLADADO_NO_AUTORIZADO">Trasladado sin Autorización</option>
                                    <option value="NO_HABIDO">No Habido / Faltante</option>
                                </select>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition cursor-pointer text-center"
                                >
                                    Registrar Conciliación
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Detail of Assets and Filter -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                DETALLE DE ACTIVOS DEL SNAPSHOT ({{ $filteredVerifs->count() }})
                            </h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-slate-500">Filtrar:</span>
                                <select
                                    wire:model.live="auditFilterStatus"
                                    class="text-xs border border-slate-300 dark:border-slate-700 rounded-lg px-2.5 py-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none"
                                >
                                    <option value="ALL">Todos los Registros</option>
                                    <option value="PENDIENTE">Pendientes de Verificación</option>
                                    <option value="CONCILIADO">Conciliados (Conformes)</option>
                                    <option value="TRASLADADO_NO_AUTORIZADO">Traslados No Autorizados</option>
                                    <option value="NO_HABIDO">No Habidos / Faltantes</option>
                                </select>
                            </div>
                        </div>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden max-h-72 overflow-y-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 sticky top-0 font-semibold border-b border-slate-200 dark:border-slate-700">
                                    <tr>
                                        <th class="p-2.5">Código Informático</th>
                                        <th class="p-2.5">Patrimonial</th>
                                        <th class="p-2.5">Equipo / Modelo</th>
                                        <th class="p-2.5">Oficina Esperada</th>
                                        <th class="p-2.5">Oficina Hallada</th>
                                        <th class="p-2.5">Estado Conciliación</th>
                                        <th class="p-2.5 text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse ($filteredVerifs as $v)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="p-2.5 font-mono font-bold text-indigo-700 dark:text-indigo-400">
                                                {{ $v->asset?->computer_code }}
                                            </td>
                                            <td class="p-2.5 font-mono text-slate-500">
                                                {{ $v->asset?->patrimonial_code ?: '-' }}
                                            </td>
                                            <td class="p-2.5 text-slate-600 dark:text-slate-400">
                                                -
                                            </td>
                                            <td class="p-2.5 text-slate-700 dark:text-slate-300 font-medium">
                                                {{ $v->expected_office ?: 'Sin Oficina' }}
                                            </td>
                                            <td class="p-2.5 font-semibold text-slate-900 dark:text-white">
                                                {{ $v->found_office ?: '-' }}
                                            </td>
                                            <td class="p-2.5">
                                                @if ($v->status === 'CONCILIADO')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                        CONCILIADO
                                                    </span>
                                                @elseif ($v->status === 'TRASLADADO_NO_AUTORIZADO')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                                        TRASLADADO NO AUT.
                                                    </span>
                                                @elseif (in_array($v->status, ['NO_HABIDO', 'FALTANTE']))
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                                                        NO HABIDO
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                        {{ $v->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-2.5 text-right">
                                                @if ($selectedAuditForModal->status === 'EN_PROCESO')
                                                    <button
                                                        type="button"
                                                        wire:click="quickVerifyAsset({{ $v->id }})"
                                                        class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-[11px] font-semibold cursor-pointer transition"
                                                    >
                                                        Verificar
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-6 text-center text-slate-400">
                                                No hay activos que coincidan con el filtro seleccionado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0">
                    <div>
                        @if ($selectedAuditForModal->status === 'EN_PROCESO')
                            <button
                                type="button"
                                wire:click="closeAudit({{ $selectedAuditForModal->id }})"
                                wire:confirm="¿Está seguro de cerrar el inventario anual definitivo? No se podrán realizar más verificaciones."
                                class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow transition cursor-pointer flex items-center gap-1.5"
                            >
                                🔒 Cerrar Inventario Anual Definitivo
                            </button>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            wire:click="$set('isAuditReconciliationModalOpen', false)"
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition cursor-pointer"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
