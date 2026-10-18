<div class="space-y-6 animate-fadeIn">
<!-- 1. Header del Catálogo -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2.5">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Catálogo de Permisos Granulares
                </h2>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold border border-emerald-300/40">
                    {{ $counts['total'] }} permisos
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Diccionario oficial de operaciones autorizables estructuradas por subsistema del SIGM Castilla.
            </p>
        </div>

        <!-- Resumen por Subsistemas -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-[#16a34a]"></span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $counts['central_dashboard'] }}</span>
            </div>
            <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-[#7c3aed]"></span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $counts['helpdesk_support'] }}</span>
            </div>
            <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-[#2563eb]"></span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $counts['it_inventory'] }}</span>
            </div>
            <div class="flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-[#ea580c]"></span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $counts['transport_management'] }}</span>
            </div>
        </div>
    </div>

    <!-- 2. Filtros y Búsqueda -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <!-- Tabs de Subsistema -->
            <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                <button
                    type="button"
                    wire:click="setSubsystemFilter('TODOS')"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer {{ $subsystemFilter === 'TODOS'
                        ? 'bg-emerald-600 text-white shadow-xs'
                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                    }}"
                >
                    Todos ({{ $counts['total'] }})
                </button>

                <button
                    type="button"
                    wire:click="setSubsystemFilter('central_dashboard')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 cursor-pointer {{ $subsystemFilter === 'central_dashboard'
                        ? 'bg-emerald-600 text-white shadow-xs'
                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                    }}"
                >
                    <span class="w-2 h-2 rounded-full bg-[#16a34a]"></span>
                    <span>Dashboard Central y Configuración</span>
                    <span class="text-[10px] opacity-80">({{ $counts['central_dashboard'] }})</span>
                </button>

                <button
                    type="button"
                    wire:click="setSubsystemFilter('helpdesk_support')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 cursor-pointer {{ $subsystemFilter === 'helpdesk_support'
                        ? 'bg-emerald-600 text-white shadow-xs'
                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                    }}"
                >
                    <span class="w-2 h-2 rounded-full bg-[#7c3aed]"></span>
                    <span>Soporte Técnico y Helpdesk</span>
                    <span class="text-[10px] opacity-80">({{ $counts['helpdesk_support'] }})</span>
                </button>

                <button
                    type="button"
                    wire:click="setSubsystemFilter('it_inventory')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 cursor-pointer {{ $subsystemFilter === 'it_inventory'
                        ? 'bg-emerald-600 text-white shadow-xs'
                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                    }}"
                >
                    <span class="w-2 h-2 rounded-full bg-[#2563eb]"></span>
                    <span>Inventario y Gestión TI (ITAM)</span>
                    <span class="text-[10px] opacity-80">({{ $counts['it_inventory'] }})</span>
                </button>

                <button
                    type="button"
                    wire:click="setSubsystemFilter('transport_management')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 cursor-pointer {{ $subsystemFilter === 'transport_management'
                        ? 'bg-emerald-600 text-white shadow-xs'
                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                    }}"
                >
                    <span class="w-2 h-2 rounded-full bg-[#ea580c]"></span>
                    <span>Licencias de Transportes</span>
                    <span class="text-[10px] opacity-80">({{ $counts['transport_management'] }})</span>
                </button>
            </div>

            <!-- Buscador -->
            <div class="w-full sm:w-72">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por código (ej. admin.users.create)..."
                    class="w-full px-3 py-1.5 text-xs rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium"
                />
            </div>
        </div>
    </div>

    <!-- 3. Listado Agrupado por Subsistema -->
    @if (empty($subsystems))
        <div class="bg-white dark:bg-slate-900 p-12 text-center rounded-2xl border border-slate-200 dark:border-slate-800">
            <p class="text-3xl mb-2">🔍</p>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">
                No se encontraron permisos coincidentes
            </h4>
            <p class="text-xs text-slate-400 mt-1">
                Intente ajustar los términos de búsqueda o cambiar los filtros seleccionados.
            </p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($subsystems as $subKey => $subsystem)
                @php
                    $colorHex = $subsystem['colorHex'] ?? match ($subKey) {
                        'central_dashboard' => '#16a34a',
                        'helpdesk_support' => '#7c3aed',
                        'it_inventory' => '#2563eb',
                        'transport_management' => '#ea580c',
                        default => '#16a34a',
                    };
                @endphp
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <!-- Cabecera del Subsistema -->
                    <div
                        class="px-6 py-4 flex items-center justify-between border-b border-slate-200 dark:border-slate-800"
                        style="border-left-color: {{ $colorHex }}; border-left-width: 6px;"
                    >
                        <div class="flex items-center space-x-3">
                            <span
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-xs"
                                style="background-color: {{ $colorHex }};"
                            >
                                🗝️
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $subsystem['name'] }}
                                </h3>
                                <span class="text-[10px] font-mono text-slate-400">
                                    subsistema: {{ $subsystem['code'] }}
                                </span>
                            </div>
                        </div>

                        <span
                            class="px-2.5 py-1 rounded-full text-xs font-bold text-white shadow-xs"
                            style="background-color: {{ $colorHex }};"
                        >
                            {{ count($subsystem['permissions']) }} permisos
                        </span>
                    </div>

                    <!-- Grid de Permisos -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($subsystem['permissions'] as $perm)
                            @php
                                $badgeStyle = match ($perm['tagColor'] ?? '') {
                                    'emerald' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
                                    'blue' => 'bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 border-sky-300 dark:border-sky-800',
                                    'amber' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-300 dark:border-amber-800',
                                    'purple' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-300 dark:border-purple-800',
                                    default => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-300 dark:border-purple-800',
                                };
                            @endphp
                            <div class="p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 dark:hover:border-slate-700 transition-all flex flex-col justify-between">
                                <div>
                                    <!-- Cabecera de la tarjeta: Código & Categoría -->
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="font-mono font-bold text-[11px] text-slate-800 dark:text-slate-200 px-2 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 truncate">
                                            {{ $perm['code'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border shrink-0 {{ $badgeStyle }}">
                                            {{ $perm['tag'] }}
                                        </span>
                                    </div>

                                    <!-- Nombre del Permiso -->
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-1.5 leading-snug">
                                        {{ $perm['name'] }}
                                    </h4>

                                    <!-- Descripción de Alcance -->
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                                        {{ $perm['description'] }}
                                    </p>
                                </div>

                                <!-- Estado -->
                                <div class="pt-2.5 mt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between gap-2 text-[10px]">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold inline-flex items-center gap-1 shrink-0 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                        <span>Activo en SIGM</span>
                                    </span>
                                    <span class="text-slate-400 uppercase font-mono font-bold text-[10px] shrink-0 whitespace-nowrap">
                                        {{ $perm['scope'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
