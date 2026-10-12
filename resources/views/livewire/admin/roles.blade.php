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

    <!-- 1. Header con Título y Acciones -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2.5">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Mantenimiento de Roles Institucionales
                </h2>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold border border-emerald-300/40">
                    {{ $counts['total'] }} roles
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Administre los perfiles institucionales de la Municipalidad de Castilla y configure los permisos asociados a cada función.
            </p>
        </div>

        <button
            type="button"
            wire:click="openCreateModal"
            class="px-4 py-2.5 rounded-lg bg-[#0d6e3c] hover:bg-[#0b5c32] text-white font-semibold text-xs sm:text-sm shadow-sm transition-all hover:shadow flex items-center space-x-2 shrink-0 cursor-pointer"
        >
            <span class="text-base font-bold">+</span>
            <span>Nuevo Rol Institucional</span>
        </button>
    </div>

    <!-- 2. Tarjetas de Resumen Rápido -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Roles Registrados -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    TOTAL ROLES REGISTRADOS
                </span>
                <p class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">{{ $counts['total'] }}</p>
                <p class="text-[10px] text-slate-500">Perfiles de seguridad activos</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-lg font-bold">
                🎭
            </div>
        </div>

        <!-- Roles Base del Sistema -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    ROLES BASE DEL SISTEMA
                </span>
                <p class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-0.5">{{ $counts['system'] }}</p>
                <p class="text-[10px] text-slate-500">Protegidos contra eliminación accidental</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300 flex items-center justify-center text-lg font-bold">
                🔒
            </div>
        </div>

        <!-- Roles Personalizados -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    ROLES PERSONALIZADOS
                </span>
                <p class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-0.5">{{ $counts['custom'] }}</p>
                <p class="text-[10px] text-slate-500">Creados por la administración municipal</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 flex items-center justify-center text-lg font-bold">
                👤
            </div>
        </div>
    </div>

    <!-- 3. Barra de Búsqueda y Filtros -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
            <button
                type="button"
                wire:click="setScopeFilter('TODOS')"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer {{ $scopeFilter === 'TODOS'
                    ? 'bg-[#0d6e3c] text-white shadow-xs'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                Todos ({{ $counts['total'] }})
            </button>

            <button
                type="button"
                wire:click="setScopeFilter('transportes')"
                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1 cursor-pointer {{ $scopeFilter === 'transportes'
                    ? 'bg-[#0d6e3c] text-white shadow-xs'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>🚗</span>
                <span>Licencias de Transporte</span>
                <span class="text-[10px] opacity-80">({{ $counts['transportes'] }})</span>
            </button>

            <button
                type="button"
                wire:click="setScopeFilter('itam')"
                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1 cursor-pointer {{ $scopeFilter === 'itam'
                    ? 'bg-[#0d6e3c] text-white shadow-xs'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>💻</span>
                <span>Inventario TI (ITAM)</span>
                <span class="text-[10px] opacity-80">({{ $counts['itam'] }})</span>
            </button>

            <button
                type="button"
                wire:click="setScopeFilter('helpdesk')"
                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1 cursor-pointer {{ $scopeFilter === 'helpdesk'
                    ? 'bg-[#0d6e3c] text-white shadow-xs'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>🎧</span>
                <span>Soporte y Helpdesk</span>
                <span class="text-[10px] opacity-80">({{ $counts['helpdesk'] }})</span>
            </button>

            <button
                type="button"
                wire:click="setScopeFilter('global')"
                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1 cursor-pointer {{ $scopeFilter === 'global'
                    ? 'bg-[#0d6e3c] text-white shadow-xs'
                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>🌐</span>
                <span>Multisubsistema (Global)</span>
                <span class="text-[10px] opacity-80">({{ $counts['global'] }})</span>
            </button>
        </div>

        <div class="w-full sm:w-64">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por rol, código..."
                class="w-full px-3 py-1.5 text-xs rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
            />
        </div>
    </div>

    <!-- 4. Listado de Roles -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                Listado de Roles ({{ $total }})
            </h3>
            <span class="text-xs text-slate-400">
                Haga clic en "<a href="{{ route('admin.matrix') }}" wire:navigate class="text-amber-500 hover:text-amber-600 font-bold inline-flex items-center gap-0.5 cursor-pointer transition-colors">⚡ Asignar Permisos</a>" para configurar privilegios por módulo
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Rol & Identificador</th>
                        <th class="py-3 px-4">Ámbito / Subsistema</th>
                        <th class="py-3 px-4">Descripción Funcional</th>
                        <th class="py-3 px-4">Permisos Concedidos</th>
                        <th class="py-3 px-4 text-center">Tipo</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                        <th class="py-3 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <!-- Rol & Identificador -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-sm shadow-xs font-bold shrink-0"
                                        style="background-color: {{ $role['iconBgHex'] }}; color: {{ $role['iconColorHex'] }};"
                                    >
                                        {{ $role['icon'] }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white block text-xs">
                                            {{ $role['name'] }}
                                        </span>
                                        <span class="font-mono text-[10px] text-slate-400">
                                            {{ $role['code'] }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Ámbito / Subsistema -->
                            <td class="py-3.5 px-4">
                                <span
                                    class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold text-white shadow-xs"
                                    style="background-color: {{ $role['scopeColorHex'] }};"
                                >
                                    <span>{{ $role['scopeIcon'] }}</span>
                                    <span>{{ $role['scopeLabel'] }}</span>
                                </span>
                            </td>

                            <!-- Descripción Funcional -->
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 max-w-xs">
                                <p class="line-clamp-2 leading-relaxed text-xs">{{ $role['description'] }}</p>
                            </td>

                            <!-- Permisos Concedidos -->
                            <td class="py-3.5 px-4">
                                <a
                                    href="{{ route('admin.matrix', ['rol' => $role['code']]) }}"
                                    wire:navigate
                                    class="space-y-1 block group cursor-pointer max-w-[150px]"
                                    title="Configurar permisos de {{ $role['name'] }} en la Matriz"
                                >
                                    <div class="flex items-center justify-between text-[11px] font-bold">
                                        <span class="text-emerald-700 dark:text-emerald-400">
                                            {{ $role['grantedPermissions'] }} de {{ $role['totalPermissions'] }}
                                        </span>
                                        <span class="text-slate-400 font-mono">{{ $role['percentage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                        <div
                                            class="bg-emerald-500 h-1.5 rounded-full transition-all duration-300"
                                            style="width: {{ $role['percentage'] }}%;"
                                        ></div>
                                    </div>
                                </a>
                            </td>

                            <!-- Tipo -->
                            <td class="py-3.5 px-4 text-center">
                                @if ($role['isSystem'])
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300/40">
                                        <span>🔒</span>
                                        <span>Sistema</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300/60 dark:border-slate-700">
                                        <span>👤</span>
                                        <span>Personalizado</span>
                                    </span>
                                @endif
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
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a
                                        href="{{ route('admin.matrix', ['rol' => $role['code']]) }}"
                                        wire:navigate
                                        class="px-2.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-semibold text-[11px] hover:bg-emerald-100 transition-colors flex items-center space-x-1 cursor-pointer"
                                        title="Asignar y configurar permisos granulares"
                                    >
                                        <span>⚡</span>
                                        <span class="hidden sm:inline">Permisos</span>
                                    </a>

                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $role['id'] }})"
                                        class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs transition-colors cursor-pointer"
                                        title="Editar detalles del rol"
                                    >
                                        ✏️
                                    </button>

                                    @if (! $role['isSystem'])
                                        <button
                                            type="button"
                                            wire:click="deleteRole({{ $role['id'] }})"
                                            wire:confirm="¿Está seguro de eliminar este rol personalizado?"
                                            class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900 text-rose-700 dark:text-rose-300 text-xs transition-colors cursor-pointer"
                                            title="Eliminar rol personalizado"
                                        >
                                            🗑️
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <p class="text-base font-semibold">No se encontraron roles coincidentes</p>
                                <p class="text-xs mt-1">Intente cambiando el ámbito o el texto de búsqueda.</p>
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
                        Mostrando <strong class="font-bold text-slate-900 dark:text-white">{{ $from }}</strong> a <strong class="font-bold text-slate-900 dark:text-white">{{ $to }}</strong> de <strong class="font-bold text-slate-900 dark:text-white">{{ $total }}</strong> roles
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

    <!-- Modal: Crear / Editar Rol Institucional -->
    @if ($showCreateModal)
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
