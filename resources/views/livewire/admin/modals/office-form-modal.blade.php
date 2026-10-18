<!-- 6. Modal de Creación / Edición (OfficeFormModal) -->
    @if ($showOfficeModal))
        <div
            class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 animate-fadeIn"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden animate-scaleUp">
                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-emerald-700 via-teal-800 to-slate-900 text-white p-5 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                            🏢
                        </div>
                        <div>
                            <h3 class="font-bold text-base">
                                {{ $showEditModal ? 'Editar Dependencia / Oficina' : 'Nueva Dependencia / Oficina' }}
                            </h3>
                            <p class="text-xs text-emerald-100">
                                Estructura orgánica de la Municipalidad Distrital de Castilla
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="saveOffice" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Código Orgánico *
                            </label>
                            <input
                                type="text"
                                wire:model="formCode"
                                placeholder="Ej. 03.02.05.01"
                                class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-slate-800 dark:text-slate-200"
                                required
                            />
                            <span class="text-[10px] text-slate-400">Jerárquico (ej. 01, 02.01, 03.01.01)</span>
                            @error('formCode') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Sigla Oficial *
                            </label>
                            <input
                                type="text"
                                wire:model="formSigla"
                                placeholder="Ej. ODT, OSTR, GAT"
                                class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-bold text-slate-800 dark:text-slate-200"
                                required
                            />
                            @error('formSigla') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Nombre Completo de la Dependencia *
                        </label>
                        <input
                            type="text"
                            wire:model="formName"
                            placeholder="Ej. OFICINA DE DESARROLLO TECNOLÓGICO"
                            class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-medium text-slate-800 dark:text-slate-200"
                            required
                        />
                        @error('formName') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Dependencia Superior (Padre Orgánico)
                        </label>
                        <select
                            wire:model.live="formParentId"
                            class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-slate-200"
                        >
                            <option value="">-- Sin Dependencia Superior (Órgano Raíz / Nivel 1) --</option>
                            @foreach ($allOfficesCatalog as $o)
                                @if (! $showEditModal || $o['id'] !== $selectedOfficeId)
                                    <option value="{{ $o['id'] }}">
                                        {{ $o['code'] }} - [{{ $o['sigla'] }}] {{ $o['name'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo Sede Municipal (Requerimiento Específico) -->
                    <div class="p-3 bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-emerald-900 dark:text-emerald-300 flex items-center space-x-1.5">
                                <span>📍</span>
                                <span>Sede Municipal de Ubicación *</span>
                            </label>
                            <button
                                type="button"
                                wire:click="$toggle('isCustomSede')"
                                class="text-[11px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold cursor-pointer"
                            >
                                {{ $isCustomSede ? '← Seleccionar sede existente' : '+ Escribir otra sede' }}
                            </button>
                        </div>

                        @if (! $isCustomSede)
                            <select
                                wire:model="formSede"
                                wire:change="onSedeChange($event.target.value)"
                                class="w-full text-xs p-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-semibold text-slate-800 dark:text-slate-200"
                            >
                                @foreach ($sedes as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                                <option value="OTRA">+ Registrar / Especificar nueva Sede...</option>
                            </select>
                        @else
                            <input
                                type="text"
                                wire:model="formCustomSede"
                                placeholder="Ej. SEDE ANEXO BIBLIOTECA MUNICIPAL"
                                class="w-full text-xs p-2.5 bg-white dark:bg-slate-900 border border-emerald-400 dark:border-emerald-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-semibold text-slate-800 dark:text-slate-200"
                                required
                            />
                        @endif

                        <p class="text-[10px] text-slate-500 dark:text-slate-400">
                            Indica la sede física o local institucional donde opera esta dependencia.
                        </p>
                        @error('formCustomSede') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input
                            type="checkbox"
                            id="isActiveCheck"
                            wire:model="formIsActive"
                            class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer"
                        />
                        <label for="isActiveCheck" class="text-xs text-slate-700 dark:text-slate-300 font-medium cursor-pointer">
                            Dependencia Activa en el Organigrama Municipal
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl shadow-md transition-all cursor-pointer"
                        >
                            {{ $showEditModal ? 'Actualizar Dependencia' : 'Registrar Dependencia' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
