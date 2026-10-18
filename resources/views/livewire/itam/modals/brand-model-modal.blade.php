<!-- ========================================================================= -->
    <!-- MODAL 9: MARCA / MODELO CRUD (SRS RF-01)                                 -->
    <!-- ========================================================================= -->
    @if ($isBrandModelModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header Institucional -->
                <div class="px-6 py-4 border-b border-emerald-800 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-100 flex items-center justify-center font-bold text-lg shadow-inner">
                            {{ $catalogType === 'brand' ? '🏷️' : '📦' }}
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">
                                @if ($catalogType === 'brand')
                                    {{ $editingBrandId ? 'Editar Marca' : 'Registrar Nueva Marca' }}
                                @else
                                    {{ $editingModelId ? 'Editar Modelo' : 'Registrar Nuevo Modelo' }}
                                @endif
                            </h2>
                            <p class="text-xs text-emerald-200/90">
                                {{ ($editingBrandId || $editingModelId) ? 'Modificación en catálogo institucional' : 'Catálogos de Hardware y Software TI' }}
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="$set('isBrandModelModalOpen', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-emerald-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveBrandOrModel">
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

                        @if ($catalogType === 'model')
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Marca Fabricante *
                                </label>
                                <select wire:model="formModelBrandId"
                                        required
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                    @foreach ($allBrands as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Categoría Habitual (Opcional)
                                </label>
                                <select wire:model="formModelCategoryId"
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                    <option value="">-- General / Múltiples Categorías --</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->icon }} {{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Nombre del Modelo / Línea *
                                </label>
                                <input type="text"
                                       required
                                       placeholder="Ej. Latitude 5420, LaserJet Pro"
                                       wire:model="formModelName"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Descripción / Notas (Opcional)
                                </label>
                                <textarea rows="2"
                                          placeholder="Detalles sobre serie o características..."
                                          wire:model="formModelDescription"
                                          class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                            </div>

                            @if ($editingModelId)
                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox"
                                           id="isModelActiveCheck"
                                           wire:model="formModelIsActive"
                                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700" />
                                    <label for="isModelActiveCheck"
                                           class="text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                        Estado Activo (disponible para asignación a activos)
                                    </label>
                                </div>
                            @endif
                        @else
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Nombre de la Marca *
                                </label>
                                <input type="text"
                                       required
                                       placeholder="Ej. Asus, Cisco, Canon"
                                       wire:model="formBrandName"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Descripción / Notas (Opcional)
                                </label>
                                <textarea rows="2"
                                          placeholder="Detalles sobre serie o características..."
                                          wire:model="formBrandDescription"
                                          class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                            </div>

                            @if ($editingBrandId)
                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox"
                                           id="isBrandActiveCheck"
                                           wire:model="formBrandIsActive"
                                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700" />
                                    <label for="isBrandActiveCheck"
                                           class="text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                        Estado Activo (disponible para asignación a activos)
                                    </label>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                        <button type="button"
                                wire:click="$set('isBrandModelModalOpen', false)"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="submit"
                                class="px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer">
                            @if ($catalogType === 'brand')
                                {{ $editingBrandId ? 'Actualizar Marca' : 'Guardar Marca' }}
                            @else
                                {{ $editingModelId ? 'Actualizar Modelo' : 'Guardar Modelo' }}
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
