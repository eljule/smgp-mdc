<!-- MODAL 2: DETALLE DEL TICKET                                               -->
    <!-- ========================================================================= -->
    @if ($isDetailModalOpen && $selectedTicketForDetail)
        @php
            $canManageDetail = $this->canManageTicket($selectedTicketForDetail);
            $canReassignDetail = $this->canReassignTickets();
        @endphp
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
                        @if ($selectedTicketForDetail->assigned_technician_display ?: $selectedTicketForDetail->assigned_technician_name)
                            <strong class="font-bold text-slate-800 dark:text-slate-100">{{ $selectedTicketForDetail->assigned_technician_display ?: $selectedTicketForDetail->assigned_technician_name }}</strong>
                            <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-mono">
                                {{ $selectedTicketForDetail->assigned_technician_phone ?? 'Anexo 104' }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Sin asignar (En cola abierta)</span>
                        @endif
                    </div>

                    <div class="flex items-center flex-wrap gap-2">
                        @if ($selectedTicketForDetail->status === 'ABIERTO')
                            @if ($canManageDetail)
                                <button type="button"
                                        wire:click="takeTicket({{ $selectedTicketForDetail->id }})"
                                        class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                    ⚡ Tomar Ticket / Atender
                                </button>
                            @endif
                            @if ($canReassignDetail)
                                <button type="button"
                                        wire:click="openReassignModal({{ $selectedTicketForDetail->id }})"
                                        class="px-3.5 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 rounded-lg font-medium transition-colors cursor-pointer">
                                    Asignar a Técnico
                                </button>
                            @endif
                        @elseif (in_array(strtoupper($selectedTicketForDetail->status), ['EN_ATENCION', 'EN_LABORATORIO', 'EN_TALLER', 'EN_PAUSA']))
                            @if ($canReassignDetail)
                                <button type="button"
                                        wire:click="openReassignModal({{ $selectedTicketForDetail->id }})"
                                        class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 hover:text-white dark:bg-indigo-950/60 dark:hover:bg-indigo-600 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 rounded-lg font-bold shadow-xs transition-colors cursor-pointer flex items-center gap-1.5"
                                        title="Traspasar ticket a otro técnico (Jefatura)">
                                    <span>⇄</span>
                                    <span>Traspasar Ticket</span>
                                </button>
                            @endif

                            @if ($canManageDetail)
                                @if (strtoupper($selectedTicketForDetail->status) === 'EN_PAUSA')
                                    <button type="button"
                                            wire:click="resumeTicket({{ $selectedTicketForDetail->id }})"
                                            class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer flex items-center gap-1.5">
                                        <span>▶️</span>
                                        <span>Reanudar Atención</span>
                                    </button>
                                @else
                                    <button type="button"
                                            wire:click="openPauseModal({{ $selectedTicketForDetail->id }})"
                                            class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer flex items-center gap-1.5"
                                            title="Pausar atención por espera de repuestos, respuesta de usuario o terceros">
                                        <span>⏸️</span>
                                        <span>Pausar Atención</span>
                                    </button>
                                @endif

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
                            @endif
                        @elseif ($selectedTicketForDetail->canReceiveUserConformity())
                            @if ($selectedTicketForDetail->canUserGiveConformity(auth()->user()))
                                <button type="button"
                                        wire:click="openConformityModal({{ $selectedTicketForDetail->id }})"
                                        class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                    ⭐ Otorgar Visto Bueno (Cierre)
                                </button>
                            @else
                                <span class="px-3 py-1.5 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 border border-amber-200 dark:border-amber-800 rounded-lg text-xs font-semibold">
                                    ⏳ Resuelto (Requiere Visto Bueno del Solicitante o Jefatura ODT)
                                </span>
                            @endif
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
                    @if (! $canManageDetail && $selectedTicketForDetail->assigned_technician_id)
                        <div class="p-3 bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-2 text-slate-700 dark:text-slate-300">
                                <span class="text-base shrink-0">🔒</span>
                                <div>
                                    <span class="font-bold">Modo Solo Lectura:</span>
                                    <span>Ticket asignado a <strong>{{ $selectedTicketForDetail->assigned_technician_display }}</strong>. Solo el técnico responsable o la jefatura de oficina pueden registrar intervenciones o diagnósticos.</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded text-[10px] font-bold bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 shrink-0 ml-2">
                                Solo Consulta
                            </span>
                        </div>
                    @endif
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
                    @if ($selectedTicketForDetail->user_conformity !== null)
                        <div class="border border-blue-200 dark:border-blue-800 bg-white dark:bg-slate-900 rounded-xl p-4 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-blue-800 dark:text-blue-300 text-[11px] border-b border-blue-100 dark:border-blue-900 pb-1.5 flex justify-between items-center">
                                <span>VISTO BUENO DE CIERRE POR EL SOLICITANTE (RF-05)</span>
                                <span class="text-blue-600 dark:text-blue-400 font-bold">
                                    ★ {{ $selectedTicketForDetail->user_rating ?? 5 }}/5 ESTRELLAS
                                </span>
                            </h4>
                            <p class="text-slate-700 dark:text-slate-300 font-medium">
                                Conformidad:
                                @if ($selectedTicketForDetail->user_conformity)
                                    <strong class="text-emerald-600 dark:text-emerald-400">✓ Solución Aprobada y Conforme</strong>
                                @else
                                    <strong class="text-rose-600 dark:text-rose-400">⚠ Conformidad Observada / Disconforme</strong>
                                @endif
                            </p>
                            @if ($selectedTicketForDetail->user_feedback)
                                <div class="text-slate-600 dark:text-slate-300 italic bg-slate-50/60 dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                    “{{ $selectedTicketForDetail->user_feedback }}”
                                </div>
                            @endif
                        </div>
                    @elseif ($selectedTicketForDetail->canReceiveUserConformity())
                        <div class="border border-amber-200 dark:border-amber-800 bg-amber-50/50 dark:bg-amber-950/30 rounded-xl p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300 text-[11px]">
                                    VISTO BUENO PENDIENTE DE CONFORMIDAD
                                </h4>
                                <span class="text-amber-700 dark:text-amber-400 font-bold text-xs">
                                    ⏳ En espera de visto bueno
                                </span>
                            </div>
                            <p class="text-xs text-amber-900 dark:text-amber-200">
                                La atención técnica ha concluido. El solicitante o jefatura puede otorgar el visto bueno final para cerrar el ticket formalmente.
                            </p>
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
                        <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5 bg-white dark:bg-slate-900 shadow-xs space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2.5">
                                <div class="flex items-center space-x-2">
                                    <span class="p-1 rounded-lg bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 text-xs">
                                        📜
                                    </span>
                                    <h4 class="font-extrabold uppercase tracking-wider text-slate-800 dark:text-white text-xs">
                                        HISTORIAL DE AUDITORÍA Y TRAZABILIDAD (RNF-04)
                                    </h4>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 font-mono">
                                    {{ $selectedTicketForDetail->auditLogs->count() }} interacciones registradas
                                </span>
                            </div>

                            <div class="relative pl-4 space-y-3.5 before:content-[''] before:absolute before:left-1.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
                                @foreach ($selectedTicketForDetail->auditLogs->sortBy('id') as $log)
                                    @php
                                        $actionMeta = match(strtoupper((string) $log->action)) {
                                            'TICKET_CREADO', 'TICKET_REGISTRADO' => [
                                                'label' => 'Registro de Incidencia',
                                                'badgeBg' => 'bg-sky-50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border-sky-300 dark:border-sky-800',
                                                'dotBg' => 'bg-sky-500 ring-sky-200 dark:ring-sky-950',
                                                'icon' => '📝',
                                            ],
                                            'TICKET_TOMADO' => [
                                                'label' => 'Atención Iniciada por Técnico',
                                                'badgeBg' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
                                                'dotBg' => 'bg-emerald-500 ring-emerald-200 dark:ring-emerald-950',
                                                'icon' => '🧑‍💻',
                                            ],
                                            'PAUSA_ATENCION' => [
                                                'label' => 'Atención Puesta en Pausa',
                                                'badgeBg' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-300 dark:border-amber-800',
                                                'dotBg' => 'bg-amber-500 ring-amber-200 dark:ring-amber-950',
                                                'icon' => '⏸️',
                                            ],
                                            'REANUDACION_ATENCION' => [
                                                'label' => 'Atención Reanudada',
                                                'badgeBg' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
                                                'dotBg' => 'bg-emerald-500 ring-emerald-200 dark:ring-emerald-950',
                                                'icon' => '▶️',
                                            ],
                                            'ACTUALIZACION_TECNICA' => [
                                                'label' => 'Intervención y Diagnóstico Técnico',
                                                'badgeBg' => 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border-indigo-300 dark:border-indigo-800',
                                                'dotBg' => 'bg-indigo-500 ring-indigo-200 dark:ring-indigo-950',
                                                'icon' => '🛠️',
                                            ],
                                            'CONSUMO_INSUMO' => [
                                                'label' => 'Insumo / Repuesto de Almacén',
                                                'badgeBg' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-300 dark:border-amber-800',
                                                'dotBg' => 'bg-amber-500 ring-amber-200 dark:ring-amber-950',
                                                'icon' => '📦',
                                            ],
                                            'PRESTAMO_PROVISIONAL' => [
                                                'label' => 'Asignación de Equipo Provisional',
                                                'badgeBg' => 'bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border-purple-300 dark:border-purple-800',
                                                'dotBg' => 'bg-purple-500 ring-purple-200 dark:ring-purple-950',
                                                'icon' => '🔄',
                                            ],
                                            'REASIGNACION_TECNICO', 'TRASPASO_MASIVO_CARGA', 'TRASPASO_MASIVO_BALANCEADO' => [
                                                'label' => 'Reasignación de Técnico',
                                                'badgeBg' => 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-800',
                                                'dotBg' => 'bg-blue-500 ring-blue-200 dark:ring-blue-950',
                                                'icon' => '🔁',
                                            ],
                                            'CONFORMIDAD_CIERRE', 'CONFORMIDAD_USUARIO' => [
                                                'label' => 'Conformidad de Cierre (Visto Bueno)',
                                                'badgeBg' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
                                                'dotBg' => 'bg-emerald-500 ring-emerald-200 dark:ring-emerald-950',
                                                'icon' => '⭐',
                                            ],
                                            'CONFORMIDAD_OBSERVADA' => [
                                                'label' => 'Conformidad Observada',
                                                'badgeBg' => 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border-rose-300 dark:border-rose-800',
                                                'dotBg' => 'bg-rose-500 ring-rose-200 dark:ring-rose-950',
                                                'icon' => '⚠️',
                                            ],
                                            default => [
                                                'label' => str_replace('_', ' ', $log->action),
                                                'badgeBg' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700',
                                                'dotBg' => 'bg-slate-500 ring-slate-200 dark:ring-slate-800',
                                                'icon' => '📌',
                                            ],
                                        };

                                        $dateObj = $log->timestamp ?? $log->created_at;
                                        $formattedDate = $dateObj ? $dateObj->format('d/m/Y') : '';
                                        $formattedTime = $dateObj ? $dateObj->format('H:i:s') : '';
                                    @endphp

                                    <div class="relative flex items-start space-x-3 group">
                                        <!-- Dot indicador en la línea de tiempo -->
                                        <div class="absolute -left-[19px] top-1.5 w-2.5 h-2.5 rounded-full {{ $actionMeta['dotBg'] }} ring-4 shadow-xs"></div>

                                        <div class="flex-1 bg-slate-50/70 dark:bg-slate-800/50 rounded-xl p-3 border border-slate-200/80 dark:border-slate-800 space-y-1.5">
                                            <!-- Cabecera del evento: Acción, Fecha/Hora y Transición -->
                                            <div class="flex flex-wrap items-center justify-between gap-1.5">
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md border {{ $actionMeta['badgeBg'] }}">
                                                        <span>{{ $actionMeta['icon'] }}</span>
                                                        <span>{{ $actionMeta['label'] }}</span>
                                                    </span>

                                                    @if ($log->previous_status || $log->new_status)
                                                        @if ($log->previous_status && $log->previous_status !== $log->new_status)
                                                            <span class="inline-flex items-center gap-1 text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-200/70 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">
                                                                <span>{{ $log->previous_status }}</span>
                                                                <span>→</span>
                                                                <span class="text-emerald-600 dark:text-emerald-400 font-black">{{ $log->new_status }}</span>
                                                            </span>
                                                        @elseif ($log->new_status)
                                                            <span class="inline-flex items-center text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-200/60 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300">
                                                                {{ $log->new_status }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>

                                                <div class="text-[10px] font-mono text-slate-400 flex items-center space-x-1.5 shrink-0">
                                                    @if ($formattedDate)
                                                        <span>📅 {{ $formattedDate }}</span>
                                                        <span>•</span>
                                                    @endif
                                                    <span class="font-bold text-slate-600 dark:text-slate-300">⏰ {{ $formattedTime }}</span>
                                                </div>
                                            </div>

                                            <!-- Contenido Detallado de lo que se realizó -->
                                            <div class="text-xs text-slate-700 dark:text-slate-200 leading-relaxed pl-0.5">
                                                @php
                                                    $rawNotes = trim((string) $log->notes);
                                                    $parts = explode(' | ', $rawNotes);
                                                @endphp

                                                @if (count($parts) > 1)
                                                    <div class="space-y-1 my-1">
                                                        @foreach ($parts as $part)
                                                            @if (str_starts_with($part, 'Diagnóstico:') || str_starts_with($part, 'Diagnóstico inicial registrado:'))
                                                                <div class="p-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 text-[11px]">
                                                                    <span class="font-bold text-amber-700 dark:text-amber-400">🔍 Diagnóstico:</span>
                                                                    <span class="text-slate-800 dark:text-slate-200 ml-1">{{ trim(preg_replace('/^Diagnóstico( inicial registrado)?:/i', '', $part)) }}</span>
                                                                </div>
                                                            @elseif (str_starts_with($part, 'Solución:') || str_starts_with($part, 'Solución técnica implementada:') || str_starts_with($part, 'Solución propuesta:'))
                                                                <div class="p-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800 text-[11px]">
                                                                    <span class="font-bold text-emerald-700 dark:text-emerald-400">✅ Solución:</span>
                                                                    <span class="text-slate-800 dark:text-slate-200 ml-1">{{ trim(preg_replace('/^Solución( (técnica implementada|propuesta))?:/i', '', $part)) }}</span>
                                                                </div>
                                                            @elseif (str_starts_with($part, 'Motivo de pausa:') || str_starts_with($part, 'Motivo:'))
                                                                <div class="p-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/60 text-[11px]">
                                                                    <span class="font-bold text-amber-800 dark:text-amber-400">⏸️ Motivo de Pausa:</span>
                                                                    <span class="text-amber-900 dark:text-amber-200 ml-1">{{ trim(preg_replace('/^(Motivo de pausa|Motivo):/i', '', $part)) }}</span>
                                                                </div>
                                                            @else
                                                                <div class="text-[11px] text-slate-600 dark:text-slate-300">
                                                                    &bull; {{ $part }}
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-[11px] text-slate-600 dark:text-slate-300">
                                                        {{ $rawNotes ?: 'Acción ejecutada sobre el ticket.' }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Responsable que ejecutó la interacción -->
                                            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 dark:border-slate-700/60 text-[10px] text-slate-400">
                                                <div class="flex items-center space-x-1.5">
                                                    <span>👤</span>
                                                    <span>Operador:</span>
                                                    <strong class="text-slate-700 dark:text-slate-300 font-semibold">
                                                        {{ $log->performed_by_user_name }}
                                                    </strong>
                                                </div>
                                                <span class="font-mono text-[9px] text-slate-400">ID Evento #{{ $log->id }}</span>
                                            </div>
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
