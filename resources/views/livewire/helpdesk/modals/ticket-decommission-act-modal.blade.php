<!-- MODAL 7: ACTA OFICIAL DE BAJA PATRIMONIAL (IMPRIMIBLE)                    -->
    <!-- ========================================================================= -->
    @if ($isDecommissionModalOpen && $decommissionTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-fadeIn">
                <!-- Barra superior de controles (no imprimible) -->
                <div class="flex items-center justify-between px-6 py-3.5 bg-slate-900 text-white print:hidden shrink-0">
                    <div class="flex items-center space-x-2">
                        <span class="h-3 w-3 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="font-semibold text-sm">
                            Documento Oficial: Informe Técnico de Baja Patrimonial (SRS RF-11 / RNF-05)
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="inline-flex items-center px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm cursor-pointer"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimir / Guardar PDF
                        </button>
                        <button
                            type="button"
                            wire:click="$set('isDecommissionModalOpen', false)"
                            class="text-slate-400 hover:text-white p-1 rounded-md transition-colors cursor-pointer text-xl leading-none"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Vista del documento imprimible en formato A4 -->
                <div class="p-8 md:p-12 overflow-y-auto font-serif text-slate-800 bg-white print:p-0 print:m-0 flex-1">
                    <!-- Encabezado Municipal -->
                    <div class="border-b-2 border-slate-900 pb-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="w-20 h-20 shrink-0 flex items-center justify-center bg-blue-50 border border-blue-200 rounded-lg text-blue-900 font-bold text-xs text-center p-1 font-sans">
                                ESCUDO CASTILLA
                            </div>
                            <div class="text-center flex-1 px-4">
                                <h1 class="text-xl font-bold tracking-tight text-slate-900 uppercase font-serif">
                                    MUNICIPALIDAD DISTRITAL DE CASTILLA
                                </h1>
                                <p class="text-xs text-slate-600 font-sans uppercase font-medium tracking-wide">
                                    PROVINCIA Y DEPARTAMENTO DE PIURA - PERÚ
                                </p>
                                <p class="text-xs text-slate-500 font-sans">
                                    Gerencia de Administración y Finanzas | Subgerencia de Informática y Sistemas
                                </p>
                            </div>
                            <div class="w-24 text-right flex flex-col items-end">
                                <div class="text-[10px] font-mono text-slate-500 font-sans">N° EXPEDIENTE</div>
                                <div class="text-xs font-mono font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-200">
                                    {{ $decommissionTicket->technicalDetail?->decommission_act_number ?? 'ACTA-BAJA-2026-0001' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Título Principal del Informe Técnico -->
                    <div class="text-center my-6">
                        <h2 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-2 inline-block font-serif">
                            INFORME TÉCNICO DE DECLARATORIA DE INOPERATIVIDAD Y BAJA PATRIMONIAL
                        </h2>
                        <div class="text-xs text-slate-500 font-sans mt-1">
                            Remitido a la Subgerencia de Control Patrimonial y Margesí de Bienes
                        </div>
                    </div>

                    <!-- Tabla de Metadatos Generales -->
                    <div class="bg-slate-50 border border-slate-300 rounded-md p-4 mb-6 font-sans text-xs">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <span class="text-slate-500 block">Fecha de Emisión:</span>
                                <span class="font-semibold text-slate-800">
                                    {{ now()->translatedFormat('d \d\e F \d\e Y') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Ticket Helpdesk Origen:</span>
                                <span class="font-semibold text-blue-700 font-mono">{{ $decommissionTicket->ticket_number }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Oficina / Dependencia:</span>
                                <span class="font-semibold text-slate-800">{{ $decommissionTicket->office_name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Técnico Dictaminante:</span>
                                <span class="font-semibold text-slate-800">{{ $decommissionTicket->assigned_technician_display ?: ($decommissionTicket->assigned_technician_name ?? 'Ing. Carlos Mendoza (Soporte TI)') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Identificación del Equipo Tecnológico Evaluado -->
                    <div class="mb-6 font-sans">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-l-4 border-red-600 pl-2 mb-3">
                            1. IDENTIFICACIÓN DEL EQUIPO TECNOLÓGICO EVALUADO
                        </h3>
                        <table class="w-full text-xs border border-slate-300">
                            <tbody>
                                <tr class="border-b border-slate-200 bg-slate-100/50">
                                    <td class="p-2.5 font-semibold text-slate-600 w-1/4 border-r border-slate-200">Código Informático:</td>
                                    <td class="p-2.5 font-mono font-bold text-blue-800 w-1/4 border-r border-slate-200">
                                        {{ $decommissionTicket->asset_computer_code ?? 'N/A (Periférico / Insumo)' }}
                                    </td>
                                    <td class="p-2.5 font-semibold text-slate-600 w-1/4 border-r border-slate-200">Categoría de Activo:</td>
                                    <td class="p-2.5 text-slate-800 w-1/4">
                                        {{ $decommissionTicket->asset_category ?? $decommissionTicket->category }}
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-200">
                                    <td class="p-2.5 font-semibold text-slate-600 border-r border-slate-200">Asunto Reportado:</td>
                                    <td class="p-2.5 text-slate-800 border-r border-slate-200 font-medium" colspan="3">
                                        {{ $decommissionTicket->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600 border-r border-slate-200">Usuario Custodio:</td>
                                    <td class="p-2.5 text-slate-800 border-r border-slate-200">
                                        {{ $decommissionTicket->applicant_name }}
                                    </td>
                                    <td class="p-2.5 font-semibold text-slate-600 border-r border-slate-200">Estado Técnico Final:</td>
                                    <td class="p-2.5 font-bold text-red-600">INOPERATIVO / PROCEDE A BAJA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- 2. Diagnóstico Técnico de Laboratorio y Verificación de Falla -->
                    <div class="mb-6 font-sans">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-l-4 border-red-600 pl-2 mb-3">
                            2. DIAGNÓSTICO TÉCNICO DE LABORATORIO Y VERIFICACIÓN DE FALLA
                        </h3>
                        <div class="border border-slate-300 rounded p-3 text-xs text-slate-700 bg-slate-50/50 leading-relaxed">
                            <p class="font-semibold text-slate-900 mb-1">Causa Raíz Verificada:</p>
                            <p class="mb-3">
                                {{ $decommissionTicket->technicalDetail?->real_diagnosis ?? 'Cortocircuito irreversible en etapa de regulación VRM de placa madre e integrado puente sur calcinado.' }}
                            </p>
                            <p class="font-semibold text-slate-900 mb-1">Acción Técnica Realizada y Justificación de Baja:</p>
                            <p>
                                {{ $decommissionTicket->technicalDetail?->solution_applied ?? 'Equipo tecnológicamente obsoleto sin disponibilidad de placas socket compatibles. Se emite baja técnica.' }}
                            </p>
                        </div>
                    </div>

                    <!-- 3. Causal Técnica y Destino Final del Bien -->
                    <div class="mb-8 font-sans">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-l-4 border-red-600 pl-2 mb-3">
                            3. CAUSAL TÉCNICA Y DESTINO FINAL DEL BIEN
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="border border-slate-300 rounded p-3">
                                <span class="font-semibold text-slate-800 block mb-1">Causal de Baja (Directiva SBN):</span>
                                <span class="text-slate-600 leading-relaxed">
                                    {{ $decommissionTicket->technicalDetail?->decommission_reason ?? 'Daño físico irreversible por quemadura interna de circuitería principal y costo de reposición no costo-efectivo.' }}
                                </span>
                            </div>
                            <div class="border border-slate-300 rounded p-3">
                                <span class="font-semibold text-slate-800 block mb-1">Destino Final Recomendado:</span>
                                <span class="text-slate-600 leading-relaxed">
                                    {{ $decommissionTicket->technicalDetail?->decommission_destination ?? 'Almacén de Control Patrimonial / Chatarreo Municipal' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bloque Legal de Conformidad -->
                    <div class="text-[11px] text-slate-500 font-sans italic border-t border-slate-300 pt-3 mb-10 leading-tight">
                        Se emite el presente Informe Técnico de acuerdo con las normativas de la Dirección General de Abastecimiento (DGA) y la Superintendencia Nacional de Bienes Estatales (SBN) para los fines de desincorporación física y contable del bien en el Inventario Patrimonial de la Municipalidad Distrital de Castilla.
                    </div>

                    <!-- Bloque de Firmas Cuádruple -->
                    <div class="grid grid-cols-3 gap-8 pt-8 font-sans text-center text-xs">
                        <div class="border-t border-slate-800 pt-2">
                            <div class="font-bold text-slate-900">{{ $decommissionTicket->assigned_technician_display ?: ($decommissionTicket->assigned_technician_name ?? 'Ing. Carlos Mendoza (Soporte TI)') }}</div>
                            <div class="text-[10px] text-slate-600">Técnico de Soporte TI</div>
                            <div class="text-[10px] text-slate-400">Oficina de Informática y Sistemas</div>
                        </div>
                        <div class="border-t border-slate-800 pt-2">
                            <div class="font-bold text-slate-900">Ing. Jefe de Sistemas</div>
                            <div class="text-[10px] text-slate-600">Jefe de la Unidad de TI (V° B°)</div>
                            <div class="text-[10px] text-slate-400">Municipalidad Distrital de Castilla</div>
                        </div>
                        <div class="border-t border-slate-800 pt-2">
                            <div class="font-bold text-slate-900">Control Patrimonial</div>
                            <div class="text-[10px] text-slate-600">Recepción y Baja de Margesí</div>
                            <div class="text-[10px] text-slate-400">Gerencia de Administración y Finanzas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
