<!-- ========================================================================= -->
    <!-- MODAL 7: REGISTRAR / EDITAR INSUMO TECNOLÓGICO                            -->
    <!-- ========================================================================= -->
    @if ($isSupplyModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Header con degradado Cyan -> Blue idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-cyan-600 to-blue-700 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📦</span>
                        <div>
                            <h2 class="text-base font-bold">
                                {{ $supplyAdjustMode ? 'Ajuste de Stock en Almacén' : ($editingSupplyId ? 'Editar Insumo / Consumible' : 'Registrar Insumo Tecnológico') }}
                            </h2>
                            <p class="text-xs text-cyan-100">
                                {{ $editingSupplyId ? "{$formSupplyName} (Stock: {$formSupplyStock} {$formSupplyUnit})" : 'Inventario de Consumibles TI' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isSupplyModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveSupply" class="p-6 space-y-4">
                    @if ($editingSupplyId)
                        <div class="flex justify-end">
                            <button
                                type="button"
                                wire:click="$toggle('supplyAdjustMode')"
                                class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300 hover:underline cursor-pointer"
                            >
                                {{ $supplyAdjustMode ? 'Volver a edición de datos' : '¿Registrar Entrada / Salida de Stock?' }}
                            </button>
                        </div>
                    @endif

                    @if ($supplyAdjustMode && $editingSupplyId)
                        <div class="space-y-4">
                            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Insumo:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $formSupplyName }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Stock Actual:</span>
                                    <span class="font-mono font-bold text-cyan-700 dark:text-cyan-400">{{ $formSupplyStock }} {{ $formSupplyUnit }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Variación de Cantidad (+ para ingreso, - para retiro o merma) <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    wire:model="supplyDelta"
                                    placeholder="Ej. +10 o -2"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Motivo del Ajuste <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="supplyAdjustReason"
                                    placeholder="Ej. Ingreso por Orden de Compra N° 124 / Merma por rotura"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>
                    @else
                        <!-- Nombre del Insumo / Descripción -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nombre del Insumo / Descripción <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="formSupplyName"
                                placeholder="Ej. Tóner HP LaserJet 58A Negro / Bobina UTP Cat6 305m"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Categoría y Unidad -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Categoría de Insumo <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="formSupplyCategory"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                >
                                    <option value="TONER_TINTA">Tóner e Insumos de Impresión</option>
                                    <option value="CABLEADO_RED">Cableado y Tendido de Red (UTP / Fibra)</option>
                                    <option value="CONECTORES">Conectores RJ-45, Jacks y Patch Panels</option>
                                    <option value="PASTA_TERMICA">Pastas Térmicas y Disipadores</option>
                                    <option value="REPUESTOS_HARDWARE">Repuestos de Hardware (Fuentes, Discos)</option>
                                    <option value="HERRAMIENTAS">Herramientas de Soporte TI</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Unidad de Medida <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="formSupplyUnit"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                >
                                    <option value="UNIDAD">UNIDAD</option>
                                    <option value="METROS">METROS</option>
                                    <option value="BOBINA">BOBINA</option>
                                    <option value="CAJA">CAJA</option>
                                    <option value="TUBO">TUBO</option>
                                    <option value="PAQUETE">PAQUETE</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stock Inicial y Umbral Crítico -->
                        <div class="grid grid-cols-2 gap-4">
                            @if (!$editingSupplyId)
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Stock Inicial <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        min="0"
                                        wire:model="formSupplyStock"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                        required
                                    />
                                </div>
                            @else
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Stock Actual
                                    </label>
                                    <input
                                        type="number"
                                        value="{{ $formSupplyStock }}"
                                        disabled
                                        class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-slate-500 rounded-lg cursor-not-allowed"
                                    />
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Umbral Crítico de Alerta (Min Stock) <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    min="1"
                                    wire:model="formSupplyMinStock"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                />
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">SRS: Alerta visual si stock es ≤ 2 unidades.</p>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Ubicación en Almacén / Estante
                            </label>
                            <input
                                type="text"
                                wire:model="formSupplyLocation"
                                placeholder="Almacén ODT - Estante 1"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                            />
                        </div>

                        <!-- Notas / Modelos Compatibles -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Notas / Modelos Compatibles
                            </label>
                            <textarea
                                rows="2"
                                wire:model="formSupplyNotes"
                                placeholder="Ej. Compatible con impresoras HP LaserJet M404dw y M428fdw..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>
                    @endif

                    <!-- Botones Cancelar / Registrar Insumo -->
                    <div class="pt-3 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="$set('isSupplyModalOpen', false)"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                        >
                            {{ $supplyAdjustMode ? 'Confirmar Ajuste' : ($editingSupplyId ? 'Guardar Cambios' : 'Registrar Insumo') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
