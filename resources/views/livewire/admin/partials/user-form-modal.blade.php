@php
    $rolePresetsList = [
        [
            'role' => 'Administrador Central',
            'modulesCount' => 4,
        ],
        [
            'role' => 'Técnico de Soporte e ITAM',
            'modulesCount' => 3,
        ],
        [
            'role' => 'Operador de Transportes',
            'modulesCount' => 2,
        ],
        [
            'role' => 'Personal General / Reportante',
            'modulesCount' => 1,
        ],
    ];

    $availableModulesList = [
        [
            'code' => 'admin',
            'icon' => '🏢',
            'name' => 'Dashboard Central y Configuración',
            'description' => 'Gestión central de usuarios, permisos, personas y tablas maestras.',
        ],
        [
            'code' => 'transportes',
            'icon' => '🚗',
            'name' => 'Licencias de Transportes',
            'description' => 'Empadronamiento de mototaxis, licencias clase B y fiscalización.',
        ],
        [
            'code' => 'itam',
            'icon' => '💻',
            'name' => 'Inventario y Gestión TI',
            'description' => 'Activos de hardware/software (ITAM), dependencias y garantías.',
        ],
        [
            'code' => 'helpdesk',
            'icon' => '🎧',
            'name' => 'Soporte Técnico y Helpdesk',
            'description' => 'Mesa de ayuda informática, tickets y atención a usuarios.',
        ],
    ];
@endphp

@if ($showUserModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[92vh] overflow-y-auto">
            <!-- Botón Cerrar -->
            <button
                type="button"
                wire:click="closeUserModal"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-2xl font-bold cursor-pointer transition-colors"
            >
                &times;
            </button>

            <!-- Encabezado -->
            <div class="mb-5">
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-1">
                    Crear Usuario y Otorgar Permisos
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Vincule una persona municipal y asigne los subsistemas a los que tendrá acceso de forma gráfica.
                </p>
            </div>

            <!-- Error Banner -->
            @if ($userModalError)
                <div class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center space-x-2">
                    <span>⚠️</span>
                    <span>{{ $userModalError }}</span>
                </div>
            @endif

            <form wire:submit.prevent="saveUser" class="space-y-4 text-xs">
                <!-- 1. Vincular Servidor Municipal (Personal) -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-xs text-slate-800 dark:text-slate-200">
                            1. Vincular Servidor Municipal (Personal):
                        </label>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">
                            Autocompleta nombre y sugerencia de usuario
                        </span>
                    </div>

                    <select
                        wire:model.live="userFormPersonId"
                        class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-medium text-xs"
                    >
                        <option value="">-- Seleccionar Persona de la institución (Opcional) --</option>
                        @foreach ($personnelList as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->full_name }} (DNI: {{ $p->document_number }} - {{ $p->office }})
                            </option>
                        @endforeach
                    </select>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <label class="block font-bold text-xs text-slate-700 dark:text-slate-300 mb-1">
                                Nombre Completo *
                            </label>
                            <input
                                type="text"
                                required
                                wire:model="userFormFullName"
                                placeholder="Ej. Juan Alberto Pérez"
                                class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-xs"
                            />
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-700 dark:text-slate-300 mb-1">
                                Usuario de Ingreso *
                            </label>
                            <input
                                type="text"
                                required
                                wire:model="userFormUsername"
                                placeholder="Ej. jperez"
                                class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-mono font-bold text-xs"
                            />
                        </div>

                        <div>
                            <label class="block font-bold text-xs text-slate-700 dark:text-slate-300 mb-1">
                                Contraseña Inicial *
                            </label>
                            <input
                                type="text"
                                required
                                wire:model="userFormPassword"
                                placeholder="Mínimo 4 caracteres"
                                class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-mono text-xs"
                            />
                        </div>
                    </div>
                </div>

                <!-- 2. Asignación Rápida con Plantilla de Rol -->
                <div>
                    <label class="block font-bold text-xs text-slate-800 dark:text-slate-200 mb-2">
                        2. Asignación Rápida con Plantilla de Rol:
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach ($rolePresetsList as $preset)
                            @php $isRoleActive = $userFormRole === $preset['role']; @endphp
                            <button
                                key="{{ $preset['role'] }}"
                                type="button"
                                wire:click="applyRolePreset('{{ $preset['role'] }}')"
                                class="p-2.5 rounded-xl border text-left transition-all cursor-pointer {{ $isRoleActive ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200 ring-2 ring-emerald-500/30' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:border-slate-300' }}"
                            >
                                <span class="block font-bold text-xs leading-tight mb-1">
                                    {{ $preset['role'] }}
                                </span>
                                <span class="block text-[10px] {{ $isRoleActive ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400' }}">
                                    {{ $preset['modulesCount'] }} módulos
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Módulos Autorizados (Haga clic para encender o apagar) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="font-bold text-xs text-slate-800 dark:text-slate-200">
                            3. Módulos Autorizados (Haga clic para encender o apagar):
                        </label>
                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                            {{ count($userFormModules) }} de 4 asignados
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($availableModulesList as $mod)
                            @php $isChecked = in_array($mod['code'], $userFormModules, true); @endphp
                            <div
                                wire:click="toggleUserModule('{{ $mod['code'] }}')"
                                class="p-3.5 rounded-xl border cursor-pointer transition-all flex items-start space-x-3 {{ $isChecked ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/30 ring-1 ring-emerald-500' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 opacity-60 hover:opacity-100' }}"
                            >
                                <input
                                    type="checkbox"
                                    @checked($isChecked)
                                    class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 mt-0.5 pointer-events-none"
                                />
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-base">{{ $mod['icon'] }}</span>
                                        <span class="font-bold text-xs text-slate-900 dark:text-white">
                                            {{ $mod['name'] }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">
                                        {{ $mod['description'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="pt-4 flex justify-end space-x-2.5 border-t border-slate-200 dark:border-slate-700">
                    <button
                        type="button"
                        wire:click="closeUserModal"
                        class="px-5 py-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold cursor-pointer transition-colors text-xs"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#008f5d] hover:bg-emerald-800 text-white font-bold shadow-md cursor-pointer transition-colors flex items-center space-x-1.5 text-xs"
                    >
                        <span>✓</span>
                        <span>Guardar Usuario y Permisos</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif