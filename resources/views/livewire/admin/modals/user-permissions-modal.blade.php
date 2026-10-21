    <!-- Modal Interactivo: Editar Permisos de Subsistema -->
    @if ($showEditModal && $selectedUser)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            <div
                class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative"
                @click.outside="$wire.closeModal()"
            >
                <!-- Botón Cerrar -->
                <button
                    type="button"
                    wire:click="closeModal"
                    class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold cursor-pointer transition-colors"
                >
                    &times;
                </button>

                <!-- Encabezado -->
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-1">
                    Editar Permisos de {{ $selectedUser['username'] }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">
                    Colaborador: <strong>{{ $selectedUser['fullName'] }}</strong> ({{ $selectedUser['email'] }})
                </p>

                <div class="space-y-4 text-xs">
                    <!-- Rol Asignado -->
                    <div>
                        <label class="block font-bold text-slate-800 dark:text-slate-200 mb-1">
                            Rol Asignado:
                        </label>
                        <select
                            wire:model.live="selectedUserRole"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-bold text-xs"
                        >
                            <option value="Administrador Central">Administrador Central</option>
                            <option value="Jefe de Oficina / Soporte TI">Jefe de Oficina / Soporte TI</option>
                            <option value="Técnico de Soporte e ITAM">Técnico de Soporte e ITAM</option>
                            <option value="Operador de Transportes">Operador de Transportes</option>
                            <option value="Personal General / Reportante">Personal General / Reportante</option>
                            <option value="Personalizado">Personalizado</option>
                        </select>
                    </div>

                    <!-- Subsistemas Autorizados -->
                    <div>
                        <label class="block font-bold text-slate-800 dark:text-slate-200 mb-2">
                            Subsistemas Autorizados:
                        </label>
                        <div class="space-y-2">
                            @php
                                $modulesForEdit = [
                                    [
                                        'code' => 'admin',
                                        'icon' => '🏢',
                                        'name' => 'Dashboard Central y Configuración',
                                        'route' => '/admin',
                                    ],
                                    [
                                        'code' => 'transportes',
                                        'icon' => '🚗',
                                        'name' => 'Licencias de Transportes',
                                        'route' => '/transportes',
                                    ],
                                    [
                                        'code' => 'itam',
                                        'icon' => '💻',
                                        'name' => 'Inventario y Gestión TI',
                                        'route' => '/itam',
                                    ],
                                    [
                                        'code' => 'helpdesk',
                                        'icon' => '🎧',
                                        'name' => 'Soporte Técnico y Helpdesk',
                                        'route' => '/soporte',
                                    ],
                                ];
                            @endphp

                            @foreach ($modulesForEdit as $mod)
                                @php
                                    $isChecked = in_array($mod['code'], $userSubsystems, true) || in_array('all', $userSubsystems, true);
                                @endphp
                                <div
                                    wire:click="toggleSubsystem('{{ $mod['code'] }}')"
                                    class="p-3 rounded-xl border cursor-pointer transition-all flex items-center justify-between {{ $isChecked ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-slate-500 opacity-60 hover:opacity-100' }}"
                                >
                                    <div class="flex items-center space-x-2.5">
                                        <span class="text-xl">{{ $mod['icon'] }}</span>
                                        <div>
                                            <span class="font-bold text-xs">{{ $mod['name'] }}</span>
                                            <span class="block text-[10px] text-slate-400 font-mono">{{ $mod['route'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $isChecked ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                        <span class="font-bold text-xs {{ $isChecked ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400' }}">
                                            {{ $isChecked ? 'Autorizado' : 'Sin Acceso' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="pt-4 flex justify-end space-x-2.5 border-t border-slate-200 dark:border-slate-700">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-5 py-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold cursor-pointer transition-colors text-xs"
                        >
                            Cancelar
                        </button>
                        <button
                            id="btn-save-permissions"
                            type="button"
                            wire:click="savePermissions"
                            class="px-5 py-2.5 rounded-xl bg-[#008f5d] hover:bg-emerald-800 text-white font-bold shadow-md cursor-pointer transition-colors text-xs"
                        >
                            Guardar Cambios de Permisos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
