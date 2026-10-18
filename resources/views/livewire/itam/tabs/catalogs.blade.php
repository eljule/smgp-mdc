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
