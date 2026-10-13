<div class="space-y-6 pb-12">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

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
                                wire:click="setPage({{ max(1, $currentPage - 1) }})"
                                @disabled($currentPage <= 1)
                                class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-medium hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                            >
                                Anterior
                            </button>

                            @for ($p = 1; $p <= $totalPages; $p++)
                                @if ($p === 1 || $p === $totalPages || abs($p - $currentPage) <= 1)
                                    <button
                                        type="button"
                                        wire:click="setPage({{ $p }})"
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
                                wire:click="setPage({{ min($totalPages, $currentPage + 1) }})"
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

    <!-- 6. Modal de Creación / Edición (OfficeFormModal) -->
    @if ($showCreateModal || $showEditModal)
        <div
            class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 animate-fadeIn"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden animate-scaleUp">
                <!-- Header del Modal -->
                <div class="bg-gradient-to-r from-emerald-700 via-teal-800 to-slate-900 text-white p-5 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-lg">
                            🏢
                        </div>
                        <div>
                            <h3 class="font-bold text-base">
                                {{ $showEditModal ? 'Editar Dependencia / Oficina' : 'Nueva Dependencia / Oficina' }}
                            </h3>
                            <p class="text-xs text-emerald-100">
                                Estructura orgánica de la Municipalidad Distrital de Castilla
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="saveOffice" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Código Orgánico *
                            </label>
                            <input
                                type="text"
                                wire:model="formCode"
                                placeholder="Ej. 03.02.05.01"
                                class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono text-slate-800 dark:text-slate-200"
                                required
                            />
                            <span class="text-[10px] text-slate-400">Jerárquico (ej. 01, 02.01, 03.01.01)</span>
                            @error('formCode') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Sigla Oficial *
                            </label>
                            <input
                                type="text"
                                wire:model="formSigla"
                                placeholder="Ej. ODT, OSTR, GAT"
                                class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-bold text-slate-800 dark:text-slate-200"
                                required
                            />
                            @error('formSigla') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Nombre Completo de la Dependencia *
                        </label>
                        <input
                            type="text"
                            wire:model="formName"
                            placeholder="Ej. OFICINA DE DESARROLLO TECNOLÓGICO"
                            class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-medium text-slate-800 dark:text-slate-200"
                            required
                        />
                        @error('formName') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Dependencia Superior (Padre Orgánico)
                        </label>
                        <select
                            wire:model.live="formParentId"
                            class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-slate-200"
                        >
                            <option value="">-- Sin Dependencia Superior (Órgano Raíz / Nivel 1) --</option>
                            @foreach ($allOfficesCatalog as $o)
                                @if (! $showEditModal || $o['id'] !== $selectedOfficeId)
                                    <option value="{{ $o['id'] }}">
                                        {{ $o['code'] }} - [{{ $o['sigla'] }}] {{ $o['name'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo Sede Municipal (Requerimiento Específico) -->
                    <div class="p-3 bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40 rounded-xl space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-emerald-900 dark:text-emerald-300 flex items-center space-x-1.5">
                                <span>📍</span>
                                <span>Sede Municipal de Ubicación *</span>
                            </label>
                            <button
                                type="button"
                                wire:click="$toggle('isCustomSede')"
                                class="text-[11px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold cursor-pointer"
                            >
                                {{ $isCustomSede ? '← Seleccionar sede existente' : '+ Escribir otra sede' }}
                            </button>
                        </div>

                        @if (! $isCustomSede)
                            <select
                                wire:model="formSede"
                                wire:change="onSedeChange($event.target.value)"
                                class="w-full text-xs p-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none font-semibold text-slate-800 dark:text-slate-200"
                            >
                                @foreach ($sedes as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                                <option value="OTRA">+ Registrar / Especificar nueva Sede...</option>
                            </select>
                        @else
                            <input
                                type="text"
                                wire:model="formCustomSede"
                                placeholder="Ej. SEDE ANEXO BIBLIOTECA MUNICIPAL"
                                class="w-full text-xs p-2.5 bg-white dark:bg-slate-900 border border-emerald-400 dark:border-emerald-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase font-semibold text-slate-800 dark:text-slate-200"
                                required
                            />
                        @endif

                        <p class="text-[10px] text-slate-500 dark:text-slate-400">
                            Indica la sede física o local institucional donde opera esta dependencia.
                        </p>
                        @error('formCustomSede') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input
                            type="checkbox"
                            id="isActiveCheck"
                            wire:model="formIsActive"
                            class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4 cursor-pointer"
                        />
                        <label for="isActiveCheck" class="text-xs text-slate-700 dark:text-slate-300 font-medium cursor-pointer">
                            Dependencia Activa en el Organigrama Municipal
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl shadow-md transition-all cursor-pointer"
                        >
                            {{ $showEditModal ? 'Actualizar Dependencia' : 'Registrar Dependencia' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- 6. MODAL DE GESTIÓN Y CONFIGURACIÓN DE SEDES -->
    <!-- ========================================== -->
    @if ($showSedesModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-4xl w-full max-h-[92vh] flex flex-col shadow-2xl overflow-hidden">
                <!-- Cabecera del Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-lg font-bold">
                            📍
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">
                                Catálogo de Sedes Municipales y Configuración de Colores
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Gestione los locales físicos de la municipalidad y configure el color de sus insignias para el organigrama y catálogo.
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeSedesModal"
                        class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 text-xl font-bold rounded-lg cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Contenido del Modal (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">

                    <!-- Formulario de Inserción / Edición de Sede -->
                    @if ($showSedeForm)
                        <div class="p-5 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/20 dark:bg-emerald-950/20 space-y-4 animate-fadeIn">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                    <span>{{ $selectedSedeId ? '✏️ Modificar Sede Municipal' : '➕ Registrar Nueva Sede Municipal' }}</span>
                                </h4>
                                <button
                                    type="button"
                                    wire:click="cancelSedeForm"
                                    class="text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-semibold cursor-pointer"
                                >
                                    Cancelar
                                </button>
                            </div>

                            @if ($sedeErrorMessage)
                                <div class="p-3 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl font-semibold">
                                    ⚠️ {{ $sedeErrorMessage }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Nombre Oficial de la Sede *:
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="sedeFormName"
                                        placeholder="Ej. NUEVA SEDE AGENCIA MUNICIPAL TACALÁ"
                                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                        required
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Código / Sigla:
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="sedeFormCode"
                                        placeholder="Ej. TACALA_01"
                                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-mono uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                    />
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Dirección o Referencia Física:
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="sedeFormAddress"
                                        placeholder="Ej. Av. Guardia Civil s/n frente al Parque Principal"
                                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <!-- Selector de Paleta de Colores -->
                            <div class="space-y-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    🎨 Configuración de Color Distintivo:
                                </label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Seleccione el color de identificación que se mostrará en las etiquetas del organigrama en árbol y en el listado de dependencias.
                                </p>

                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 pt-1">
                                    @foreach ($availableColors as $cKey => $cData)
                                        <button
                                            type="button"
                                            wire:click="setSedeColor('{{ $cKey }}')"
                                            class="p-2 rounded-xl border text-left transition-all cursor-pointer flex items-center space-x-2 {{ $sedeFormColor === $cKey
                                                ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white dark:bg-slate-900 shadow-sm font-bold'
                                                : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 hover:bg-white text-slate-700 dark:text-slate-300'
                                            }}"
                                        >
                                            <span class="w-4 h-4 rounded-full flex-shrink-0 border border-black/10 shadow-2xs" style="background-color: {{ $cData['hex'] }};"></span>
                                            <span class="text-[11px] truncate flex-1">{{ explode(' ', $cData['label'])[0] }}</span>
                                            @if ($sedeFormColor === $cKey)
                                                <span class="text-emerald-600 text-xs font-bold">✓</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Vista previa del Badge -->
                                <div class="pt-2 flex items-center gap-3">
                                    <span class="text-xs text-slate-500 font-semibold">Vista previa de insignia:</span>
                                    <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-bold border {{ $currentSedeBadgePreview }}">
                                        <span>📍</span>
                                        <span>{{ $sedeFormName ? strtoupper($sedeFormName) : 'NOMBRE DE LA SEDE' }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Botones del Formulario -->
                            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200 dark:border-slate-800">
                                <button
                                    type="button"
                                    wire:click="cancelSedeForm"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="button"
                                    wire:click="saveSede"
                                    class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-all cursor-pointer flex items-center gap-1.5"
                                >
                                    <span>💾</span>
                                    <span>{{ $selectedSedeId ? 'Guardar Cambios' : 'Registrar Sede' }}</span>
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Barra Superior con Botón de Inserción -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">
                                Mostrando <strong>{{ count($sedesModels) }}</strong> sedes institucionales registradas
                            </span>
                            <button
                                type="button"
                                wire:click="openCreateSedeForm"
                                class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer"
                            >
                                <span>➕</span>
                                <span>Registrar Nueva Sede</span>
                            </button>
                        </div>
                    @endif

                    <!-- Listado / Tabla de Sedes Registradas -->
                    <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                    <th class="py-3 px-4">Sede / Distintivo de Color</th>
                                    <th class="py-3 px-3">Código</th>
                                    <th class="py-3 px-4">Dirección / Referencia</th>
                                    <th class="py-3 px-3 text-center">Dependencias</th>
                                    <th class="py-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @forelse ($sedesModels as $sedeItem)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                        <!-- Badge con Color Configurado -->
                                        <td class="py-3 px-4 font-semibold">
                                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sedeItem->badge_class }}">
                                                <span>📍</span>
                                                <span class="truncate max-w-[240px]">{{ $sedeItem->name }}</span>
                                            </span>
                                        </td>

                                        <!-- Código -->
                                        <td class="py-3 px-3 font-mono text-[11px] text-slate-500">
                                            {{ $sedeItem->code ?: '—' }}
                                        </td>

                                        <!-- Dirección -->
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400 text-[11px] truncate max-w-[200px]">
                                            {{ $sedeItem->address ?: 'Distrito de Castilla' }}
                                        </td>

                                        <!-- Cantidad de Dependencias -->
                                        <td class="py-3 px-3 text-center font-bold">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                {{ $sedeItem->offices_count }} oficinas
                                            </span>
                                        </td>

                                        <!-- Acciones -->
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end space-x-1">
                                                <button
                                                    type="button"
                                                    wire:click="openEditSedeForm({{ $sedeItem->id }})"
                                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-amber-600 dark:text-amber-400 transition-colors cursor-pointer"
                                                    title="Modificar sede y color"
                                                >
                                                    ✏️
                                                </button>
                                                <button
                                                    type="button"
                                                    wire:click="deleteSede({{ $sedeItem->id }})"
                                                    wire:confirm="¿Está seguro de eliminar esta sede municipal? Las dependencias vinculadas se reasignarán automáticamente a la Sede Principal."
                                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-rose-600 dark:text-rose-400 transition-colors cursor-pointer"
                                                    title="Eliminar sede"
                                                >
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                            No hay sedes registradas en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex justify-end">
                    <button
                        type="button"
                        wire:click="closeSedesModal"
                        class="px-5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-200/80 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl transition-colors cursor-pointer"
                    >
                        Cerrar Ventana
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 8. Modal de Código QR de Oficina para Mesa de Ayuda -->
    @if ($showQrModal && $selectedOfficeForQr)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-data="{
                copied: false,
                downloading: false,
                copyLink(url) {
                    navigator.clipboard.writeText(url).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2500);
                    });
                },
                downloadImage(url, name) {
                    this.downloading = true;
                    fetch(url)
                        .then(r => r.blob())
                        .then(blob => {
                            const blobUrl = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = blobUrl;
                            a.download = 'QR_Oficina_' + name.replace(/[^a-zA-Z0-9_-]/g, '_') + '.png';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(blobUrl);
                            this.downloading = false;
                        })
                        .catch(() => {
                            this.downloading = false;
                            window.open(url, '_blank');
                        });
                },
                printSign() {
                    window.print();
                }
             }"
             @keydown.escape.window="$wire.closeQrModal()">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden text-center animate-in fade-in zoom-in-95 duration-150">
                <!-- Header del Modal -->
                <div class="px-6 py-4 bg-emerald-700 dark:bg-emerald-800 text-white flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-left">
                        <span class="text-xl">📱</span>
                        <div>
                            <h3 class="font-bold text-sm leading-tight text-white">Código QR para Mesa de Ayuda TI</h3>
                            <p class="text-[11px] text-emerald-100">Cartel de Oficina y Reporte Móvil de Incidencias</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeQrModal" class="p-1 hover:bg-emerald-600/50 rounded-lg text-emerald-100 hover:text-white transition-colors cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Contenido -->
                <div class="p-6 space-y-4">
                    <!-- Datos de la Oficina -->
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-mono text-xs font-bold">
                            <span>Código: {{ $selectedOfficeForQr['code'] }}</span>
                            <span>&bull;</span>
                            <span>[{{ $selectedOfficeForQr['sigla'] }}]</span>
                        </div>
                        <h4 class="text-base font-black text-slate-900 dark:text-white uppercase leading-snug">
                            {{ $selectedOfficeForQr['name'] }}
                        </h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            📍 {{ $selectedOfficeForQr['sede'] }}
                        </p>
                    </div>

                    <!-- Código QR Centrado -->
                    <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 dark:border-slate-700 shadow-inner inline-block mx-auto group relative">
                        <img src="{{ $selectedOfficeForQr['qrUrl'] }}"
                             alt="QR {{ $selectedOfficeForQr['name'] }}"
                             class="w-56 h-56 mx-auto object-contain select-none">
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed max-w-sm mx-auto">
                        Al escanear este código con la cámara de un celular, el trabajador ingresará directamente al formulario de tickets con esta oficina preseleccionada.
                    </p>

                    <!-- Enlace directo con botón de copiar -->
                    <div class="bg-slate-50 dark:bg-slate-800/80 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2 text-left">
                        <div class="truncate text-[11px] font-mono text-slate-600 dark:text-slate-300 select-all">
                            {{ $selectedOfficeForQr['ticketUrl'] }}
                        </div>
                        <button type="button"
                                @click="copyLink('{{ $selectedOfficeForQr['ticketUrl'] }}')"
                                class="shrink-0 px-2.5 py-1 text-xs font-bold rounded-lg transition-colors cursor-pointer"
                                :class="copied ? 'bg-emerald-600 text-white' : 'bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200'">
                            <span x-show="!copied">Copiar</span>
                            <span x-show="copied" x-cloak>¡Copiado! ✓</span>
                        </button>
                    </div>

                    <!-- Acciones Principales -->
                    <div class="grid grid-cols-2 gap-2.5 pt-2">
                        <!-- Imprimir Cartel -->
                        <button type="button"
                                @click="printSign()"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-[#046A38] hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-md transition-all cursor-pointer">
                            <span>🖨️</span>
                            <span>Imprimir Cartel QR</span>
                        </button>

                        <!-- Descargar Imagen -->
                        <button type="button"
                                @click="downloadImage('{{ $selectedOfficeForQr['qrUrl'] }}', '{{ $selectedOfficeForQr['sigla'] ?? 'OFICINA' }}')"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all cursor-pointer"
                                :disabled="downloading">
                            <span>📥</span>
                            <span x-show="!downloading">Descargar Imagen</span>
                            <span x-show="downloading" x-cloak>Descargando...</span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ $selectedOfficeForQr['ticketUrl'] }}" target="_blank" class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold hover:underline flex items-center gap-1">
                            <span>Abrir formulario en otra pestaña</span>
                            <span>&rarr;</span>
                        </a>
                        <button type="button" wire:click="closeQrModal" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plantilla Imprimible A4 de Cartel QR de Oficina (Solo visible al imprimir) -->
        <div id="office-qr-print-sheet" class="hidden print:block fixed inset-0 bg-white text-black p-8 font-sans z-[9999]">
            <div class="max-w-2xl mx-auto border-4 border-[#046A38] rounded-3xl p-8 text-center space-y-6">
                <div class="flex items-center justify-center gap-4 border-b-2 border-emerald-800 pb-4">
                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto object-contain" alt="Castilla">
                    <div class="text-left">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">MUNICIPALIDAD DISTRITAL DE CASTILLA</h4>
                        <h1 class="text-2xl font-black text-slate-900">MESA DE AYUDA Y SOPORTE TI</h1>
                        <p class="text-xs text-slate-600 font-semibold">Subgerencia de Informática y Estadística</p>
                    </div>
                </div>

                <div class="py-2">
                    <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-900 font-mono font-bold text-xs rounded-full uppercase mb-2">
                        DEPENDENCIA MUNICIPAL
                    </span>
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">
                        {{ $selectedOfficeForQr['name'] }}
                    </h2>
                    <p class="text-xs text-slate-600 font-medium mt-1">
                        Sede: {{ $selectedOfficeForQr['sede'] }} &bull; Código: {{ $selectedOfficeForQr['code'] }}
                    </p>
                </div>

                <div class="p-4 bg-white border-2 border-slate-300 rounded-2xl inline-block shadow-sm">
                    <img src="{{ $selectedOfficeForQr['qrUrl'] }}" alt="QR Mesa de Ayuda" class="w-72 h-72 mx-auto object-contain">
                </div>

                <div class="bg-emerald-50 rounded-2xl p-5 text-left text-xs text-emerald-950 space-y-2 border border-emerald-200">
                    <h4 class="font-bold text-emerald-900 uppercase tracking-wide text-center text-sm mb-2">
                        PASOS PARA REGISTRAR SU INCIDENCIA TÉCNICA
                    </h4>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-lg font-black text-emerald-700">1</span>
                            <span class="text-xs font-semibold text-slate-800">Apunte la cámara de su celular al código QR</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-lg font-black text-emerald-700">2</span>
                            <span class="text-xs font-semibold text-slate-800">Detalle el inconveniente en su equipo o sistema</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-lg font-black text-emerald-700">3</span>
                            <span class="text-xs font-semibold text-slate-800">Soporte TI atenderá su ticket a la brevedad</span>
                        </div>
                    </div>
                </div>

                <div class="text-[11px] text-slate-500 border-t border-slate-200 pt-3 flex justify-between items-center">
                    <span>Sistema Integrado de Gestión Municipal - SIGM Castilla</span>
                    <span class="font-bold">Colocar este cartel en un lugar visible de la oficina</span>
                </div>
            </div>
        </div>
    @endif

</div>
