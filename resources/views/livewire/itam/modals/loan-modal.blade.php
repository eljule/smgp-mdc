<!-- ========================================================================= -->
    <!-- MODAL 8: REGISTRAR PRÉSTAMO / RESERVA TEMPORAL DE ACTIVOS                 -->
    <!-- ========================================================================= -->
    @if ($isLoanModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Header con degradado Púrpura -> Índigo idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-purple-700 to-indigo-800 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">{{ $editingLoanId ? '📥' : '📅' }}</span>
                        <div>
                            <h2 class="text-base font-bold">
                                {{ $editingLoanId ? "Devolución y Retorno de Préstamo ({$editingLoanNumber})" : 'Registrar Préstamo / Reserva Temporal de Activos' }}
                            </h2>
                            <p class="text-xs text-purple-200">
                                {{ $editingLoanId ? 'Validación de estado físico al retorno (RF-20)' : 'Gestión de Cañones, Laptops de Contingencia y Accesorios (RF-17, RF-18)' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isLoanModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveLoan" class="p-6 space-y-4">
                    @if ($editingLoanId)
                        <!-- Modo Retorno -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Estado Físico al Retorno <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="loanReturnCondition" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    <option value="BUENO">BUENO - Operativo y sin daños</option>
                                    <option value="REGULAR">REGULAR - Desgaste leve de uso</option>
                                    <option value="MALO">MALO - Daño cosmético / requiere revisión</option>
                                    <option value="INOPERATIVO">INOPERATIVO - Falla técnica / derivar a soporte</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Observaciones de Recepción / Check de Accesorios
                                </label>
                                <textarea
                                    rows="2"
                                    wire:model="loanReturnNotes"
                                    placeholder="Verificación de cargadores, cables HDMI, puntero, estuche..."
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"
                                ></textarea>
                            </div>
                        </div>
                    @else
                        <!-- Modo Registrar Préstamo -->
                        <!-- Seleccionar Activo(s) para Préstamo -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Seleccionar Activo(s) para Préstamo <span class="text-rose-500">*</span>
                            </label>
                            @if ($loanableAssets->isEmpty())
                                <div class="p-3 text-xs text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-xl leading-relaxed">
                                    No hay activos marcados como disponibles para préstamo. En el catálogo de activos marque la casilla "Disponible para préstamo" en proyectores o laptops.
                                </div>
                            @else
                                <div class="max-h-36 overflow-y-auto border border-slate-200 dark:border-slate-700 rounded-xl divide-y divide-slate-100 dark:divide-slate-800 p-1">
                                    @foreach ($loanableAssets as $ast)
                                        @php $isSelected = in_array($ast->id, $loanSelectedAssetIds, true); @endphp
                                        <div
                                            wire:click="toggleLoanAsset({{ $ast->id }})"
                                            class="flex items-center justify-between p-2 rounded-lg cursor-pointer text-xs transition {{ $isSelected ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-900 dark:text-purple-200 font-semibold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-700 dark:text-slate-300' }}"
                                        >
                                            <div class="flex items-center space-x-2">
                                                <input
                                                    type="checkbox"
                                                    @checked($isSelected)
                                                    class="rounded text-purple-600 focus:ring-purple-500 pointer-events-none"
                                                />
                                                <span class="font-mono text-purple-700 dark:text-purple-400 font-bold">{{ $ast->computer_code }}</span>
                                                <span>{{ $ast->category?->name }} - {{ $ast->brand?->name }} {{ $ast->model?->name }}</span>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] rounded {{ $ast->status === 'OPERATIVO' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                                {{ $ast->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('loanSelectedAssetIds')
                                <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dependencia y Funcionario -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Dependencia Solicitante <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="loanDepartment"
                                    placeholder="Ej. Gerencia de Rentas"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Funcionario / Solicitante <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="loanBorrowerName"
                                    placeholder="Nombre del responsable"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Fecha de Inicio / Salida <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    wire:model="loanStartDate"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Fecha Estimada de Devolución <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    wire:model="loanExpectedReturnDate"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Motivo del Evento -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Motivo del Evento / Reunión / Capacitación <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="loanReason"
                                placeholder="Ej. Capacitación sobre nuevo TUPA en auditorio municipal"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Observaciones / Accesorios Entregados -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Observaciones / Accesorios Entregados
                            </label>
                            <textarea
                                rows="2"
                                wire:model="loanNotes"
                                placeholder="Incluye puntero láser, cable HDMI de 5m, estuche..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>
                    @endif

                    <!-- Footer -->
                    <div class="pt-3 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">
                            * Se generará el Acta de Préstamo y Salida en PDF (RF-20).
                        </span>
                        <div class="flex space-x-2">
                            <button
                                type="button"
                                wire:click="$set('isLoanModalOpen', false)"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                            >
                                {{ $editingLoanId ? 'Confirmar Retorno & Acta' : 'Generar Préstamo & Acta' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
