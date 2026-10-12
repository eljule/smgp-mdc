<div class="space-y-6 animate-fadeIn">
    <!-- Notificación Flash -->
    @if ($flashMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center justify-between text-xs sm:text-sm font-semibold transition-all shadow-xs"
        >
            <div class="flex items-center space-x-2">
                <span class="text-base">✅</span>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800 font-bold cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Gestión Intuitiva de Usuarios y Permisos
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Cree cuentas de usuario vinculadas a servidores municipales y otorgue permisos por subsistema con un solo clic.
            </p>
        </div>

        <button
            type="button"
            wire:click="openCreateUserModal"
            class="px-4 py-2.5 rounded-lg bg-[#0d6e3c] hover:bg-[#0b5c32] text-white font-semibold text-xs sm:text-sm shadow-sm transition-all hover:shadow flex items-center space-x-2 shrink-0 cursor-pointer"
        >
            <span class="font-bold">+</span>
            <span>Nuevo Usuario con Permisos</span>
        </button>
    </div>

    <!-- Plantillas de Roles Rápidos Informativas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($presets as $preset)
            @php
                $dotColorHex = match ($preset['dotColor'] ?? '') {
                    'emerald' => '#16a34a',
                    'blue' => '#2563eb',
                    'amber' => '#ea580c',
                    'purple' => '#7c3aed',
                    default => '#16a34a',
                };
            @endphp
            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <span
                            class="w-3 h-3 rounded-full shrink-0"
                            style="background-color: {{ $dotColorHex }};"
                        ></span>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">
                            {{ $preset['title'] }}
                        </h4>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                        {{ $preset['description'] }}
                    </p>
                </div>
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[10px] text-slate-400">
                    <span>{{ $preset['modulesCount'] }} módulos habilitados</span>
                    <button
                        type="button"
                        wire:click="setRoleFilter('{{ $preset['title'] }}')"
                        class="font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 cursor-pointer"
                    >
                        Plantilla
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Listado de Usuarios -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                    Cuentas de Usuario Activas ({{ $counts['active'] ?? 49 }})
                </h3>
                @if ($roleFilter !== 'TODOS')
                    <span class="inline-flex items-center space-x-1 text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 font-semibold">
                        <span>Filtro: {{ $roleFilter }}</span>
                        <button type="button" wire:click="setRoleFilter('TODOS')" class="ml-1 text-emerald-600 hover:text-emerald-900 font-bold cursor-pointer">&times;</button>
                    </span>
                @endif
            </div>
            <span class="text-xs text-slate-400">Haga clic en &quot;Editar Permisos&quot; para ajustar subsistemas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Usuario</th>
                        <th class="py-3 px-4">Nombre Completo</th>
                        <th class="py-3 px-4">Rol Principal</th>
                        <th class="py-3 px-4">Subsistemas Autorizados</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($users as $user)
                        @php
                            $isRoot = strtoupper($user['username']) === 'ROOT';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <!-- Usuario -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900 dark:text-white">
                                <span class="inline-flex items-center space-x-1.5">
                                    <span class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] text-slate-700 dark:text-slate-200 font-bold shrink-0">
                                        {{ strtolower(substr($user['username'], 0, 2)) }}
                                    </span>
                                    <span>{{ $user['username'] }}</span>
                                </span>
                            </td>

                            <!-- Nombre Completo -->
                            <td class="py-3.5 px-4 text-slate-800 dark:text-slate-200 font-medium">
                                {{ $user['fullName'] }}
                                <span class="block text-[10px] text-slate-400 lowercase">{{ $user['email'] }}</span>
                            </td>

                            <!-- Rol Principal -->
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-300/60 dark:border-slate-700">
                                    {{ $user['primaryRole'] }}
                                </span>
                            </td>

                            <!-- Subsistemas Autorizados -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @if ($isRoot || in_array('all', $user['subsystems'], true))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                                            🍃 Todos los subsistemas (Acceso Total)
                                        </span>
                                    @else
                                        @foreach ($user['subsystems'] as $subKey)
                                            @if ($subKey === 'admin' || $subKey === 'central_dashboard')
                                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold text-white shadow-xs bg-[#16a34a]" title="Dashboard Central y Configuración">
                                                    <span>📊</span>
                                                    <span class="truncate max-w-[140px]">Dashboard Central y Co...</span>
                                                </span>
                                            @elseif ($subKey === 'itam' || $subKey === 'it_inventory')
                                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold text-white shadow-xs bg-[#2563eb]" title="Inventario y Gestión TI">
                                                    <span>💻</span>
                                                    <span class="truncate max-w-[140px]">Inventario y Gestión TI</span>
                                                </span>
                                            @elseif ($subKey === 'helpdesk' || $subKey === 'helpdesk_support')
                                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold text-white shadow-xs bg-[#7c3aed]" title="Soporte Técnico y Helpdesk">
                                                    <span>🎧</span>
                                                    <span class="truncate max-w-[140px]">Soporte Técnico y Help...</span>
                                                </span>
                                            @elseif ($subKey === 'transportes' || $subKey === 'transport_licenses')
                                                <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold text-white shadow-xs bg-[#ea580c]" title="Licencias de Transportes">
                                                    <span>🚗</span>
                                                    <span class="truncate max-w-[140px]">Gestión de Transportes</span>
                                                </span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                                <span class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400">
                                    Activo
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="py-3.5 px-4 text-center">
                                <button
                                    type="button"
                                    wire:click="openEditPermissions({{ $user['id'] }})"
                                    class="px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-semibold text-xs hover:bg-emerald-100 transition-colors cursor-pointer"
                                >
                                    ⚙ Editar Permisos
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <p class="text-base font-semibold">No se encontraron cuentas de usuario</p>
                                <p class="text-xs mt-1">Pruebe ajustando el término de búsqueda o el filtro de rol.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($total > 0)
            <div class="px-4 sm:px-6 py-3.5 bg-slate-50/90 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 text-xs flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-3 text-slate-600 dark:text-slate-300">
                    <span>
                        Mostrando <strong class="font-bold text-slate-900 dark:text-white">{{ $from }}</strong> a <strong class="font-bold text-slate-900 dark:text-white">{{ $to }}</strong> de <strong class="font-bold text-slate-900 dark:text-white">{{ $total }}</strong> usuarios
                    </span>
                    <div class="flex items-center space-x-1.5 pl-2 border-l border-slate-300 dark:border-slate-700">
                        <span class="text-[11px] text-slate-400">Filas por pág:</span>
                        <select
                            wire:model.live="perPage"
                            class="py-1 px-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:outline-none cursor-pointer"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-1 select-none">
                    <button
                        type="button"
                        wire:click="gotoPage(1)"
                        @disabled($currentPage <= 1)
                        class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                        title="Primera página"
                    >
                        &laquo;
                    </button>
                    <button
                        type="button"
                        wire:click="gotoPage({{ $currentPage - 1 }})"
                        @disabled($currentPage <= 1)
                        class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium text-[11px] cursor-pointer"
                        title="Página anterior"
                    >
                        &lsaquo; Anterior
                    </button>

                    <div class="flex items-center space-x-1 px-1">
                        @for ($p = 1; $p <= $totalPages; $p++)
                            <button
                                type="button"
                                wire:click="gotoPage({{ $p }})"
                                class="min-w-[28px] h-7 px-2 text-xs font-bold rounded-lg transition-colors cursor-pointer {{ $currentPage === $p ? 'bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-500' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                            >
                                {{ $p }}
                            </button>
                        @endfor
                    </div>

                    <button
                        type="button"
                        wire:click="gotoPage({{ $currentPage + 1 }})"
                        @disabled($currentPage >= $totalPages)
                        class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium text-[11px] cursor-pointer"
                        title="Página siguiente"
                    >
                        Siguiente &rsaquo;
                    </button>
                    <button
                        type="button"
                        wire:click="gotoPage({{ $totalPages }})"
                        @disabled($currentPage >= $totalPages)
                        class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                        title="Última página"
                    >
                        &raquo;
                    </button>
                </div>
            </div>
        @endif
    </div>

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

    <!-- Modal: Crear Usuario y Otorgar Permisos -->
    @include('livewire.admin.partials.user-form-modal')
</div>
