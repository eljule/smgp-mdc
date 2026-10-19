<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Contraseña | SIGM Castilla</title>

    <!-- Script anti-parpadeo para tema oscuro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-50 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 antialiased flex flex-col justify-between selection:bg-emerald-600 selection:text-white transition-colors duration-200">

    <!-- Header Institucional Mínimo -->
    <header class="bg-[#046A38] text-white py-4 px-6 border-b border-emerald-900 shadow-sm">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}" alt="Escudo de Castilla" class="h-10 w-auto object-contain drop-shadow-xs">
                <div>
                    <h1 class="text-sm font-bold tracking-wider leading-tight text-white uppercase">
                        MUNICIPALIDAD DISTRITAL DE CASTILLA
                    </h1>
                    <p class="text-xs text-emerald-200 font-medium">
                        SIGM &bull; Seguridad de Cuentas y Accesos
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a
                    href="{{ route('logout') }}"
                    class="px-3 py-1.5 rounded-lg bg-emerald-900/80 hover:bg-emerald-900 text-xs font-semibold text-emerald-100 hover:text-white border border-emerald-700/60 transition-colors"
                >
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-6">
        <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
            
            <!-- Banner Superior del Card -->
            <div class="p-6 bg-gradient-to-r from-emerald-800 to-emerald-900 text-white relative">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-xs flex items-center justify-center text-2xl border border-white/20">
                        🔑
                    </div>
                    <div>
                        <h2 class="text-lg font-black tracking-tight text-white">
                            {{ $isProvisional ? 'Cambio Obligatorio de Contraseña' : 'Actualización de Contraseña Personal' }}
                        </h2>
                        <p class="text-xs text-emerald-200 font-medium">
                            Usuario: <strong class="text-white font-mono">{{ $user->username }}</strong> &bull; {{ $user->display_role }}
                        </p>
                    </div>
                </div>

                @if ($isProvisional)
                    <div class="mt-4 p-3 bg-amber-500/20 border border-amber-400/40 rounded-xl text-xs text-amber-100 flex items-start space-x-2">
                        <span class="text-base shrink-0">⚠️</span>
                        <p class="leading-relaxed">
                            Su cuenta cuenta con una <strong>contraseña provisional</strong> asignada por el Administrador. Debe definir una nueva clave personal para habilitar el acceso a los módulos municipales.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Formulario de Cambio -->
            <form action="{{ route('password.update') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Mensajes de Alerta -->
                @if (session('error'))
                    <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-300 dark:border-rose-800 rounded-xl text-xs text-rose-800 dark:text-rose-200 flex items-start space-x-2">
                        <span class="text-base leading-none">❌</span>
                        <div class="flex-1 font-medium">{{ session('error') }}</div>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="p-3.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-800 rounded-xl text-xs text-amber-800 dark:text-amber-200 flex items-start space-x-2">
                        <span class="text-base leading-none">⚠️</span>
                        <div class="flex-1 font-medium">{{ session('warning') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-300 dark:border-rose-800 rounded-xl text-xs text-rose-800 dark:text-rose-200 space-y-1">
                        @foreach ($errors->all() as $err)
                            <div class="flex items-center space-x-1.5">
                                <span>&bull;</span>
                                <span>{{ $err }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Campo Contraseña Actual -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        {{ $isProvisional ? 'Contraseña Provisional Actual *' : 'Contraseña Actual *' }}
                    </label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        required
                        autocomplete="current-password"
                        placeholder="Ingrese la contraseña que usó para entrar"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none transition"
                    />
                </div>

                <!-- Campo Nueva Contraseña -->
                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Nueva Contraseña Personal *
                    </label>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        required
                        minlength="6"
                        autocomplete="new-password"
                        placeholder="Mínimo 6 caracteres"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none transition"
                    />
                </div>

                <!-- Confirmación de Nueva Contraseña -->
                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Confirmar Nueva Contraseña *
                    </label>
                    <input
                        type="password"
                        id="new_password_confirmation"
                        name="new_password_confirmation"
                        required
                        minlength="6"
                        autocomplete="new-password"
                        placeholder="Repita la nueva contraseña"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none transition"
                    />
                </div>

                <!-- Indicaciones de Seguridad -->
                <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700/60 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
                    <p class="font-bold text-slate-700 dark:text-slate-300">Requisitos de seguridad:</p>
                    <p>&bull; Debe tener al menos 6 caracteres.</p>
                    <p>&bull; No debe ser idéntica a su contraseña provisional anterior.</p>
                    <p>&bull; No comparta sus credenciales con terceros bajo ninguna circunstancia.</p>
                </div>

                <!-- Botones de Acción -->
                <div class="pt-4 flex items-center justify-between border-t border-slate-200 dark:border-slate-800">
                    @if (! $isProvisional)
                        <a
                            href="{{ route('portal.launcher') }}"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition"
                        >
                            Cancelar
                        </a>
                    @else
                        <span class="text-[11px] text-slate-400">Paso obligatorio</span>
                    @endif

                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all hover:scale-102 cursor-pointer flex items-center space-x-1.5"
                    >
                        <span>✓</span>
                        <span>Guardar Contraseña y Continuar</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer Institucional -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        Municipalidad Distrital de Castilla &bull; Gerencia de Tecnologías de la Información &bull; 2026
    </footer>

</body>
</html>
