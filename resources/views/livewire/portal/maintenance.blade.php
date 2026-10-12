<div class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 font-sans">
    <!-- Header Institucional -->
    <header class="w-full bg-[#0d4f2f] dark:bg-slate-900 border-b border-emerald-800 dark:border-slate-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <a href="{{ route('portal.launcher') }}" class="flex items-center space-x-3.5 group cursor-pointer">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Escudo Oficial de Castilla"
                    class="h-10 sm:h-11 w-auto object-contain drop-shadow transition-transform group-hover:scale-105"
                />
                <div class="flex flex-col">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider leading-tight text-white group-hover:text-emerald-200 transition-colors">
                        Municipalidad Distrital de Castilla
                    </span>
                    <span class="text-[13px] font-medium text-emerald-200/90 leading-tight">
                        SIGM <span class="font-normal text-emerald-200">| Modo Mantenimiento</span>
                    </span>
                </div>
            </a>

            <div class="flex items-center space-x-3">
                <!-- Alternador de Tema Dark/Light (Alpine.js) -->
                <div x-data="themeSwitcher">
                    <button type="button"
                            @click="toggle()"
                            class="p-2 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 text-emerald-100 hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white cursor-pointer"
                            :title="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                            aria-label="Alternar modo oscuro">
                        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>

                <a
                    href="{{ route('portal.launcher') }}"
                    class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 border border-emerald-600/40 text-white transition-colors flex items-center gap-1.5"
                >
                    <span>&larr;</span>
                    <span>Panel de Subsistemas</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Notificación si el mantenimiento ya culminó -->
    @if ($statusNotice)
        <div class="bg-amber-600 text-white px-4 py-3 text-center text-xs sm:text-sm font-semibold shadow-md flex items-center justify-center space-x-2 animate-fadeIn">
            <span>ℹ️ {{ $statusNotice }}</span>
        </div>
    @endif

    <!-- Contenido principal -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="max-w-2xl w-full bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-3xl shadow-xl p-6 sm:p-10 relative overflow-hidden">
            <!-- Acento superior de color -->
            <div
                class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600"
            ></div>

            <!-- Badge superior -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs font-bold uppercase tracking-wide self-start">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping inline-block"></span>
                    <span>Modo Mantenimiento en Curso</span>
                </div>

                @if ($route)
                    <span class="text-[11px] font-mono px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 self-start">
                        Ruta: {{ $route }}
                    </span>
                @endif
            </div>

            <!-- Gráfico central e Icono -->
            <div class="text-center my-4">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-amber-100/70 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 mb-4 shadow-inner relative group">
                    <span class="text-5xl animate-bounce">⚙️</span>
                    <span class="absolute -bottom-1 -right-1 text-2xl">⏳</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $title }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 uppercase font-semibold tracking-wider">
                    Sistema Integrado de Gestión Municipal (SIGM - Castilla)
                </p>
            </div>

            <!-- Mensaje descriptivo -->
            <div class="mt-6 p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/80 text-center">
                <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-200 leading-relaxed font-medium">
                    {{ $message }}
                </p>
            </div>

            <!-- Caja destacada: Tiempo Aproximado de Recuperación -->
            <div class="mt-5 p-5 rounded-2xl bg-gradient-to-br from-amber-50/80 via-orange-50/40 to-amber-100/60 dark:from-amber-950/40 dark:via-slate-900 dark:to-orange-950/30 border border-amber-200 dark:border-amber-800/60 shadow-sm text-center">
                <div class="flex items-center justify-center space-x-2 text-amber-800 dark:text-amber-300 font-bold text-xs uppercase tracking-wider mb-1">
                    <span>⏱️ Tiempo Estimado de Espera y Recuperación</span>
                </div>
                <div class="text-lg sm:text-2xl font-black text-amber-900 dark:text-amber-200 tracking-tight my-1">
                    {{ $waitTime }}
                </div>
                <p class="text-[11px] text-amber-700/90 dark:text-amber-300/80 max-w-md mx-auto leading-normal">
                    El equipo de la Oficina de Desarrollo Tecnológico (ODT) se encuentra desplegando las mejoras técnicas para garantizar la integridad y rendimiento del subsistema.
                </p>
            </div>

            <!-- Indicador de fases / pipeline -->
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center mb-4">
                    Progreso de la Intervención
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-[10px] sm:text-xs">
                    <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 font-semibold">
                        <div>✓ Fase 1</div>
                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Pausa Operativa</div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700 text-amber-800 dark:text-amber-200 font-bold animate-pulse">
                        <div>⟳ Fase 2</div>
                        <div class="text-[10px] font-normal">Mantenimiento</div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-400 font-medium">
                        <div>○ Fase 3</div>
                        <div class="text-[10px] font-normal">Restablecimiento</div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                <button
                    wire:click="checkStatus"
                    wire:loading.attr="disabled"
                    type="button"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs sm:text-sm shadow-md transition-all duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 cursor-pointer"
                >
                    <span wire:loading.class="animate-spin">⟳</span>
                    <span wire:loading.remove>Verificar Disponibilidad</span>
                    <span wire:loading>Verificando estado...</span>
                </button>

                <a
                    href="{{ route('portal.launcher') }}"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm text-center transition-all duration-200"
                >
                    Volver a Subsistemas
                </a>

                <a
                    href="{{ url('/soporte/nuevo-ticket') }}"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold text-xs sm:text-sm text-center transition-all duration-200"
                >
                    Mesa de Ayuda (Soporte)
                </a>
            </div>
        </div>
    </main>

    <!-- Footer Institucional -->
    <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-4 text-center text-xs text-slate-400">
        <p>Municipalidad Distrital de Castilla &copy; 2026 &bull; Oficina de Desarrollo Tecnológico</p>
    </footer>
</div>
