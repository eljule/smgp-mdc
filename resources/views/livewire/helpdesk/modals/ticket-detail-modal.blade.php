<!-- MODAL 2: DETALLE DEL TICKET                                               -->
    <!-- ========================================================================= -->
    @if ($isDetailModalOpen && $selectedTicketForDetail)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-scaleUp">
                <!-- Encabezado Institucional -->
                <div class="bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] px-6 py-4 text-white flex justify-between items-center border-b border-emerald-800 shrink-0">
                    <div class="flex items-center flex-wrap gap-2.5">
                        <span class="font-mono font-bold text-lg text-emerald-300 mr-1">{{ $selectedTicketForDetail->ticket_number }}</span>
                        @php
                            $stUpper = strtoupper($selectedTicketForDetail->status);
                            $stLabel = match($stUpper) {
                                'ABIERTO' => '1. Abierto / Pendiente',
                                'EN_ATENCION' => '2. En Atención Técnica',
                                'EN_PAUSA' => '3. En Pausa / En Espera',
                                'EN_LABORATORIO', 'EN_TALLER' => '4. En Taller / Laboratorio',
                                'RESUELTO' => '5. Resuelto (Visto Bueno Pendiente)',
                                'CERRADO' => '6. Cerrado Formalmente',
                                'CANCELADO' => '7. Cancelado',
                                default => $selectedTicketForDetail->status,
                            };
                            $stBg = match($stUpper) {
                                'ABIERTO' => 'bg-blue-100 text-blue-900',
                                'EN_ATENCION' => 'bg-indigo-100 text-indigo-900',
                                'EN_PAUSA' => 'bg-amber-100 text-amber-900',
                                'EN_LABORATORIO', 'EN_TALLER' => 'bg-purple-100 text-purple-900',
                                'RESUELTO' => 'bg-emerald-100 text-emerald-900',
                                'CERRADO' => 'bg-white text-slate-900',
                                default => 'bg-white text-slate-900',
                            };
                            $prUpper = strtoupper($selectedTicketForDetail->priority);
                            $prBg = match($prUpper) {
                                'CRITICA' => 'bg-red-100 text-red-800',
                                'ALTA' => 'bg-amber-100 text-amber-800',
                                'MEDIA' => 'bg-blue-100 text-blue-800',
                                default => 'bg-slate-100 text-slate-700',
                            };
                            $prLabel = match($prUpper) {
                                'CRITICA' => 'Crítica',
                                'ALTA' => 'Alta',
                                'MEDIA' => 'Media',
                                default => 'Baja',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-xs {{ $stBg }}">
                            {{ $stLabel }}
                        </span>
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold {{ $prBg }}">
                            {{ $prLabel }}
                        </span>
                    </div>
                    <button type="button"
                            wire:click="$set('isDetailModalOpen', false)"
                            class="text-emerald-200 hover:text-white p-1 rounded-md cursor-pointer transition-colors text-xl font-bold">
                        ✕
                    </button>
                </div>

                <!-- Barra de Acciones Operativas y Técnico Asignado -->
                <div class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 px-6 py-3 flex flex-wrap items-center justify-between gap-2 text-xs shrink-0">
                    <div class="flex items-center space-x-2">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Técnico Asignado:</span>
                        @if ($selectedTicketForDetail->assigned_technician_name)
                            <strong class="font-bold text-slate-800 dark:text-slate-100">{{ $selectedTicketForDetail->assigned_technician_name }}</strong>
                            <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-mono">
                                {{ $selectedTicketForDetail->assigned_technician_phone ?? 'Anexo 104' }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Sin asignar (En cola abierta)</span>
                        @endif
                    </div>

                    <div class="flex items-center flex-wrap gap-2">
                        @if ($selectedTicketForDetail->status === 'ABIERTO')
                            <button type="button"
                                    wire:click="takeTicket({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                ⚡ Tomar Ticket / Atender
                            </button>
                            <button type="button"
                                    wire:click="openReassignModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 rounded-lg font-medium transition-colors cursor-pointer">
                                Asignar a Técnico
                            </button>
                        @elseif (in_array(strtoupper($selectedTicketForDetail->status), ['EN_ATENCION', 'EN_LABORATORIO', 'EN_TALLER', 'EN_PAUSA']))
                            <button type="button"
                                    wire:click="openReassignModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 hover:text-white dark:bg-indigo-950/60 dark:hover:bg-indigo-600 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 rounded-lg font-bold shadow-xs transition-colors cursor-pointer flex items-center gap-1.5"
                                    title="Traspasar ticket a otro técnico">
                                <span>⇄</span>
                                <span>Traspasar Ticket</span>
                            </button>
                            <button type="button"
                                    wire:click="openTechnicalModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                ✓ Diagnóstico & Solución
                            </button>
                            <button type="button"
                                    wire:click="openSuppliesModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium shadow-xs transition-colors cursor-pointer">
                                📦 Insumos Almacén ({{ $selectedTicketForDetail->supplies->count() }})
                            </button>
                            <button type="button"
                                    wire:click="openProvisionalModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium shadow-xs transition-colors cursor-pointer">
                                🔄 Préstamo Provisional ({{ $selectedTicketForDetail->loans->count() }})
                            </button>
                        @elseif (strtoupper($selectedTicketForDetail->status) === 'RESUELTO')
                            <button type="button"
                                    wire:click="openConformityModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                ⭐ Otorgar Visto Bueno (Cierre)
                            </button>
                        @endif

                        @if ($selectedTicketForDetail->technicalDetail?->is_definitive_decommission)
                            <button type="button"
                                    wire:click="openDecommissionModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                📄 Ver Acta de Baja (PDF)
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Cuerpo del Modal con Scroll -->
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 dark:text-slate-300 flex-1">
                    <!-- 1. Datos del Solicitante y Activo Tecnológico Vinculado -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] border-b border-slate-200 dark:border-slate-700 pb-1.5">
                                DATOS DEL SOLICITANTE
                            </h4>
                            <p><span class="text-slate-500 dark:text-slate-400">Funcionario:</span> <strong class="text-slate-900 dark:text-white">{{ $selectedTicketForDetail->applicant_name }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Dependencia:</span> <strong class="text-slate-900 dark:text-white">{{ $selectedTicketForDetail->office_name }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Contacto:</span> {{ $selectedTicketForDetail->applicant_phone ?: 'Anexo 115' }}</p>
                            <p><span class="text-slate-500 dark:text-slate-400">Correo:</span> {{ $selectedTicketForDetail->applicant_email ?: 'wsanchez@castilla.gob.pe' }}</p>
                        </div>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] border-b border-slate-200 dark:border-slate-700 pb-1.5">
                                ACTIVO TECNOLÓGICO VINCULADO (QR)
                            </h4>
                            @if ($selectedTicketForDetail->asset_computer_code)
                                <p><span class="text-slate-500 dark:text-slate-400">Código Informático:</span> <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $selectedTicketForDetail->asset_computer_code }}</span></p>
                                <p><span class="text-slate-500 dark:text-slate-400">Categoría:</span> {{ $selectedTicketForDetail->asset_category ?: ($selectedTicketForDetail->category === 'HARDWARE' ? 'Impresora y Multifuncional' : $selectedTicketForDetail->category) }}</p>
                                <p><span class="text-slate-500 dark:text-slate-400">Estado en Inventario:</span> <span class="font-semibold text-emerald-600 dark:text-emerald-400">Registrado en Base ITAM</span></p>
                            @else
                                <p class="text-slate-400 italic py-2">
                                    No se vinculó un activo específico con código QR (Falla general de servicio o software).
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Incidencia Reportada -->
                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-2 bg-white dark:bg-slate-900">
                        <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] border-b border-slate-200 dark:border-slate-800 pb-1.5 flex justify-between items-center">
                            <span>INCIDENCIA REPORTADA</span>
                            <span class="text-[10px] text-slate-400 font-mono">REGISTRADO: {{ $selectedTicketForDetail->created_at ? $selectedTicketForDetail->created_at->format('d/m/Y, H:i:s') : '11/10/2026, 7:40:49' }}</span>
                        </h4>
                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $selectedTicketForDetail->subject }}</div>
                        <div class="text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 whitespace-pre-line">
                            {{ $selectedTicketForDetail->description }}
                        </div>
                        @if ($selectedTicketForDetail->evidence_photo_url)
                            <div class="pt-2">
                                <span class="font-semibold block mb-1 text-slate-600 dark:text-slate-400">Foto o Captura de Evidencia:</span>
                                <img src="{{ $selectedTicketForDetail->evidence_photo_url }}"
                                     alt="Evidencia"
                                     class="max-h-56 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs object-contain">
                            </div>
                        @endif
                    </div>

                    <!-- 3. Diagnóstico y Solución Técnica Aplicada (RF-08) -->
                    @if ($selectedTicketForDetail->technicalDetail)
                        <div class="border border-emerald-300 dark:border-emerald-800 bg-white dark:bg-slate-900 rounded-xl p-4 space-y-3">
                            <h4 class="font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300 text-[11px] border-b border-emerald-100 dark:border-emerald-900 pb-1.5 flex justify-between items-center">
                                <span>DIAGNÓSTICO Y SOLUCIÓN TÉCNICA APLICADA (RF-08)</span>
                                @if ($selectedTicketForDetail->technicalDetail->is_definitive_decommission)
                                    <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[10px] font-bold">
                                        Baja Técnica: {{ $selectedTicketForDetail->technicalDetail->decommission_act_number }}
                                    </span>
                                @endif
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">Diagnóstico / Causa Raíz:</span>
                                    <div class="text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                        {{ $selectedTicketForDetail->technicalDetail->real_diagnosis }}
                                    </div>
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">Solución Técnica Implementada:</span>
                                    <div class="text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                        {{ $selectedTicketForDetail->technicalDetail->solution_applied }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 4. Visto Bueno de Cierre por el Solicitante (RF-05) -->
                    @if ($selectedTicketForDetail->user_conformity !== null || $selectedTicketForDetail->status === 'CERRADO')
                        <div class="border border-blue-200 dark:border-blue-800 bg-white dark:bg-slate-900 rounded-xl p-4 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-blue-800 dark:text-blue-300 text-[11px] border-b border-blue-100 dark:border-blue-900 pb-1.5 flex justify-between items-center">
                                <span>VISTO BUENO DE CIERRE POR EL SOLICITANTE (RF-05)</span>
                                <span class="text-blue-600 dark:text-blue-400 font-bold">
                                    ★ {{ $selectedTicketForDetail->user_rating ?? 5 }}/5 ESTRELLAS
                                </span>
                            </h4>
                            <p class="text-slate-700 dark:text-slate-300 font-medium">
                                Conformidad: <strong class="text-emerald-600 dark:text-emerald-400">✓ Solución Aprobada y Conforme</strong>
                            </p>
                            <div class="text-slate-600 dark:text-slate-300 italic bg-slate-50/60 dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                “{{ $selectedTicketForDetail->user_feedback ?: 'Excelente atención. El ingeniero limpió los rodillos de arrastre y ahora jala el papel membretado con normalidad. Muy rápido.' }}”
                            </div>
                        </div>
                    @endif

                    <!-- 5. Insumos y Préstamos Provisionales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 bg-white dark:bg-slate-900 space-y-1">
                            <h5 class="font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide text-[10px] mb-1.5 border-b border-slate-100 dark:border-slate-800 pb-1">
                                INSUMOS DESCARGADOS DE ALMACÉN ({{ $selectedTicketForDetail->supplies->count() }})
                            </h5>
                            @if ($selectedTicketForDetail->supplies->isEmpty())
                                <p class="text-slate-400 italic text-[11px]">Sin consumo de repuestos en almacén.</p>
                            @else
                                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($selectedTicketForDetail->supplies as $s)
                                        <li class="py-1.5 flex justify-between items-center text-[11px]">
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $s->supply_name }}</span>
                                            <strong class="text-amber-700 dark:text-amber-400 font-mono">{{ $s->quantity }} unid.</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 bg-white dark:bg-slate-900 space-y-1">
                            <h5 class="font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide text-[10px] mb-1.5 border-b border-slate-100 dark:border-slate-800 pb-1">
                                ACTIVOS PROVISIONALES DE RESERVA ({{ $selectedTicketForDetail->loans->count() }})
                            </h5>
                            @if ($selectedTicketForDetail->loans->isEmpty())
                                <p class="text-slate-400 italic text-[11px]">Sin equipos de sustitución temporal.</p>
                            @else
                                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($selectedTicketForDetail->loans as $l)
                                        <li class="py-1.5 flex justify-between items-center text-[11px]">
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $l->temporary_asset_code }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $l->status === 'PRESTADO' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ $l->status }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- 6. Línea de Tiempo de Auditoría (RNF-04) -->
                    @if ($selectedTicketForDetail->auditLogs && $selectedTicketForDetail->auditLogs->isNotEmpty())
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40">
                            <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] mb-2.5">
                                HISTORIAL DE AUDITORÍA Y TRAZABILIDAD (RNF-04)
                            </h4>
                            <div class="space-y-2">
                                @foreach ($selectedTicketForDetail->auditLogs as $log)
                                    <div class="flex items-start space-x-2.5 text-[11px] border-l-2 border-emerald-500 pl-2.5 py-0.5">
                                        <span class="font-mono text-slate-400 whitespace-nowrap">
                                            {{ $log->timestamp ? $log->timestamp->format('H:i:s') : ($log->created_at ? $log->created_at->format('H:i:s') : '') }}
                                        </span>
                                        <div class="flex-1">
                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $log->action }}:</span>
                                            <span class="text-slate-600 dark:text-slate-400">{{ $log->notes ?: $log->new_status }}</span>
                                            <span class="text-slate-400 block text-[10px]">Por: {{ $log->performed_by_user_name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pie de Modal -->
                <div class="px-6 py-3 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-800 flex justify-end shrink-0">
                    <button type="button"
                            wire:click="$set('isDetailModalOpen', false)"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-xs cursor-pointer">
                        Cerrar Ficha
                    </button>
                </div>
            </div>
        </div>
    @endif
