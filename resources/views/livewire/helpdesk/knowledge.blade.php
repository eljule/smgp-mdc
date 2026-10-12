<div class="space-y-6">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.helpdesk') }}" class="hover:underline">Helpdesk</a>
                <span>•</span>
                <span>Base de Conocimientos</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>📚</span>
                <span>Base de Conocimientos TI</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Guías de autoayuda, procedimientos técnicos de resolución rápida y manuales operativos de Castilla.
            </p>
        </div>
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer">
            <span>+</span>
            <span>Nuevo Artículo</span>
        </button>
    </div>

    <!-- Barra de Búsqueda -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar soluciones por título, síntoma o etiqueta..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <select wire:model.live="category" class="w-full sm:w-48 px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-700 dark:text-slate-200">
            <option value="TODAS">Todas las Categorías</option>
            <option value="HARDWARE">Hardware</option>
            <option value="SOFTWARE">Software</option>
            <option value="RED_INTERNET">Red & Internet</option>
        </select>
    </div>

    <!-- Lista de Artículos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($articles as $art)
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs hover:border-emerald-500 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                            {{ $art->category }}
                        </span>
                        <span class="text-[11px] text-slate-400">👁️ {{ $art->views_count }} vistas</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-2">{{ $art->title }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-3">{{ $art->summary }}</p>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">{{ $art->author_technician_name }}</span>
                    <button wire:click="selectArticle({{ $art->id }})" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline cursor-pointer">
                        Ver Guía →
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">
                No se encontraron artículos en la base de conocimientos.
            </div>
        @endforelse
    </div>

    <!-- Modal Ver Artículo -->
    @if ($selectedArticle)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                            {{ $selectedArticle->category }}
                        </span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-1">{{ $selectedArticle->title }}</h3>
                    </div>
                    <button wire:click="closeArticle" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <div class="space-y-4 text-xs sm:text-sm">
                    @if ($selectedArticle->symptoms)
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-900">
                            <h4 class="font-bold text-amber-800 dark:text-amber-300 mb-1">Síntomas Observados:</h4>
                            <p class="text-slate-700 dark:text-slate-200">{{ $selectedArticle->symptoms }}</p>
                        </div>
                    @endif

                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-2">Procedimiento de Solución Paso a Paso:</h4>
                        <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-100 dark:border-slate-800 whitespace-pre-line font-sans leading-relaxed text-slate-800 dark:text-slate-200">
                            {{ $selectedArticle->solution_steps }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button wire:click="closeArticle" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold">Cerrar</button>
                </div>
            </div>
        </div>
    @endif
</div>
