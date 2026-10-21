<div class="space-y-6 animate-fadeIn">
            <!-- Header Card -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Parque Tecnológico Institucional</span><span class="sr-only">Inventario y Gestión TI (ITAM) - Total Equipos - Parque Informático por Categoría</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                            {{ $assets->total() }} Activos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control integral de hardware, correlativos automáticos, vinculación Padre-Hijo, actas oficiales y especificaciones adaptativas.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    <button wire:click="openAssetModal"
                            type="button"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                        <span>+</span>
                        <span>Registrar Activo</span>
                    </button>
                    <button wire:click="setTab('categories')"
                            type="button"
                            class="px-3.5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs border border-slate-300 dark:border-slate-700 transition-colors flex items-center space-x-1.5 cursor-pointer">
                        <span>⚙️</span>
                        <span>Esquemas Dinámicos</span>
                    </button>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                            Total Activos TI
                        </span>
                        <span class="text-2xl font-black text-slate-900 dark:text-white">
                            {{ $counts['assets'] ?? $assets->total() }}
                        </span>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold block mt-0.5">
                            &bull; Base de datos MDC
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-950/60 flex items-center justify-center text-xl text-cyan-700 dark:text-cyan-400">
                        💻
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                            Operativos
                        </span>
                        <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                            {{ $assets->where('status', 'OPERATIVO')->count() }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">
                            {{ ($counts['assets'] ?? 0) > 0 ? round(($assets->where('status', 'OPERATIVO')->count() / $counts['assets']) * 100) : 100 }}% disponibilidad
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 flex items-center justify-center text-xl text-emerald-700 dark:text-emerald-400">
                        🟢
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                            En Mantenimiento
                        </span>
                        <span class="text-2xl font-black text-amber-600 dark:text-amber-400">
                            {{ $assets->where('status', 'EN_MANTENIMIENTO')->count() }}
                        </span>
                        <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold block mt-0.5">
                            En taller o revisión
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 flex items-center justify-center text-xl text-amber-700 dark:text-amber-400">
                        🛠️
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                            Para Préstamos
                        </span>
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">
                            {{ $assets->where('is_loanable', true)->count() }}
                        </span>
                        <span class="text-[10px] text-slate-400 block mt-0.5">
                            Equipos clasificados RF-17
                        </span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/60 flex items-center justify-center text-xl text-purple-700 dark:text-purple-400">
                        ⏱️
                    </div>
                </div>
            </div>

            <!-- Barra de Filtros -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                        🔍
                    </span>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar por código (ej. MDC-TI-PC-0001), SBN, serie o custodio..."
                           class="w-full pl-8 pr-4 py-2 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="flex flex-wrap gap-2.5">
                    <select wire:model.live="selectedCategory"
                            class="text-xs px-2.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        <option value="">Todas las Categorías</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="selectedStatus"
                            class="text-xs px-2.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        <option value="">Todos los Estados</option>
                        <option value="OPERATIVO">Operativo</option>
                        <option value="EN_MANTENIMIENTO">En Mantenimiento</option>
                        <option value="EN_CUSTODIA">En Custodia</option>
                        <option value="EN_PRESTAMO">En Préstamo</option>
                        <option value="EN_DESUSO">En Desuso</option>
                        <option value="PARA_BAJA">Para Baja</option>
                    </select>

                    <select wire:model.live="selectedBrand"
                            class="text-xs px-2.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        <option value="">Todas las Marcas</option>
                        @foreach ($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Tabla de Activos -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">Código Informático / SBN</th>
                                <th class="py-3 px-4">Categoría & Equipo</th>
                                <th class="py-3 px-4">Periféricos / Hijos</th>
                                <th class="py-3 px-4">Ubicación & Custodio</th>
                                <th class="py-3 px-4">Estado / Condición</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($assets as $asset)
                                @php
                                    $catColor = $asset->category?->color ?? '#059669';
                                    $childCount = $asset->childAssets ? $asset->childAssets->count() : 0;
                                    $isGeneric = str_contains(strtoupper($asset->brand?->name ?? ''), 'GENÉRICO')
                                        || str_contains(strtoupper($asset->brand?->name ?? ''), 'DETERMINAR')
                                        || str_contains(strtoupper($asset->model?->name ?? ''), 'POR VERIFICAR')
                                        || str_contains(strtoupper($asset->notes ?? ''), 'POR VERIFICAR')
                                        || str_contains(strtoupper($asset->notes ?? ''), 'PROVISIONAL');
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-extrabold text-emerald-700 dark:text-emerald-400">
                                                {{ $asset->computer_code }}
                                            </span>
                                            @if ($asset->is_loanable)
                                                <span class="text-[9px] px-1.5 py-0.5 rounded font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300">
                                                    PRESTABLE
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 block font-mono">
                                            SBN: {{ $asset->patrimonial_code ?: 'Sin SBN' }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-sm shadow-sm"
                                                  style="background-color: {{ $catColor }}20; color: {{ $catColor }};">
                                                {{ $asset->category?->icon ?? '💻' }}
                                            </span>
                                            <div>
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-bold text-slate-900 dark:text-white">
                                                        {{ $asset->brand?->name }} {{ $asset->model?->name }}
                                                    </span>
                                                    @if ($isGeneric)
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-300 dark:border-amber-700 shadow-xs"
                                                              title="Equipo con marca o modelo provisional. Requiere verificación técnica posterior.">
                                                            <span>⚠️</span>
                                                            <span>Por Verificar</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-[10px] text-slate-400">
                                                    {{ $asset->category?->name }} &bull; Serie: {{ $asset->serial_number ?: 'S/N' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        @if ($childCount > 0)
                                            <button type="button"
                                                    wire:click="openChildModal({{ $asset->id }})"
                                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[11px] font-bold hover:bg-amber-200 transition-colors cursor-pointer">
                                                <span>🔗</span>
                                                <span>{{ $childCount }} Periférico{{ $childCount !== 1 ? 's' : '' }}</span>
                                            </button>
                                        @elseif ($asset->parent_asset_id)
                                            <span class="text-[10px] text-slate-500 font-mono">
                                                ↳ Hijo de {{ $asset->parentAsset?->computer_code }}
                                            </span>
                                        @else
                                            <button type="button"
                                                    wire:click="openChildModal({{ $asset->id }})"
                                                    class="text-[11px] text-slate-400 hover:text-emerald-600 flex items-center gap-1 cursor-pointer">
                                                <span>+ Vincular Hijos</span>
                                            </button>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4">
                                        <span class="block text-slate-800 dark:text-slate-200 font-semibold">
                                            {{ $asset->office ?: 'Almacén TI' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">
                                            👤 {{ $asset->assignedPerson ? $asset->assignedPerson->full_name : 'Sin custodio' }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4">
                                        @php
                                            $stBadgeClass = match($asset->status) {
                                                'OPERATIVO' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border-emerald-300 dark:border-emerald-800',
                                                'EN_MANTENIMIENTO' => 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400 border-amber-300 dark:border-amber-800',
                                                'EN_PRESTAMO', 'PRESTADO' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 border-indigo-300 dark:border-indigo-800',
                                                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border-slate-300 dark:border-slate-700',
                                            };
                                        @endphp
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $stBadgeClass }}">
                                            {{ str_replace('_', ' ', $asset->status) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">
                                            Condición: {{ $asset->physical_condition }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                        <button type="button"
                                                wire:click="openDetailModal({{ $asset->id }})"
                                                class="px-2 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 rounded-lg transition-colors cursor-pointer"
                                                title="Ver Ficha Técnica y QR">
                                            📋 Ficha
                                        </button>
                                        <button type="button"
                                                wire:click="openMovementModal({{ $asset->id }})"
                                                class="px-2 py-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 rounded-lg transition-colors cursor-pointer"
                                                title="Trasladar o reasignar equipo (RF-09)">
                                            🔄 Trasladar
                                        </button>
                                        <button type="button"
                                                wire:click="openActaModal('ASIGNACION', {{ $asset->id }})"
                                                class="px-2 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer"
                                                title="Generar Acta Oficial PDF (RF-10)">
                                            📄 Acta
                                        </button>
                                        <button type="button"
                                                wire:click="openMaintenanceModal({{ $asset->id }})"
                                                class="px-2 py-1 text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 hover:bg-amber-100 rounded-lg transition-colors cursor-pointer"
                                                title="Programar mantenimiento (RF-13)">
                                            🛠️
                                        </button>
                                        <button type="button"
                                                wire:click="openAssetModal({{ $asset->id }})"
                                                class="px-2 py-1 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer"
                                                title="Editar Activo">
                                            ✏️
                                        </button>
                                        <button type="button"
                                                wire:confirm="¿Está seguro de eliminar o dar de baja este activo tecnológico?"
                                                wire:click="deleteAsset({{ $asset->id }})"
                                                class="px-2 py-1 text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 rounded-lg transition-colors cursor-pointer"
                                                title="Eliminar Activo (Borrado Lógico RNF-05)">
                                            🗑️
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="6" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">🔍</span>
                                        No se encontraron activos tecnológicos que coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 sm:px-6 py-3.5 bg-slate-50/90 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 text-xs flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-3 text-slate-600 dark:text-slate-300">
                        <span>
                            Mostrando <strong class="font-bold text-slate-900 dark:text-white">{{ $assets->total() > 0 ? $assets->firstItem() : 0 }}</strong> a
                            <strong class="font-bold text-slate-900 dark:text-white">{{ $assets->total() > 0 ? $assets->lastItem() : 0 }}</strong> de
                            <strong class="font-bold text-slate-900 dark:text-white">{{ $assets->total() }}</strong> activos TI
                        </span>
                        <div class="flex items-center space-x-1.5 pl-2 border-l border-slate-300 dark:border-slate-700">
                            <span class="text-[11px] text-slate-400">Filas por pág:</span>
                            <select wire:model.live="perPage"
                                    class="py-1 px-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center space-x-1 select-none">
                        <button type="button"
                                wire:click="previousPage"
                                @disabled($assets->onFirstPage())
                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium text-[11px]"
                                title="Página anterior">
                            &lsaquo; Anterior
                        </button>

                        <div class="flex items-center space-x-1 px-1">
                            @foreach(range(1, max(1, $assets->lastPage())) as $page)
                                <button type="button"
                                        wire:click="gotoPage({{ $page }})"
                                        class="min-w-[28px] h-7 px-2 text-xs font-bold rounded-lg transition-colors {{ $assets->currentPage() === $page ? 'bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-500' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                    {{ $page }}
                                </button>
                            @endforeach
                        </div>

                        <button type="button"
                                wire:click="nextPage"
                                @disabled(!$assets->hasMorePages())
                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors font-medium text-[11px]"
                                title="Página siguiente">
                            Siguiente &rsaquo;
                        </button>
                    </div>
                </div>
            </div>
        </div>
