<div class="space-y-6 pb-12">
<!-- Encabezado del Tab / Header Principal -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div>
            <div class="flex items-center space-x-2">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Tabla Maestra de Personas
                </h2>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold">
                    {{ $total }} registros
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Clasificación fundamental del SIGM: <strong>Personal</strong> (servidores municipales) y <strong>Administrados</strong> (ciudadanos con trámites en línea o presenciales).
            </p>
        </div>

        <button
            type="button"
            wire:click="openCreateModal"
            class="px-4 py-2.5 rounded-lg bg-[#00965e] hover:bg-[#007f50] text-white font-semibold text-xs sm:text-sm shadow-xs transition-all hover:scale-105 flex items-center space-x-1.5 cursor-pointer shrink-0"
        >
            <span class="text-base leading-none font-bold">+</span>
            <span>Registrar Persona</span>
        </button>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 bg-white dark:bg-slate-900 p-3 sm:p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <!-- Pestañas de Tipo / Clasificación -->
        <div class="flex items-center space-x-1.5 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg text-xs overflow-x-auto shrink-0">
            <button
                type="button"
                wire:click="setFilter('TODOS')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors whitespace-nowrap cursor-pointer {{ $filterType === 'TODOS'
                    ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                Todos ({{ $counts['total'] }})
            </button>
            <button
                type="button"
                wire:click="setFilter('PERSONAL')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5 {{ $filterType === 'PERSONAL'
                    ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>💼</span>
                <span>Personal Municipal ({{ $counts['personal'] }})</span>
            </button>
            <button
                type="button"
                wire:click="setFilter('ADMINISTRADO')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5 {{ $filterType === 'ADMINISTRADO'
                    ? 'bg-white dark:bg-slate-900 text-blue-800 dark:text-blue-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>🏛️</span>
                <span>Administrados / Ciudadanos ({{ $counts['administrados'] }})</span>
            </button>
        </div>

        <!-- Grupo de Filtros y Búsqueda -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full xl:w-auto">
            <!-- Búsqueda Inteligente por Oficinas -->
            <div class="w-full sm:w-56 md:w-64 shrink-0">
                <x-office-search-select
                    wire:model.live="officeFilter"
                    :offices="$offices"
                    mode="filter"
                    align="right"
                    placeholder="Todas las oficinas..."
                    allow-clear
                />
            </div>

            <!-- Buscador por Texto -->
            <div class="flex items-center space-x-2 w-full sm:w-60 md:w-64 shrink-0">
                <div class="relative w-full">
                    <input
                        type="text"
                        placeholder="Buscar por DNI, RUC, nombre..."
                        wire:model.live.debounce.300ms="search"
                        class="w-full pl-3 pr-7 py-1.5 text-xs rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                    @if ($search)
                        <button
                            type="button"
                            wire:click="$set('search', '')"
                            class="absolute right-2 top-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs cursor-pointer"
                            title="Limpiar búsqueda"
                        >
                            ✕
                        </button>
                    @endif
                </div>
                <button
                    type="button"
                    class="px-3.5 py-1.5 rounded-lg bg-[#00965e] hover:bg-[#007f50] text-white text-xs font-semibold shadow-xs cursor-pointer shrink-0 transition-colors"
                >
                    Buscar
                </button>
            </div>
        </div>
    </div>

    @if ($officeFilter)
        <div class="flex items-center gap-2 px-1 -mt-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300/80 dark:border-emerald-800 font-medium shadow-xs">
                <span>🏢 Oficina filtrada:</span>
                <strong class="font-bold">{{ $officeFilter }}</strong>
                <button
                    type="button"
                    wire:click="clearOfficeFilter"
                    class="ml-1 text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 dark:hover:text-white font-bold cursor-pointer"
                    title="Quitar filtro de oficina"
                >
                    ✕
                </button>
            </span>
        </div>
    @endif

    <!-- Tabla Principal de Personas -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">DOCUMENTO</th>
                        <th class="py-3 px-4">CLASIFICACIÓN</th>
                        <th class="py-3 px-4">NOMBRES / RAZÓN SOCIAL</th>
                        <th class="py-3 px-4">CONTACTO</th>
                        <th class="py-3 px-4">DETALLE INSTITUCIONAL / TRÁMITE</th>
                        <th class="py-3 px-4 text-center">ESTADO</th>
                        <th class="py-3 px-4 text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($people as $person)
                        @php
                            $isPersonal = ($person['type'] ?? 'PERSONAL') === 'PERSONAL';
                            $isCesado = ($person['laborStatus'] ?? 'ACTIVO') === 'CESADO';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors {{ $isCesado ? 'bg-slate-50/50 dark:bg-slate-900/40 opacity-75' : '' }}">
                            <!-- Documento -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                <span class="text-[10px] text-slate-400 font-normal mr-1">
                                    {{ $person['documentType'] ?? 'DNI' }}:
                                </span>
                                {{ $person['documentNumber'] }}
                            </td>

                            <!-- Clasificación -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if ($isPersonal)
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#e6f7ef] dark:bg-emerald-950/60 text-[#059669] dark:text-emerald-300 border border-emerald-300/60 dark:border-emerald-800">
                                        <span>💼</span>
                                        <span>Personal</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border border-blue-300/60 dark:border-blue-800">
                                        <span>🏛️</span>
                                        <span>Administrado</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Nombres / Razón Social -->
                            <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white uppercase">
                                {{ $person['fullName'] }}
                            </td>

                            <!-- Contacto -->
                            <td class="py-3.5 px-4 space-y-0.5 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                <div class="flex items-center space-x-1 text-[11px]">
                                    <span class="text-purple-500">📧</span>
                                    <span class="truncate max-w-[160px] text-slate-500 dark:text-slate-400">{{ $person['email'] ?: '—' }}</span>
                                </div>
                                <div class="flex items-center space-x-1 text-[11px]">
                                    <span class="text-rose-500">📞</span>
                                    <span class="text-slate-500 dark:text-slate-400">{{ $person['phone'] ?: '—' }}</span>
                                </div>
                            </td>

                            <!-- Detalle Institucional / Trámite -->
                            <td class="py-3.5 px-4 max-w-xs">
                                @if ($isPersonal)
                                    <div class="font-bold text-slate-800 dark:text-slate-200 uppercase leading-snug">
                                        {{ $person['position'] ?: 'SERVIDOR MUNICIPAL' }}
                                    </div>
                                    <div class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium truncate mt-0.5">
                                        {{ $person['office'] ?: '—' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            {{ $person['laborCondition'] ?? 'CAS' }}
                                        </span>
                                    </div>
                                @else
                                    <div class="text-slate-500 dark:text-slate-400 text-xs italic">
                                        Ciudadano / Contribuyente
                                    </div>
                                    @if (! empty($person['allowsOnlineAccess']))
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            ✓ Habilitado Trámites Digitales
                                        </span>
                                    @endif
                                @endif
                            </td>

                            <!-- Estado -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($isCesado)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 dark:text-rose-400">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Cesado</span>
                                    </span>
                                    @if (! empty($person['departureDate']))
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            {{ $person['departureDate'] }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Activo</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $person['id'] }})"
                                        class="px-2.5 py-1 rounded-lg border border-blue-200 dark:border-blue-800/80 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/50 font-semibold text-[11px] transition-colors cursor-pointer flex items-center space-x-1"
                                    >
                                        <span>✏️</span>
                                        <span>Editar</span>
                                    </button>

                                    @if ($isPersonal)
                                        @if ($isCesado)
                                            <button
                                                type="button"
                                                wire:click="openCeaseDetailModal({{ $person['id'] }})"
                                                class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold text-[11px] transition-colors cursor-pointer flex items-center space-x-1"
                                            >
                                                <span>📋</span>
                                                <span>Ver Cese</span>
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="openCeaseModal({{ $person['id'] }})"
                                                class="px-2.5 py-1 rounded-lg border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-semibold text-[11px] transition-colors cursor-pointer flex items-center space-x-1"
                                            >
                                                <span>🔴</span>
                                                <span>Cese / Baja</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No se encontraron registros de personas para el criterio especificado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Barra de Paginación -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
            <!-- Izquierda: Contador y Selector por Página -->
            <div class="flex items-center gap-3">
                <span>
                    Mostrando <strong class="text-slate-800 dark:text-slate-200">{{ $from }}</strong> a <strong class="text-slate-800 dark:text-slate-200">{{ $to }}</strong> de <strong class="text-slate-800 dark:text-slate-200">{{ $total }}</strong> personas
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="text-[11px]">Filas por pág:</span>
                    <select
                        wire:model.live="perPage"
                        class="rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <!-- Derecha: Navegación Numérica de Páginas -->
            <div class="flex items-center space-x-1">
                <button
                    type="button"
                    wire:click="gotoPage({{ $currentPage - 1 }})"
                    @disabled($currentPage <= 1)
                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer"
                >
                    &lsaquo; Anterior
                </button>

                @for ($p = 1; $p <= $totalPages; $p++)
                    <button
                        type="button"
                        wire:click="gotoPage({{ $p }})"
                        class="w-7 h-7 rounded-md font-bold text-xs flex items-center justify-center transition-colors cursor-pointer {{ $p === $currentPage
                            ? 'bg-[#00965e] text-white shadow-xs'
                            : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'
                        }}"
                    >
                        {{ $p }}
                    </button>
                @endfor

                <button
                    type="button"
                    wire:click="gotoPage({{ $currentPage + 1 }})"
                    @disabled($currentPage >= $totalPages)
                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer"
                >
                    Siguiente &rsaquo;
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. MODAL DE FORMULARIO: REGISTRO / EDICIÓN DE PERSONA                      -->
    <!-- ========================================================================= -->

<!-- ========================================================================= -->

    <!-- ========================================================================= -->
