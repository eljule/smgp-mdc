<!-- ========================================================================= -->
    <!-- MODAL 10: DOCUMENTACIÓN OFICIAL DIGITAL (ACTA OFICIAL RF-10, RF-16, RF-20) -->
    <!-- ========================================================================= -->
    @if ($isActaModalOpen)
        @php
            $effAsset = $actaAsset;
            $meses = [
                1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
                5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
                9 => 'setiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
            ];
            $todayFormatted = date('j') . ' de ' . ($meses[(int) date('n')] ?? date('F')) . ' de ' . date('Y');

            $docTitle = match ($actaType) {
                'ASIGNACION' => 'ACTA DE ASIGNACIÓN Y ENTREGA DE EQUIPO TI',
                'TRANSFERENCIA' => 'FICHA DE MOVIMIENTO Y TRANSFERENCIA ENTRE DEPENDENCIAS',
                'MANTENIMIENTO' => 'HOJA DE SERVICIO E INFORME TÉCNICO DE MANTENIMIENTO',
                'PRESTAMO' => 'ACTA DE SALIDA Y RETORNO DE PRÉSTAMO TEMPORAL',
                default => 'ACTA OFICIAL DE ACTIVO TI',
            };

            $docCode = $actaMovement?->acta_number
                ?? ($actaMaintenance?->order_number
                ?? ($actaLoan?->loan_number
                ?? 'MDC-TI-DOC-' . date('Y') . '-0001'));

            $operationDate = $todayFormatted;
            if ($actaMovement?->movement_date) {
                $mCarbon = \Carbon\Carbon::parse($actaMovement->movement_date);
                $operationDate = $mCarbon->day . ' de ' . ($meses[$mCarbon->month] ?? $mCarbon->format('F')) . ' de ' . $mCarbon->year;
            } elseif ($actaMaintenance?->scheduled_date) {
                $mCarbon = \Carbon\Carbon::parse($actaMaintenance->scheduled_date);
                $operationDate = $mCarbon->day . ' de ' . ($meses[$mCarbon->month] ?? $mCarbon->format('F')) . ' de ' . $mCarbon->year;
            } elseif ($actaLoan?->loan_date) {
                $mCarbon = \Carbon\Carbon::parse($actaLoan->loan_date);
                $operationDate = $mCarbon->day . ' de ' . ($meses[$mCarbon->month] ?? $mCarbon->format('F')) . ' de ' . $mCarbon->year;
            }

            $technicianName = $actaMovement?->technician_name
                ?? ($actaMaintenance?->assigned_technician_name
                ?? 'TÉCNICO DE SOPORTE TI');

            $custodianName = $actaMovement?->new_custodian_name
                ?? ($actaLoan?->requesting_name
                ?? ($effAsset?->assignedPerson ? $effAsset->assignedPerson->full_name : 'CARLOS ENRIQUE MENDOZA CHUNGA'));

            $specs = is_array($effAsset?->specifications)
                ? $effAsset->specifications
                : (json_decode($effAsset?->specifications ?? '[]', true) ?: []);
            $categorySchema = $effAsset?->category?->custom_fields_schema ?? [];
            $orderedSpecs = [];
            $handledKeys = [];

            if (is_array($categorySchema)) {
                foreach ($categorySchema as $f) {
                    $fKey = $f['key'] ?? '';
                    if (! $fKey) continue;

                    $matchedKey = null;
                    if (array_key_exists($fKey, $specs)) {
                        $matchedKey = $fKey;
                    } else {
                        foreach (array_keys($specs) as $k) {
                            if (strtolower((string) $k) === strtolower((string) $fKey)) {
                                $matchedKey = $k;
                                break;
                            }
                        }
                    }

                    if ($matchedKey !== null) {
                        $handledKeys[] = $matchedKey;
                        $orderedSpecs[] = [
                            'label' => $f['label'] ?? ucfirst(str_replace('_', ' ', $fKey)),
                            'unit' => $f['unit'] ?? null,
                            'val' => $specs[$matchedKey],
                        ];
                    }
                }
            }

            foreach ($specs as $k => $v) {
                if (! in_array($k, $handledKeys, true)) {
                    $orderedSpecs[] = [
                        'label' => ucwords(str_replace('_', ' ', (string) $k)),
                        'unit' => null,
                        'val' => $v,
                    ];
                }
            }
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs animate-fadeIn print:p-0 print:bg-white print:static">
            <div class="relative w-full max-w-4xl max-h-[92vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-scaleUp print:shadow-none print:border-none print:w-full print:max-w-none print:max-h-none print:overflow-visible">
                <!-- Actions Bar (Hidden in Print) -->
                <div class="flex items-center justify-between px-6 py-4 bg-slate-900 text-white shrink-0 print:hidden">
                    <div class="flex items-center space-x-3">
                        <span class="text-xl">📄</span>
                        <div>
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-indigo-400">
                                DOCUMENTACIÓN OFICIAL DIGITAL
                            </h2>
                            <p class="text-xs text-slate-300">
                                {{ $docTitle }} &mdash; {{ $docCode }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button"
                                onclick="window.print()"
                                class="inline-flex items-center space-x-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow transition-all cursor-pointer">
                            <span>🖨️</span>
                            <span>Imprimir / Guardar PDF</span>
                        </button>
                        <button type="button"
                                wire:click="$set('isActaModalOpen', false)"
                                class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs transition cursor-pointer">
                            Cerrar
                        </button>
                    </div>
                </div>

                <!-- Printable Paper Sheet -->
                <div class="flex-1 overflow-y-auto p-8 sm:p-12 text-slate-800 text-sm leading-relaxed bg-white print:overflow-visible print:p-0" id="printable-acta">
                    <!-- Header -->
                    <div class="border-b-2 border-slate-900 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold tracking-widest text-slate-500 uppercase">
                                    REPÚBLICA DEL PERÚ
                                </p>
                                <h1 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                                    MUNICIPALIDAD DISTRITAL DE CASTILLA
                                </h1>
                                <p class="text-xs font-semibold text-indigo-800 uppercase tracking-wide">
                                    OFICINA DE TECNOLOGÍAS DE LA INFORMACIÓN Y SISTEMAS
                                </p>
                                <p class="text-[11px] text-slate-500">
                                    Plaza Luis Montero S/N - Castilla, Piura | Teléf: (073) 345678
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="border-2 border-indigo-700 rounded-lg px-4 py-2 bg-indigo-50/50 text-center">
                                    <p class="text-[10px] font-bold text-indigo-900 uppercase">DOCUMENTO OFICIAL</p>
                                    <p class="text-sm font-mono font-bold text-slate-900">{{ $docCode }}</p>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Fecha de Emisión: {{ $todayFormatted }}</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <h2 class="text-base font-bold text-slate-900 tracking-wide uppercase underline decoration-indigo-600 decoration-2 underline-offset-4">
                                {{ $docTitle }}
                            </h2>
                        </div>
                    </div>

                    <!-- 1. Datos Generales de la Operación -->
                    <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">1. DATOS GENERALES DE LA OPERACIÓN</h3>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="text-slate-500">Tipo de Trámite:</p>
                                <p class="font-semibold text-slate-900">{{ $actaType }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Fecha de Registro:</p>
                                <p class="font-semibold text-slate-900">{{ $operationDate }}</p>
                            </div>
                            @if ($actaMovement)
                                <div>
                                    <p class="text-slate-500">Dependencia de Origen:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->from_office ?: 'Almacén Central TI' }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Dependencia de Destino:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->to_office }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Motivo del Traslado:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->reason }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Técnico Informático:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->technician_name }}</p>
                                </div>
                            @endif
                            @if ($actaMaintenance)
                                <div>
                                    <p class="text-slate-500">Tipo de Mantenimiento:</p>
                                    <p class="font-semibold text-slate-900 uppercase">{{ $actaMaintenance->type }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Estado de Orden:</p>
                                    <p class="font-semibold text-slate-900 uppercase">{{ $actaMaintenance->status }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Técnico Asignado:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMaintenance->assigned_technician_name }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Fecha Programada:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMaintenance->scheduled_date }}</p>
                                </div>
                            @endif
                            @if ($actaLoan)
                                <div>
                                    <p class="text-slate-500">Beneficiario / Solicitante:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->requesting_name }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Dependencia Solicitante:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->department }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Fecha Inicio Préstamo:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->loan_date }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Fecha Estimada Devolución:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->expected_return_date }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Especificaciones Técnicas del Activo Principal -->
                    @if ($effAsset)
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">2. ESPECIFICACIONES TÉCNICAS DEL ACTIVO PRINCIPAL</h3>
                            <table class="w-full border-collapse border border-slate-300 text-xs">
                                <tbody>
                                    <tr>
                                        <td class="border border-slate-300 p-2 font-semibold w-1/4">Código Informático</td>
                                        <td class="border border-slate-300 p-2 font-mono font-bold text-indigo-700 w-1/4">{{ $effAsset->computer_code }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold w-1/4">Código Patrimonial SBN</td>
                                        <td class="border border-slate-300 p-2 font-mono w-1/4">{{ $effAsset->patrimonial_code ?: 'No registrado' }}</td>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <td class="border border-slate-300 p-2 font-semibold">Categoría</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->category?->name ?? 'Equipo TI' }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold">Número de Serie</td>
                                        <td class="border border-slate-300 p-2 font-mono">{{ $effAsset->serial_number ?: 'S/N' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-slate-300 p-2 font-semibold">Marca / Modelo</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->brand?->name }} {{ $effAsset->model?->name }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold">Estado Operativo / Condición</td>
                                        <td class="border border-slate-300 p-2">
                                            <span class="font-bold">{{ $effAsset->status }}</span> ({{ $effAsset->physical_condition ?? 'BUENO' }})
                                        </td>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <td class="border border-slate-300 p-2 font-semibold">Ubicación / Oficina</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->office ?: 'Oficina TI' }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold">Custodio / Responsable</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->assignedPerson ? $effAsset->assignedPerson->full_name : 'Sin asignar' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if (count($orderedSpecs) > 0)
                                <div class="mt-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-[11px] font-bold text-slate-700 uppercase">
                                            COMPONENTES TÉCNICOS INTERNOS ({{ strtoupper($effAsset->category?->name ?? 'EQUIPO TI') }}):
                                        </p>
                                        <span class="text-[10px] text-slate-500 font-mono">
                                            {{ count($orderedSpecs) }} especificaciones
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-[11px]">
                                        @foreach ($orderedSpecs as $spec)
                                            @php
                                                $isArray = is_array($spec['val']);
                                                $isBoolean = is_bool($spec['val']);
                                                $displayVal = $isBoolean ? ($spec['val'] ? 'Sí' : 'No') : (string) ($spec['val'] ?? '');
                                                if ($spec['unit'] && $displayVal && ! $isArray && ! str_contains(strtolower($displayVal), strtolower($spec['unit']))) {
                                                    $displayVal .= ' ' . $spec['unit'];
                                                }
                                            @endphp
                                            <div class="p-2 rounded-lg bg-white border border-slate-200 flex flex-col justify-start {{ $isArray ? 'col-span-2 md:col-span-3' : '' }}">
                                                <span class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">
                                                    {{ $spec['label'] }} {{ ($spec['unit'] && (! $displayVal || $isArray)) ? "({$spec['unit']})" : '' }}
                                                </span>
                                                @if ($isArray)
                                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                                        @forelse ($spec['val'] as $item)
                                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-300 text-[10px] font-semibold">
                                                                {{ $item }}
                                                            </span>
                                                        @empty
                                                            <span class="text-slate-400 italic text-[10px]">Ninguno</span>
                                                        @endforelse
                                                    </div>
                                                @else
                                                    <span class="font-semibold text-slate-800 mt-0.5">
                                                        {{ $displayVal ?: '-' }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- 3. Subcomponentes y Periféricos Vinculados -->
                    @if ($effAsset && $effAsset->childAssets && $effAsset->childAssets->isNotEmpty())
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">
                                3. SUBCOMPONENTES Y PERIFÉRICOS VINCULADOS (RELACIÓN JERÁRQUICA RF-05)
                            </h3>
                            <table class="w-full border-collapse border border-slate-300 text-xs">
                                <thead>
                                    <tr class="bg-slate-100 text-left">
                                        <th class="border border-slate-300 p-2">Código Informático</th>
                                        <th class="border border-slate-300 p-2">Categoría</th>
                                        <th class="border border-slate-300 p-2">Marca / Modelo</th>
                                        <th class="border border-slate-300 p-2">N° Serie</th>
                                        <th class="border border-slate-300 p-2">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($effAsset->childAssets as $ch)
                                        <tr>
                                            <td class="border border-slate-300 p-2 font-mono font-bold text-indigo-700">{{ $ch->computer_code }}</td>
                                            <td class="border border-slate-300 p-2">{{ $ch->category?->name ?? 'Periférico' }}</td>
                                            <td class="border border-slate-300 p-2">{{ $ch->brand?->name }} {{ $ch->model?->name }}</td>
                                            <td class="border border-slate-300 p-2 font-mono">{{ $ch->serial_number ?: 'S/N' }}</td>
                                            <td class="border border-slate-300 p-2 font-semibold">{{ $ch->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- 4. Acciones Técnicas e Insumos Utilizados (Mantenimiento) -->
                    @if ($actaMaintenance)
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">4. ACCIONES TÉCNICAS E INSUMOS UTILIZADOS</h3>
                            <div class="p-3 bg-slate-50 border border-slate-300 rounded-lg mb-3 text-xs space-y-2">
                                <div>
                                    <p class="font-semibold text-slate-900">Diagnóstico:</p>
                                    <p class="text-slate-700">{{ $actaMaintenance->diagnosis ?: 'Revisión y diagnóstico preventivo rutinario.' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Trabajo Realizado:</p>
                                    <p class="text-slate-700">{{ $actaMaintenance->activities_performed ?: 'Limpieza de componentes, optimización de software y test operativo.' }}</p>
                                </div>
                            </div>

                            @if ($actaMaintenance->suppliesUsed && $actaMaintenance->suppliesUsed->isNotEmpty())
                                <div>
                                    <p class="text-[11px] font-bold text-slate-700 uppercase mb-1">Insumos y Repuestos Descargados de Stock (RF-15):</p>
                                    <table class="w-full border-collapse border border-slate-300 text-xs">
                                        <thead>
                                            <tr class="bg-slate-100 text-left">
                                                <th class="border border-slate-300 p-2">Insumo</th>
                                                <th class="border border-slate-300 p-2">Categoría</th>
                                                <th class="border border-slate-300 p-2">Cantidad Utilizada</th>
                                                <th class="border border-slate-300 p-2">Unidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($actaMaintenance->suppliesUsed as $su)
                                                <tr>
                                                    <td class="border border-slate-300 p-2 font-semibold">{{ $su->supply?->name }}</td>
                                                    <td class="border border-slate-300 p-2">{{ $su->supply?->category }}</td>
                                                    <td class="border border-slate-300 p-2 font-bold text-indigo-700">{{ $su->quantity_used }}</td>
                                                    <td class="border border-slate-300 p-2">{{ $su->supply?->unit }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Declaración de Conformidad y Firmas -->
                    <div class="mt-8 border-t border-slate-300 pt-4">
                        <p class="text-[11px] text-slate-600 text-justify mb-8 leading-relaxed">
                            <strong>DECLARACIÓN DE CONFORMIDAD:</strong> Mediante el presente documento, las partes dan fe de la veracidad de la información técnica detallada, así como del estado de operatividad de los equipos entregados o intervenidos. El custodio asume la responsabilidad directa de su custodia y uso exclusivo para las funciones encomendadas en la Municipalidad Distrital de Castilla, conforme a las directivas internas de control patrimonial y seguridad de la información.
                        </p>

                        <div class="grid grid-cols-2 gap-12 pt-12">
                            <div class="text-center border-t border-slate-900 pt-2">
                                <p class="font-bold text-xs text-slate-900 uppercase">
                                    {{ $technicianName }}
                                </p>
                                <p class="text-[11px] text-slate-500">Subgerencia de Informática y Sistemas</p>
                                <p class="text-[10px] text-slate-400">Entrega Conforme</p>
                            </div>
                            <div class="text-center border-t border-slate-900 pt-2">
                                <p class="font-bold text-xs text-slate-900 uppercase">
                                    {{ $custodianName }}
                                </p>
                                <p class="text-[11px] text-slate-500">Dependencia Usuaria / Receptora</p>
                                <p class="text-[10px] text-slate-400">Recepción y Conformidad</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-4 border-t border-slate-200 text-center text-[10px] text-slate-400 flex justify-between items-center">
                        <span>SIGM - Sistema Integrado de Gestión Municipal | Municipalidad Distrital de Castilla</span>
                        <span class="font-mono">Página 1 de 1</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
