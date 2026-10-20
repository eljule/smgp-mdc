<div class="space-y-6 animate-fadeIn">
    <!-- Notificación Flotante con Rebote -->
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

    <!-- Barra de Búsqueda Inteligente y Filtros Rápidos -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <!-- Pestañas de Filtro por Rol Principal -->
        <div class="flex items-center space-x-1.5 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg text-xs w-full sm:w-auto overflow-x-auto">
            <button
                type="button"
                wire:click="setRoleFilter('TODOS')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer whitespace-nowrap {{ $roleFilter === 'TODOS'
                    ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                Todos ({{ $counts['total'] ?? 49 }})
            </button>
            <button
                type="button"
                wire:click="setRoleFilter('Administrador Central')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer flex items-center justify-center space-x-1.5 whitespace-nowrap {{ $roleFilter === 'Administrador Central'
                    ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Admin Central</span>
            </button>
            <button
                type="button"
                wire:click="setRoleFilter('Técnico de Soporte e ITAM')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer flex items-center justify-center space-x-1.5 whitespace-nowrap {{ $roleFilter === 'Técnico de Soporte e ITAM'
                    ? 'bg-white dark:bg-slate-900 text-blue-800 dark:text-blue-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                <span>Soporte e ITAM</span>
            </button>
            <button
                type="button"
                wire:click="setRoleFilter('Operador de Transportes')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer flex items-center justify-center space-x-1.5 whitespace-nowrap {{ $roleFilter === 'Operador de Transportes'
                    ? 'bg-white dark:bg-slate-900 text-amber-800 dark:text-amber-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Transportes</span>
            </button>
            <button
                type="button"
                wire:click="setRoleFilter('Personal General / Reportante')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer flex items-center justify-center space-x-1.5 whitespace-nowrap {{ $roleFilter === 'Personal General / Reportante'
                    ? 'bg-white dark:bg-slate-900 text-purple-800 dark:text-purple-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                <span>Personal General</span>
            </button>
        </div>

        <!-- Buscador Inteligente -->
        <div class="flex items-center space-x-2 w-full sm:w-80 shrink-0">
            <div class="relative w-full">
                <input
                    id="users-search-input"
                    type="text"
                    placeholder="Buscar por DNI, nombre..."
                    wire:model.live.debounce.300ms="search"
                    class="w-full px-3 py-1.5 text-xs rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 shadow-2xs"
                />
                @if (!empty($search))
                    <button
                        type="button"
                        wire:click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition cursor-pointer"
                        title="Limpiar búsqueda"
                    >
                        <span class="text-sm font-bold leading-none">&times;</span>
                    </button>
                @endif
            </div>
            <button
                type="button"
                wire:click="$refresh"
                class="px-3.5 py-1.5 rounded-lg bg-[#00965e] hover:bg-[#007f50] text-white text-xs font-semibold shadow-xs transition cursor-pointer shrink-0"
            >
                Buscar
            </button>
        </div>
    </div>

    <!-- Listado de Usuarios -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                    Cuentas de Usuario Activas ({{ $counts['active'] ?? 49 }})
                </h3>
                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    {{ $total }} {{ $total === 1 ? 'resultado' : 'resultados' }}
                </span>
                @if ($roleFilter !== 'TODOS')
                    <span class="inline-flex items-center space-x-1 text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 font-semibold">
                        <span>Rol: {{ $roleFilter }}</span>
                        <button type="button" wire:click="setRoleFilter('TODOS')" class="ml-1 text-emerald-600 hover:text-emerald-900 font-bold cursor-pointer">&times;</button>
                    </span>
                @endif
                @if (!empty($search))
                    <span class="inline-flex items-center space-x-1 text-xs px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 font-semibold">
                        <span>Búsqueda: &quot;{{ $search }}&quot;</span>
                        <button type="button" wire:click="clearSearch" class="ml-1 text-blue-600 hover:text-blue-900 font-bold cursor-pointer">&times;</button>
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
                                <div class="flex items-center space-x-2">
                                    <span class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-[10px] text-slate-700 dark:text-slate-200 font-bold shrink-0">
                                        {{ strtolower(substr($user['username'], 0, 2)) }}
                                    </span>
                                    <div>
                                        <span class="block leading-tight text-xs font-mono font-bold">{{ $user['username'] }}</span>
                                        @if (!empty($user['dni']) && $user['dni'] !== $user['username'])
                                            <span class="inline-flex items-center text-[10px] text-slate-400 dark:text-slate-500 font-sans font-normal">
                                                DNI: {{ $user['dni'] }}
                                            </span>
                                        @elseif (is_numeric($user['username']) && strlen((string)$user['username']) === 8)
                                            <span class="inline-block text-[9px] px-1.5 py-0.2 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 font-sans font-semibold">
                                                DNI
                                            </span>
                                        @endif
                                    </div>
                                </div>
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
                                @if (! empty($user['isLocked']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        <span>Bloqueado</span>
                                    </span>
                                @elseif (($user['status'] ?? 'Activo') === 'Activo')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 dark:text-emerald-400">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Activo</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        <span>Inactivo</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    <button
                                        id="btn-edit-permissions-{{ $user['id'] }}"
                                        type="button"
                                        wire:click="openEditPermissions({{ $user['id'] }}, '{{ $user['username'] }}')"
                                        class="px-2.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-semibold text-xs hover:bg-emerald-100 transition-colors cursor-pointer"
                                        title="Modificar permisos y roles"
                                    >
                                        ⚙ Permisos
                                    </button>

                                    <button
                                        id="btn-reset-password-{{ $user['id'] }}"
                                        type="button"
                                        wire:click="openResetPassword({{ $user['id'] }}, '{{ $user['username'] }}')"
                                        class="px-2.5 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-800 text-amber-800 dark:text-amber-300 font-semibold text-xs hover:bg-amber-100 transition-colors cursor-pointer"
                                        title="Resetear contraseña provisional"
                                    >
                                        🔑 Reset Clave
                                    </button>

                                    @if (! empty($user['isLocked']))
                                        <button
                                            id="btn-unlock-user-{{ $user['id'] }}"
                                            type="button"
                                            wire:click="unlockUser({{ $user['id'] }}, '{{ $user['username'] }}')"
                                            class="px-2.5 py-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800 text-rose-700 dark:text-rose-300 font-bold text-xs hover:bg-rose-100 transition-colors cursor-pointer"
                                            title="Desbloquear cuenta bloqueada"
                                        >
                                            🔓 Desbloquear
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-xl">
                                        🔍
                                    </div>
                                    <div class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                        No se encontraron cuentas de usuario
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">
                                        @if (!empty($search))
                                            No hay usuarios que coincidan con la búsqueda <strong class="text-slate-700 dark:text-slate-200">&quot;{{ $search }}&quot;</strong>.
                                        @else
                                            No hay usuarios registrados bajo los filtros seleccionados.
                                        @endif
                                    </p>
                                    @if (!empty($search) || $roleFilter !== 'TODOS')
                                        <button
                                            type="button"
                                            wire:click="clearSearch(); setRoleFilter('TODOS')"
                                            class="mt-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-emerald-700 dark:text-emerald-400 text-xs font-bold transition cursor-pointer"
                                        >
                                            Restablecer Filtros y Búsqueda
                                        </button>
                                    @endif
                                </div>
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

