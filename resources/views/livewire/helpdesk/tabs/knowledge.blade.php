<div class="space-y-6 animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-amber-600 block mb-1">
                        KNOWLEDGE BASE TI (RF-14)
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Guías Estandarizadas de Solución Técnica
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                        Repositorio institucional de fallas comunes y procedimientos paso a paso para agilizar la resolución de incidencias en dependencias municipales.
                    </p>
                </div>
                <button type="button"
                        wire:click="openKbModal"
                        class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md transition-colors whitespace-nowrap cursor-pointer">
                    + Publicar Nueva Guía
                </button>
            </div>

            <!-- Buscador y Filtro de Base de Conocimiento -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text"
                           placeholder="Buscar guías por palabra clave (atasco de papel, red, reinicios, Active Directory)..."
                           wire:model.live.debounce.300ms="kbSearch"
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-900 dark:text-white" />
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <select wire:model.live="kbCategory"
                        class="p-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <option value="ALL">Todas las Categorías</option>
                    <option value="HARDWARE">Hardware</option>
                    <option value="SOFTWARE">Software</option>
                    <option value="RED_INTERNET">Red / Internet</option>
                    <option value="PERMISOS">Permisos / Accesos</option>
                    <option value="OTROS">Otros</option>
                </select>
            </div>

            <!-- Cuadrícula de Artículos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($kbArticles as $art)
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-3 hover:shadow-md transition-shadow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                    {{ $art->category }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    👁 {{ $art->views_count }} vistas | 👍 {{ $art->helpful_count }} votos
                                </span>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-snug">
                                {{ $art->title }}
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mt-1">
                                {{ $art->summary }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">
                                Por: {{ $art->author_technician_name ?? 'Mesa Técnica' }}
                            </span>
                            <button type="button"
                                    wire:click="openViewKbModal({{ $art->id }})"
                                    class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                Leer Solución Completa →
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12 text-slate-400 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
                        No se encontraron guías que coincidan con la búsqueda.
                    </div>
                @endforelse
            </div>
        </div>
