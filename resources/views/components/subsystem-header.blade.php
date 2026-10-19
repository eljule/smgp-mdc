@props([
    'moduleName' => 'Panel de Administración',
    'subsystem' => 'admin',
    'criticalSuppliesCount' => 2,
    'delayedLoansCount' => 1,
    'openTicketsCount' => 5,
])

@php
    $subsystemAuthUser = auth()->user();
    $subsystemInitials = $subsystemAuthUser?->initials ?? 'RO';
    $subsystemTitle = $subsystemAuthUser ? ($subsystemAuthUser->display_title ?? mb_strtoupper($subsystemAuthUser->full_name)) : 'ADMINISTRADOR DEL SISTEMA SIGM';
    $subsystemRole = $subsystemAuthUser?->display_role ?? 'Administrador Central';
@endphp

<header class="bg-[#046A38] text-white border-b border-emerald-800 shadow-md sticky top-0 z-40 transition-colors duration-200">
    <div class="w-full px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Lado izquierdo: Botón colapsar menú + Escudo e Identidad Distrital -->
        <div class="flex items-center space-x-3 sm:space-x-4">
            <!-- Botón Móvil: Abrir Drawer Lateral -->
            <button
                type="button"
                @click="mobileSidebarOpen = true"
                class="md:hidden p-2 rounded-lg text-emerald-100 hover:text-white hover:bg-emerald-800/80 focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer"
                title="Abrir menú de navegación"
                aria-label="Abrir menú lateral"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Botón Escritorio: Colapsar/Expandir Barra Lateral -->
            <button
                type="button"
                @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden md:inline-flex p-2 rounded-lg text-emerald-100 hover:text-white hover:bg-emerald-800/80 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition-colors cursor-pointer"
                title="Colapsar / expandir menú"
                aria-label="Alternar menú lateral"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="{{ $subsystem === 'admin' ? route('subsystems.central') : ($subsystem === 'itam' ? url('/itam') : ($subsystem === 'helpdesk' ? url('/helpdesk') : route('portal.launcher'))) }}"
               class="flex items-center space-x-3 cursor-pointer group">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Escudo Oficial del Distrito de Castilla"
                    class="h-10 sm:h-11 w-auto object-contain drop-shadow transition-transform duration-200 group-hover:scale-105"
                />
                <div class="flex flex-col">
                    <span class="text-xs sm:text-sm font-bold tracking-wider leading-tight text-white group-hover:text-emerald-200 transition-colors">
                        MUNICIPALIDAD DISTRITAL DE CASTILLA
                    </span>
                    <span class="text-[13px] font-medium text-emerald-200/90 leading-tight flex items-center gap-2">
                        @if ($subsystem === 'itam')
                            <span>SIGM <span class="font-light text-emerald-200">| Activos TI (ITAM)</span><span class="sr-only">Inventario y Gestión TI</span></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                v1.0
                            </span>
                        @else
                            <span>SIGM <span class="font-normal text-emerald-200">| {{ $moduleName }}</span></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                v13.19.0
                            </span>
                        @endif
                    </span>
                </div>
            </a>
        </div>

        <!-- Lado derecho: Acciones de cabecera y Accesos Rápidos -->
        <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- ITAM: Badges de Alerta Rápida -->
            @if ($subsystem === 'itam')
                @if ($criticalSuppliesCount > 0)
                    <a href="{{ url('/itam?tab=supplies') }}"
                       class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[11px] font-bold animate-pulse"
                       title="{{ $criticalSuppliesCount }} insumos con stock crítico">
                        <span>⚠️</span>
                        <span>{{ $criticalSuppliesCount }} Stock Crítico</span>
                    </a>
                @endif
                @if ($delayedLoansCount > 0)
                    <a href="{{ url('/itam?tab=loans') }}"
                       class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[11px] font-bold"
                       title="{{ $delayedLoansCount }} préstamos con devolución retrasada">
                        <span>⏱️</span>
                        <span>{{ $delayedLoansCount }} Demorados</span>
                    </a>
                @endif
            @endif

            <!-- HELPDESK: Badges y Botón Nuevo Ticket -->
            @if ($subsystem === 'helpdesk')
                @if ($openTicketsCount > 0)
                    <a href="{{ url('/helpdesk?tab=tickets') }}"
                       class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[11px] font-bold animate-pulse"
                       title="{{ $openTicketsCount }} tickets abiertos">
                        <span>🔔</span>
                        <span>{{ $openTicketsCount }} Abiertos</span>
                    </a>
                @endif
                <a href="{{ url('/soporte/nuevo-ticket') }}"
                   class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-all hover:scale-105"
                   title="Generar nueva incidencia o ticket">
                    <span>+</span>
                    <span class="hidden sm:inline">Nuevo Ticket</span>
                </a>
            @endif

            <!-- Botón de regreso al lanzador -->
            <a
                href="{{ route('portal.launcher') }}"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 dark:bg-slate-800 text-white text-xs font-semibold shadow-xs border border-emerald-600/40 transition-all hover:-translate-y-0.5 cursor-pointer"
                title="Ir a la cuadrícula de subsistemas"
            >
                <span>🚀</span>
                <span class="hidden sm:inline">Lanzador</span>
            </a>

            <!-- Botón Central -->
            <a
                href="{{ route('subsystems.central') }}"
                class="hidden lg:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-900/60 hover:bg-emerald-800 text-emerald-100 text-xs font-semibold shadow-xs border border-emerald-700/50 transition-all cursor-pointer"
                title="Ir al Dashboard Central de Administración"
            >
                <span>⚙️</span>
                <span class="hidden sm:inline">Central</span>
            </a>

            <!-- Selector de Tema (Alpine.js puro) -->
            <div x-data="themeSwitcher">
                <button
                    type="button"
                    @click="toggle()"
                    class="p-2 rounded-lg text-emerald-200 hover:text-white hover:bg-emerald-800/80 dark:hover:bg-slate-800 transition-colors focus:outline-none cursor-pointer"
                    :title="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                    aria-label="Alternar modo oscuro"
                >
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Perfil del Usuario Activo Institucional Dinámico -->
            <div class="hidden md:flex items-center space-x-3 bg-emerald-900/60 dark:bg-slate-800/80 border border-emerald-700/50 dark:border-slate-700 px-3.5 py-1.5 rounded-lg text-xs">
                <div class="w-7 h-7 rounded-full bg-emerald-700 dark:bg-emerald-600 flex items-center justify-center font-bold text-white shadow-inner">
                    {{ $subsystemInitials }}
                </div>
                <div class="flex flex-col text-left">
                    <span class="font-bold text-white leading-tight uppercase">
                        {{ $subsystemTitle }}
                    </span>
                    <span class="text-[10px] text-emerald-300 font-medium">
                        {{ $subsystemRole }}
                    </span>
                </div>
            </div>

            <!-- Botón Cambiar Contraseña -->
            <a
                href="{{ route('password.change') }}"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-900/60 hover:bg-emerald-800 text-xs font-medium text-emerald-100 hover:text-white border border-emerald-700/60 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                title="Cambiar mi contraseña"
            >
                <span>🔑</span>
                <span class="hidden sm:inline">Cambiar Clave</span>
            </a>

            <!-- Botón Cerrar Sesión en Cabecera de Subsistema -->
            <a
                href="{{ route('logout') }}"
                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-900/70 hover:bg-emerald-900 text-xs font-medium text-emerald-100 hover:text-white border border-emerald-700/60 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white cursor-pointer"
                title="Cerrar Sesión"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="hidden sm:inline">Cerrar Sesión</span>
            </a>
        </div>
    </div>
</header>
