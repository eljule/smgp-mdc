    <!-- 5. Modal: Nuevo / Editar Subsistema -->
    @if ($showSubsystemModal))
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden relative max-h-[92vh] overflow-y-auto"
                @click.outside="$wire.closeModal()"
            >
                <!-- Cabecera Institucional Verde -->
                <div class="bg-[#00875a] px-6 py-4 flex items-center justify-between text-white sticky top-0 z-10">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 dark:bg-white/10 flex items-center justify-center text-xl shadow-xs shrink-0">
                            {{ $formIcon ?: '🏢' }}
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-white leading-tight">
                                {{ $showEditModal ? 'Editar Subsistema SIGM' : 'Registrar Nuevo Subsistema' }}
                            </h3>
                            <p class="text-xs text-emerald-100 font-normal mt-0.5">
                                {{ $showEditModal ? 'Modifique los parámetros operativos y visuales del módulo' : 'Añada un nuevo módulo o subsistema a la plataforma municipal' }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition-colors text-base font-bold cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="saveModule" class="p-6 space-y-4">
                    <!-- Fila 1: Código (Slug) & Nombre -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                CÓDIGO IDENTIFICADOR (SLUG)
                            </label>
                            <input
                                type="text"
                                wire:model="formCode"
                                required
                                placeholder="ej. rentas_tributaria"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            <span class="text-[10px] text-slate-400 mt-1 block">Identificador en permisos y base de datos.</span>
                            @error('formCode') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                NOMBRE DEL SUBSISTEMA
                            </label>
                            <input
                                type="text"
                                wire:model="formName"
                                required
                                placeholder="ej. Administración Tributaria (Rentas)"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            @error('formName') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 2: Descripción Funcional -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                            DESCRIPCIÓN FUNCIONAL
                        </label>
                        <textarea
                            wire:model="formDescription"
                            required
                            rows="2"
                            placeholder="Describa el alcance de este subsistema..."
                            class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"
                        ></textarea>
                        @error('formDescription') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Fila 3: Ruta de Acceso & Orden -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                RUTA DE ACCESO (URL)
                            </label>
                            <input
                                type="text"
                                wire:model="formRoute"
                                required
                                placeholder="/"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            @error('formRoute') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                ORDEN DE VISUALIZACIÓN
                            </label>
                            <input
                                type="number"
                                wire:model="formOrder"
                                min="1"
                                required
                                placeholder="5"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            @error('formOrder') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 4: Icono Representativo -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                            ICONO REPRESENTATIVO (EMOJI O SVG)
                        </label>
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div class="w-24 h-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center text-xl shadow-2xs font-bold shrink-0">
                                {{ $formIcon }}
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Seleccione un emoji rápido:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $presetIcons = ['🏢', '🚗', '💻', '🎧', '📊', '📑', '🏛️', '🛡️', '⚙️', '📂', '💳', '🚚'];
                            @endphp
                            @foreach ($presetIcons as $emoji)
                                <button
                                    type="button"
                                    wire:click="$set('formIcon', '{{ $emoji }}')"
                                    class="w-8 h-8 rounded-lg border text-base flex items-center justify-center transition-all cursor-pointer {{ $formIcon === $emoji
                                        ? 'bg-emerald-100 dark:bg-emerald-950/60 border-emerald-500 scale-110 shadow-xs'
                                        : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-750'
                                    }}"
                                >
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fila 5: Color Distintivo de Acento -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                            COLOR DISTINTIVO DE ACENTO
                        </label>
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div
                                class="w-9 h-9 rounded-xl border border-slate-300 dark:border-slate-700 p-0.5 shadow-2xs"
                                style="background-color: {{ $formAccentColorHex }};"
                            ></div>
                            <span class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $formAccentColorHex }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            @php
                                $presetColors = ['#16a34a', '#ea580c', '#2563eb', '#7c3aed', '#059669', '#dc2626', '#0891b2', '#d97706'];
                            @endphp
                            @foreach ($presetColors as $c)
                                <button
                                    type="button"
                                    wire:click="selectAccentColor('{{ $c }}')"
                                    class="w-6 h-6 rounded-full border-2 transition-transform cursor-pointer {{ $formAccentColorHex === $c
                                        ? 'scale-125 border-slate-900 dark:border-white shadow'
                                        : 'border-transparent hover:scale-110'
                                    }}"
                                    style="background-color: {{ $c }};"
                                ></button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fila 6: Opciones de Estado -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="formIsActive"
                                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                            />
                            <span>Subsistema Activo en el Sistema</span>
                        </label>

                        <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="formRequiresAuth"
                                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                            />
                            <span>Requiere Autenticación / Roles</span>
                        </label>
                    </div>

                    <!-- Fila 7: Botones -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#00875a] hover:bg-[#00734c] active:bg-[#005e3e] shadow-md transition-all cursor-pointer"
                        >
                            {{ $showEditModal ? 'Guardar Cambios' : 'Registrar Subsistema' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
