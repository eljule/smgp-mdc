<div class="space-y-6 pb-12" x-data="{ qrPreview: false }">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />


    <!-- ========================================================================= -->
    <!-- TAB 1: PARQUE TECNOLÓGICO INSTITUCIONAL (INVENTARIO ACTIVOS)              -->
    <!-- ========================================================================= -->
    @if ($activeTab === 'inventory')
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
                                            👤 {{ $asset->assignedPerson ? $asset->assignedPerson->first_name . ' ' . $asset->assignedPerson->last_name : 'Sin custodio' }}
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

    <!-- ========================================================================= -->
    <!-- TAB 2: MOVIMIENTOS & ACTAS (SRS RF-09, RF-10)                             -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'movements')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Trazabilidad de Movimientos & Reasignaciones</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                            {{ $movements->count() }} Registros
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Historial auditable de transferencias entre dependencias, asignaciones iniciales y actas formales en PDF (RF-10).
                    </p>
                </div>
                <button type="button"
                        wire:click="openMovementModal()"
                        class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Registrar Traslado / Asignación</span>
                </button>
            </div>

            <!-- Tabla de Movimientos -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">N° Acta Oficial</th>
                                <th class="py-3 px-4">Activo Informático</th>
                                <th class="py-3 px-4">Tipo Movimiento</th>
                                <th class="py-3 px-4">Origen ➔ Destino</th>
                                <th class="py-3 px-4">Técnico & Custodio</th>
                                <th class="py-3 px-4">Fecha</th>
                                <th class="py-3 px-4 text-right">Documento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($movements as $mov)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-emerald-700 dark:text-emerald-400">
                                        {{ $mov->acta_number ?: 'ACTA-' . $mov->id }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 dark:text-white block">
                                            {{ $mov->asset?->computer_code ?? 'ACTIVO #' . $mov->asset_id }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $mov->asset?->brand?->name }} {{ $mov->asset?->model?->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                            {{ $mov->movement_type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-slate-400 line-through text-[11px] block">
                                            {{ $mov->origin_office_name ?: 'Almacén Central' }}
                                        </span>
                                        <span class="font-bold text-slate-800 dark:text-slate-200 block">
                                            ➔ {{ $mov->destination_office_name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-semibold text-slate-800 dark:text-slate-200 block">
                                            {{ $mov->destination_person_name ?: 'Por Asignar' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">
                                            Autorizado: {{ $mov->authorized_by_user_name ?: 'Administrador SIGM' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                        {{ $mov->movement_date ? \Carbon\Carbon::parse($mov->movement_date)->format('d/m/Y') : $mov->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <button type="button"
                                                wire:click="openActaModal('TRANSFERENCIA', {{ $mov->asset_id }}, {{ $mov->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                            <span>📄</span>
                                            <span>Ver Acta PDF</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="7" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">🔄</span>
                                        No hay movimientos registrados. Use el botón "Trasladar" en cualquier activo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: MANTENIMIENTOS (SRS RF-13, RF-15, RF-16)                          -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'maintenance')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Órdenes de Mantenimiento Preventivo & Correctivo</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                            {{ $maintenanceOrders->count() }} Órdenes
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control de fallas, diagnósticos técnicos, descargo automático de insumos (RF-15) y emisión de hoja de servicio (RF-16).
                    </p>
                </div>
                <button type="button"
                        wire:click="openMaintenanceModal({{ $assets->first()?->id ?? 1 }})"
                        class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Programar Mantenimiento</span>
                </button>
            </div>

            <!-- Tabla de Órdenes -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">N° Orden</th>
                                <th class="py-3 px-4">Activo</th>
                                <th class="py-3 px-4">Tipo & Prioridad</th>
                                <th class="py-3 px-4">Diagnóstico / Acciones</th>
                                <th class="py-3 px-4">Técnico</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($maintenanceOrders as $ord)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-amber-700 dark:text-amber-400">
                                        {{ $ord->order_number }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 dark:text-white block">
                                            {{ $ord->asset?->computer_code ?? 'ACTIVO #' . $ord->asset_id }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $ord->asset?->brand?->name }} {{ $ord->asset?->model?->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            {{ $ord->type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 max-w-xs">
                                        <span class="text-slate-800 dark:text-slate-200 font-medium block truncate">
                                            {{ $ord->description }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block truncate">
                                            {{ $ord->observations ?: 'Sin observaciones adicionales' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700 dark:text-slate-300">
                                        {{ $ord->assigned_technician_name ?: 'Equipo Técnico ODT' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $ord->status === 'COMPLETADO' ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : 'bg-amber-100 text-amber-700 border-amber-300' }}">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        <button type="button"
                                                wire:click="openActaModal('MANTENIMIENTO', {{ $ord->asset_id }}, null, {{ $ord->id }})"
                                                class="px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                            📄 Hoja de Servicio
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="7" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">🛠️</span>
                                        No hay órdenes de mantenimiento en curso o completadas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: INSUMOS & STOCK (SRS RF-14, RF-15) - CUADRÍCULA DE TARJETAS        -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'supplies')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Stock de Insumos & Consumibles TI</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-cyan-100 dark:bg-cyan-900/40 text-cyan-800 dark:text-cyan-300">
                            {{ $supplies->count() }} Artículos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control de tóners por impresora, bobinas y patch cords de cable de red, conectores RJ-45 y pasta térmica (RF-14).
                    </p>
                </div>
                <button type="button"
                        wire:click="openSupplyModal"
                        class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Nuevo Insumo</span>
                </button>
            </div>

            <!-- Banner de Stock Crítico -->
            @if ($criticalSupplies->isNotEmpty())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <h4 class="text-xs font-bold text-rose-800 dark:text-rose-200 uppercase tracking-wider">
                                Alerta de Abastecimiento Crítico
                            </h4>
                            <p class="text-xs text-rose-700 dark:text-rose-300">
                                Hay {{ $criticalSupplies->count() }} insumos con stock igual o inferior a 2 unidades (umbral mínimo SRS).
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-rose-700 dark:text-rose-300 px-3 py-1 bg-white dark:bg-slate-900 rounded-xl border border-rose-300">
                        Atención Inmediata
                    </span>
                </div>
            @endif

            <!-- Cuadrícula de Insumos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($supplies as $sup)
                    @php $isCritical = $sup->stock <= $sup->min_stock; @endphp
                    <div class="p-5 rounded-2xl border bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between space-y-4 {{ $isCritical ? 'border-rose-300 dark:border-rose-800/80 ring-1 ring-rose-400/20' : 'border-slate-200 dark:border-slate-800' }}">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ match($sup->category) {
                                        'TONER_TINTA' => 'Tóner / Impresión',
                                        'CABLEADO_RED' => 'Cableado de Red',
                                        'CONECTORES' => 'Conectores RJ-45',
                                        'PASTA_TERMICA' => 'Pasta Térmica',
                                        'REPUESTOS_HARDWARE' => 'Hardware / Repuesto',
                                        'HERRAMIENTAS' => 'Herramientas TI',
                                        default => $sup->category ?: 'Consumible'
                                    } }}
                                </span>
                                @if ($isCritical)
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400 border border-rose-300">
                                        Stock Crítico
                                    </span>
                                @endif
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">
                                {{ $sup->name }}
                            </h4>
                            <p class="text-[11px] text-slate-400">
                                📍 {{ $sup->location ?: 'Almacén ODT' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block font-semibold">Stock Actual</span>
                                <span class="text-xl font-black {{ $isCritical ? 'text-rose-600' : 'text-slate-900 dark:text-white' }}">
                                    {{ $sup->stock }} <span class="text-xs font-normal text-slate-400">{{ $sup->unit ?: 'UND' }}</span>
                                </span>
                            </div>
                            <button type="button"
                                    wire:click="openSupplyModal({{ $sup->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold text-cyan-700 dark:text-cyan-400 bg-cyan-50 dark:bg-cyan-950/40 hover:bg-cyan-100 rounded-xl transition-colors cursor-pointer">
                                Ajustar Stock
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">📦</span>
                        No hay insumos registrados en el almacén TI.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- TAB 5: PRÉSTAMOS & RESERVAS (SRS RF-17, RF-18, RF-19, RF-20)              -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'loans')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Cesión Temporal & Reservas de Equipos</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300">
                            {{ $loans->count() }} Préstamos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Control de cañones multimedia, laptops de contingencia y parlantes prestados a dependencias municipales con alertas de retraso (RF-19).
                    </p>
                </div>
                <button type="button"
                        wire:click="openLoanModal"
                        class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Registrar Préstamo / Reserva</span>
                </button>
            </div>

            <!-- Alerta de Retrasos -->
            @php
                $delayedCount = $loans->where('status', 'ACTIVO')->filter(fn ($l) => $l->expected_return_date && \Carbon\Carbon::parse($l->expected_return_date)->isPast())->count();
            @endphp
            @if ($delayedCount > 0)
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⏰</span>
                        <div>
                            <h4 class="text-xs font-bold text-amber-800 dark:text-amber-200 uppercase tracking-wider">
                                Alertas de Devolución Retrasada
                            </h4>
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                Hay {{ $delayedCount }} préstamos que han sobrepasado la fecha fin estimada de retorno.
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-amber-800 dark:text-amber-300 px-3 py-1 bg-white dark:bg-slate-900 rounded-xl border border-amber-300">
                        Notificar a Dependencia
                    </span>
                </div>
            @endif

            <!-- Tabla de Préstamos -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4">N° Préstamo</th>
                                <th class="py-3 px-4">Dependencia & Solicitante</th>
                                <th class="py-3 px-4">Equipos Cedidos</th>
                                <th class="py-3 px-4">Período</th>
                                <th class="py-3 px-4">Estado</th>
                                <th class="py-3 px-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-medium">
                            @forelse ($loans as $ln)
                                @php
                                    $isDelayed = $ln->status === 'ACTIVO' && $ln->expected_return_date && \Carbon\Carbon::parse($ln->expected_return_date)->isPast();
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-indigo-700 dark:text-indigo-400">
                                        {{ $ln->loan_number }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-900 dark:text-white block">
                                            {{ $ln->applicant_office_name }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            👤 {{ $ln->applicant_person_name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 block">
                                            {{ $ln->asset?->computer_code ?? 'Equipo Institucional' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $ln->asset?->brand?->name }} {{ $ln->asset?->model?->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 font-mono text-[11px]">
                                        <span class="block text-slate-600 dark:text-slate-300">
                                            {{ $ln->loan_date ? \Carbon\Carbon::parse($ln->loan_date)->format('d/m/Y') : '' }}
                                        </span>
                                        <span class="text-[10px] {{ $isDelayed ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                                            Retorno: {{ $ln->expected_return_date ? \Carbon\Carbon::parse($ln->expected_return_date)->format('d/m/Y') : 'Indefinido' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase border {{ $ln->status === 'ACTIVO' ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-emerald-100 text-emerald-700 border-emerald-300' }}">
                                                {{ $ln->status }}
                                            </span>
                                            @if ($isDelayed)
                                                <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-rose-500 text-white animate-pulse">
                                                    DEMORADO
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-1">
                                        @if ($ln->status === 'ACTIVO')
                                            <button type="button"
                                                    wire:click="returnLoan({{ $ln->id }})"
                                                    class="px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 rounded-lg transition-colors cursor-pointer">
                                                ✓ Registrar Retorno
                                            </button>
                                        @endif
                                        <button type="button"
                                                wire:click="openActaModal('PRESTAMO', {{ $ln->asset_id ?? 1 }}, null, null, {{ $ln->id }})"
                                                class="px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                            📄 Acta
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="6" class="py-12 text-center text-slate-400">
                                        <span class="text-3xl block mb-2">⏱️</span>
                                        No hay préstamos activos o registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- TAB 6: AUDITORÍA ANUAL & CIERRE PATRIMONIAL (SRS RF-11, RF-12)            -->
        <!-- ========================================================================= -->
    <!-- TAB 6: AUDITORÍA ANUAL & CONTROL PATRIMONIAL (SRS RF-11, RF-12)            -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'audits')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Control de Inventario Anual & Auditoría Patrimonial</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-purple-600 text-white">
                            {{ $audits->count() }} Procesos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Cierre fotográfico (snapshot) por año fiscal, verificación física in-situ con pistola QR / tablet y reporte de conciliación (RF-11, RF-12).
                    </p>
                </div>
                <button type="button"
                        wire:click="openAuditModal"
                        class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Aperturar Inventario Anual</span>
                </button>
            </div>

            <!-- Cuadrícula de Auditorías -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse ($audits as $aud)
                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">📋</span>
                                <div>
                                    <h4 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                                        {{ $aud->title }}
                                    </h4>
                                    <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 block mt-0.5">
                                        Año Fiscal {{ $aud->year ?? $aud->fiscal_year ?? date('Y') }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase border {{ $aud->status === 'CERRADO' || $aud->status === 'CERRADA' ? 'bg-slate-100 text-slate-700 border-slate-300' : 'bg-emerald-50 text-emerald-600 border-emerald-300 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-700' }}">
                                {{ $aud->status === 'EN_PROCESO' ? 'EN PROCESO' : $aud->status }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl text-xs">
                            <div>
                                <span class="text-[11px] text-slate-400 font-medium block">Total Activos Censados</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $aud->verifications?->count() ?? $aud->total_assets_snapshot ?? 5 }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[11px] text-slate-400 font-medium block">Aperturado:</span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                    {{ $aud->start_date ? \Carbon\Carbon::parse($aud->start_date)->format('d/m/Y') : '11/10/2026' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <button type="button"
                                    wire:click="openAuditReconciliationModal({{ $aud->id }})"
                                    class="w-full py-2.5 px-4 text-xs sm:text-sm font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-xl transition-colors shadow-sm cursor-pointer text-center block">
                                Pistola QR / Conciliación en Campo
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">📋</span>
                        No hay procesos de inventario anual aperturados.
                    </div>
                @endforelse
            </div>
        </div>

<!-- ========================================================================= -->
    @elseif ($activeTab === 'software')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Catálogo de Software Institucional & Licencias</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300">
                            {{ $softwareList->count() }} Títulos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Gestión de sistemas operativos, suites ofimáticas, antivirus y herramientas especializadas municipales (RF-02).
                    </p>
                </div>
                <button type="button"
                        wire:click="openSoftwareModal"
                        class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Registrar Software</span>
                </button>
            </div>

            <!-- Cuadrícula de Software -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($softwareList as $sw)
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-cyan-50 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-300 border border-cyan-200">
                                    {{ $sw->license_type ?: 'LICENCIA' }}
                                </span>
                                <span class="text-xs font-mono font-bold text-slate-500">
                                    v{{ $sw->version ?: '1.0' }}
                                </span>
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">
                                {{ $sw->name }}
                            </h4>
                            <p class="text-[11px] text-slate-400">
                                Fabricante: {{ $sw->developer ?? ($sw->publisher ?? 'Institucional') }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block font-semibold">Licencias Asignadas</span>
                                <span class="text-base font-black text-slate-900 dark:text-white">
                                    {{ $sw->assets_count ?? 0 }} / {{ $sw->total_licenses ?: 'ILIMITADO' }}
                                </span>
                            </div>
                            <button type="button"
                                    wire:click="openSoftwareModal({{ $sw->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                ✏️ Editar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">💿</span>
                        No hay títulos de software registrados.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- TAB 8: CATEGORÍAS & ESQUEMAS DINÁMICOS (SRS RF-03)                       -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'categories')
        <div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Categorías Tecnológicas & Esquemas Adaptativos
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Cada categoría cuenta con su propio esquema dinámico de parámetros técnicos (procesadores, pantallas, memorias, puertos, etc.).
                    </p>
                </div>
                <button type="button"
                        wire:click="openCategoryModal"
                        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Nueva Categoría</span>
                </button>
            </div>

            <!-- Cuadrícula de Categorías -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($categories as $cat)
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-2xl shadow-sm"
                                         style="background-color: {{ $cat->color ?? '#059669' }}20; color: {{ $cat->color ?? '#059669' }};">
                                        {{ $cat->icon ?: '💻' }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">
                                            {{ $cat->name }}
                                        </h4>
                                        <span class="text-xs font-mono font-bold text-emerald-600">
                                            Prefijo: {{ $cat->code }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                                {{ $cat->description ?: 'Categoría estándar para inventario tecnológico municipal.' }}
                            </p>

                            <!-- Campos Dinámicos en Tag -->
                            @if (!empty($cat->custom_fields))
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach ($cat->custom_fields as $cf)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold">
                                            {{ $cf['label'] ?? ($cf['name'] ?? 'Campo') }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <button type="button"
                                    wire:click="openCategorySchemaModal({{ $cat->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 rounded-xl transition-colors cursor-pointer flex items-center gap-1">
                                <span>⚙️</span>
                                <span>Configurar Esquema Dinámico</span>
                            </button>
                            <button type="button"
                                    wire:click="openCategoryModal({{ $cat->id }})"
                                    class="p-1.5 text-xs text-slate-400 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                                    title="Editar Categoría">
                                ✏️
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- TAB 9: MARCAS & MODELOS HOMOLOGADOS (SRS RF-01)                           -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'catalogs')
        <div class="space-y-6 animate-fadeIn">
            <!-- Banner Superior -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Catálogo Homologado de Marcas y Modelos
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Estandarización de fabricantes y líneas de productos para evitar redundancias y variaciones tipográficas (RF-01).
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                            wire:click="openBrandModal"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm transition-colors cursor-pointer">
                        + Nueva Marca
                    </button>
                    <button type="button"
                            wire:click="openModelModal"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl shadow-sm transition-colors cursor-pointer">
                        + Nuevo Modelo
                    </button>
                </div>
            </div>

            <!-- Dos Columnas: Marcas y Modelos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Columna 1: Marcas -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>🏷️</span>
                                <span>Marcas Fabricantes ({{ $brands->count() }})</span>
                            </h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Fabricantes de equipamiento</p>
                        </div>
                        <button type="button"
                                wire:click="openBrandModal"
                                class="px-3 py-1.5 text-xs font-semibold bg-emerald-600 text-white rounded-xl shadow-sm hover:bg-emerald-700 transition-colors cursor-pointer">
                            + Nueva Marca
                        </button>
                    </div>

                    <!-- Buscador de Marcas -->
                    <div>
                        <input type="text"
                               placeholder="Buscar marca..."
                               wire:model.live.debounce.300ms="searchBrand"
                               class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-[500px] overflow-y-auto pr-1">
                        @if ($brands->isEmpty())
                            <p class="text-center py-6 text-xs text-slate-400">No se encontraron marcas registradas</p>
                        @else
                            @foreach ($brands as $b)
                                <div class="py-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/30 px-2 rounded-lg transition-colors group">
                                    <div class="flex-1 pr-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $b->name }}</span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $b->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                                {{ $b->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">
                                            {{ $b->models_count ?? $b->models->count() }} {{ ($b->models_count ?? $b->models->count()) === 1 ? 'modelo registrado' : 'modelos registrados' }}
                                            @if ($b->description) &bull; {{ $b->description }} @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button type="button"
                                                wire:click="openBrandModal({{ $b->id }})"
                                                title="Editar Marca"
                                                class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg transition-colors cursor-pointer">
                                            ✏️
                                        </button>
                                        <button type="button"
                                                wire:click="deleteBrand({{ $b->id }})"
                                                wire:confirm="¿Está seguro de eliminar la marca '{{ $b->name }}'?"
                                                title="Eliminar Marca"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition-colors cursor-pointer">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Columna 2: Modelos -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>📦</span>
                                <span>Modelos y Líneas ({{ $models->count() }})</span>
                            </h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Líneas de producto homologadas</p>
                        </div>
                        <button type="button"
                                wire:click="openModelModal"
                                class="px-3 py-1.5 text-xs font-semibold bg-emerald-600 text-white rounded-xl shadow-sm hover:bg-emerald-700 transition-colors cursor-pointer">
                            + Nuevo Modelo
                        </button>
                    </div>

                    <!-- Filtros de Modelos -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input type="text"
                               placeholder="Buscar modelo..."
                               wire:model.live.debounce.300ms="searchModel"
                               class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        <select wire:model.live="filterModelBrandId"
                                class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            <option value="">Todas las marcas</option>
                            @foreach ($allBrands as $br)
                                <option value="{{ $br->id }}">{{ $br->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-[500px] overflow-y-auto pr-1">
                        @if ($models->isEmpty())
                            <p class="text-center py-6 text-xs text-slate-400">No se encontraron modelos registrados</p>
                        @else
                            @foreach ($models as $m)
                                <div class="py-3 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/30 px-2 rounded-lg transition-colors group">
                                    <div class="flex-1 pr-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $m->name }}</span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $m->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                                {{ $m->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">
                                            Marca: <strong class="text-slate-600 dark:text-slate-300">{{ $m->brand?->name }}</strong>
                                            @if ($m->category?->name) &bull; Categoría: {{ $m->category->name }} @endif
                                            @if ($m->description) &bull; {{ $m->description }} @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button type="button"
                                                wire:click="openModelModal({{ $m->id }})"
                                                title="Editar Modelo"
                                                class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg transition-colors cursor-pointer">
                                            ✏️
                                        </button>
                                        <button type="button"
                                                wire:click="deleteModel({{ $m->id }})"
                                                wire:confirm="¿Está seguro de eliminar el modelo '{{ $m->name }}'?"
                                                title="Eliminar Modelo"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition-colors cursor-pointer">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif ($activeTab === 'metrics')
        <div class="space-y-6 animate-fadeIn">
            <!-- Banner Superior -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Dashboard Ejecutivo de Indicadores TI (KPIs)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Semáforos operativos, alertas de inventario crítico, vencimiento de garantías y cumplimiento de retornos.
                </p>
            </div>

            <!-- 4 Tarjetas de Indicadores Principales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">DISPONIBILIDAD TI</span>
                    <div class="text-3xl font-black text-emerald-600 mt-1">
                        {{ $metrics['disponibilidad'] }}%
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Equipos en estado operativo</p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">INSUMOS CRÍTICOS</span>
                    <div class="text-3xl font-black mt-1 {{ $metrics['criticalSuppliesCount'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        {{ $metrics['criticalSuppliesCount'] }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Con stock menor a 2 unidades</p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">MANTENIMIENTOS PENDIENTES</span>
                    <div class="text-3xl font-black text-amber-600 mt-1">
                        {{ $metrics['pendingMaintenance'] }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">En taller o programados</p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">PRÉSTAMOS RETRASADOS</span>
                    <div class="text-3xl font-black mt-1 {{ $metrics['delayedLoans'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        {{ $metrics['delayedLoans'] }}
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Vencidos sin retorno</p>
                </div>
            </div>

            <!-- Gráficos de Distribución con Barras de Progreso -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Desglose por Categoría -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                        Distribución de Activos por Categoría
                    </h4>
                    <div class="space-y-3">
                        @foreach ($metrics['categories'] as $cat)
                            @php
                                $cnt = $cat->assets_count;
                                $pct = ($metrics['totalAssets'] > 0) ? round(($cnt / $metrics['totalAssets']) * 100) : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                        <span>{{ $cat->icon ?: '💻' }}</span>
                                        <span>{{ $cat->name }}</span>
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">
                                        {{ $cnt }} ({{ $pct }}%)
                                    </span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         style="width: {{ $pct }}%; background-color: {{ $cat->color ?? '#10b981' }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desglose por Estado -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                        Estado Operativo del Parque Informático
                    </h4>
                    <div class="space-y-3">
                        @foreach ($metrics['statuses'] as $st)
                            @php
                                $cnt = $st['count'];
                                $pct = ($metrics['totalAssets'] > 0) ? round(($cnt / $metrics['totalAssets']) * 100) : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">
                                        {{ $st['label'] }}
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">
                                        {{ $cnt }} ({{ $pct }}%)
                                    </span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500"
                                         style="width: {{ $pct }}%; background-color: {{ $st['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @elseif ($activeTab === 'qr_lookup')
        <div class="space-y-6 animate-fadeIn">
            <div class="p-8 rounded-2xl bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] text-white shadow-xl space-y-4">
                <div class="max-w-2xl">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-300">
                        Búsqueda Rápida de Ficha Técnica Patrimonial
                    </span>
                    <h3 class="text-2xl font-black mt-1">
                        Consulta Inmediata por Código Informático / Pistola QR
                    </h3>
                    <p class="text-xs text-emerald-100/90 mt-1">
                        Escanee con un lector de código de barras / QR la etiqueta física adherida al activo, o ingrese manualmente el código informático correlativo (ej. MDC-TI-PC-0001, MDC-TI-MON-0002).
                    </p>
                </div>

                <form wire:submit="handleQuickLookup" class="flex gap-2 max-w-md pt-2">
                    <input type="text"
                           wire:model="quickLookupCode"
                           placeholder="MDC-TI-PC-0001"
                           class="flex-1 text-xs font-mono font-bold px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-emerald-200/50 outline-none focus:ring-2 focus:ring-emerald-400">
                    <button type="submit"
                            class="px-6 py-3 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-md transition-colors cursor-pointer">
                        Consultar Ficha
                    </button>
                </form>

                @if ($lookupError)
                    <p class="text-xs font-medium text-rose-300 bg-rose-950/60 p-3.5 rounded-xl border border-rose-800">
                        {{ $lookupError }}
                    </p>
                @endif

                @if ($lookupResult)
                    <div class="p-5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-base font-black text-emerald-300">
                                    {{ $lookupResult->computer_code }}
                                </span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase bg-emerald-500/30 text-emerald-200">
                                    {{ $lookupResult->status }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-white mt-1">
                                {{ $lookupResult->brand?->name }} {{ $lookupResult->model?->name }} ({{ $lookupResult->category?->name }})
                            </h4>
                            <p class="text-xs text-emerald-200/80">
                                📍 {{ $lookupResult->office ?: 'Almacén ODT' }} &bull; Custodio: {{ $lookupResult->assignedPerson ? $lookupResult->assignedPerson->first_name . ' ' . $lookupResult->assignedPerson->last_name : 'Sin asignar' }}
                            </p>
                        </div>
                        <button type="button"
                                wire:click="openDetailModal({{ $lookupResult->id }})"
                                class="px-4 py-2.5 rounded-xl bg-white text-emerald-900 font-bold text-xs shadow-md hover:bg-emerald-50 transition-colors cursor-pointer whitespace-nowrap">
                            Ver Ficha Completa & QR
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 1: VINCULAR PERIFÉRICOS & COMPONENTES HIJOS (SRS)                   -->
    <!-- ========================================================================= -->
    @if ($isChildModalOpen && $parentForChildModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#0d4f2f] to-[#105d38] text-white">
                    <div>
                        <h3 class="text-base font-bold">Vincular Periféricos & Componentes Hijos (SRS)</h3>
                        <p class="text-xs text-emerald-200">
                            Equipo Principal: {{ $parentForChildModal->computer_code }} &bull; {{ $parentForChildModal->brand?->name }} {{ $parentForChildModal->model?->name }}
                        </p>
                    </div>
                    <button wire:click="$set('isChildModalOpen', false)" class="text-white/80 hover:text-white text-xl cursor-pointer">&times;</button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Componentes actualmente vinculados -->
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">
                            Periféricos Vinculados a este Equipo
                        </h4>
                        @if ($parentForChildModal->childAssets && $parentForChildModal->childAssets->isNotEmpty())
                            <div class="space-y-2">
                                @foreach ($parentForChildModal->childAssets as $child)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/60">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-lg">{{ $child->category?->icon ?? '🔗' }}</span>
                                            <div>
                                                <span class="font-mono font-bold text-xs text-slate-900 dark:text-white">{{ $child->computer_code }}</span>
                                                <span class="text-[11px] text-slate-500 block">
                                                    {{ $child->brand?->name }} {{ $child->model?->name }} &bull; Serie: {{ $child->serial_number ?: 'S/N' }}
                                                </span>
                                            </div>
                                        </div>
                                        <button type="button"
                                                wire:click="unlinkChildAsset({{ $child->id }})"
                                                class="px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                            Desvincular
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 py-3 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                                Este equipo no cuenta con periféricos ni componentes secundarios asociados.
                            </p>
                        @endif
                    </div>

                    <!-- Vincular Nuevo Componente -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Vincular Nuevo Periférico / Componente Operativo
                        </h4>
                        <div class="flex gap-2">
                            <select wire:model="selectedChildAssetId"
                                    class="flex-1 text-xs px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <option value="">Seleccione un equipo disponible para vincular...</option>
                                @foreach ($candidateChildAssets as $candidate)
                                    <option value="{{ $candidate->id }}">
                                        {{ $candidate->computer_code }} - {{ $candidate->category?->name }}: {{ $candidate->brand?->name }} {{ $candidate->model?->name }} (S/N: {{ $candidate->serial_number ?: 'S/N' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button"
                                    wire:click="linkChildAsset"
                                    class="px-4 py-2 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-colors cursor-pointer">
                                + Vincular
                            </button>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                    <button type="button"
                            wire:click="$set('isChildModalOpen', false)"
                            class="px-4 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-300 transition-colors cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 2: CONFIGURACIÓN DE PARÁMETROS TÉCNICOS POR CATEGORÍA               -->
    <!-- ========================================================================= -->
    @if ($isCategorySchemaModalOpen && $categoryForSchema)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
            <div class="relative w-full max-w-4xl max-h-[90vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-sm"
                             style="background-color: {{ $categoryForSchema->color ?? '#2563eb' }}20; color: {{ $categoryForSchema->color ?? '#2563eb' }};">
                            {{ $categoryForSchema->icon ?: '💻' }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>Configuración de Parámetros Técnicos</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold uppercase bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                                    {{ $categoryForSchema->code }}
                                </span>
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Define las especificaciones dinámicas que se solicitarán al registrar activos de <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $categoryForSchema->name }}</span>.
                            </p>
                        </div>
                    </div>

                    <!-- Tab Switcher: Editor / Preview -->
                    <div class="flex items-center gap-2 bg-slate-200 dark:bg-slate-800 p-1 rounded-lg">
                        <button type="button"
                                wire:click="$set('schemaTab', 'editor')"
                                class="px-3 py-1 text-xs font-semibold rounded-md transition-all cursor-pointer {{ $schemaTab === 'editor' ? 'bg-white dark:bg-slate-700 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                            Editor de Esquema ({{ count($schemaFields) }})
                        </button>
                        <button type="button"
                                wire:click="$set('schemaTab', 'preview')"
                                class="px-3 py-1 text-xs font-semibold rounded-md transition-all cursor-pointer {{ $schemaTab === 'preview' ? 'bg-white dark:bg-slate-700 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                            Vista Previa en Vivo
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    @if ($schemaErrorMsg)
                        <div class="p-3 text-xs font-medium text-rose-700 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50 rounded-xl flex items-center justify-between">
                            <span>{{ $schemaErrorMsg }}</span>
                            <button type="button" wire:click="$set('schemaErrorMsg', null)" class="text-rose-500 hover:text-rose-700 cursor-pointer">
                                ✕
                            </button>
                        </div>
                    @endif

                    @if ($schemaTab === 'editor')
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 block">
                                        @if (empty($schemaFields))
                                            No hay parámetros definidos aún. Haz clic en "Agregar Parámetro" para comenzar.
                                        @else
                                            Se han configurado {{ count($schemaFields) }} atributos técnicos para esta categoría.
                                        @endif
                                    </span>
                                    @if (count($schemaFields) > 1)
                                        <span class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium flex items-center gap-1.5 mt-0.5">
                                            <span>💡</span>
                                            <span>Puedes reordenar los parámetros usando las flechas (↑ / ↓) o arrastrando las tarjetas.</span>
                                        </span>
                                    @endif
                                </div>

                                <button type="button"
                                        wire:click="addSchemaField"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm self-start sm:self-auto shrink-0 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Agregar Parámetro</span>
                                </button>
                            </div>

                            <!-- Lista de Parámetros -->
                            <div class="space-y-3" x-data="{ draggedId: null, overId: null }">
                                @php $cardNum = 0; $totalCount = count($schemaFields); @endphp
                                @foreach ($schemaFields as $fieldId => $field)
                                    @php $cardNum++; @endphp
                                    <div wire:key="schema-field-{{ $fieldId }}"
                                         draggable="true"
                                         x-on:dragstart="if ($event.target.tagName === 'INPUT' || $event.target.tagName === 'SELECT' || $event.target.tagName === 'TEXTAREA' || $event.target.closest('button')) { $event.preventDefault(); return; } draggedId = '{{ $fieldId }}'; $event.dataTransfer.effectAllowed = 'move'; $event.dataTransfer.setData('text/plain', '{{ $fieldId }}')"
                                         x-on:dragover.prevent="overId = '{{ $fieldId }}'"
                                         x-on:dragleave="if (overId === '{{ $fieldId }}') overId = null"
                                         x-on:drop.prevent="
                                             const fromId = draggedId || $event.dataTransfer.getData('text/plain');
                                             if (fromId && fromId !== '{{ $fieldId }}') {
                                                 $wire.reorderSchemaField(fromId, '{{ $fieldId }}');
                                             }
                                             draggedId = null;
                                             overId = null;
                                         "
                                         x-on:dragend="draggedId = null; overId = null"
                                         :class="{
                                             'opacity-40 border-dashed border-emerald-500 bg-emerald-50/20 scale-[0.99]': draggedId === '{{ $fieldId }}',
                                             'border-emerald-500 bg-emerald-50/40 dark:bg-emerald-950/30 ring-2 ring-emerald-500/30 shadow-md': overId === '{{ $fieldId }}' && draggedId !== '{{ $fieldId }}'
                                         }"
                                         class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all space-y-3 cursor-default">
                                        <!-- Cabecera de la Tarjeta -->
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700/60 pb-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <div class="cursor-grab active:cursor-grabbing p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors" title="Arrastra para reordenar la posición">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 8h16M4 16h16" />
                                                    </svg>
                                                </div>

                                                <span class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-300">
                                                    {{ $cardNum }}
                                                </span>
                                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ $field['label'] ?: 'Parámetro sin nombre' }}
                                                </span>
                                                <code class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">
                                                    key: {{ $field['key'] ?? '' }}
                                                </code>
                                            </div>

                                            <div class="flex items-center gap-1.5">
                                                <!-- Mover Arriba -->
                                                <button type="button"
                                                        wire:click="moveSchemaField('{{ $fieldId }}', 'up')"
                                                        @if ($cardNum === 1) disabled @endif
                                                        class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-slate-400 transition-colors cursor-pointer"
                                                        title="Mover arriba">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </button>

                                                <!-- Mover Abajo -->
                                                <button type="button"
                                                        wire:click="moveSchemaField('{{ $fieldId }}', 'down')"
                                                        @if ($cardNum === $totalCount) disabled @endif
                                                        class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-slate-400 transition-colors cursor-pointer"
                                                        title="Mover abajo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>

                                                <span class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></span>

                                                <!-- Eliminar -->
                                                <button type="button"
                                                        wire:click="removeSchemaField('{{ $fieldId }}')"
                                                        class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 p-1.5 rounded transition-colors cursor-pointer"
                                                        title="Eliminar parámetro">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Grid de Configuración -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                            <div>
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    Etiqueta Visible *
                                                </label>
                                                <input type="text"
                                                       wire:key="f-lbl-{{ $fieldId }}"
                                                       wire:model="schemaFields.{{ $fieldId }}.label"
                                                       placeholder="Ej. Procesador (CPU)"
                                                       class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                            </div>

                                            <div>
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    Clave Técnica (JSON key) *
                                                </label>
                                                <input type="text"
                                                       wire:key="f-key-{{ $fieldId }}"
                                                       wire:model="schemaFields.{{ $fieldId }}.key"
                                                       placeholder="Ej. processor, ram_gb"
                                                       class="w-full text-xs font-mono px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                            </div>

                                            <div>
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    Tipo de Dato
                                                </label>
                                                <select wire:key="f-typ-{{ $fieldId }}"
                                                        wire:model.live="schemaFields.{{ $fieldId }}.type"
                                                        class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                                    <option value="text">Texto simple</option>
                                                    <option value="number">Número</option>
                                                    <option value="select">Selección / Desplegable</option>
                                                    <option value="tags">Lista / Etiquetas dinámicas (Array)</option>
                                                    <option value="boolean">Booleano (Sí/No)</option>
                                                    <option value="textarea">Área de texto amplio</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center gap-3 pt-4 sm:pt-0">
                                                <label class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                                    <input type="checkbox"
                                                           wire:key="f-req-{{ $fieldId }}"
                                                           wire:model="schemaFields.{{ $fieldId }}.required"
                                                           class="rounded text-emerald-600 focus:ring-emerald-500" />
                                                    <span>Obligatorio</span>
                                                </label>

                                                <div class="flex-1">
                                                    <input type="text"
                                                           wire:key="f-unt-{{ $fieldId }}"
                                                           wire:model="schemaFields.{{ $fieldId }}.unit"
                                                           placeholder="Unidad (GB, '', Watts)"
                                                           class="w-full text-xs px-2 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Opciones extra para Select / Tags -->
                                        @if (in_array($field['type'] ?? 'text', ['select', 'tags', 'list']))
                                            <div class="pt-2 border-t border-slate-200 dark:border-slate-800/80">
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    {{ ($field['type'] ?? 'text') === 'select' ? 'Opciones de la lista desplegable (separadas por coma)' : 'Opciones sugeridas para agregar rápido (opcional, separadas por coma)' }}
                                                </label>
                                                <input type="text"
                                                       wire:key="f-opt-{{ $fieldId }}"
                                                       wire:model="schemaFields.{{ $fieldId }}.options_str"
                                                       placeholder="{{ ($field['type'] ?? 'text') === 'select' ? 'Ej. 4 GB, 8 GB, 16 GB, 32 GB, 64 GB' : 'Ej. Office 2021 LTSC, AutoCAD 2024, ArcGIS Pro' }}"
                                                       class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                            </div>
                                        @endif

                                        <!-- Placeholder / sugerencia -->
                                        <div>
                                            <input type="text"
                                                   wire:key="f-ph-{{ $fieldId }}"
                                                   wire:model="schemaFields.{{ $fieldId }}.placeholder"
                                                   placeholder="Texto de sugerencia / placeholder (opcional)"
                                                   class="w-full text-xs px-2.5 py-1 rounded border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Vista Previa en Vivo -->
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/40 text-xs text-emerald-800 dark:text-emerald-300">
                                Esta es la visualización exacta que verá el operador al registrar o editar activos pertenecientes a la categoría <strong>{{ $categoryForSchema->name }}</strong>.
                            </div>

                            <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                                @if (empty($schemaFields))
                                    <p class="text-xs text-slate-400 text-center py-6">
                                        No hay parámetros configurados para mostrar en la vista previa.
                                    </p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($schemaFields as $pf)
                                            <div class="{{ ($pf['type'] ?? 'text') === 'textarea' ? 'md:col-span-2' : '' }}">
                                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                                    {{ $pf['label'] }}
                                                    @if (!empty($pf['required'])) <span class="text-rose-500">*</span> @endif
                                                    @if (!empty($pf['unit'])) <span class="text-slate-400 text-[10px]">({{ $pf['unit'] }})</span> @endif
                                                </label>

                                                @if (($pf['type'] ?? 'text') === 'select')
                                                    <select wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                            class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                                        <option value="">-- Seleccionar {{ $pf['label'] }} --</option>
                                                        @php
                                                            $opts = !empty($pf['options_str']) ? array_filter(array_map('trim', explode(',', $pf['options_str']))) : ($pf['options'] ?? []);
                                                        @endphp
                                                        @foreach ($opts as $opt)
                                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif (($pf['type'] ?? 'text') === 'textarea')
                                                    <textarea rows="3"
                                                              wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                              placeholder="{{ ($pf['placeholder'] ?? '') ?: ('Ingrese ' . ($pf['label'] ?? '')) }}"
                                                              class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none"></textarea>
                                                @elseif (($pf['type'] ?? 'text') === 'boolean')
                                                    <div class="flex items-center gap-2 pt-2">
                                                        <input type="checkbox"
                                                               wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                               class="rounded text-emerald-600 focus:ring-emerald-500" />
                                                        <span class="text-xs text-slate-700 dark:text-slate-300">{{ $pf['placeholder'] ?: 'Habilitado / Presente' }}</span>
                                                    </div>
                                                @elseif (($pf['type'] ?? 'text') === 'number')
                                                    <input type="number"
                                                           wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                           placeholder="{{ $pf['placeholder'] ?: '0' }}"
                                                           class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white" />
                                                @else
                                                    <input type="text"
                                                           wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                           placeholder="{{ ($pf['placeholder'] ?? '') ?: ('Ingrese ' . ($pf['label'] ?? '')) }}"
                                                           class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white" />
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- JSON result preview -->
                            <div class="space-y-1">
                                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                    Valores resultantes en JSON (PostgreSQL JSONB `specifications`)
                                </span>
                                <pre class="p-3 rounded-xl bg-slate-950 text-emerald-400 text-xs font-mono overflow-x-auto max-h-40 border border-slate-800">{{ json_encode($schemaPreviewValues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                    <button type="button"
                            wire:click="$set('isCategorySchemaModalOpen', false)"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                        Cancelar
                    </button>

                    <button type="button"
                            wire:click="saveCategorySchema"
                            class="inline-flex items-center gap-2 px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Guardar Esquema Técnico</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
<!-- ========================================================================= -->
    <!-- MODAL 3: FICHA TÉCNICA PATRIMONIAL E INFORMÁTICA & QR                      -->
    <!-- ========================================================================= -->
    @if ($isDetailModalOpen && $selectedAssetForDetail)
        @php
            $statusClasses = match ($selectedAssetForDetail->status) {
                'OPERATIVO' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-800',
                'EN_MANTENIMIENTO' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-400 dark:border-amber-800',
                'EN_CUSTODIA' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/60 dark:text-blue-400 dark:border-blue-800',
                'EN_DESUSO' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
                'PARA_BAJA' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-400 dark:border-rose-800',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            };

            $scanUrl = url('/scan/activo/' . $selectedAssetForDetail->computer_code);
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($scanUrl);

            $specs = is_array($selectedAssetForDetail->specifications)
                ? $selectedAssetForDetail->specifications
                : (json_decode($selectedAssetForDetail->specifications ?? '[]', true) ?: []);
            $categorySchema = $selectedAssetForDetail->category?->custom_fields_schema ?? [];
            $orderedSpecs = [];
            $handledKeys = [];

            if (is_array($categorySchema)) {
                foreach ($categorySchema as $f) {
                    $fKey = $f['key'] ?? '';
                    if (! $fKey) continue;

                    $matchedKey = null;
                    if (array_key_exists($fKey, $specs)) {
                        $matchedKey = $fKey;
                    } else {
                        foreach (array_keys($specs) as $k) {
                            if (strtolower((string) $k) === strtolower((string) $fKey)) {
                                $matchedKey = $k;
                                break;
                            }
                        }
                    }

                    if ($matchedKey !== null) {
                        $handledKeys[] = $matchedKey;
                        $orderedSpecs[] = [
                            'label' => $f['label'] ?? ucfirst(str_replace('_', ' ', $fKey)),
                            'unit' => $f['unit'] ?? null,
                            'val' => $specs[$matchedKey],
                        ];
                    }
                }
            }

            foreach ($specs as $k => $v) {
                if (! in_array($k, $handledKeys, true)) {
                    $orderedSpecs[] = [
                        'label' => ucwords(str_replace('_', ' ', (string) $k)),
                        'unit' => null,
                        'val' => $v,
                    ];
                }
            }
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs animate-fadeIn print:p-0 print:bg-white print:static">
            <div class="relative w-full max-w-3xl max-h-[92vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp print:shadow-none print:border-none print:w-full print:max-w-none print:max-h-none print:overflow-visible">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900 print:border-b-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-xl text-emerald-700 dark:text-emerald-400 shrink-0">
                            {{ $selectedAssetForDetail->category?->icon ?: '💻' }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-base font-extrabold text-emerald-800 dark:text-emerald-300">
                                    {{ $selectedAssetForDetail->computer_code }}
                                </span>
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase {{ $statusClasses }}">
                                    {{ str_replace('_', ' ', $selectedAssetForDetail->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Ficha Técnica Patrimonial e Informática • Municipalidad Distrital de Castilla
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 print:hidden">
                        <button type="button"
                                onclick="window.print()"
                                class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors cursor-pointer"
                                title="Imprimir Etiqueta / Ficha">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                        <button type="button"
                                wire:click="$set('isDetailModalOpen', false)"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer text-lg font-normal">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-5">
                    <!-- Card 1: Top Banner (Datos Principales & QR) -->
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-start justify-between gap-6 shadow-xs">
                        <div class="space-y-2 flex-1">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                                {{ strtoupper($selectedAssetForDetail->category?->name ?? 'EQUIPO TECNOLÓGICO') }}
                            </span>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">
                                    {{ $selectedAssetForDetail->brand?->name }} {{ $selectedAssetForDetail->model?->name }}
                                </h3>
                            </div>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-3 pt-2 text-xs">
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Código Patrimonial SBN</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $selectedAssetForDetail->patrimonial_code ?: 'No registrado' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Número de Serie</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">
                                        {{ $selectedAssetForDetail->serial_number ?: 'S/N' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Condición Física</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                                        {{ $selectedAssetForDetail->physical_condition ?? 'BUENO' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Color</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $selectedAssetForDetail->color ?: 'Estándar' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Proveedor / Compra</span>
                                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $selectedAssetForDetail->supplier ?: 'No especificado' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Garantía Fabr.</span>
                                    @if ($selectedAssetForDetail->warranty_end_date)
                                        @php
                                            $isExpired = \Carbon\Carbon::parse($selectedAssetForDetail->warranty_end_date)->isPast();
                                        @endphp
                                        <span class="font-semibold {{ $isExpired ? 'text-rose-500' : 'text-slate-800 dark:text-slate-200' }}">
                                            {{ \Carbon\Carbon::parse($selectedAssetForDetail->warranty_end_date)->format('Y-m-d') }} ({{ $isExpired ? 'Vencida' : 'Vigente' }})
                                        </span>
                                    @else
                                        <span class="font-semibold text-slate-400">Sin registrar</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Tag -->
                        <div class="p-3 bg-white rounded-xl shadow-xs border border-slate-200 flex flex-col items-center gap-1.5 shrink-0 self-start w-36">
                            <img src="{{ $qrUrl }}"
                                 alt="QR {{ $selectedAssetForDetail->computer_code }}"
                                 class="w-28 h-28 object-contain" />
                            <span class="text-[10px] font-mono font-bold text-slate-700 tracking-tight text-center">
                                {{ $selectedAssetForDetail->computer_code }}
                            </span>
                            <a href="{{ $scanUrl }}"
                               target="_blank"
                               class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-0.5 mt-0.5">
                                <span>Abrir Ficha Móvil</span>
                                <span>↗</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Asignación y Custodia Institucional -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            Asignación y Custodia Institucional
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center shrink-0 text-base">
                                    🏢
                                </div>
                                <div>
                                    <span class="text-[11px] text-slate-400 block font-normal">Oficina / Dependencia</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                                        {{ $selectedAssetForDetail->office ?: 'ALMACÉN TI (SIN ASIGNAR)' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center shrink-0 text-base">
                                    👤
                                </div>
                                <div>
                                    <span class="text-[11px] text-slate-400 block font-normal">Custodio Responsable</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">
                                        {{ $selectedAssetForDetail->assignedPerson ? trim($selectedAssetForDetail->assignedPerson->first_name . ' ' . $selectedAssetForDetail->assignedPerson->last_name) : 'SIN ASIGNAR' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Subcomponentes Vinculados (Padre - Hijo) -->
                    <div class="p-4 rounded-xl border border-amber-300 dark:border-amber-800/80 bg-amber-50/15 dark:bg-amber-950/10 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-400 flex items-center gap-1.5">
                                <span class="text-amber-600">🔗</span> SUBCOMPONENTES VINCULADOS (PADRE - HIJO)
                            </span>
                            <button type="button"
                                    wire:click="openChildModal({{ $selectedAssetForDetail->id }})"
                                    class="bg-[#d97706] hover:bg-[#b45309] text-white text-xs font-bold px-3.5 py-1.5 rounded-lg shadow-xs transition-colors cursor-pointer">
                                Gestionar Periféricos
                            </button>
                        </div>

                        @if ($selectedAssetForDetail->childAssets && $selectedAssetForDetail->childAssets->isNotEmpty())
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                @foreach ($selectedAssetForDetail->childAssets as $child)
                                    <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex items-center justify-between">
                                        <div>
                                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400 text-xs">{{ $child->computer_code }}</span>
                                            <span class="font-medium text-slate-800 dark:text-slate-200 block text-xs mt-0.5">
                                                {{ $child->brand?->name }} {{ $child->model?->name }} ({{ $child->category?->name ?? 'Periférico' }})
                                            </span>
                                            @if ($child->serial_number)
                                                <span class="text-[10px] text-slate-400 font-mono block mt-0.5">S/N: {{ $child->serial_number }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[9px] px-2 py-0.5 rounded-full font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 uppercase border border-slate-200 dark:border-slate-600">
                                            {{ $child->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-500 italic">
                                Este equipo no tiene periféricos vinculados como hijos (mouse, monitor, teclado, estabilizador).
                            </p>
                        @endif
                    </div>

                    <!-- Card 4: Especificaciones Técnicas Registradas -->
                    <div class="p-4 rounded-xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50/15 dark:bg-emerald-950/20 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">
                                ESPECIFICACIONES TÉCNICAS REGISTRADAS ({{ strtoupper($selectedAssetForDetail->category?->name ?? 'EQUIPO') }})
                            </h4>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 font-semibold border border-emerald-200 dark:border-emerald-800">
                                Esquema Dinámico
                            </span>
                        </div>

                        @if (count($orderedSpecs) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                @foreach ($orderedSpecs as $spec)
                                    @php $isArray = is_array($spec['val']); @endphp
                                    <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-xs flex flex-col justify-center {{ $isArray ? 'md:col-span-2' : '' }}">
                                        <span class="text-[11px] text-slate-400 dark:text-slate-400 font-medium">
                                            {{ $spec['label'] }} {{ $spec['unit'] ? "({$spec['unit']})" : '' }}
                                        </span>
                                        @if ($isArray)
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                @forelse ($spec['val'] as $item)
                                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[11px] font-semibold">
                                                        {{ $item }}
                                                    </span>
                                                @empty
                                                    <span class="text-slate-400 italic">Ningún elemento registrado</span>
                                                @endforelse
                                            </div>
                                        @else
                                            <span class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">
                                                {{ is_bool($spec['val']) ? ($spec['val'] ? 'Sí' : 'No') : ($spec['val'] ?: '-') }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-500 italic">
                                No se registraron especificaciones técnicas particulares para este activo.
                            </p>
                        @endif
                    </div>

                    <!-- Observaciones y Notas (si existen) -->
                    @if ($selectedAssetForDetail->notes)
                        @php
                            $isWarning = str_contains(strtoupper($selectedAssetForDetail->notes), 'VERIFICACIÓN PENDIENTE')
                                || str_contains($selectedAssetForDetail->notes, '⚠️')
                                || str_contains(strtoupper($selectedAssetForDetail->notes), 'POR VERIFICAR');
                        @endphp
                        <div class="p-4 rounded-xl border {{ $isWarning ? 'border-amber-300 dark:border-amber-800/80 bg-amber-50/70 dark:bg-amber-950/30' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30' }}">
                            <div class="flex items-center justify-between mb-1.5 flex-wrap gap-1">
                                <span class="text-[11px] font-bold uppercase flex items-center gap-1 {{ $isWarning ? 'text-amber-800 dark:text-amber-300' : 'text-slate-400' }}">
                                    @if ($isWarning) <span>⚠️</span> @endif
                                    Observaciones y Notas
                                </span>
                                @if ($isWarning)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-200 border border-amber-300 dark:border-amber-800">
                                        Auditoría / Verificación de Hardware Requerida
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs whitespace-pre-line {{ $isWarning ? 'text-amber-900 dark:text-amber-100 font-medium' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $selectedAssetForDetail->notes }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900/60 flex-wrap gap-2 print:hidden">
                    <div class="flex items-center gap-2">
                        <button type="button"
                                wire:click="$set('isDetailModalOpen', false)"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                            Cerrar
                        </button>
                        <button type="button"
                                wire:click="openActaModal('ASIGNACION', {{ $selectedAssetForDetail->id }})"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 shadow-xs transition-colors cursor-pointer">
                            <span>📄</span> Acta Oficial PDF
                        </button>
                        <button type="button"
                                wire:click="openMovementModal({{ $selectedAssetForDetail->id }})"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-900 rounded-xl hover:bg-indigo-100 transition-colors cursor-pointer">
                            <span>🔄</span> Trasladar / Asignar
                        </button>
                    </div>

                    <button type="button"
                            wire:click="openAssetModal({{ $selectedAssetForDetail->id }})"
                            class="inline-flex items-center gap-2 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Editar Activo
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 4: REGISTRAR / EDITAR ACTIVO TECNOLÓGICO                            -->
    <!-- ========================================================================= -->
    @if ($isAssetModalOpen)
        @php
            $filteredModels = $formBrandId ? $models->where('brand_id', $formBrandId) : collect();
            $currentCategory = $categories->firstWhere('id', $formCategoryId);
            $currentSchema = $currentCategory?->custom_fields_schema ?? [];
            $selectedCatCode = $currentCategory?->code ?? 'PC';
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-5xl max-h-[92vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header con Gradiente Institucional -->
                <div class="px-6 py-4 border-b border-emerald-800 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] text-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-xl shadow-inner">
                            {{ $currentCategory?->icon ?? '💻' }}
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold flex items-center gap-2">
                                <span>{{ $editingAssetId ? 'Editar Activo Tecnológico' : 'Registrar Nuevo Activo Tecnológico' }}</span>
                                @if ($editingAssetId)
                                    <span class="font-mono text-xs px-2 py-0.5 rounded bg-emerald-500/30 border border-emerald-400/30 text-emerald-200">
                                        {{ $formComputerCode }}
                                    </span>
                                @endif
                            </h2>
                            <p class="text-xs text-emerald-200/90">
                                Inventario TI Municipal &bull; Formulario adaptativo de especificaciones técnicas
                            </p>
                        </div>
                    </div>

                    <button type="button"
                            wire:click="$set('isAssetModalOpen', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-emerald-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer text-lg">
                        &times;
                    </button>
                </div>

                <!-- Formulario con Scroll Interno -->
                <form wire:submit="saveAsset" class="flex-1 overflow-y-auto p-6 space-y-6">
                    @if ($errors->any())
                        <div class="p-3 text-xs font-semibold text-rose-700 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50 rounded-xl">
                            Por favor complete los campos obligatorios requeridos (*) antes de guardar.
                        </div>
                    @endif
                    <!-- Sección 01: Clasificación y Categoría -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                                <span>01</span> CLASIFICACIÓN Y CATEGORÍA DEL ACTIVO
                            </h3>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                * Cambiar la categoría adaptará automáticamente las especificaciones técnicas
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Categoría Tecnológica -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Categoría Tecnológica *
                                </label>
                                <select wire:model.live="formCategoryId"
                                        @disabled($editingAssetId)
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-60 cursor-pointer">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }} ({{ $cat->code }})</option>
                                    @endforeach
                                </select>
                                @if ($currentCategory)
                                    <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                        Código proyectado: <span class="font-mono font-bold text-emerald-700 dark:text-emerald-400">MDC-TI-{{ $selectedCatCode }}-XXXX</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Marca -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Marca *
                                    </label>
                                    <button type="button"
                                            wire:click="$toggle('showAddBrand')"
                                            class="text-[11px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold cursor-pointer">
                                        + Nueva Marca
                                    </button>
                                </div>
                                @if ($showAddBrand)
                                    <div class="flex gap-1.5">
                                        <input type="text"
                                               wire:model="newBrandName"
                                               placeholder="Nombre marca (ej. Asus)"
                                               class="flex-1 text-xs px-2.5 py-1.5 rounded-lg border border-emerald-400 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                        <button type="button"
                                                wire:click="quickAddBrand"
                                                class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 cursor-pointer">
                                            Guardar
                                        </button>
                                        <button type="button"
                                                wire:click="$set('showAddBrand', false)"
                                                class="px-2 py-1 text-xs text-slate-500 hover:text-slate-700 cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                @else
                                    <select wire:model.live="formBrandId"
                                            class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer">
                                        <option value="">-- Seleccionar Marca --</option>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('formBrandId')
                                    <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Modelo -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Modelo *
                                    </label>
                                    @if ($formBrandId)
                                        <button type="button"
                                                wire:click="$toggle('showAddModel')"
                                                class="text-[11px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold cursor-pointer">
                                            + Nuevo Modelo
                                        </button>
                                    @endif
                                </div>
                                @if ($showAddModel)
                                    <div class="flex gap-1.5">
                                        <input type="text"
                                               wire:model="newModelName"
                                               placeholder="Nombre modelo (ej. ProBook 450)"
                                               class="flex-1 text-xs px-2.5 py-1.5 rounded-lg border border-emerald-400 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                        <button type="button"
                                                wire:click="quickAddModel"
                                                class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 cursor-pointer">
                                            Guardar
                                        </button>
                                        <button type="button"
                                                wire:click="$set('showAddModel', false)"
                                                class="px-2 py-1 text-xs text-slate-500 hover:text-slate-700 cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                @else
                                    <select wire:model="formModelId"
                                            @disabled(!$formBrandId)
                                            class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white disabled:opacity-50 cursor-pointer">
                                        <option value="">
                                            {{ !$formBrandId ? 'Primero seleccione una marca' : '-- Seleccionar Modelo --' }}
                                        </option>
                                        @foreach ($filteredModels as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('formModelId')
                                    <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sección 02: Identificación Institucional, Estado y Custodia -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                            <span>02</span> IDENTIFICACIÓN INSTITUCIONAL, ESTADO Y CUSTODIA
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Código Patrimonial (SBN) -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Código Patrimonial (SBN)
                                </label>
                                <input type="text"
                                       wire:model="formPatrimonialCode"
                                       placeholder="Ej. 740895000105"
                                       class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Número de Serie -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Número de Serie
                                </label>
                                <input type="text"
                                       wire:model="formSerialNumber"
                                       placeholder="Ej. S/N 8920FA4401"
                                       class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Color del Equipo -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Color del Equipo
                                </label>
                                <input type="text"
                                       wire:model="formColor"
                                       placeholder="Ej. Negro, Plata, Gris"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Fecha de Adquisición -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Fecha de Adquisición
                                </label>
                                <input type="date"
                                       wire:model="formAcquisitionDate"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer">
                            </div>

                            <!-- Estado Operativo -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Estado Operativo *
                                </label>
                                <select wire:model="formStatus"
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium cursor-pointer">
                                    <option value="OPERATIVO">🟢 Operativo</option>
                                    <option value="EN_MANTENIMIENTO">🟡 En Mantenimiento</option>
                                    <option value="EN_CUSTODIA">🔵 En Custodia / Almacén TI</option>
                                    <option value="EN_DESUSO">⚪ En Desuso</option>
                                    <option value="PARA_BAJA">🔴 Para Baja Técnica</option>
                                </select>
                            </div>

                            <!-- Condición Física -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Condición Física *
                                </label>
                                <select wire:model="formPhysicalCondition"
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium cursor-pointer">
                                    <option value="NUEVO">✨ Nuevo / Sellado</option>
                                    <option value="BUENO">👍 Bueno</option>
                                    <option value="REGULAR">⚠️ Regular</option>
                                    <option value="MALO">❌ Malo / Deteriorado</option>
                                </select>
                            </div>

                            <!-- Dependencia / Oficina -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Dependencia / Oficina
                                </label>
                                <input type="text"
                                       list="modal-municipal-offices"
                                       wire:model.live.debounce.300ms="formOffice"
                                       placeholder="Oficina de Desarrollo Tecnológico"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <datalist id="modal-municipal-offices">
                                    @foreach ($offices as $off)
                                        <option value="[{{ $off->acronym }}] {{ $off->name }} - {{ $off->sede }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Custodio Asignado -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block">
                                        Custodio Asignado (Personal)
                                    </label>
                                    @if ($formAssignedPersonId)
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Personal Vinculado
                                        </span>
                                    @endif
                                </div>
                                <input type="text"
                                       list="modal-custodians"
                                       wire:model.live.debounce.300ms="formAssignedPersonName"
                                       placeholder="Buscar por nombre o DNI (o dejar en blanco para Almacén TI)..."
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                <datalist id="modal-custodians">
                                    <option value="-- Sin Custodio Asignado (Almacén TI) --"></option>
                                    @foreach ($people as $p)
                                        <option value="{{ $p->full_name }} ({{ $p->document_number }})">{{ $p->position ? $p->position . ' — ' : '' }}{{ $p->office }}</option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Proveedor / Contratista -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Proveedor / Contratista
                                </label>
                                <input type="text"
                                       wire:model="formSupplier"
                                       placeholder="Ej. Deltron, Compured, Lenovo Perú"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Vencimiento Garantía -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Vencimiento Garantía
                                </label>
                                <input type="date"
                                       wire:model="formWarrantyEndDate"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer">
                            </div>

                            <!-- Disponible para Préstamos -->
                            <div class="flex items-center gap-2 pt-5 md:col-span-2">
                                <input type="checkbox"
                                       id="isLoanableCheckModal"
                                       wire:model="formIsLoanable"
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 cursor-pointer">
                                <label for="isLoanableCheckModal" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                    Disponible para Préstamo / Cesión Temporal (RF-17)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 03: Especificaciones Técnicas Dinámicas -->
                    <div class="p-5 rounded-xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/20 dark:bg-emerald-950/10 space-y-4">
                        <div class="flex items-center justify-between border-b border-emerald-200/60 dark:border-emerald-900/40 pb-2 flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                    03
                                </span>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">
                                        Especificaciones Técnicas Dinámicas
                                    </h3>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                        Campos adaptados para la categoría <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $currentCategory?->name ?? 'Seleccionada' }}</span>
                                    </p>
                                </div>
                            </div>

                            <span class="text-[11px] px-2.5 py-1 rounded-full font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                                {{ count($currentSchema) }} Parámetros configurados
                            </span>
                        </div>

                        @if (empty($currentSchema))
                            <div class="p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-center">
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Esta categoría no tiene campos técnicos específicos configurados. Los activos usarán únicamente los datos generales.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($currentSchema as $field)
                                    @php
                                        $fieldKey = $field['key'] ?? '';
                                        $fieldType = $field['type'] ?? 'text';
                                        $fieldLabel = $field['label'] ?? ucfirst($fieldKey);
                                        $isRequired = $field['required'] ?? false;
                                        $placeholder = $field['placeholder'] ?? 'Ingrese ' . strtolower($fieldLabel);
                                        $options = $field['options'] ?? [];
                                    @endphp
                                    <div class="space-y-1.5 {{ $fieldType === 'textarea' ? 'md:col-span-2' : '' }}">
                                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                            <span>{{ $fieldLabel }}</span>
                                            @if ($isRequired)
                                                <span class="text-rose-500 font-bold" title="Campo obligatorio">*</span>
                                            @endif
                                        </label>

                                        @if ($fieldType === 'text')
                                            <input type="text"
                                                   wire:model="formSpecifications.{{ $fieldKey }}"
                                                   placeholder="{{ $placeholder }}"
                                                   class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none">
                                        @elseif ($fieldType === 'number')
                                            <input type="number"
                                                   wire:model="formSpecifications.{{ $fieldKey }}"
                                                   placeholder="{{ $placeholder }}"
                                                   class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none">
                                        @elseif ($fieldType === 'select')
                                            <select wire:model="formSpecifications.{{ $fieldKey }}"
                                                    class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none cursor-pointer">
                                                <option value="">-- Seleccione una opción --</option>
                                                @foreach ($options as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        @elseif ($fieldType === 'boolean')
                                            <label class="flex items-center gap-3 p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800/40 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                                <input type="checkbox"
                                                       wire:model="formSpecifications.{{ $fieldKey }}"
                                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">
                                                    Sí / Habilitado
                                                </span>
                                            </label>
                                        @elseif ($fieldType === 'textarea')
                                            <textarea rows="2"
                                                      wire:model="formSpecifications.{{ $fieldKey }}"
                                                      placeholder="{{ $placeholder }}"
                                                      class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none resize-none"></textarea>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Sección 04: Observaciones y Notas Adicionales -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5 flex-wrap gap-1">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Observaciones y Notas Adicionales
                            </label>
                            <button type="button"
                                    wire:click="markForVerification"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 dark:hover:bg-amber-900/80 border border-amber-300 dark:border-amber-700 transition-colors shadow-xs cursor-pointer"
                                    title="Inserta nota de advertencia y preselecciona catálogo genérico si aplica">
                                <span>⚠️</span>
                                <span>Marcar para Verificación Posterior</span>
                            </button>
                        </div>
                        <textarea rows="2"
                                  wire:model="formNotes"
                                  placeholder="Detalles sobre accesorios incluidos, estado del precinto de seguridad, etc."
                                  class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none"></textarea>
                    </div>

                    <!-- Pie del Modal -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <button type="button"
                                wire:click="$set('isAssetModalOpen', false)"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer">
                            <span>✓</span>
                            <span>{{ $editingAssetId ? 'Actualizar Activo' : 'Guardar y Generar Código Informático' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 5: REGISTRAR TRASLADO / REASIGNACIÓN (RF-09)                       -->
    <!-- ========================================================================= -->
    @if ($isMovementModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Encabezado Verde Oscuro con Icono Azul -->
                <div class="flex items-center justify-between px-6 py-4 bg-emerald-700 text-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center text-white shrink-0 shadow-xs">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">Registrar Movimiento / Reasignación</h2>
                            <p class="text-xs text-emerald-100">
                                @if ($selectedMovementAsset)
                                    {{ $selectedMovementAsset->computer_code }} — {{ $selectedMovementAsset->brand?->name }} {{ $selectedMovementAsset->model?->name }}
                                @else
                                    Seleccione un equipo tecnológico para continuar
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="closeMovementModal"
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none"
                            title="Cerrar">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveMovement" class="p-6 space-y-4">
                    @if ($errors->any())
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 01 Selector y Búsqueda de Activo -->
                    <div class="p-4 rounded-xl border border-emerald-300 dark:border-emerald-800/80 bg-emerald-50/20 dark:bg-emerald-950/10 space-y-2.5">
                        <label class="block text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider">
                            Activo Informático a Trasladar / Reasignar <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text"
                                   wire:model.live.debounce.250ms="movementAssetSearch"
                                   placeholder="Filtrar por código (MDC-TI-...), serie, marca, modelo o custodio..."
                                   class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" />
                        </div>
                        <select wire:model.live="movementAssetId"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                            <option value="">-- Seleccione un activo informático del inventario --</option>
                            @foreach ($this->availableMovementAssets as $ast)
                                <option value="{{ $ast->id }}">
                                    [{{ $ast->computer_code }}] {{ $ast->brand?->name }} {{ $ast->model?->name }} — {{ $ast->office ?: 'Almacén Central TI' }} ({{ $ast->assignedPerson ? $ast->assignedPerson->full_name : 'Sin Custodio' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tarjeta de Ubicación y Custodio Actual -->
                    @if ($selectedMovementAsset)
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/40 border border-slate-200/90 dark:border-slate-700 rounded-xl text-xs space-y-2">
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-slate-500 dark:text-slate-400 font-medium shrink-0">Ubicación Actual:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-right uppercase leading-snug">
                                    {{ $selectedMovementAsset->office ?: '[OCC] OFICINA DE CONTABILIDAD Y COSTOS - PALACIO MUNICIPAL (SEDE PRINCIPAL)' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500 dark:text-slate-400 font-medium shrink-0">Custodio Actual:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-right uppercase">
                                    {{ $selectedMovementAsset->assignedPerson ? $selectedMovementAsset->assignedPerson->full_name : 'DANI DANIEL VILLEGAS PURIZACA' }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-slate-50/70 dark:bg-slate-800/30 border border-slate-200/80 dark:border-slate-700/60 rounded-xl text-center py-4">
                            <p class="text-xs italic text-slate-400 dark:text-slate-500">
                                Seleccione un activo en el selector superior para cargar su ubicación y custodio actual.
                            </p>
                        </div>
                    @endif

                    <!-- Fila 1: Tipo de Movimiento & Oficina Destino -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tipo de Movimiento <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="movementType"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                                <option value="TRASLADO">Traslado entre Áreas (RF-09)</option>
                                <option value="ASIGNACION_INICIAL">Asignación Inicial (RF-08)</option>
                                <option value="RETORNO">Retorno a Oficina de TI / Almacén</option>
                                <option value="REASIGNACION">Reasignación de Custodio Interno</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Oficina / Dependencia Destino <span class="text-rose-500">*</span>
                            </label>
                            <input list="movement-dest-offices"
                                   type="text"
                                   placeholder="Ej. Gerencia de Rentas"
                                   wire:model="movementToOffice"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   required />
                            <datalist id="movement-dest-offices">
                                @foreach ($offices as $off)
                                    <option value="[{{ $off->acronym }}] {{ $off->name }} - {{ $off->sede }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <!-- Fila 2: Nuevo Responsable & Técnico -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    Nuevo Responsable / Custodio
                                </label>
                                <button type="button"
                                        wire:click="toggleManualCustodian"
                                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer">
                                    {{ $manualCustodian ? 'Seleccionar de Lista' : 'Escribir Manual' }}
                                </button>
                            </div>
                            @if ($manualCustodian)
                                <input type="text"
                                       placeholder="Nombre del funcionario receptor"
                                       wire:model="movementNewCustodian"
                                       class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" />
                            @else
                                <select wire:model="movementTargetPersonId"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                                    <option value="">-- Sin Custodio (Almacén TI / En Custodia) --</option>
                                    @foreach ($people as $p)
                                        <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->position ?: 'Personal' }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Técnico que Ejecuta el Traslado
                            </label>
                            <input type="text"
                                   placeholder="Técnico de Soporte TI"
                                   wire:model="movementTechnician"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                   required />
                        </div>
                    </div>

                    <!-- Fila 3: Motivo de la Transferencia / Justificación -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Motivo de la Transferencia / Justificación <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               placeholder="Ej. Reubicación de personal por modernización de oficina"
                               wire:model="movementReason"
                               class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                               required />
                    </div>

                    <!-- Periféricos vinculados en lote (RF-05) -->
                    @if (($selectedMovementAsset?->childAssets?->count() ?? 0) > 0)
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                            <label class="flex items-start space-x-2 cursor-pointer">
                                <input type="checkbox"
                                       wire:model="movementIncludeChildren"
                                       class="mt-0.5 rounded text-emerald-600 focus:ring-emerald-500 dark:bg-slate-800" />
                                <div class="text-xs text-emerald-900 dark:text-emerald-300">
                                    <span class="font-bold">Mover periféricos vinculados en lote ({{ $selectedMovementAsset->childAssets->count() }} componentes):</span>
                                    <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">
                                        Conforme a la regla RF-05, se actualizará automáticamente la oficina de los periféricos asociados (monitor, teclado, mouse, etc.).
                                    </p>
                                </div>
                            </label>
                        </div>
                    @endif

                    <!-- Fila 4: Observaciones Adicionales -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Observaciones Adicionales
                        </label>
                        <textarea rows="3"
                                  placeholder="Estado del equipo al momento del traslado, accesorios entregados..."
                                  wire:model="movementNotes"
                                  class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"></textarea>
                    </div>

                    <!-- Pie del Modal con Aviso de Acta y Botones -->
                    <div class="pt-2 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">
                            * Se generará automáticamente el Acta de Movimiento en PDF (RF-10)
                        </span>
                        <div class="flex space-x-2">
                            <button type="button"
                                    wire:click="closeMovementModal"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition cursor-pointer flex items-center gap-1.5">
                                <span wire:loading.remove wire:target="saveMovement">Confirmar Traslado & Acta</span>
                                <span wire:loading wire:target="saveMovement">Generando Acta...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
<!-- ========================================================================= -->
    <!-- MODAL 6: PROGRAMAR / REGISTRAR MANTENIMIENTO (RF-13, RF-16)              -->
    <!-- ========================================================================= -->
    @if ($isMaintenanceModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Encabezado Naranja / Ámbar con Icono de Herramientas -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-amber-600 to-orange-700 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🛠️</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">Programar / Registrar Mantenimiento</h2>
                            <p class="text-xs text-amber-100">
                                @if ($selectedMaintenanceAsset)
                                    {{ $selectedMaintenanceAsset->computer_code }} — {{ $selectedMaintenanceAsset->brand?->name }} {{ $selectedMaintenanceAsset->model?->name }} ({{ $selectedMaintenanceAsset->office ?: 'Almacén Central TI' }})
                                @else
                                    Seleccione un equipo tecnológico para continuar
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="closeMaintenanceModal"
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none"
                            title="Cerrar">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveMaintenance" class="p-6 space-y-4">
                    @if ($errors->any())
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Selector de Activo si no está predefinido -->
                    @if (! $selectedMaintenanceAsset)
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Activo Informático <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="maintenanceAssetId"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                    required>
                                <option value="">-- Seleccionar Equipo del Inventario --</option>
                                @foreach ($assets as $ast)
                                    <option value="{{ $ast->id }}">
                                        [{{ $ast->computer_code }}] {{ $ast->brand?->name }} {{ $ast->model?->name }} — {{ $ast->office ?: 'Almacén Central' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Fila 1: Tipo de Mantenimiento & Prioridad -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tipo de Mantenimiento <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="maintenanceType"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                                <option value="PREVENTIVO">Preventivo (Programado / Rutinario)</option>
                                <option value="CORRECTIVO">Correctivo (Falla Operativa / Reparación)</option>
                                <option value="DIAGNOSTICO">Inspección de Diagnóstico</option>
                                <option value="SOFTWARE">Mantenimiento de Software / SO</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Prioridad <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="maintenancePriority"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                                <option value="MEDIA">Media</option>
                                <option value="ALTA">Alta</option>
                                <option value="BAJA">Baja</option>
                                <option value="CRITICA">Urgente / Crítica</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 2: Fecha Programada & Técnico Responsable -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Fecha Programada <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   wire:model="maintenanceScheduledDate"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                   required />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Técnico Responsable
                            </label>
                            <input type="text"
                                   placeholder="Técnico de Soporte TI"
                                   wire:model="maintenanceTechnician"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                   required />
                        </div>
                    </div>

                    <!-- Fila Opcional: Falla Reportada (si es Correctivo) -->
                    @if ($maintenanceType === 'CORRECTIVO')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Falla Reportada / Síntomas <span class="text-rose-500">*</span>
                            </label>
                            <textarea rows="2"
                                      placeholder="Ej. Pantalla azul al iniciar Windows, ventilador ruidoso, no enciende..."
                                      wire:model="maintenanceFailureReported"
                                      class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                      required></textarea>
                        </div>
                    @endif

                    <!-- Fila 3: Notas / Instrucciones -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Notas / Instrucciones
                        </label>
                        <textarea rows="3"
                                  placeholder="Detalles sobre el acceso al área, requerimientos técnicos..."
                                  wire:model="maintenanceNotes"
                                  class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"></textarea>
                    </div>

                    <!-- Pie del Modal con Nota RF-16 y Botones -->
                    <div class="pt-3 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">
                            * Conforme al RF-16, se emitirá la Ficha Técnica / Hoja de Servicio Oficial.
                        </span>
                        <div class="flex space-x-2">
                            <button type="button"
                                    wire:click="closeMaintenanceModal"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow-sm transition cursor-pointer flex items-center gap-1.5">
                                <span wire:loading.remove wire:target="saveMaintenance">Guardar Orden</span>
                                <span wire:loading wire:target="saveMaintenance">Guardando...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
<!-- ========================================================================= -->
    <!-- MODAL 7: REGISTRAR / EDITAR INSUMO TECNOLÓGICO                            -->
    <!-- ========================================================================= -->
    @if ($isSupplyModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Header con degradado Cyan -> Blue idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-cyan-600 to-blue-700 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📦</span>
                        <div>
                            <h2 class="text-base font-bold">
                                {{ $supplyAdjustMode ? 'Ajuste de Stock en Almacén' : ($editingSupplyId ? 'Editar Insumo / Consumible' : 'Registrar Insumo Tecnológico') }}
                            </h2>
                            <p class="text-xs text-cyan-100">
                                {{ $editingSupplyId ? "{$formSupplyName} (Stock: {$formSupplyStock} {$formSupplyUnit})" : 'Inventario de Consumibles TI' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isSupplyModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveSupply" class="p-6 space-y-4">
                    @if ($editingSupplyId)
                        <div class="flex justify-end">
                            <button
                                type="button"
                                wire:click="$toggle('supplyAdjustMode')"
                                class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-300 hover:underline cursor-pointer"
                            >
                                {{ $supplyAdjustMode ? 'Volver a edición de datos' : '¿Registrar Entrada / Salida de Stock?' }}
                            </button>
                        </div>
                    @endif

                    @if ($supplyAdjustMode && $editingSupplyId)
                        <div class="space-y-4">
                            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Insumo:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $formSupplyName }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Stock Actual:</span>
                                    <span class="font-mono font-bold text-cyan-700 dark:text-cyan-400">{{ $formSupplyStock }} {{ $formSupplyUnit }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Variación de Cantidad (+ para ingreso, - para retiro o merma) <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    wire:model="supplyDelta"
                                    placeholder="Ej. +10 o -2"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Motivo del Ajuste <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="supplyAdjustReason"
                                    placeholder="Ej. Ingreso por Orden de Compra N° 124 / Merma por rotura"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>
                    @else
                        <!-- Nombre del Insumo / Descripción -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Nombre del Insumo / Descripción <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="formSupplyName"
                                placeholder="Ej. Tóner HP LaserJet 58A Negro / Bobina UTP Cat6 305m"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Categoría y Unidad -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Categoría de Insumo <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="formSupplyCategory"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                >
                                    <option value="TONER_TINTA">Tóner e Insumos de Impresión</option>
                                    <option value="CABLEADO_RED">Cableado y Tendido de Red (UTP / Fibra)</option>
                                    <option value="CONECTORES">Conectores RJ-45, Jacks y Patch Panels</option>
                                    <option value="PASTA_TERMICA">Pastas Térmicas y Disipadores</option>
                                    <option value="REPUESTOS_HARDWARE">Repuestos de Hardware (Fuentes, Discos)</option>
                                    <option value="HERRAMIENTAS">Herramientas de Soporte TI</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Unidad de Medida <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    wire:model="formSupplyUnit"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                >
                                    <option value="UNIDAD">UNIDAD</option>
                                    <option value="METROS">METROS</option>
                                    <option value="BOBINA">BOBINA</option>
                                    <option value="CAJA">CAJA</option>
                                    <option value="TUBO">TUBO</option>
                                    <option value="PAQUETE">PAQUETE</option>
                                </select>
                            </div>
                        </div>

                        <!-- Stock Inicial y Umbral Crítico -->
                        <div class="grid grid-cols-2 gap-4">
                            @if (!$editingSupplyId)
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Stock Inicial <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        min="0"
                                        wire:model="formSupplyStock"
                                        class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                        required
                                    />
                                </div>
                            @else
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        Stock Actual
                                    </label>
                                    <input
                                        type="number"
                                        value="{{ $formSupplyStock }}"
                                        disabled
                                        class="w-full px-3 py-2 text-xs border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-slate-500 rounded-lg cursor-not-allowed"
                                    />
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Umbral Crítico de Alerta (Min Stock) <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    min="1"
                                    wire:model="formSupplyMinStock"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                                    required
                                />
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">SRS: Alerta visual si stock es ≤ 2 unidades.</p>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Ubicación en Almacén / Estante
                            </label>
                            <input
                                type="text"
                                wire:model="formSupplyLocation"
                                placeholder="Almacén ODT - Estante 1"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                            />
                        </div>

                        <!-- Notas / Modelos Compatibles -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Notas / Modelos Compatibles
                            </label>
                            <textarea
                                rows="2"
                                wire:model="formSupplyNotes"
                                placeholder="Ej. Compatible con impresoras HP LaserJet M404dw y M428fdw..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-cyan-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>
                    @endif

                    <!-- Botones Cancelar / Registrar Insumo -->
                    <div class="pt-3 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="$set('isSupplyModalOpen', false)"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                        >
                            {{ $supplyAdjustMode ? 'Confirmar Ajuste' : ($editingSupplyId ? 'Guardar Cambios' : 'Registrar Insumo') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 8: REGISTRAR PRÉSTAMO / RESERVA TEMPORAL DE ACTIVOS                 -->
    <!-- ========================================================================= -->
    @if ($isLoanModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Header con degradado Púrpura -> Índigo idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-purple-700 to-indigo-800 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">{{ $editingLoanId ? '📥' : '📅' }}</span>
                        <div>
                            <h2 class="text-base font-bold">
                                {{ $editingLoanId ? "Devolución y Retorno de Préstamo ({$editingLoanNumber})" : 'Registrar Préstamo / Reserva Temporal de Activos' }}
                            </h2>
                            <p class="text-xs text-purple-200">
                                {{ $editingLoanId ? 'Validación de estado físico al retorno (RF-20)' : 'Gestión de Cañones, Laptops de Contingencia y Accesorios (RF-17, RF-18)' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isLoanModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveLoan" class="p-6 space-y-4">
                    @if ($editingLoanId)
                        <!-- Modo Retorno -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Estado Físico al Retorno <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="loanReturnCondition" class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    <option value="BUENO">BUENO - Operativo y sin daños</option>
                                    <option value="REGULAR">REGULAR - Desgaste leve de uso</option>
                                    <option value="MALO">MALO - Daño cosmético / requiere revisión</option>
                                    <option value="INOPERATIVO">INOPERATIVO - Falla técnica / derivar a soporte</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Observaciones de Recepción / Check de Accesorios
                                </label>
                                <textarea
                                    rows="2"
                                    wire:model="loanReturnNotes"
                                    placeholder="Verificación de cargadores, cables HDMI, puntero, estuche..."
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"
                                ></textarea>
                            </div>
                        </div>
                    @else
                        <!-- Modo Registrar Préstamo -->
                        <!-- Seleccionar Activo(s) para Préstamo -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Seleccionar Activo(s) para Préstamo <span class="text-rose-500">*</span>
                            </label>
                            @if ($loanableAssets->isEmpty())
                                <div class="p-3 text-xs text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-xl leading-relaxed">
                                    No hay activos marcados como disponibles para préstamo. En el catálogo de activos marque la casilla "Disponible para préstamo" en proyectores o laptops.
                                </div>
                            @else
                                <div class="max-h-36 overflow-y-auto border border-slate-200 dark:border-slate-700 rounded-xl divide-y divide-slate-100 dark:divide-slate-800 p-1">
                                    @foreach ($loanableAssets as $ast)
                                        @php $isSelected = in_array($ast->id, $loanSelectedAssetIds, true); @endphp
                                        <div
                                            wire:click="toggleLoanAsset({{ $ast->id }})"
                                            class="flex items-center justify-between p-2 rounded-lg cursor-pointer text-xs transition {{ $isSelected ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-900 dark:text-purple-200 font-semibold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-700 dark:text-slate-300' }}"
                                        >
                                            <div class="flex items-center space-x-2">
                                                <input
                                                    type="checkbox"
                                                    @checked($isSelected)
                                                    class="rounded text-purple-600 focus:ring-purple-500 pointer-events-none"
                                                />
                                                <span class="font-mono text-purple-700 dark:text-purple-400 font-bold">{{ $ast->computer_code }}</span>
                                                <span>{{ $ast->category?->name }} - {{ $ast->brand?->name }} {{ $ast->model?->name }}</span>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] rounded {{ $ast->status === 'OPERATIVO' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                                                {{ $ast->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @error('loanSelectedAssetIds')
                                <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Dependencia y Funcionario -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Dependencia Solicitante <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="loanDepartment"
                                    placeholder="Ej. Gerencia de Rentas"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Funcionario / Solicitante <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="loanBorrowerName"
                                    placeholder="Nombre del responsable"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Fecha de Inicio / Salida <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    wire:model="loanStartDate"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Fecha Estimada de Devolución <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    wire:model="loanExpectedReturnDate"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Motivo del Evento -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Motivo del Evento / Reunión / Capacitación <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="loanReason"
                                placeholder="Ej. Capacitación sobre nuevo TUPA en auditorio municipal"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Observaciones / Accesorios Entregados -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Observaciones / Accesorios Entregados
                            </label>
                            <textarea
                                rows="2"
                                wire:model="loanNotes"
                                placeholder="Incluye puntero láser, cable HDMI de 5m, estuche..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>
                    @endif

                    <!-- Footer -->
                    <div class="pt-3 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">
                            * Se generará el Acta de Préstamo y Salida en PDF (RF-20).
                        </span>
                        <div class="flex space-x-2">
                            <button
                                type="button"
                                wire:click="$set('isLoanModalOpen', false)"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                            >
                                {{ $editingLoanId ? 'Confirmar Retorno & Acta' : 'Generar Préstamo & Acta' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 9: MARCA / MODELO CRUD (SRS RF-01)                                 -->
    <!-- ========================================================================= -->
    @if ($isBrandModelModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header Institucional -->
                <div class="px-6 py-4 border-b border-emerald-800 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-100 flex items-center justify-center font-bold text-lg shadow-inner">
                            {{ $catalogType === 'brand' ? '🏷️' : '📦' }}
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">
                                @if ($catalogType === 'brand')
                                    {{ $editingBrandId ? 'Editar Marca' : 'Registrar Nueva Marca' }}
                                @else
                                    {{ $editingModelId ? 'Editar Modelo' : 'Registrar Nuevo Modelo' }}
                                @endif
                            </h2>
                            <p class="text-xs text-emerald-200/90">
                                {{ ($editingBrandId || $editingModelId) ? 'Modificación en catálogo institucional' : 'Catálogos de Hardware y Software TI' }}
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="$set('isBrandModelModalOpen', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-emerald-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveBrandOrModel">
                    <div class="p-6 space-y-4">
                        @if ($errors->any())
                            <div class="p-3 text-xs font-semibold text-rose-700 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 dark:border-rose-900/50">
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($catalogType === 'model')
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Marca Fabricante *
                                </label>
                                <select wire:model="formModelBrandId"
                                        required
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                    @foreach ($allBrands as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Categoría Habitual (Opcional)
                                </label>
                                <select wire:model="formModelCategoryId"
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                                    <option value="">-- General / Múltiples Categorías --</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}">{{ $c->icon }} {{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Nombre del Modelo / Línea *
                                </label>
                                <input type="text"
                                       required
                                       placeholder="Ej. Latitude 5420, LaserJet Pro"
                                       wire:model="formModelName"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Descripción / Notas (Opcional)
                                </label>
                                <textarea rows="2"
                                          placeholder="Detalles sobre serie o características..."
                                          wire:model="formModelDescription"
                                          class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                            </div>

                            @if ($editingModelId)
                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox"
                                           id="isModelActiveCheck"
                                           wire:model="formModelIsActive"
                                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700" />
                                    <label for="isModelActiveCheck"
                                           class="text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                        Estado Activo (disponible para asignación a activos)
                                    </label>
                                </div>
                            @endif
                        @else
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Nombre de la Marca *
                                </label>
                                <input type="text"
                                       required
                                       placeholder="Ej. Asus, Cisco, Canon"
                                       wire:model="formBrandName"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Descripción / Notas (Opcional)
                                </label>
                                <textarea rows="2"
                                          placeholder="Detalles sobre serie o características..."
                                          wire:model="formBrandDescription"
                                          class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"></textarea>
                            </div>

                            @if ($editingBrandId)
                                <div class="flex items-center gap-2 pt-1">
                                    <input type="checkbox"
                                           id="isBrandActiveCheck"
                                           wire:model="formBrandIsActive"
                                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700" />
                                    <label for="isBrandActiveCheck"
                                           class="text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                        Estado Activo (disponible para asignación a activos)
                                    </label>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                        <button type="button"
                                wire:click="$set('isBrandModelModalOpen', false)"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="submit"
                                class="px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer">
                            @if ($catalogType === 'brand')
                                {{ $editingBrandId ? 'Actualizar Marca' : 'Guardar Marca' }}
                            @else
                                {{ $editingModelId ? 'Actualizar Modelo' : 'Guardar Modelo' }}
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
<!-- ========================================================================= -->
    <!-- MODAL 10: DOCUMENTACIÓN OFICIAL DIGITAL (ACTA OFICIAL RF-10, RF-16, RF-20) -->
    <!-- ========================================================================= -->
    @if ($isActaModalOpen)
        @php
            $effAsset = $actaAsset;
            $meses = [
                1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
                5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
                9 => 'setiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
            ];
            $todayFormatted = date('j') . ' de ' . ($meses[(int) date('n')] ?? date('F')) . ' de ' . date('Y');

            $docTitle = match ($actaType) {
                'ASIGNACION' => 'ACTA DE ASIGNACIÓN Y ENTREGA DE EQUIPO TI',
                'TRANSFERENCIA' => 'FICHA DE MOVIMIENTO Y TRANSFERENCIA ENTRE DEPENDENCIAS',
                'MANTENIMIENTO' => 'HOJA DE SERVICIO E INFORME TÉCNICO DE MANTENIMIENTO',
                'PRESTAMO' => 'ACTA DE SALIDA Y RETORNO DE PRÉSTAMO TEMPORAL',
                default => 'ACTA OFICIAL DE ACTIVO TI',
            };

            $docCode = $actaMovement?->acta_number
                ?? ($actaMaintenance?->order_number
                ?? ($actaLoan?->loan_number
                ?? 'MDC-TI-DOC-' . date('Y') . '-0001'));

            $operationDate = $todayFormatted;
            if ($actaMovement?->movement_date) {
                $mCarbon = \Carbon\Carbon::parse($actaMovement->movement_date);
                $operationDate = $mCarbon->day . ' de ' . ($meses[$mCarbon->month] ?? $mCarbon->format('F')) . ' de ' . $mCarbon->year;
            } elseif ($actaMaintenance?->scheduled_date) {
                $mCarbon = \Carbon\Carbon::parse($actaMaintenance->scheduled_date);
                $operationDate = $mCarbon->day . ' de ' . ($meses[$mCarbon->month] ?? $mCarbon->format('F')) . ' de ' . $mCarbon->year;
            } elseif ($actaLoan?->loan_date) {
                $mCarbon = \Carbon\Carbon::parse($actaLoan->loan_date);
                $operationDate = $mCarbon->day . ' de ' . ($meses[$mCarbon->month] ?? $mCarbon->format('F')) . ' de ' . $mCarbon->year;
            }

            $technicianName = $actaMovement?->technician_name
                ?? ($actaMaintenance?->assigned_technician_name
                ?? 'TÉCNICO DE SOPORTE TI');

            $custodianName = $actaMovement?->new_custodian_name
                ?? ($actaLoan?->requesting_name
                ?? ($effAsset?->assignedPerson ? trim($effAsset->assignedPerson->first_name . ' ' . $effAsset->assignedPerson->last_name) : 'CARLOS ENRIQUE MENDOZA CHUNGA'));

            $specs = is_array($effAsset?->specifications)
                ? $effAsset->specifications
                : (json_decode($effAsset?->specifications ?? '[]', true) ?: []);
            $categorySchema = $effAsset?->category?->custom_fields_schema ?? [];
            $orderedSpecs = [];
            $handledKeys = [];

            if (is_array($categorySchema)) {
                foreach ($categorySchema as $f) {
                    $fKey = $f['key'] ?? '';
                    if (! $fKey) continue;

                    $matchedKey = null;
                    if (array_key_exists($fKey, $specs)) {
                        $matchedKey = $fKey;
                    } else {
                        foreach (array_keys($specs) as $k) {
                            if (strtolower((string) $k) === strtolower((string) $fKey)) {
                                $matchedKey = $k;
                                break;
                            }
                        }
                    }

                    if ($matchedKey !== null) {
                        $handledKeys[] = $matchedKey;
                        $orderedSpecs[] = [
                            'label' => $f['label'] ?? ucfirst(str_replace('_', ' ', $fKey)),
                            'unit' => $f['unit'] ?? null,
                            'val' => $specs[$matchedKey],
                        ];
                    }
                }
            }

            foreach ($specs as $k => $v) {
                if (! in_array($k, $handledKeys, true)) {
                    $orderedSpecs[] = [
                        'label' => ucwords(str_replace('_', ' ', (string) $k)),
                        'unit' => null,
                        'val' => $v,
                    ];
                }
            }
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs animate-fadeIn print:p-0 print:bg-white print:static">
            <div class="relative w-full max-w-4xl max-h-[92vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-scaleUp print:shadow-none print:border-none print:w-full print:max-w-none print:max-h-none print:overflow-visible">
                <!-- Actions Bar (Hidden in Print) -->
                <div class="flex items-center justify-between px-6 py-4 bg-slate-900 text-white shrink-0 print:hidden">
                    <div class="flex items-center space-x-3">
                        <span class="text-xl">📄</span>
                        <div>
                            <h2 class="text-sm font-semibold tracking-wide uppercase text-indigo-400">
                                DOCUMENTACIÓN OFICIAL DIGITAL
                            </h2>
                            <p class="text-xs text-slate-300">
                                {{ $docTitle }} &mdash; {{ $docCode }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button"
                                onclick="window.print()"
                                class="inline-flex items-center space-x-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow transition-all cursor-pointer">
                            <span>🖨️</span>
                            <span>Imprimir / Guardar PDF</span>
                        </button>
                        <button type="button"
                                wire:click="$set('isActaModalOpen', false)"
                                class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs transition cursor-pointer">
                            Cerrar
                        </button>
                    </div>
                </div>

                <!-- Printable Paper Sheet -->
                <div class="flex-1 overflow-y-auto p-8 sm:p-12 text-slate-800 text-sm leading-relaxed bg-white print:overflow-visible print:p-0" id="printable-acta">
                    <!-- Header -->
                    <div class="border-b-2 border-slate-900 pb-4 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-bold tracking-widest text-slate-500 uppercase">
                                    REPÚBLICA DEL PERÚ
                                </p>
                                <h1 class="text-lg font-black text-slate-900 uppercase tracking-tight">
                                    MUNICIPALIDAD DISTRITAL DE CASTILLA
                                </h1>
                                <p class="text-xs font-semibold text-indigo-800 uppercase tracking-wide">
                                    OFICINA DE TECNOLOGÍAS DE LA INFORMACIÓN Y SISTEMAS
                                </p>
                                <p class="text-[11px] text-slate-500">
                                    Plaza Luis Montero S/N - Castilla, Piura | Teléf: (073) 345678
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="border-2 border-indigo-700 rounded-lg px-4 py-2 bg-indigo-50/50 text-center">
                                    <p class="text-[10px] font-bold text-indigo-900 uppercase">DOCUMENTO OFICIAL</p>
                                    <p class="text-sm font-mono font-bold text-slate-900">{{ $docCode }}</p>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Fecha de Emisión: {{ $todayFormatted }}</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <h2 class="text-base font-bold text-slate-900 tracking-wide uppercase underline decoration-indigo-600 decoration-2 underline-offset-4">
                                {{ $docTitle }}
                            </h2>
                        </div>
                    </div>

                    <!-- 1. Datos Generales de la Operación -->
                    <div class="mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                        <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">1. DATOS GENERALES DE LA OPERACIÓN</h3>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="text-slate-500">Tipo de Trámite:</p>
                                <p class="font-semibold text-slate-900">{{ $actaType }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500">Fecha de Registro:</p>
                                <p class="font-semibold text-slate-900">{{ $operationDate }}</p>
                            </div>
                            @if ($actaMovement)
                                <div>
                                    <p class="text-slate-500">Dependencia de Origen:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->from_office ?: 'Almacén Central TI' }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Dependencia de Destino:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->to_office }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Motivo del Traslado:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->reason }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Técnico Informático:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMovement->technician_name }}</p>
                                </div>
                            @endif
                            @if ($actaMaintenance)
                                <div>
                                    <p class="text-slate-500">Tipo de Mantenimiento:</p>
                                    <p class="font-semibold text-slate-900 uppercase">{{ $actaMaintenance->type }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Estado de Orden:</p>
                                    <p class="font-semibold text-slate-900 uppercase">{{ $actaMaintenance->status }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Técnico Asignado:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMaintenance->assigned_technician_name }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Fecha Programada:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaMaintenance->scheduled_date }}</p>
                                </div>
                            @endif
                            @if ($actaLoan)
                                <div>
                                    <p class="text-slate-500">Beneficiario / Solicitante:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->requesting_name }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Dependencia Solicitante:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->department }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Fecha Inicio Préstamo:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->loan_date }}</p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Fecha Estimada Devolución:</p>
                                    <p class="font-semibold text-slate-900">{{ $actaLoan->expected_return_date }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Especificaciones Técnicas del Activo Principal -->
                    @if ($effAsset)
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">2. ESPECIFICACIONES TÉCNICAS DEL ACTIVO PRINCIPAL</h3>
                            <table class="w-full border-collapse border border-slate-300 text-xs">
                                <tbody>
                                    <tr>
                                        <td class="border border-slate-300 p-2 font-semibold w-1/4">Código Informático</td>
                                        <td class="border border-slate-300 p-2 font-mono font-bold text-indigo-700 w-1/4">{{ $effAsset->computer_code }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold w-1/4">Código Patrimonial SBN</td>
                                        <td class="border border-slate-300 p-2 font-mono w-1/4">{{ $effAsset->patrimonial_code ?: 'No registrado' }}</td>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <td class="border border-slate-300 p-2 font-semibold">Categoría</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->category?->name ?? 'Equipo TI' }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold">Número de Serie</td>
                                        <td class="border border-slate-300 p-2 font-mono">{{ $effAsset->serial_number ?: 'S/N' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border border-slate-300 p-2 font-semibold">Marca / Modelo</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->brand?->name }} {{ $effAsset->model?->name }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold">Estado Operativo / Condición</td>
                                        <td class="border border-slate-300 p-2">
                                            <span class="font-bold">{{ $effAsset->status }}</span> ({{ $effAsset->physical_condition ?? 'BUENO' }})
                                        </td>
                                    </tr>
                                    <tr class="bg-slate-50">
                                        <td class="border border-slate-300 p-2 font-semibold">Ubicación / Oficina</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->office ?: 'Oficina TI' }}</td>
                                        <td class="border border-slate-300 p-2 font-semibold">Custodio / Responsable</td>
                                        <td class="border border-slate-300 p-2">{{ $effAsset->assignedPerson ? trim($effAsset->assignedPerson->first_name . ' ' . $effAsset->assignedPerson->last_name) : 'Sin asignar' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @if (count($orderedSpecs) > 0)
                                <div class="mt-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-[11px] font-bold text-slate-700 uppercase">
                                            COMPONENTES TÉCNICOS INTERNOS ({{ strtoupper($effAsset->category?->name ?? 'EQUIPO TI') }}):
                                        </p>
                                        <span class="text-[10px] text-slate-500 font-mono">
                                            {{ count($orderedSpecs) }} especificaciones
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-[11px]">
                                        @foreach ($orderedSpecs as $spec)
                                            @php
                                                $isArray = is_array($spec['val']);
                                                $isBoolean = is_bool($spec['val']);
                                                $displayVal = $isBoolean ? ($spec['val'] ? 'Sí' : 'No') : (string) ($spec['val'] ?? '');
                                                if ($spec['unit'] && $displayVal && ! $isArray && ! str_contains(strtolower($displayVal), strtolower($spec['unit']))) {
                                                    $displayVal .= ' ' . $spec['unit'];
                                                }
                                            @endphp
                                            <div class="p-2 rounded-lg bg-white border border-slate-200 flex flex-col justify-start {{ $isArray ? 'col-span-2 md:col-span-3' : '' }}">
                                                <span class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">
                                                    {{ $spec['label'] }} {{ ($spec['unit'] && (! $displayVal || $isArray)) ? "({$spec['unit']})" : '' }}
                                                </span>
                                                @if ($isArray)
                                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                                        @forelse ($spec['val'] as $item)
                                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-300 text-[10px] font-semibold">
                                                                {{ $item }}
                                                            </span>
                                                        @empty
                                                            <span class="text-slate-400 italic text-[10px]">Ninguno</span>
                                                        @endforelse
                                                    </div>
                                                @else
                                                    <span class="font-semibold text-slate-800 mt-0.5">
                                                        {{ $displayVal ?: '-' }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- 3. Subcomponentes y Periféricos Vinculados -->
                    @if ($effAsset && $effAsset->childAssets && $effAsset->childAssets->isNotEmpty())
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">
                                3. SUBCOMPONENTES Y PERIFÉRICOS VINCULADOS (RELACIÓN JERÁRQUICA RF-05)
                            </h3>
                            <table class="w-full border-collapse border border-slate-300 text-xs">
                                <thead>
                                    <tr class="bg-slate-100 text-left">
                                        <th class="border border-slate-300 p-2">Código Informático</th>
                                        <th class="border border-slate-300 p-2">Categoría</th>
                                        <th class="border border-slate-300 p-2">Marca / Modelo</th>
                                        <th class="border border-slate-300 p-2">N° Serie</th>
                                        <th class="border border-slate-300 p-2">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($effAsset->childAssets as $ch)
                                        <tr>
                                            <td class="border border-slate-300 p-2 font-mono font-bold text-indigo-700">{{ $ch->computer_code }}</td>
                                            <td class="border border-slate-300 p-2">{{ $ch->category?->name ?? 'Periférico' }}</td>
                                            <td class="border border-slate-300 p-2">{{ $ch->brand?->name }} {{ $ch->model?->name }}</td>
                                            <td class="border border-slate-300 p-2 font-mono">{{ $ch->serial_number ?: 'S/N' }}</td>
                                            <td class="border border-slate-300 p-2 font-semibold">{{ $ch->status }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- 4. Acciones Técnicas e Insumos Utilizados (Mantenimiento) -->
                    @if ($actaMaintenance)
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-900 uppercase mb-2">4. ACCIONES TÉCNICAS E INSUMOS UTILIZADOS</h3>
                            <div class="p-3 bg-slate-50 border border-slate-300 rounded-lg mb-3 text-xs space-y-2">
                                <div>
                                    <p class="font-semibold text-slate-900">Diagnóstico:</p>
                                    <p class="text-slate-700">{{ $actaMaintenance->diagnosis ?: 'Revisión y diagnóstico preventivo rutinario.' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">Trabajo Realizado:</p>
                                    <p class="text-slate-700">{{ $actaMaintenance->activities_performed ?: 'Limpieza de componentes, optimización de software y test operativo.' }}</p>
                                </div>
                            </div>

                            @if ($actaMaintenance->suppliesUsed && $actaMaintenance->suppliesUsed->isNotEmpty())
                                <div>
                                    <p class="text-[11px] font-bold text-slate-700 uppercase mb-1">Insumos y Repuestos Descargados de Stock (RF-15):</p>
                                    <table class="w-full border-collapse border border-slate-300 text-xs">
                                        <thead>
                                            <tr class="bg-slate-100 text-left">
                                                <th class="border border-slate-300 p-2">Insumo</th>
                                                <th class="border border-slate-300 p-2">Categoría</th>
                                                <th class="border border-slate-300 p-2">Cantidad Utilizada</th>
                                                <th class="border border-slate-300 p-2">Unidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($actaMaintenance->suppliesUsed as $su)
                                                <tr>
                                                    <td class="border border-slate-300 p-2 font-semibold">{{ $su->supply?->name }}</td>
                                                    <td class="border border-slate-300 p-2">{{ $su->supply?->category }}</td>
                                                    <td class="border border-slate-300 p-2 font-bold text-indigo-700">{{ $su->quantity_used }}</td>
                                                    <td class="border border-slate-300 p-2">{{ $su->supply?->unit }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Declaración de Conformidad y Firmas -->
                    <div class="mt-8 border-t border-slate-300 pt-4">
                        <p class="text-[11px] text-slate-600 text-justify mb-8 leading-relaxed">
                            <strong>DECLARACIÓN DE CONFORMIDAD:</strong> Mediante el presente documento, las partes dan fe de la veracidad de la información técnica detallada, así como del estado de operatividad de los equipos entregados o intervenidos. El custodio asume la responsabilidad directa de su custodia y uso exclusivo para las funciones encomendadas en la Municipalidad Distrital de Castilla, conforme a las directivas internas de control patrimonial y seguridad de la información.
                        </p>

                        <div class="grid grid-cols-2 gap-12 pt-12">
                            <div class="text-center border-t border-slate-900 pt-2">
                                <p class="font-bold text-xs text-slate-900 uppercase">
                                    {{ $technicianName }}
                                </p>
                                <p class="text-[11px] text-slate-500">Subgerencia de Informática y Sistemas</p>
                                <p class="text-[10px] text-slate-400">Entrega Conforme</p>
                            </div>
                            <div class="text-center border-t border-slate-900 pt-2">
                                <p class="font-bold text-xs text-slate-900 uppercase">
                                    {{ $custodianName }}
                                </p>
                                <p class="text-[11px] text-slate-500">Dependencia Usuaria / Receptora</p>
                                <p class="text-[10px] text-slate-400">Recepción y Conformidad</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-4 border-t border-slate-200 text-center text-[10px] text-slate-400 flex justify-between items-center">
                        <span>SIGM - Sistema Integrado de Gestión Municipal | Municipalidad Distrital de Castilla</span>
                        <span class="font-mono">Página 1 de 1</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 11: APERTURA DE INVENTARIO FÍSICO ANUAL (SRS RF-11)                 -->
    <!-- ========================================================================= -->
    @if ($isAuditModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp flex flex-col">
                <!-- Header con degradado Slate-900 -> Indigo-950 -> Blue-900 idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📋</span>
                        <div>
                            <h2 class="text-base font-bold">
                                Apertura de Inventario Físico Anual (RF-11)
                            </h2>
                            <p class="text-xs text-indigo-200">
                                Generación de fotografía (snapshot) del inventario por año fiscal
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isAuditModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <form wire:submit="saveAudit" class="space-y-4 max-w-2xl mx-auto py-2">
                        <!-- Requisito RF-11 Callout -->
                        <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 rounded-xl text-xs text-blue-900 dark:text-blue-200 leading-relaxed">
                            <strong>Requisito RF-11 (Cierres y Aperturas de Inventario Anual):</strong> Al crear la auditoría se congelará una fotografía (snapshot) de todos los activos, sus ubicaciones actuales y custodios para contrastar contra el levantamiento en campo.
                        </div>

                        <!-- Año Fiscal -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Año Fiscal de Inventario <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="formAuditYear"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Título / Denominación -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Título / Denominación de la Auditoría <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="formAuditTitle"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Notas / Base Legal -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Notas / Base Legal / Resolución de Inicio
                            </label>
                            <textarea
                                rows="2"
                                wire:model="formAuditNotes"
                                placeholder="Ej. Conforme a Resolución de Alcaldía N° 045-2026-MDC/A..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>

                        <!-- Botones de Acción del Formulario -->
                        <div class="flex justify-end space-x-2 pt-2">
                            <button
                                type="button"
                                wire:click="$set('isAuditModalOpen', false)"
                                class="px-4 py-2 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                            >
                                Tomar Snapshot & Aperturar Inventario
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer del Modal -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end flex-shrink-0">
                    <button
                        type="button"
                        wire:click="$set('isAuditModalOpen', false)"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium rounded-lg transition cursor-pointer"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL: AUDITORÍA & CONCILIACIÓN FÍSICA EN CAMPO (SRS RF-11, RF-12)        -->
    <!-- ========================================================================= -->
    @if ($isAuditReconciliationModalOpen && $selectedAuditForModal)
        @php
            $verifs = $selectedAuditForModal->verifications ?? collect();
            $totalExpected = $verifs->count();
            $conciliados = $verifs->where('status', 'CONCILIADO')->count();
            $traslados = $verifs->where('status', 'TRASLADADO_NO_AUTORIZADO')->count();
            $noHabidos = $verifs->whereIn('status', ['NO_HABIDO', 'FALTANTE'])->count();
            $verifiedTotal = $conciliados + $traslados;
            $progressPct = $totalExpected > 0 ? (int) round(($verifiedTotal / $totalExpected) * 100) : 0;

            $filteredVerifs = $verifs->filter(function ($v) use ($auditFilterStatus) {
                if ($auditFilterStatus === 'ALL') return true;
                if ($auditFilterStatus === 'PENDIENTE') return $v->status === 'PENDIENTE';
                if ($auditFilterStatus === 'CONCILIADO') return $v->status === 'CONCILIADO';
                if ($auditFilterStatus === 'TRASLADADO_NO_AUTORIZADO') return $v->status === 'TRASLADADO_NO_AUTORIZADO';
                if ($auditFilterStatus === 'NO_HABIDO') return in_array($v->status, ['NO_HABIDO', 'FALTANTE']);
                return true;
            });
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden max-h-[92vh] flex flex-col animate-scaleUp">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 bg-slate-900 text-white flex-shrink-0 border-b border-slate-800">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📋</span>
                        <div>
                            <h2 class="text-base font-bold text-white">
                                Auditoría & Conciliación Física: {{ $selectedAuditForModal->title }}
                            </h2>
                            <p class="text-xs text-slate-300">
                                Año Fiscal {{ $selectedAuditForModal->year ?? $selectedAuditForModal->fiscal_year ?? 2026 }} — Estado: {{ $selectedAuditForModal->status }}
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isAuditReconciliationModalOpen', false)" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto space-y-5 flex-1">
                    <!-- 5 KPI Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                        <div class="bg-slate-50 border border-slate-200 dark:bg-slate-800/60 dark:border-slate-700 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">TOTAL ESPERADOS</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalExpected }}</p>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/30 dark:border-emerald-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">CONCILIADOS</p>
                            <p class="text-2xl font-black text-emerald-700 dark:text-emerald-300 mt-1">{{ $conciliados }}</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 dark:bg-amber-950/30 dark:border-amber-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider">TRASLADOS NO AUT.</p>
                            <p class="text-2xl font-black text-amber-700 dark:text-amber-300 mt-1">{{ $traslados }}</p>
                        </div>
                        <div class="bg-rose-50 border border-rose-200 dark:bg-rose-950/30 dark:border-rose-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-rose-700 dark:text-rose-300 uppercase tracking-wider">NO HABIDOS / FALT.</p>
                            <p class="text-2xl font-black text-rose-700 dark:text-rose-300 mt-1">{{ $noHabidos }}</p>
                        </div>
                        <div class="bg-indigo-50 border border-indigo-200 dark:bg-indigo-950/30 dark:border-indigo-800 p-3 rounded-xl text-center">
                            <p class="text-[10px] font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider">PROGRESO FÍSICO</p>
                            <p class="text-2xl font-black text-indigo-700 dark:text-indigo-300 mt-1">{{ $progressPct }}%</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Avance de Verificación Física en Campo</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $progressPct }}% completado</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-teal-500 to-indigo-600 rounded-full transition-all duration-500"
                                 style="width: {{ $progressPct }}%"></div>
                        </div>
                    </div>

                    <!-- Quick Scanner Box (Tablet / QR / Barcode) -->
                    @if ($selectedAuditForModal->status === 'EN_PROCESO')
                        <div class="p-4 bg-slate-900 text-white rounded-2xl shadow-inner space-y-3 border border-slate-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-base">📱</span>
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">
                                        LEVANTAMIENTO RÁPIDO EN CAMPO (TABLET / LECTOR DE CÓDIGO DE BARRAS / QR)
                                    </h4>
                                </div>
                                <span class="text-[11px] text-slate-400">RNF-04 Optimizado para tablets</span>
                            </div>

                            <form wire:submit.prevent="registerAuditVerification" class="grid grid-cols-1 sm:grid-cols-4 gap-2.5 items-center">
                                <input
                                    type="text"
                                    placeholder="Escanear Código (MDC-TI-PC-0001)"
                                    wire:model="auditScanCode"
                                    class="px-3 py-2 text-xs bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-indigo-400 font-mono focus:outline-none"
                                />
                                <input
                                    type="text"
                                    placeholder="Oficina donde fue hallado..."
                                    wire:model="auditFoundOffice"
                                    class="px-3 py-2 text-xs bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                />
                                <select
                                    wire:model="auditVerificationStatus"
                                    class="px-3 py-2 text-xs bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                >
                                    <option value="CONCILIADO">Hallado en su Oficina (Conciliado)</option>
                                    <option value="TRASLADADO_NO_AUTORIZADO">Trasladado sin Autorización</option>
                                    <option value="NO_HABIDO">No Habido / Faltante</option>
                                </select>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition cursor-pointer text-center"
                                >
                                    Registrar Conciliación
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Detail of Assets and Filter -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                DETALLE DE ACTIVOS DEL SNAPSHOT ({{ $filteredVerifs->count() }})
                            </h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-slate-500">Filtrar:</span>
                                <select
                                    wire:model.live="auditFilterStatus"
                                    class="text-xs border border-slate-300 dark:border-slate-700 rounded-lg px-2.5 py-1 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none"
                                >
                                    <option value="ALL">Todos los Registros</option>
                                    <option value="PENDIENTE">Pendientes de Verificación</option>
                                    <option value="CONCILIADO">Conciliados (Conformes)</option>
                                    <option value="TRASLADADO_NO_AUTORIZADO">Traslados No Autorizados</option>
                                    <option value="NO_HABIDO">No Habidos / Faltantes</option>
                                </select>
                            </div>
                        </div>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden max-h-72 overflow-y-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 sticky top-0 font-semibold border-b border-slate-200 dark:border-slate-700">
                                    <tr>
                                        <th class="p-2.5">Código Informático</th>
                                        <th class="p-2.5">Patrimonial</th>
                                        <th class="p-2.5">Equipo / Modelo</th>
                                        <th class="p-2.5">Oficina Esperada</th>
                                        <th class="p-2.5">Oficina Hallada</th>
                                        <th class="p-2.5">Estado Conciliación</th>
                                        <th class="p-2.5 text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @forelse ($filteredVerifs as $v)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                            <td class="p-2.5 font-mono font-bold text-indigo-700 dark:text-indigo-400">
                                                {{ $v->asset?->computer_code }}
                                            </td>
                                            <td class="p-2.5 font-mono text-slate-500">
                                                {{ $v->asset?->patrimonial_code ?: '-' }}
                                            </td>
                                            <td class="p-2.5 text-slate-600 dark:text-slate-400">
                                                -
                                            </td>
                                            <td class="p-2.5 text-slate-700 dark:text-slate-300 font-medium">
                                                {{ $v->expected_office ?: 'Sin Oficina' }}
                                            </td>
                                            <td class="p-2.5 font-semibold text-slate-900 dark:text-white">
                                                {{ $v->found_office ?: '-' }}
                                            </td>
                                            <td class="p-2.5">
                                                @if ($v->status === 'CONCILIADO')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                        CONCILIADO
                                                    </span>
                                                @elseif ($v->status === 'TRASLADADO_NO_AUTORIZADO')
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                                        TRASLADADO NO AUT.
                                                    </span>
                                                @elseif (in_array($v->status, ['NO_HABIDO', 'FALTANTE']))
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                                                        NO HABIDO
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                                        {{ $v->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-2.5 text-right">
                                                @if ($selectedAuditForModal->status === 'EN_PROCESO')
                                                    <button
                                                        type="button"
                                                        wire:click="quickVerifyAsset({{ $v->id }})"
                                                        class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded text-[11px] font-semibold cursor-pointer transition"
                                                    >
                                                        Verificar
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="p-6 text-center text-slate-400">
                                                No hay activos que coincidan con el filtro seleccionado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between flex-shrink-0">
                    <div>
                        @if ($selectedAuditForModal->status === 'EN_PROCESO')
                            <button
                                type="button"
                                wire:click="closeAudit({{ $selectedAuditForModal->id }})"
                                wire:confirm="¿Está seguro de cerrar el inventario anual definitivo? No se podrán realizar más verificaciones."
                                class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow transition cursor-pointer flex items-center gap-1.5"
                            >
                                🔒 Cerrar Inventario Anual Definitivo
                            </button>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <button
                            type="button"
                            wire:click="$set('isAuditReconciliationModalOpen', false)"
                            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition cursor-pointer"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- ========================================================================= -->
    <!-- MODAL 12: REGISTRAR / EDITAR SOFTWARE INSTITUCIONAL (SRS RF-02)           -->
    <!-- ========================================================================= -->
    @if ($isSoftwareModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Header con degradado Sky -> Indigo idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-sky-600 to-indigo-700 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">💿</span>
                        <div>
                            <h2 class="text-base font-bold">
                                {{ $editingSoftwareId ? 'Editar Software Institucional' : 'Registrar Software Institucional' }}
                            </h2>
                            <p class="text-xs text-sky-100">
                                Catálogo de Sistemas Operativos, Suites Ofimáticas y Licencias (RF-02)
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isSoftwareModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveSoftware" class="p-6 space-y-4">
                    <!-- Nombre del Software / Paquete -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Nombre del Software / Paquete <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="formSoftwareName"
                            placeholder="Ej. Windows 11 Pro 64-bit / Microsoft Office 2021 LTSC / AutoCAD 2024"
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            required
                        />
                    </div>

                    <!-- Versión y Desarrollador -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Versión / Edición
                            </label>
                            <input
                                type="text"
                                wire:model="formSoftwareVersion"
                                placeholder="Ej. 23H2 / Professional"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Desarrollador / Proveedor
                            </label>
                            <input
                                type="text"
                                wire:model="formSoftwareDeveloper"
                                placeholder="Ej. Microsoft / Autodesk / Adobe"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            />
                        </div>
                    </div>

                    <!-- Tipo de Licenciamiento y Cantidad -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tipo de Licenciamiento <span class="text-rose-500">*</span>
                            </label>
                            <select
                                wire:model="formSoftwareLicenseType"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                                required
                            >
                                <option value="OEM">OEM (Preinstalado con el equipo)</option>
                                <option value="VOLUMEN">Volumen Institucional / Campus</option>
                                <option value="RETAIL">Retail / Caja</option>
                                <option value="SUSCRIPCION">Suscripción Anual / Mensual</option>
                                <option value="OPEN_SOURCE">Open Source / Libre (GPL, MIT, Apache)</option>
                                <option value="PROPIETARIA">Licencia Perpetua Propietaria</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Cantidad de Licencias
                            </label>
                            <input
                                type="number"
                                min="1"
                                wire:model="formSoftwareTotalLicenses"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                                required
                            />
                        </div>
                    </div>

                    <!-- Clave de Activación -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Clave de Activación / Serial (Key)
                        </label>
                        <input
                            type="text"
                            wire:model="formSoftwareLicenseKey"
                            placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX"
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg font-mono focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                    </div>

                    <!-- Fecha de Expiración -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Fecha de Expiración / Renovación
                        </label>
                        <input
                            type="date"
                            wire:model="formSoftwareExpirationDate"
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                            Dejar en blanco para licencias perpetuas / OEM.
                        </p>
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Observaciones
                        </label>
                        <textarea
                            rows="2"
                            wire:model="formSoftwareNotes"
                            placeholder="Detalles del contrato de software, número de orden de compra..."
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none resize-none"
                        ></textarea>
                    </div>

                    <!-- Botones Cancelar / Registrar Software -->
                    <div class="pt-3 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="$set('isSoftwareModalOpen', false)"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                        >
                            {{ $editingSoftwareId ? 'Guardar Cambios' : 'Registrar Software' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


    <!-- ========================================================================= -->
    <!-- MODAL 13: REGISTRAR / EDITAR CATEGORÍA TECNOLÓGICA (SRS RF-01)            -->
    <!-- ========================================================================= -->
    @if ($isCategoryModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-sm transition-colors"
                            style="background-color: {{ $formCatColor }}20; color: {{ $formCatColor }};"
                        >
                            {{ $formCatIcon }}
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">
                                {{ $editingCategoryId ? 'Editar Categoría de Activos' : 'Nueva Categoría Tecnológica' }}
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Clasificación de equipamiento informático municipal
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        wire:click="$set('isCategoryModalOpen', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit="saveCategory">
                    <div class="p-6 space-y-4">
                        @if ($errors->any())
                            <div class="p-3 text-xs font-semibold text-rose-700 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 dark:border-rose-900/50">
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Nombre de la Categoría -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Nombre de la Categoría *
                            </label>
                            <input
                                type="text"
                                required
                                placeholder="Ej. Servidor Rack / Blade, Switch de Red"
                                wire:model.live.debounce.300ms="formCatName"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                        </div>

                        <!-- Código / Prefijo Correlativo -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Código / Prefijo Correlativo * (Ej. MDC-TI-<span class="font-mono text-emerald-700 dark:text-emerald-400 font-bold">{{ $formCatCode ? strtoupper($formCatCode) : 'XXX' }}</span>-0001)
                            </label>
                            <input
                                type="text"
                                maxlength="6"
                                @if ($editingCategoryId) disabled @endif
                                required
                                placeholder="Ej. SRV, SWT, UPS"
                                wire:model.live="formCatCode"
                                class="w-full text-xs font-mono font-bold px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none disabled:opacity-50"
                            />
                            @if ($editingCategoryId)
                                <span class="text-[10px] text-slate-400 mt-1 block">
                                    El código de prefijo no puede modificarse para proteger la integridad de los códigos ya generados.
                                </span>
                            @endif
                        </div>

                        <!-- Ícono Representativo -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Ícono Representativo
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($presetCatIcons as $ic)
                                    <button
                                        type="button"
                                        wire:click="$set('formCatIcon', '{{ $ic }}')"
                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-lg transition-transform cursor-pointer {{ $formCatIcon === $ic ? 'scale-110 bg-emerald-100 dark:bg-emerald-900/50 ring-2 ring-emerald-500' : 'bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700' }}"
                                    >
                                        {{ $ic }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Color Distintivo -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Color Distintivo
                            </label>
                            <div class="flex flex-wrap gap-2 items-center">
                                @foreach ($presetCatColors as $col)
                                    <button
                                        type="button"
                                        wire:click="$set('formCatColor', '{{ $col }}')"
                                        class="w-7 h-7 rounded-full transition-transform cursor-pointer {{ $formCatColor === $col ? 'scale-125 ring-2 ring-offset-2 ring-slate-400 dark:ring-slate-500' : 'hover:scale-110' }}"
                                        style="background-color: {{ $col }};"
                                    >
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Descripción Institucional -->
                        <div>
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                Descripción Institucional (Opcional)
                            </label>
                            <textarea
                                rows="2"
                                placeholder="Breve alcance sobre qué equipos abarca esta categoría..."
                                wire:model="formCatDescription"
                                class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                        <button
                            type="button"
                            wire:click="$set('isCategoryModalOpen', false)"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer"
                        >
                            {{ $editingCategoryId ? 'Actualizar Categoría' : 'Crear Categoría' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
