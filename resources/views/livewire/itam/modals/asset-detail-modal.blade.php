<!-- ========================================================================= -->
    <!-- MODAL 3: FICHA TÉCNICA PATRIMONIAL E INFORMÁTICA & QR                      -->
    <!-- ========================================================================= -->
    @if ($isDetailModalOpen && $selectedAssetForDetail)
        @php
            $statusClasses = match ($selectedAssetForDetail->status) {
                'OPERATIVO' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800',
                'EN_MANTENIMIENTO' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-400 dark:border-amber-800',
                'EN_CUSTODIA' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-800',
                'EN_DESUSO' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
                'PARA_BAJA' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-400 dark:border-rose-800',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            };

            $scanUrl = url('/scan/activo/' . $selectedAssetForDetail->computer_code);
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($scanUrl);

            $specs = is_array($selectedAssetForDetail->specifications)
                ? $selectedAssetForDetail->specifications
                : (json_decode($selectedAssetForDetail->specifications ?? '[]', true) ?: []);
            $categorySchema = $selectedAssetForDetail->category?->custom_fields_schema ?? [];
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
            <div class="relative w-full max-w-3xl max-h-[92vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp print:shadow-none print:border-none print:w-full print:max-w-none print:max-h-none print:overflow-visible">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900 print:border-b-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-xl text-emerald-700 dark:text-emerald-400 shrink-0">
                            {{ $selectedAssetForDetail->category?->icon ?: '💻' }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-base font-extrabold text-emerald-800 dark:text-emerald-300">
                                    {{ $selectedAssetForDetail->computer_code }}
                                </span>
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase {{ $statusClasses }}">
                                    {{ str_replace('_', ' ', $selectedAssetForDetail->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Ficha Técnica Patrimonial e Informática • Municipalidad Distrital de Castilla
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 print:hidden">
                        <button type="button"
                                onclick="window.print()"
                                class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors cursor-pointer"
                                title="Imprimir Etiqueta / Ficha">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                        <button type="button"
                                wire:click="$set('isDetailModalOpen', false)"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer text-lg font-normal">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-5">
                    <!-- Card 1: Top Banner (Datos Principales & QR) -->
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-start justify-between gap-6 shadow-xs">
                        <div class="space-y-2 flex-1">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                {{ strtoupper($selectedAssetForDetail->category?->name ?? 'EQUIPO TECNOLÓGICO') }}
                            </span>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">
                                    {{ $selectedAssetForDetail->brand?->name }} {{ $selectedAssetForDetail->model?->name }}
                                </h3>
                            </div>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-3 pt-2 text-xs">
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Código Patrimonial SBN</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $selectedAssetForDetail->patrimonial_code ?: 'No registrado' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Número de Serie</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">
                                        {{ $selectedAssetForDetail->serial_number ?: 'S/N' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Condición Física</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                                        {{ $selectedAssetForDetail->physical_condition ?? 'BUENO' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Color</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $selectedAssetForDetail->color ?: 'Estándar' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Proveedor / Compra</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $selectedAssetForDetail->supplier ?: 'No especificado' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Garantía Fabr.</span>
                                    @if ($selectedAssetForDetail->warranty_end_date)
                                        @php
                                            $isExpired = \Carbon\Carbon::parse($selectedAssetForDetail->warranty_end_date)->isPast();
                                        @endphp
                                        <span class="font-semibold {{ $isExpired ? 'text-rose-500' : 'text-slate-800 dark:text-slate-200' }}">
                                            {{ \Carbon\Carbon::parse($selectedAssetForDetail->warranty_end_date)->format('Y-m-d') }} ({{ $isExpired ? 'Vencida' : 'Vigente' }})
                                        </span>
                                    @else
                                        <span class="font-semibold text-slate-400">Sin registrar</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Tag -->
                        <div class="p-3 bg-white rounded-xl shadow-xs border border-slate-200 flex flex-col items-center gap-1.5 shrink-0 self-start w-36">
                            <img src="{{ $qrUrl }}"
                                 alt="QR {{ $selectedAssetForDetail->computer_code }}"
                                 class="w-28 h-28 object-contain" />
                            <span class="text-[10px] font-mono font-bold text-slate-700 tracking-tight text-center">
                                {{ $selectedAssetForDetail->computer_code }}
                            </span>
                            <a href="{{ $scanUrl }}"
                               target="_blank"
                               class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-0.5 mt-0.5">
                                <span>Abrir Ficha Móvil</span>
                                <span>↗</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Asignación y Custodia Institucional -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Asignación y Custodia Institucional
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center shrink-0 text-base">
                                    🏢
                                </div>
                                <div>
                                    <span class="text-[11px] text-slate-400 block font-normal">Oficina / Dependencia</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                                        {{ $selectedAssetForDetail->office ?: 'ALMACÉN TI (SIN ASIGNAR)' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center shrink-0 text-base">
                                    👤
                                </div>
                                <div>
                                    <span class="text-[11px] text-slate-400 block font-normal">Custodio Responsable</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                                        {{ $selectedAssetForDetail->assignedPerson ? $selectedAssetForDetail->assignedPerson->full_name : 'SIN ASIGNAR' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Subcomponentes Vinculados (Padre - Hijo) -->
                    <div class="p-4 rounded-xl border border-amber-300 dark:border-amber-800/80 bg-amber-50/15 dark:bg-amber-950/10 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-400 flex items-center gap-1.5">
                                <span class="text-amber-600">🔗</span> SUBCOMPONENTES VINCULADOS (PADRE - HIJO)
                            </span>
                            <button type="button"
                                    wire:click="openChildModal({{ $selectedAssetForDetail->id }})"
                                    class="bg-[#d97706] hover:bg-[#b45309] text-white text-xs font-bold px-3.5 py-1.5 rounded-lg shadow-xs transition-colors cursor-pointer">
                                Gestionar Periféricos
                            </button>
                        </div>

                        @if ($selectedAssetForDetail->childAssets && $selectedAssetForDetail->childAssets->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                @foreach ($selectedAssetForDetail->childAssets as $child)
                                    <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-between">
                                        <div>
                                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400 text-xs">{{ $child->computer_code }}</span>
                                            <span class="font-medium text-slate-800 dark:text-slate-200 block text-xs mt-0.5">
                                                {{ $child->brand?->name }} {{ $child->model?->name }} ({{ $child->category?->name ?? 'Periférico' }})
                                            </span>
                                            @if ($child->serial_number)
                                                <span class="text-[10px] text-slate-400 font-mono block mt-0.5">S/N: {{ $child->serial_number }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[9px] px-2 py-0.5 rounded-full font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 uppercase border border-slate-200 dark:border-slate-600">
                                            {{ $child->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-500 italic">
                                Este equipo no tiene periféricos vinculados como hijos (mouse, monitor, teclado, estabilizador).
                            </p>
                        @endif
                    </div>

                    <!-- Card 4: Especificaciones Técnicas Registradas -->
                    <div class="p-4 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50/15 dark:bg-emerald-950/20 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">
                                ESPECIFICACIONES TÉCNICAS REGISTRADAS ({{ strtoupper($selectedAssetForDetail->category?->name ?? 'EQUIPO') }})
                            </h4>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 font-semibold border border-emerald-200 dark:border-emerald-800">
                                Esquema Dinámico
                            </span>
                        </div>

                        @if (count($orderedSpecs) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                @foreach ($orderedSpecs as $spec)
                                    @php $isArray = is_array($spec['val']); @endphp
                                    <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex flex-col justify-center {{ $isArray ? 'md:col-span-2' : '' }}">
                                        <span class="text-[11px] text-slate-400 dark:text-slate-400 font-medium">
                                            {{ $spec['label'] }} {{ $spec['unit'] ? "({$spec['unit']})" : '' }}
                                        </span>
                                        @if ($isArray)
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                @forelse ($spec['val'] as $item)
                                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[11px] font-semibold">
                                                        {{ $item }}
                                                    </span>
                                                @empty
                                                    <span class="text-slate-400 italic">Ningún elemento registrado</span>
                                                @endforelse
                                            </div>
                                        @else
                                            <span class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">
                                                {{ is_bool($spec['val']) ? ($spec['val'] ? 'Sí' : 'No') : ($spec['val'] ?: '-') }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-500 italic">
                                No se registraron especificaciones técnicas particulares para este activo.
                            </p>
                        @endif
                    </div>

                    <!-- Observaciones y Notas (si existen) -->
                    @if ($selectedAssetForDetail->notes)
                        @php
                            $isWarning = str_contains(strtoupper($selectedAssetForDetail->notes), 'VERIFICACIÓN PENDIENTE')
                                || str_contains($selectedAssetForDetail->notes, '⚠️')
                                || str_contains(strtoupper($selectedAssetForDetail->notes), 'POR VERIFICAR');
                        @endphp
                        <div class="p-4 rounded-xl border {{ $isWarning ? 'border-amber-300 dark:border-amber-800/80 bg-amber-50/70 dark:bg-amber-950/30' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30' }}">
                            <div class="flex items-center justify-between mb-1.5 flex-wrap gap-1">
                                <span class="text-[11px] font-bold uppercase flex items-center gap-1 {{ $isWarning ? 'text-amber-800 dark:text-amber-300' : 'text-slate-400' }}">
                                    @if ($isWarning) <span>⚠️</span> @endif
                                    Observaciones y Notas
                                </span>
                                @if ($isWarning)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-200 border border-amber-300 dark:border-amber-800">
                                        Auditoría / Verificación de Hardware Requerida
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs whitespace-pre-line {{ $isWarning ? 'text-amber-900 dark:text-amber-100 font-medium' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $selectedAssetForDetail->notes }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900/60 flex-wrap gap-2 print:hidden">
                    <div class="flex items-center gap-2">
                        <button type="button"
                                wire:click="$set('isDetailModalOpen', false)"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                            Cerrar
                        </button>
                        <button type="button"
                                wire:click="openActaModal('ASIGNACION', {{ $selectedAssetForDetail->id }})"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 shadow-xs transition-colors cursor-pointer">
                            <span>📄</span> Acta Oficial PDF
                        </button>
                        <button type="button"
                                wire:click="openMovementModal({{ $selectedAssetForDetail->id }})"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-900 rounded-xl hover:bg-indigo-100 transition-colors cursor-pointer">
                            <span>🔄</span> Trasladar / Asignar
                        </button>
                    </div>

                    <button type="button"
                            wire:click="openAssetModal({{ $selectedAssetForDetail->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Editar Activo
                    </button>
                </div>
            </div>
        </div>
    @endif
