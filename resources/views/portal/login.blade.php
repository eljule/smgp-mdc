<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema | SIGM Castilla</title>

    <!-- Script anti-parpadeo robusto para tema oscuro y claro -->
    <script>
        (function() {
            try {
                const storedTheme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {
                console.error('Error aplicando tema inicial:', e);
            }
        })();

        window.toggleTheme = function() {
            const html = document.documentElement;
            const isDark = !html.classList.contains('dark');
            if (isDark) {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: isDark } }));
            return isDark;
        };
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-white dark:bg-slate-950 font-sans text-gray-800 dark:text-gray-100 antialiased selection:bg-emerald-600 selection:text-white transition-colors duration-200">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Panel Izquierdo: Branding Institucional Castilla -->
        <div class="relative hidden md:flex md:w-1/2 bg-[#046A38] text-white flex-col justify-between p-8 lg:p-12 overflow-hidden">
            <!-- Textura de fondo institucional -->
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                 style="background-image: radial-gradient(circle at 25px 25px, white 2%, transparent 0%), radial-gradient(circle at 75px 75px, white 2%, transparent 0%); background-size: 100px 100px;"></div>

            <!-- Top Badge -->
            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-900/80 border border-emerald-700/60 text-xs font-medium text-emerald-100 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>ECOSISTEMA MODULAR SIGM</span>
                </span>
            </div>

            <!-- Centro: Tarjeta Promocional Institucional -->
            <div class="relative z-10 max-w-lg mx-auto w-full my-auto">
                <div class="bg-emerald-950/40 backdrop-blur-md rounded-2xl border border-emerald-700/40 p-8 shadow-2xl space-y-5">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Escudo de Castilla" class="h-12 w-auto object-contain">
                        <div class="text-left">
                            <span class="block text-[11px] uppercase tracking-wider text-emerald-200 font-bold">MUNICIPALIDAD DE</span>
                            <span class="block text-sm font-black tracking-wide text-white">CASTILLA</span>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-white tracking-tight leading-snug">
                        Municipalidad Distrital de Castilla
                    </h2>

                    <p class="text-sm text-emerald-100/90 leading-relaxed font-light">
                        Acceso exclusivo para colaboradores y personal técnico autorizado. Gestione los servicios tributarios, soporte helpdesk e inventarios tecnológicos de forma centralizada.
                    </p>

                    <div class="pt-2 flex items-center gap-2 text-xs text-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Innovación y Eficiencia al servicio del ciudadano.</span>
                    </div>
                </div>
            </div>

            <!-- Footer Izquierdo -->
            <div class="relative z-10 text-xs text-emerald-200/70">
                &copy; 2026 Municipalidad de Castilla &bull; ODT v13.19.0
            </div>
        </div>

        <!-- Panel Derecho: Formulario de Login -->
        <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-12 relative bg-white dark:bg-slate-900 transition-colors duration-200">
            <!-- Alternador de Tema Top Right -->
            <div class="flex justify-end" x-data="{
                darkMode: document.documentElement.classList.contains('dark'),
                toggle() {
                    this.darkMode = window.toggleTheme();
                }
            }" @theme-changed.window="darkMode = $event.detail.dark">
                <button type="button"
                        @click="toggle()"
                        onclick="window.toggleTheme()"
                        class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-600 dark:text-amber-300 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer"
                        :title="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                        aria-label="Alternar tema claro/oscuro">
                    <!-- Icono Luna (Modo Claro) -->
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <!-- Icono Sol (Modo Oscuro) -->
                    <svg x-show="darkMode" x-cloak class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Formulario Central -->
            <div class="w-full max-w-md mx-auto my-auto space-y-6">
                <!-- Branding Cabecera Form -->
                <div class="text-center space-y-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Escudo de Castilla" class="h-14 w-auto mx-auto object-contain">
                    <span class="block text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">MUNICIPALIDAD DE CASTILLA</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Entre a su cuenta
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        ¿No tienes una cuenta? <a href="#" class="text-emerald-700 dark:text-emerald-400 font-semibold hover:underline">Solicitar Registro de Usuario</a>
                    </p>
                </div>

                <!-- Formulario de Autenticación Segura (POST hacia LoginController) -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-4" x-data="{ showPass: false, user: 'root' }">
                    @csrf

                    @if (session('error'))
                        <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-2">
                            <span class="font-bold">✕</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Usuario -->
                    <div class="space-y-1">
                        <label for="username" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Usuario <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            x-model="user"
                            required
                            class="block w-full px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg shadow-xs focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 dark:focus:ring-emerald-500 font-medium"
                        >
                    </div>

                    <!-- Contraseña con botón ver/ocultar -->
                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                :type="showPass ? 'text' : 'password'"
                                value="admin123"
                                required
                                class="block w-full px-3.5 py-2.5 pr-10 text-sm bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg shadow-xs focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 dark:focus:ring-emerald-500"
                            >
                            <button
                                type="button"
                                @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none"
                                title="Mostrar / Ocultar contraseña">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center justify-between text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-gray-600 dark:text-gray-400">
                            <input type="checkbox" name="remember" value="1" checked class="rounded-sm border-gray-300 text-emerald-700 focus:ring-emerald-500">
                            <span>Recordarme</span>
                        </label>
                    </div>

                    <!-- Botón Entrar -->
                    <x-ui.button
                        variant="primary"
                        size="md"
                        type="submit"
                        class="w-full bg-[#046A38] hover:bg-emerald-800 py-2.5 text-sm font-bold shadow-md cursor-pointer">
                        Entrar
                    </x-ui.button>

                    <div class="text-center pt-1">
                        <a href="{{ route('portal.launcher') }}" class="text-xs text-gray-500 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors">
                            &larr; Volver al Portal Principal
                        </a>
                    </div>

                    <!-- Cuentas para prueba de permisos -->
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-800 space-y-2.5">
                        <span class="block text-center text-[10px] uppercase font-bold tracking-wider text-gray-400 dark:text-gray-500">
                            CUENTAS PARA PRUEBA DE PERMISOS
                        </span>

                        <div class="grid grid-cols-3 gap-2">
                            <!-- ROOT -->
                            <button
                                type="button"
                                @click="user = 'root'; $nextTick(() => $el.closest('form').submit())"
                                class="p-2 rounded-lg border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50/50 dark:bg-emerald-950/20 text-center hover:bg-emerald-100/50 transition-colors cursor-pointer">
                                <span class="block text-xs font-bold text-emerald-800 dark:text-emerald-300">👑 ROOT</span>
                                <span class="block text-[10px] text-emerald-600 dark:text-emerald-400">4 módulos</span>
                            </button>

                            <!-- jperez -->
                            <button
                                type="button"
                                @click="user = 'jperez'; $nextTick(() => $el.closest('form').submit())"
                                class="p-2 rounded-lg border border-blue-200 dark:border-blue-800/80 bg-blue-50/50 dark:bg-blue-950/20 text-center hover:bg-blue-100/50 transition-colors cursor-pointer">
                                <span class="block text-xs font-bold text-blue-800 dark:text-blue-300">🛡️ jperez</span>
                                <span class="block text-[10px] text-blue-600 dark:text-blue-400">3 módulos</span>
                            </button>

                            <!-- mgomez -->
                            <button
                                type="button"
                                @click="user = 'mgomez'; $nextTick(() => $el.closest('form').submit())"
                                class="p-2 rounded-lg border border-purple-200 dark:border-purple-800/80 bg-purple-50/50 dark:bg-purple-950/20 text-center hover:bg-purple-100/50 transition-colors cursor-pointer">
                                <span class="block text-xs font-bold text-purple-800 dark:text-purple-300">💳 mgomez</span>
                                <span class="block text-[10px] text-purple-600 dark:text-purple-400">2 módulos</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer Derecho -->
            <div class="text-center text-[11px] text-gray-400 dark:text-gray-500 mt-6">
                Oficina de Desarrollo Tecnológico &bull; Municipalidad Distrital de Castilla
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
