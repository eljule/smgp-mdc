<div class="space-y-6 pb-12">
<!-- 1. Encabezado Principal -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-emerald-700 via-teal-800 to-slate-900 p-6 rounded-2xl text-white shadow-xl">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-emerald-300 tracking-wider uppercase mb-1">
                <span>🏛️</span>
                <span>MUNICIPALIDAD DISTRITAL DE CASTILLA</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight">
                Estructura Orgánica y Dependencias
            </h1>
            <p class="text-xs text-emerald-100/90 mt-1 max-w-2xl">
                Catálogo jerárquico de oficinas, gerencias, subgerencias y unidades organizacionales con asignación de sede física institucional.
            </p>
        </div>
        <div class="flex items-center space-x-2.5">
            <button
                type="button"
                wire:click="openSedesModal"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-800/90 hover:bg-slate-700 text-white border border-slate-600/70 font-bold text-xs rounded-xl shadow-lg transition-transform active:scale-95 cursor-pointer"
            >
                <span class="text-base">📍</span>
                <span>Configurar Sedes</span>
            </button>
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center space-x-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition-transform active:scale-95 cursor-pointer"
            >
                <span class="text-base">➕</span>
                <span>Nueva Dependencia</span>
            </button>
        </div>
    </div>


    <!-- 2. Tarjetas de Estadísticas (4 Stat Cards) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Card 1: Total Dependencias -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span>Total Dependencias</span>
                <span class="text-base">🏢</span>
            </div>
            <div class="text-2xl font-black text-slate-900 dark:text-white">
                {{ $metrics['total'] }}
            </div>
            <span class="text-[11px] text-emerald-600 font-semibold">100% Estructura ROF</span>
        </div>

        <!-- Card 2: Sedes Municipales (Interactivo) -->
        <div wire:click="openSedesModal" class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm hover:border-emerald-400 dark:hover:border-emerald-600 transition-colors cursor-pointer group">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span>Sedes Municipales</span>
                <span class="text-base group-hover:scale-110 transition-transform">📍</span>
            </div>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                {{ $metrics['sedes'] }}
            </div>
            <span class="text-[11px] text-slate-500 flex items-center justify-between">
                <span>Locales institucionales</span>
                <span class="text-emerald-600 font-semibold group-hover:underline">Configurar →</span>
            </span>
        </div>

        <!-- Card 3: Alta Dirección y Gerencias -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span>Alta Dirección y Gerencias</span>
                <span class="text-base">👔</span>
            </div>
            <div class="text-2xl font-black text-blue-600 dark:text-blue-400">
                {{ $metrics['alta_direccion'] }}
            </div>
            <span class="text-[11px] text-slate-500">Nivel 1 institucional</span>
        </div>

        <!-- Card 4: Subgerencias y Unidades -->
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1">
                <span>Subgerencias y Unidades</span>
                <span class="text-base">📂</span>
            </div>
            <div class="text-2xl font-black text-purple-600 dark:text-purple-400">
                {{ $metrics['subgerencias_unidades'] }}
            </div>
            <span class="text-[11px] text-slate-500">Áreas operativas</span>
        </div>
    </div>

    <!-- 3. Barra de Filtros y Switch de Vista -->
    <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm space-y-3">
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <!-- Búsqueda -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">
                    🔍
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por código (ej. 03.02), sigla (ej. ODT) o nombre..."
                    class="w-full text-xs pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                />
            </div>

            <!-- Filtro por Sede -->
            <div class="flex items-center space-x-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 whitespace-nowrap">
                    📍 Sede:
                </label>
                <select
                    wire:model.live="sedeFilter"
                    class="text-xs p-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer"
                >
                    <option value="TODAS">Todas las Sedes ({{ count($allOfficesCatalog) }})</option>
                    @foreach ($sedes as $s)
                        <option value="{{ $s }}">
                            {{ $s }} ({{ collect($allOfficesCatalog)->where('sede', $s)->count() }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Nivel -->
            <div class="flex items-center space-x-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 whitespace-nowrap">
                    Nivel:
                </label>
                <select
                    wire:model.live="levelFilter"
                    class="text-xs p-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer"
                >
                    <option value="TODOS">Todos los Niveles</option>
                    <option value="1">Nivel 1: Alta Dirección / Gerencias</option>
                    <option value="2">Nivel 2: Subgerencias / Of. Generales</option>
                    <option value="3">Nivel 3: Oficinas</option>
                    <option value="4">Nivel 4: Unidades / Áreas</option>
                </select>
            </div>

            <!-- Toggle Tabla / Árbol -->
            <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                <button
                    type="button"
                    wire:click="setViewMode('table')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors cursor-pointer {{ $viewMode === 'table'
                        ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'
                    }}"
                >
                    📋 Tabla
                </button>
                <button
                    type="button"
                    wire:click="setViewMode('chart')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors cursor-pointer {{ ($viewMode === 'chart' || $viewMode === 'tree')
                        ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm'
                        : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'
                    }}"
                >
                    🌳 Organigrama
                </button>
            </div>
        </div>
    </div>

    <!-- 4. Vista Tabla Detallada -->
    @if ($viewMode === 'table')
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 w-24">CÓDIGO</th>
                            <th class="py-3.5 px-3 w-20">SIGLA</th>
                            <th class="py-3.5 px-4">DEPENDENCIA / OFICINA</th>
                            <th class="py-3.5 px-4">DEPENDENCIA SUPERIOR</th>
                            <th class="py-3.5 px-4">SEDE DE UBICACIÓN</th>
                            <th class="py-3.5 px-3 w-24 text-center">NIVEL</th>
                            <th class="py-3.5 px-3 w-20 text-center">ESTADO</th>
                            <th class="py-3.5 px-4 w-24 text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse ($offices as $office)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                <!-- CÓDIGO -->
                                <td class="py-3 px-4 font-mono font-bold text-teal-700 dark:text-teal-400 whitespace-nowrap">
                                    {{ $office['code'] }}
                                </td>

                                <!-- SIGLA -->
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono font-bold text-[10px] border border-slate-200 dark:border-slate-700">
                                        {{ $office['sigla'] }}
                                    </span>
                                </td>

                                <!-- DEPENDENCIA / OFICINA -->
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900 dark:text-white uppercase">
                                        {{ $office['name'] }}
                                    </div>
                                </td>

                                <!-- DEPENDENCIA SUPERIOR -->
                                <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                    @if ($office['parentName'])
                                        <div class="flex items-center space-x-1.5">
                                            <span class="text-[10px] text-slate-400">↳</span>
                                            <span class="text-[11px] truncate max-w-xs">{{ $office['parentName'] }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">-- Órgano Superior --</span>
                                    @endif
                                </td>

                                <!-- SEDE DE UBICACIÓN -->
                                @php
                                    $sedeBadgeColor = \App\Models\Sede::getBadgeClasses($office['sede']);
                                @endphp
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $sedeBadgeColor }}">
                                        <span>📍</span>
                                        <span class="truncate max-w-[200px]">{{ $office['sede'] }}</span>
                                    </span>
                                </td>

                                <!-- NIVEL -->
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        Nivel {{ $office['level'] }}
                                    </span>
                                </td>

                                <!-- ESTADO -->
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $office['isActive'] ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-400' }}">
                                        {{ $office['isActive'] ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                <!-- ACCIONES -->
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end space-x-1">
                                        <button
                                            type="button"
                                            wire:click="openQrModal({{ $office['id'] }})"
                                            class="p-1.5 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg text-emerald-600 dark:text-emerald-400 transition-colors cursor-pointer"
                                            title="Generar / Imprimir Código QR para Tickets de Mesa de Ayuda"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="openEditModal({{ $office['id'] }})"
                                            class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-amber-600 dark:text-amber-400 transition-colors cursor-pointer"
                                            title="Editar dependencia"
                                        >
                                            ✏️
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="deleteOffice({{ $office['id'] }})"
                                            wire:confirm="¿Está seguro de eliminar la dependencia {{ $office['name'] }}?"
                                            class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-rose-600 dark:text-rose-400 transition-colors cursor-pointer"
                                            title="Eliminar dependencia"
                                        >
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 italic">
                                    No se encontraron dependencias que coincidan con los filtros.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($totalFiltered > 0)
                <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                    <div>
                        Mostrando
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ min($totalFiltered, ($currentPage - 1) * $perPage + 1) }}</span>
                        a
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ min($totalFiltered, $currentPage * $perPage) }}</span>
                        de
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $totalFiltered }}</span>
                        dependencias
                    </div>

                    @if ($totalPages > 1)
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                wire:click="gotoPage({{ max(1, $currentPage - 1) }})"
                                @disabled($currentPage <= 1)
                                class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-medium hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                            >
                                Anterior
                            </button>

                            @for ($p = 1; $p <= $totalPages; $p++)
                                @if ($p === 1 || $p === $totalPages || abs($p - $currentPage) <= 1)
                                    <button
                                        type="button"
                                        wire:click="gotoPage({{ $p }})"
                                        class="px-2.5 py-1 rounded-lg border font-bold text-xs transition-colors cursor-pointer {{ $p === $currentPage
                                            ? 'bg-emerald-600 text-white border-emerald-600'
                                            : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50'
                                        }}"
                                    >
                                        {{ $p }}
                                    </button>
                                @elseif (abs($p - $currentPage) === 2)
                                    <span class="px-1 text-slate-400">...</span>
                                @endif
                            @endfor

                            <button
                                type="button"
                                wire:click="gotoPage({{ min($totalPages, $currentPage + 1) }})"
                                @disabled($currentPage >= $totalPages)
                                class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-medium hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                            >
                                Siguiente
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @else
        <!-- 5. Vista Organigrama Jerárquico (Árbol ROF) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm">
                        Árbol Jerárquico Organizacional
                    </h3>
                    <p class="text-xs text-slate-400">
                        Visualización anidada por niveles de mando y dependencias adscritas.
                        <span class="sr-only">Organigrama Institucional de Castilla</span>
                    </p>
                </div>
                <span class="text-xs bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 px-3 py-1 rounded-full font-bold">
                    {{ count($allOfficesCatalog) }} Órganos Principales
                </span>
            </div>

            <div class="space-y-3">
                @foreach ($treeOffices as $rootNode)
                    @include('livewire.admin.office-tree-node', ['node' => $rootNode])
                @endforeach
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
