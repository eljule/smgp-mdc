    <!-- Modal: Crear / Editar Rol Institucional -->
    @if ($showRoleModal))
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            <div
                class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl relative"
                @click.outside="$wire.closeModal()"
            >
                <button
                    type="button"
                    wire:click="closeModal"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold cursor-pointer transition-colors"
                >
                    &times;
                </button>

                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xl font-bold shadow-sm">
                        🎭
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">
                            {{ $isEditing ? 'Editar Rol Institucional' : 'Crear Nuevo Rol Institucional' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $isEditing ? 'Modifique la información base del rol institucional.' : 'Defina un nuevo rol para asignarle permisos granulares por subsistema.' }}
                        </p>
                    </div>
                </div>

                <form wire:submit.prevent="saveRole" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-800 dark:text-slate-200 mb-1">
                            Nombre del Rol: <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model.live="formRoleName"
                            placeholder="Ej. Operador de Fiscalización Tributaria"
                            required
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white focus:ring-1 focus:ring-emerald-500 font-medium"
                        />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 dark:text-slate-200 mb-1">
                            Identificador / Código de Sistema: <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="formRoleCode"
                            placeholder="Ej. tax_inspector"
                            required
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white focus:ring-1 focus:ring-emerald-500 font-mono text-xs"
                        />
                        <span class="text-[11px] text-slate-400 mt-0.5 block">
                            Código único en minúsculas y sin espacios.
                        </span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 dark:text-slate-200 mb-1">
                            Subsistema / Ámbito de Aplicación:
                        </label>
                        <select
                            wire:model="formRoleScope"
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-medium"
                        >
                            <option value="global">Multisubsistema (Global)</option>
                            <option value="transportes">Licencias de Transporte</option>
                            <option value="itam">Inventario TI (ITAM)</option>
                            <option value="helpdesk">Soporte y Helpdesk</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-800 dark:text-slate-200 mb-1">
                            Descripción Funcional:
                        </label>
                        <textarea
                            wire:model="formRoleDescription"
                            rows="2.5"
                            placeholder="Describa el alcance operativo de las facultades de este rol..."
                            class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white focus:ring-1 focus:ring-emerald-500 font-normal leading-relaxed resize-none"
                        ></textarea>
                    </div>

                    <div class="pt-4 flex justify-end space-x-2.5 border-t border-slate-200 dark:border-slate-700">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold cursor-pointer transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#008f5d] hover:bg-emerald-800 text-white font-bold shadow-md cursor-pointer transition-colors"
                        >
                            {{ $isEditing ? 'Actualizar Rol' : 'Guardar Nuevo Rol' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
