<!-- ========================================================================= -->
    <!-- MODAL 5: REGISTRAR TRASLADO / REASIGNACIÓN (RF-09)                       -->
    <!-- ========================================================================= -->
    @if ($isMovementModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Encabezado Verde Oscuro con Icono Azul -->
                <div class="flex items-center justify-between px-6 py-4 bg-emerald-700 text-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">Registrar Movimiento / Reasignación</h2>
                            <p class="text-xs text-emerald-100">
                                @if ($selectedMovementAsset)
                                    {{ $selectedMovementAsset->computer_code }} — {{ $selectedMovementAsset->brand?->name }} {{ $selectedMovementAsset->model?->name }}
                                @else
                                    Seleccione un equipo tecnológico para continuar
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="closeMovementModal"
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none"
                            title="Cerrar">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveMovement" class="p-6 space-y-4">
                    @if ($errors->any())
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 01 Selector de Activo con DataList -->
                    <div class="p-4 rounded-xl border border-emerald-300 dark:border-emerald-800/80 bg-emerald-50/20 dark:bg-emerald-950/10 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">
                                Activo Informático a Trasladar / Reasignar <span class="text-rose-500">*</span>
                            </label>
                            @if ($selectedMovementAsset)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950 px-2.5 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-700">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Activo Seleccionado
                                </span>
                            @endif
                        </div>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                   list="movement-assets-datalist"
                                   wire:model.live.debounce.300ms="movementAssetInput"
                                   placeholder="Escriba o seleccione código (MDC-TI-...), serie, marca, modelo o custodio..."
                                   class="w-full pl-9 pr-3 py-2.5 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   autocomplete="off" />
                        </div>

                        <datalist id="movement-assets-datalist">
                            @foreach ($this->availableMovementAssets as $ast)
                                <option value="[{{ $ast->computer_code }}] {{ $ast->brand?->name }} {{ $ast->model?->name }} — {{ $ast->office ?: 'Almacén Central TI' }} ({{ $ast->assignedPerson ? $ast->assignedPerson->full_name : 'Sin Custodio' }})">
                                    Serie: {{ $ast->serial_number ?: 'S/N' }} | Patrimonial: {{ $ast->patrimonial_code ?: 'S/C' }}
                                </option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Tarjeta de Ubicación y Custodio Actual -->
                    @if ($selectedMovementAsset)
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 border border-slate-200/90 dark:border-slate-700 rounded-xl text-xs space-y-2">
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 dark:text-slate-400 font-medium shrink-0">Ubicación Actual:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-right uppercase leading-snug">
                                    {{ $selectedMovementAsset->office ?: '[OCC] OFICINA DE CONTABILIDAD Y COSTOS - PALACIO MUNICIPAL (SEDE PRINCIPAL)' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500 dark:text-slate-400 font-medium shrink-0">Custodio Actual:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-right uppercase">
                                    {{ $selectedMovementAsset->assignedPerson ? $selectedMovementAsset->assignedPerson->full_name : 'DANI DANIEL VILLEGAS PURIZACA' }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-slate-50/70 dark:bg-slate-800/30 border border-slate-200/80 dark:border-slate-700/60 rounded-xl text-center py-4">
                            <p class="text-xs italic text-slate-400 dark:text-slate-500">
                                Seleccione un activo en el selector superior para cargar su ubicación y custodio actual.
                            </p>
                        </div>
                    @endif

                    <!-- Fila 1: Tipo de Movimiento & Oficina Destino -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tipo de Movimiento <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="movementType"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                                <option value="TRASLADO">Traslado entre Áreas (RF-09)</option>
                                <option value="ASIGNACION_INICIAL">Asignación Inicial (RF-08)</option>
                                <option value="RETORNO">Retorno a Oficina de TI / Almacén</option>
                                <option value="REASIGNACION">Reasignación de Custodio Interno</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Oficina / Dependencia Destino <span class="text-rose-500">*</span>
                            </label>
                            <input list="movement-dest-offices"
                                   type="text"
                                   placeholder="Ej. Gerencia de Rentas"
                                   wire:model="movementToOffice"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   required />
                            <datalist id="movement-dest-offices">
                                @foreach ($offices as $off)
                                    <option value="[{{ $off->acronym }}] {{ $off->name }} - {{ $off->sede }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <!-- Fila 2: Nuevo Responsable & Técnico -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Nuevo Responsable / Custodio
                                </label>
                                <button type="button"
                                        wire:click="toggleManualCustodian"
                                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer">
                                    {{ $manualCustodian ? 'Seleccionar de Lista' : 'Escribir Manual' }}
                                </button>
                            </div>
                            @if ($manualCustodian)
                                <input type="text"
                                       placeholder="Nombre del funcionario receptor"
                                       wire:model="movementNewCustodian"
                                       class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" />
                            @else
                                <select wire:model="movementTargetPersonId"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                                    <option value="">-- Sin Custodio (Almacén TI / En Custodia) --</option>
                                    @foreach ($people as $p)
                                        <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->position ?: 'Personal' }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Técnico que Ejecuta el Traslado
                            </label>
                            <input type="text"
                                   placeholder="Técnico de Soporte TI"
                                   wire:model="movementTechnician"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   required />
                        </div>
                    </div>

                    <!-- Fila 3: Motivo de la Transferencia / Justificación -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Motivo de la Transferencia / Justificación <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               placeholder="Ej. Reubicación de personal por modernización de oficina"
                               wire:model="movementReason"
                               class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                               required />
                    </div>

                    <!-- Periféricos vinculados en lote (RF-05) -->
                    @if (($selectedMovementAsset?->childAssets?->count() ?? 0) > 0)
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                            <label class="flex items-start space-x-2 cursor-pointer">
                                <input type="checkbox"
                                       wire:model="movementIncludeChildren"
                                       class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 dark:bg-slate-800" />
                                <div class="text-xs text-emerald-900 dark:text-emerald-300">
                                    <span class="font-bold">Mover periféricos vinculados en lote ({{ $selectedMovementAsset->childAssets->count() }} componentes):</span>
                                    <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">
                                        Conforme a la regla RF-05, se actualizará automáticamente la oficina de los periféricos asociados (monitor, teclado, mouse, etc.).
                                    </p>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Fila 4: Observaciones Adicionales -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Observaciones Adicionales
                        </label>
                        <textarea rows="3"
                                  placeholder="Estado del equipo al momento del traslado, accesorios entregados..."
                                  wire:model="movementNotes"
                                  class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"></textarea>
                    </div>

                    <!-- Pie del Modal con Aviso de Acta y Botones -->
                    <div class="pt-2 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">
                            * Se generará automáticamente el Acta de Movimiento en PDF (RF-10)
                        </span>
                        <div class="flex space-x-2">
                            <button type="button"
                                    wire:click="closeMovementModal"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition cursor-pointer flex items-center gap-1.5">
                                <span wire:loading.remove wire:target="saveMovement">Confirmar Traslado & Acta</span>
                                <span wire:loading wire:target="saveMovement">Generando Acta...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
