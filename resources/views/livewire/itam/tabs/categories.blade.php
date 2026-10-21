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
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 pb-4 border-b border-slate-100 dark:border-slate-800">
                                {{ $cat->description ?: 'Categoría estándar para inventario tecnológico municipal.' }}
                            </p>
                            @php
                                $customFieldsCount = is_array($cat->custom_fields) ? count($cat->custom_fields) : 0;
                            @endphp
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                    Parámetros Técnicos ({{ $customFieldsCount }})
                                </span>
                            </div>

                            <!-- Campos Dinámicos en Tag -->
                            @if ($customFieldsCount > 0)
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach ($cat->custom_fields as $cf)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold">
                                            {{ $cf['label'] ?? ($cf['name'] ?? 'Campo') }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[11px] text-slate-400 italic pt-0.5">
                                    Sin parámetros configurados
                                </p>
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
