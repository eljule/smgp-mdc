<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema Integrado de Gestión Municipal - Municipalidad Distrital de Castilla. Plataforma modular para servicios tributarios, soporte helpdesk e inventario tecnológico.">
    <title>{{ $title ?? 'SIGM | Municipalidad Distrital de Castilla' }}</title>

    <!-- Script anti-parpadeo para tema oscuro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

        
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 antialiased dark:bg-gray-950 dark:text-slate-100 font-sans selection:bg-emerald-600 selection:text-white">

    @unless($hideHeader ?? false)
    <!-- Header Institucional -->
    <header class="bg-[#0d4f2f] text-white border-b border-emerald-900 shadow-md sticky top-0 z-40 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Branding Municipal -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('portal.launcher') }}" class="flex items-center gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 rounded-lg p-1">
                        <img src="{{ asset('images/logo.png') }}" alt="Escudo de Castilla" class="h-10 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform duration-200">
                        <div class="flex flex-col">
                            <span class="text-xs sm:text-sm font-bold tracking-wider leading-tight text-white group-hover:text-emerald-200 transition-colors">
                                MUNICIPALIDAD DISTRITAL DE CASTILLA
                            </span>
                            <span class="text-[13px] font-medium text-emerald-200/90 leading-tight">
                                SIGM | Panel de Subsistemas
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Controles de Usuario y Tema -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Alternador de Tema Dark/Light (Alpine.js puro) -->
                    <div x-data="themeSwitcher">
                        <button type="button"
                                @click="toggle()"
                                class="p-2 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 text-emerald-100 hover:text-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                                :title="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                                aria-label="Alternar modo oscuro">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Usuario Activo -->
                    <div class="hidden sm:flex items-center gap-2.5 bg-emerald-900/60 border border-emerald-700/60 rounded-lg px-3 py-1.5">
                        <div class="w-7 h-7 rounded-md bg-emerald-700 text-white flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            {{ $userInfo['initials'] ?? 'RO' }}
                        </div>
                        <div class="flex flex-col text-left">
                            <span class="text-xs font-semibold leading-tight text-white">{{ $userInfo['title'] ?? 'ADMINISTRADOR DEL SISTEMA SIGM' }}</span>
                            <span class="text-[10px] text-emerald-200 leading-tight">{{ $userInfo['role'] ?? 'Administrador Central' }}</span>
                        </div>
                    </div>

                    <!-- Botón Cerrar Sesión (Redirige a la vista principal de Login) -->
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-900/70 hover:bg-emerald-900 text-xs font-medium text-emerald-100 hover:text-white border border-emerald-700/70 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    @endunless

    <!-- Contenido Principal -->
    <x-global-notifications />
    <main class="flex-grow flex flex-col w-full">
        {{ $slot }}
    </main>

    @unless($hideFooter ?? false)
    <!-- Footer Institucional -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-6 text-center text-xs text-gray-500 dark:text-gray-400 mt-auto transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-1">
            <p class="font-medium text-gray-700 dark:text-gray-300">
                Municipalidad Distrital de Castilla &copy; 2026 &bull; Todos los derechos reservados. &bull; Desarrollado por <span class="text-emerald-700 dark:text-emerald-400 font-semibold">Ing. Julio Chavez Crisanto</span>
            </p>
            <p class="text-[11px] text-gray-400 dark:text-gray-500">
                Oficina de Desarrollo Tecnológico &bull; Transformación Digital y Transparencia
            </p>
        </div>
    </footer>
    @endunless

    @livewireScripts
</body>
</html>
