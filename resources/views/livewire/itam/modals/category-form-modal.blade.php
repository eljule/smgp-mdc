<!-- ========================================================================= -->
    <!-- MODAL 13: REGISTRAR / EDITAR CATEGORÍA TECNOLÓGICA (SRS RF-01)            -->
    <!-- ========================================================================= -->
    @if ($isCategoryModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-sm transition-colors"
                            style="background-color: {{ $formCatColor }}20; color: {{ $formCatColor }};"
                        >
                            {{ $formCatIcon }}
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">
                                {{ $editingCategoryId ? 'Editar Categoría de Activos' : 'Nueva Categoría Tecnológica' }}
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Clasificación de equipamiento informático municipal
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        wire:click="$set('isCategoryModalOpen', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit="saveCategory">
                    <div class="p-6 space-y-4">
                        @if ($errors->any())
                            <div class="p-3 text-xs font-semibold text-rose-700 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 dark:border-rose-900/50">
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Nombre de la Categoría -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Nombre de la Categoría *
                            </label>
                            <input
                                type="text"
                                required
                                placeholder="Ej. Servidor Rack / Blade, Switch de Red"
                                wire:model.live.debounce.300ms="formCatName"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                        </div>

                        <!-- Código / Prefijo Correlativo -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Código / Prefijo Correlativo * (Ej. MDC-TI-<span class="font-mono text-emerald-700 dark:text-emerald-400 font-bold">{{ $formCatCode ? strtoupper($formCatCode) : 'XXX' }}</span>-0001)
                            </label>
                            <input
                                type="text"
                                maxlength="6"
                                @if ($editingCategoryId) disabled @endif
                                required
                                placeholder="Ej. SRV, SWT, UPS"
                                wire:model.live="formCatCode"
                                class="w-full text-xs font-mono font-bold px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none disabled:opacity-50"
                            />
                            @if ($editingCategoryId)
                                <span class="text-[10px] text-slate-400 mt-1 block">
                                    El código de prefijo no puede modificarse para proteger la integridad de los códigos ya generados.
                                </span>
                            @endif
                        </div>

                        <!-- Ícono Representativo -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Ícono Representativo
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($presetCatIcons as $ic)
                                    <button
                                        type="button"
                                        wire:click="$set('formCatIcon', '{{ $ic }}')"
                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-lg transition-transform cursor-pointer {{ $formCatIcon === $ic ? 'scale-110 bg-emerald-100 dark:bg-emerald-900/50 ring-2 ring-emerald-500' : 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700' }}"
                                    >
                                        {{ $ic }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Color Distintivo -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Color Distintivo
                            </label>
                            <div class="flex flex-wrap gap-2 items-center">
                                @foreach ($presetCatColors as $col)
                                    <button
                                        type="button"
                                        wire:click="$set('formCatColor', '{{ $col }}')"
                                        class="w-7 h-7 rounded-full transition-transform cursor-pointer {{ $formCatColor === $col ? 'scale-125 ring-2 ring-offset-2 ring-slate-400 dark:ring-slate-500' : 'hover:scale-110' }}"
                                        style="background-color: {{ $col }};"
                                    >
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Descripción Institucional -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Descripción Institucional (Opcional)
                            </label>
                            <textarea
                                rows="2"
                                placeholder="Breve alcance sobre qué equipos abarca esta categoría..."
                                wire:model="formCatDescription"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                        <button
                            type="button"
                            wire:click="$set('isCategoryModalOpen', false)"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer"
                        >
                            {{ $editingCategoryId ? 'Actualizar Categoría' : 'Crear Categoría' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
