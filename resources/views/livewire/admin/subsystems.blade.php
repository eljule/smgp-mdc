<div class="space-y-6 pb-12">
    <!-- 1. Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/80 dark:border-emerald-800/80 flex items-center justify-center text-2xl shrink-0">
                🧩
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Catálogo de Subsistemas SIGM
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                    Administración centralizada de módulos distritales, rutas, estados operativos y modo de mantenimiento.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <!-- Badge Total -->
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                Total: {{ $metrics['total'] }}
            </span>

            <!-- Badge Activos -->
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                Activos: {{ $metrics['activos'] }}
            </span>

            <!-- Badge Mantenimiento -->
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 inline-flex items-center gap-1">
                <span>🔧</span>
                <span>Mantenimiento: {{ $metrics['mantenimiento'] }}</span>
            </span>

            <!-- Botón Nuevo Subsistema -->
            <button
                type="button"
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs sm:text-sm shadow-xs transition-colors cursor-pointer ml-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Nuevo Subsistema</span>
            </button>
        </div>
    </div>

    <!-- Flash message -->
    @if ($flashMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs sm:text-sm font-semibold transition-all"
        >
            <div class="flex items-center gap-2">
                <span class="text-lg">✓</span>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- 2. Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        <!-- Search input -->
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nombre, código o ruta..."
                class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
            >
        </div>

        <!-- Filter tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto">
            <button
                type="button"
                wire:click="setFilter('todos')"
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ $filter === 'todos'
                    ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                }}"
            >
                Todos ({{ $metrics['total'] }})
            </button>
            <button
                type="button"
                wire:click="setFilter('operativos')"
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ $filter === 'operativos'
                    ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                }}"
            >
                Operativos ({{ $metrics['activos'] }})
            </button>
            <button
                type="button"
                wire:click="setFilter('mantenimiento')"
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ $filter === 'mantenimiento'
                    ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                }}"
            >
                En Mantenimiento ({{ $metrics['mantenimiento'] }})
            </button>
            <button
                type="button"
                wire:click="setFilter('inactivos')"
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ $filter === 'inactivos'
                    ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-xs'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'
                }}"
            >
                Inactivos ({{ $metrics['inactivos'] }})
            </button>
        </div>
    </div>

    <!-- 3. Subsystems Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($modules as $module)
            @php
                $isMaintenance = $module->status === \App\Enums\ModuleStatus::Maintenance;
                $accentBorder = match ($module->accent_color->value ?? 'emerald') {
                    'amber' => 'border-t-4 border-amber-500',
                    'blue' => 'border-t-4 border-blue-500',
                    'purple' => 'border-t-4 border-purple-500',
                    default => 'border-t-4 border-emerald-500',
                };
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col justify-between transition-shadow hover:shadow-md {{ $accentBorder }}">
                <!-- Card Top Info -->
                <div>
                    <!-- Header inside card -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <!-- Icon Box -->
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 bg-slate-50 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                                {{ $module->icon_emoji }}
                            </div>

                            <!-- Scope and Title -->
                            <div>
                                <span class="text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 font-mono uppercase block">
                                    {{ $module->display_code }}
                                </span>
                                <h3 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base leading-snug mt-0.5">
                                    {{ $module->name }}
                                </h3>
                            </div>
                        </div>

                        <!-- Status and Route Badges -->
                        <div class="flex flex-col items-end gap-1.5 shrink-0">
                            @if ($isMaintenance)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 uppercase tracking-wider border border-amber-200 dark:border-amber-800">
                                    <span>🔧</span>
                                    <span>MANTENIMIENTO</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Activo</span>
                                </span>
                            @endif

                            <span class="px-2 py-0.5 rounded text-[11px] font-mono text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700">
                                Ruta: {{ $module->route }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mt-3.5">
                        {{ $module->description }}
                    </p>

                    <!-- Maintenance notice box -->
                    @if ($isMaintenance)
                        <div class="mt-3.5 p-3 rounded-xl bg-amber-50/90 dark:bg-amber-950/30 border border-amber-200/90 dark:border-amber-800/60 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-amber-900 dark:text-amber-200">
                                <span class="inline-flex items-center gap-1.5">
                                    <span>⚙️</span>
                                    <span>Espera: {{ $module->wait_time ?? 'Hasta nuevo aviso' }}</span>
                                </span>
                                <a href="{{ $module->route }}" wire:navigate class="text-amber-800 hover:text-amber-950 dark:text-amber-300 inline-flex items-center gap-1 text-[11px] underline font-semibold cursor-pointer">
                                    <span>👁️ Ver Pantalla</span>
                                </a>
                            </div>
                            <p class="text-[11px] italic text-amber-800/90 dark:text-amber-300/80 leading-relaxed line-clamp-2">
                                "{{ $module->status_message ?? 'Este subsistema se encuentra temporalmente suspendido por trabajos de mantenimiento y optimización.' }}"
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Footer / Actions row -->
                <div class="mt-5 pt-3.5 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        @if ($isMaintenance)
                            <button
                                type="button"
                                wire:click="toggleMaintenance({{ $module->id }})"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white shadow-xs transition-colors cursor-pointer inline-flex items-center gap-1"
                                title="Desactivar modo mantenimiento"
                            >
                                <span>🔧</span>
                                <span>Mantenimiento Activo</span>
                            </button>
                        @else
                            <button
                                type="button"
                                wire:click="toggleMaintenance({{ $module->id }})"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800 transition-colors cursor-pointer inline-flex items-center gap-1"
                                title="Activar modo mantenimiento"
                            >
                                <span>🔧</span>
                                <span>Mantenimiento</span>
                            </button>
                        @endif

                        <button
                            type="button"
                            wire:click="openEditModal({{ $module->id }})"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 transition-colors cursor-pointer inline-flex items-center gap-1"
                            title="Editar parámetros"
                        >
                            <span>✏️</span>
                            <span>Editar</span>
                        </button>
                    </div>

                    <a
                        href="{{ $module->route }}"
                        wire:navigate
                        class="px-4 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white shadow-xs transition-colors cursor-pointer inline-flex items-center gap-1"
                    >
                        <span>Ingresar</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
                <span class="text-3xl block mb-2">🔍</span>
                <p class="text-sm font-semibold">No se encontraron subsistemas que coincidan con los criterios de búsqueda.</p>
                <button
                    type="button"
                    wire:click="setFilter('todos')"
                    class="mt-2 text-xs text-emerald-600 hover:underline font-bold cursor-pointer"
                >
                    Ver todos los subsistemas
                </button>
            </div>
        @endforelse
    </div>

    <!-- 4. Footer Pagination -->
    <div class="p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 shadow-xs">
        <div>
            Mostrando
            <span class="font-bold text-slate-800 dark:text-slate-200">1 a {{ $totalCount }}</span>
            de
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $metrics['total'] }}</span>
            registros
        </div>

        <div class="flex items-center gap-1.5">
            <button
                type="button"
                disabled
                class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-semibold opacity-40 cursor-not-allowed"
            >
                &lt; Anterior
            </button>
            <button
                type="button"
                class="w-8 h-8 rounded-lg font-bold text-xs bg-emerald-600 text-white shadow-xs"
            >
                1
            </button>
            <button
                type="button"
                disabled
                class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 font-semibold opacity-40 cursor-not-allowed"
            >
                Siguiente &gt;
            </button>
        </div>
    </div>

    <!-- 5. Modal: Nuevo / Editar Subsistema -->
    @if ($showCreateModal || $showEditModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-in fade-in duration-150"
            x-data
            @keydown.escape.window="$wire.closeModal()"
        >
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden relative max-h-[92vh] overflow-y-auto"
                @click.outside="$wire.closeModal()"
            >
                <!-- Cabecera Institucional Verde -->
                <div class="bg-[#00875a] px-6 py-4 flex items-center justify-between text-white sticky top-0 z-10">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-white/20 dark:bg-white/10 flex items-center justify-center text-xl shadow-xs shrink-0">
                            {{ $formIcon ?: '🏢' }}
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-white leading-tight">
                                {{ $showEditModal ? 'Editar Subsistema SIGM' : 'Registrar Nuevo Subsistema' }}
                            </h3>
                            <p class="text-xs text-emerald-100 font-normal mt-0.5">
                                {{ $showEditModal ? 'Modifique los parámetros operativos y visuales del módulo' : 'Añada un nuevo módulo o subsistema a la plataforma municipal' }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition-colors text-base font-bold cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit.prevent="saveModule" class="p-6 space-y-4">
                    <!-- Fila 1: Código (Slug) & Nombre -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                CÓDIGO IDENTIFICADOR (SLUG)
                            </label>
                            <input
                                type="text"
                                wire:model="formCode"
                                required
                                placeholder="ej. rentas_tributaria"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            <span class="text-[10px] text-slate-400 mt-1 block">Identificador en permisos y base de datos.</span>
                            @error('formCode') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                NOMBRE DEL SUBSISTEMA
                            </label>
                            <input
                                type="text"
                                wire:model="formName"
                                required
                                placeholder="ej. Administración Tributaria (Rentas)"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            @error('formName') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 2: Descripción Funcional -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                            DESCRIPCIÓN FUNCIONAL
                        </label>
                        <textarea
                            wire:model="formDescription"
                            required
                            rows="2"
                            placeholder="Describa el alcance de este subsistema..."
                            class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"
                        ></textarea>
                        @error('formDescription') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <!-- Fila 3: Ruta de Acceso & Orden -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                RUTA DE ACCESO (URL)
                            </label>
                            <input
                                type="text"
                                wire:model="formRoute"
                                required
                                placeholder="/"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            @error('formRoute') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                                ORDEN DE VISUALIZACIÓN
                            </label>
                            <input
                                type="number"
                                wire:model="formOrder"
                                min="1"
                                required
                                placeholder="5"
                                class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            />
                            @error('formOrder') <span class="text-[10px] text-rose-500 block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Fila 4: Icono Representativo -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                            ICONO REPRESENTATIVO (EMOJI O SVG)
                        </label>
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div class="w-24 h-11 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-center text-xl shadow-2xs font-bold shrink-0">
                                {{ $formIcon }}
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Seleccione un emoji rápido:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $presetIcons = ['🏢', '🚗', '💻', '🎧', '📊', '📑', '🏛️', '🛡️', '⚙️', '📂', '💳', '🚚'];
                            @endphp
                            @foreach ($presetIcons as $emoji)
                                <button
                                    type="button"
                                    wire:click="$set('formIcon', '{{ $emoji }}')"
                                    class="w-8 h-8 rounded-lg border text-base flex items-center justify-center transition-all cursor-pointer {{ $formIcon === $emoji
                                        ? 'bg-emerald-100 dark:bg-emerald-950/60 border-emerald-500 scale-110 shadow-xs'
                                        : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-750'
                                    }}"
                                >
                                    {{ $emoji }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fila 5: Color Distintivo de Acento -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 uppercase tracking-wider">
                            COLOR DISTINTIVO DE ACENTO
                        </label>
                        <div class="flex items-center space-x-3 mb-2.5">
                            <div
                                class="w-9 h-9 rounded-xl border border-slate-300 dark:border-slate-700 p-0.5 shadow-2xs"
                                style="background-color: {{ $formAccentColorHex }};"
                            ></div>
                            <span class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $formAccentColorHex }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            @php
                                $presetColors = ['#16a34a', '#ea580c', '#2563eb', '#7c3aed', '#059669', '#dc2626', '#0891b2', '#d97706'];
                            @endphp
                            @foreach ($presetColors as $c)
                                <button
                                    type="button"
                                    wire:click="selectAccentColor('{{ $c }}')"
                                    class="w-6 h-6 rounded-full border-2 transition-transform cursor-pointer {{ $formAccentColorHex === $c
                                        ? 'scale-125 border-slate-900 dark:border-white shadow'
                                        : 'border-transparent hover:scale-110'
                                    }}"
                                    style="background-color: {{ $c }};"
                                ></button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fila 6: Opciones de Estado -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="formIsActive"
                                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                            />
                            <span>Subsistema Activo en el Sistema</span>
                        </label>

                        <label class="flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="formRequiresAuth"
                                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                            />
                            <span>Requiere Autenticación / Roles</span>
                        </label>
                    </div>

                    <!-- Fila 7: Botones -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#00875a] hover:bg-[#00734c] active:bg-[#005e3e] shadow-md transition-all cursor-pointer"
                        >
                            {{ $showEditModal ? 'Guardar Cambios' : 'Registrar Subsistema' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
