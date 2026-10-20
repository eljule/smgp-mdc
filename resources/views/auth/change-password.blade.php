<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isProvisional ? 'Cambio Obligatorio de Contraseña' : 'Actualización de Contraseña' }} | SIGM Castilla</title>

    <!-- Script anti-parpadeo para tema oscuro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .brand-gradient {
            background: linear-gradient(135deg, #046A38 0%, #065f46 60%, #064e3b 100%);
        }
        .brand-bg-card {
            background: linear-gradient(135deg, #046A38 0%, #057a41 50%, #034f2a 100%);
        }
    </style>
</head>
<body class="min-h-full bg-slate-100 dark:bg-slate-950 font-sans text-slate-800 dark:text-slate-100 antialiased flex flex-col justify-between selection:bg-emerald-600 selection:text-white transition-colors duration-200">

    <!-- Header Institucional Superior -->
    <header class="brand-gradient text-white py-3.5 px-6 border-b border-emerald-900 shadow-md">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3.5">
                <img src="{{ asset('images/logo.png') }}" alt="Escudo de Castilla" class="h-10 w-auto object-contain drop-shadow-sm">
                <div>
                    <h1 class="text-xs sm:text-sm font-black tracking-wider leading-tight text-white uppercase">
                        MUNICIPALIDAD DISTRITAL DE CASTILLA
                    </h1>
                    <p class="text-[11px] text-emerald-200 font-medium">
                        SIGM &bull; Seguridad de Cuentas y Control de Accesos
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2.5">
                <!-- Selector de Tema Claro / Oscuro -->
                <button
                    type="button"
                    onclick="window.toggleTheme()"
                    class="p-2 rounded-xl bg-emerald-900/60 hover:bg-emerald-900 text-emerald-200 hover:text-white border border-emerald-700/60 transition cursor-pointer text-xs flex items-center gap-1.5"
                    title="Alternar modo oscuro / claro"
                >
                    <span class="dark:hidden">🌙</span>
                    <span class="hidden dark:inline">☀️</span>
                </button>

                <!-- Cerrar Sesión -->
                <a
                    href="{{ route('logout') }}"
                    class="px-3.5 py-1.5 rounded-xl bg-emerald-950/70 hover:bg-emerald-950 text-xs font-semibold text-emerald-100 hover:text-white border border-emerald-700/70 transition-colors shadow-xs"
                >
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <!-- Contenedor Principal del Formulario -->
    <main
        class="flex-1 flex items-center justify-center p-4 sm:p-6 my-6"
        x-data="{
            currentPassword: '',
            newPassword: '',
            confirmPassword: '',
            showCurrent: false,
            showNew: false,
            showConfirm: false,
            get hasMinLength() { return this.newPassword.length >= 6; },
            get passwordsMatch() { return this.newPassword.length > 0 && this.newPassword === this.confirmPassword; },
            get isDifferent() { return this.newPassword.length > 0 && this.newPassword !== this.currentPassword; },
            get canSubmit() { return this.hasMinLength && this.passwordsMatch && this.currentPassword.length > 0; }
        }"
    >
        <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden transition-all">
            
            <!-- Banner Superior del Card -->
            <div class="brand-bg-card p-6 text-white relative">
                <!-- Brillo sutil decorativo -->
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex items-start space-x-4">
                    <div class="w-13 h-13 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center text-2xl border border-white/25 shadow-inner shrink-0">
                        🔑
                    </div>
                    <div class="flex-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/20 border border-white/30 text-[10px] font-bold text-white uppercase tracking-wider mb-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                            <span>{{ $isProvisional ? 'Primer Acceso al Sistema' : 'Seguridad de la Cuenta' }}</span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-black tracking-tight text-white leading-snug">
                            {{ $isProvisional ? 'Definir Nueva Contraseña Personal' : 'Actualización de Contraseña Personal' }}
                        </h2>
                        <p class="text-xs text-emerald-100 font-normal mt-0.5 leading-relaxed">
                            Por su seguridad, establezca una clave secreta que solo usted conozca.
                        </p>
                    </div>
                </div>

                <!-- Tarjeta de Identidad del Usuario Logueado -->
                <div class="relative z-10 mt-4 p-3 rounded-2xl bg-black/20 backdrop-blur-sm border border-white/15 flex items-center justify-between gap-3">
                    <div class="flex items-center space-x-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-white/20 text-white font-bold text-xs flex items-center justify-center shrink-0 border border-white/25">
                            {{ $user->initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-white truncate">
                                {{ $user->full_name ?: $user->display_title }}
                            </p>
                            <p class="text-[11px] text-emerald-200 truncate">
                                Usuario: <span class="font-mono font-semibold text-white">{{ $user->username }}</span>
                                @if (!empty($user->person?->document_number) && $user->person->document_number !== $user->username)
                                    &bull; DNI: <span class="font-mono text-white">{{ $user->person->document_number }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <span class="inline-block px-2 py-1 rounded-lg bg-emerald-950/80 border border-emerald-400/40 text-[10px] font-bold text-emerald-200 shrink-0">
                        {{ $user->display_role }}
                    </span>
                </div>
            </div>

            <!-- Formulario de Cambio de Contraseña -->
            <form action="{{ route('password.update') }}" method="POST" class="p-6 sm:p-7 space-y-4.5">
                @csrf

                <!-- Banner de Aviso Único (Alto Contraste y Redacción Clara) -->
                @if ($isProvisional)
                    <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border border-amber-300 dark:border-amber-700/80 flex items-start space-x-3 text-amber-900 dark:text-amber-100">
                        <span class="text-xl shrink-0 mt-0.5">⚠️</span>
                        <div class="text-xs leading-relaxed">
                            <strong class="font-bold block text-amber-950 dark:text-amber-200 mb-0.5">
                                Contraseña Provisional Activa
                            </strong>
                            <span>
                                Su cuenta tiene actualmente con una clave temporal asignada por el Administrador. Para activar su acceso a los módulos municipales, defina su nueva contraseña privada.
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Errores de Validación o Mensajes Flash -->
                @if (session('error'))
                    <div class="p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800 text-xs text-rose-900 dark:text-rose-100 flex items-start space-x-2.5">
                        <span class="text-base leading-none shrink-0 mt-0.5">❌</span>
                        <div class="flex-1 font-semibold">{{ session('error') }}</div>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-300 dark:border-rose-800 text-xs text-rose-900 dark:text-rose-100 space-y-1">
                        <p class="font-bold flex items-center gap-1 text-rose-950 dark:text-rose-200">
                            <span>❌</span>
                            <span>Por favor corrija los siguientes errores:</span>
                        </p>
                        <ul class="list-disc list-inside space-y-0.5 pl-2 font-medium">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Campo: Contraseña Actual / Provisional -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        {{ $isProvisional ? 'Contraseña Provisional Actual *' : 'Contraseña Actual *' }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            :type="showCurrent ? 'text' : 'password'"
                            id="current_password"
                            name="current_password"
                            x-model="currentPassword"
                            required
                            autocomplete="current-password"
                            placeholder="Ingrese la contraseña que usó para entrar"
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/90 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition shadow-xs"
                        />
                        <button
                            type="button"
                            @click="showCurrent = !showCurrent"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer transition-colors"
                            tabindex="-1"
                            title="Alternar visibilidad"
                        >
                            <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showCurrent" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Campo: Nueva Contraseña Personal -->
                <div>
                    <label for="new_password" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Nueva Contraseña Personal *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <input
                            :type="showNew ? 'text' : 'password'"
                            id="new_password"
                            name="new_password"
                            x-model="newPassword"
                            required
                            minlength="6"
                            autocomplete="new-password"
                            placeholder="Mínimo 6 caracteres"
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/90 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition shadow-xs"
                        />
                        <button
                            type="button"
                            @click="showNew = !showNew"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer transition-colors"
                            tabindex="-1"
                            title="Alternar visibilidad"
                        >
                            <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showNew" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Campo: Confirmar Nueva Contraseña -->
                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Confirmar Nueva Contraseña *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input
                            :type="showConfirm ? 'text' : 'password'"
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            x-model="confirmPassword"
                            required
                            minlength="6"
                            autocomplete="new-password"
                            placeholder="Repita la nueva contraseña"
                            class="w-full pl-10 pr-11 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800/90 text-slate-900 dark:text-white text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition shadow-xs"
                        />
                        <button
                            type="button"
                            @click="showConfirm = !showConfirm"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer transition-colors"
                            tabindex="-1"
                            title="Alternar visibilidad"
                        >
                            <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Lista de Requisitos Interactiva en Tiempo Real -->
                <div class="p-3.5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/70 text-xs space-y-1.5">
                    <p class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <span>🛡️</span>
                        <span>Requisitos de seguridad:</span>
                    </p>
                    <div class="space-y-1 text-[11px]">
                        <p class="flex items-center space-x-2" :class="hasMinLength ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-500 dark:text-slate-400'">
                            <span x-text="hasMinLength ? '✓' : '•'" class="font-bold"></span>
                            <span>Debe contener al menos 6 caracteres</span>
                        </p>
                        <p class="flex items-center space-x-2" :class="passwordsMatch ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-500 dark:text-slate-400'">
                            <span x-text="passwordsMatch ? '✓' : '•'" class="font-bold"></span>
                            <span>Ambas contraseñas deben ser exactamente idénticas</span>
                        </p>
                        <p class="flex items-center space-x-2" :class="isDifferent ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-500 dark:text-slate-400'">
                            <span x-text="isDifferent ? '✓' : '•'" class="font-bold"></span>
                            <span>Debe ser diferente a su contraseña provisional actual</span>
                        </p>
                    </div>
                </div>

                <!-- Barra de Acciones Final -->
                <div class="pt-4 flex items-center justify-between border-t border-slate-200 dark:border-slate-800">
                    @if (! $isProvisional)
                        <a
                            href="{{ route('portal.launcher') }}"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer"
                        >
                            Cancelar y Volver
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Paso obligatorio</span>
                        </span>
                    @endif

                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl text-white font-bold text-xs shadow-md shadow-emerald-900/20 transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer flex items-center space-x-2"
                        style="background-color: #046A38;"
                        onmouseover="this.style.backgroundColor='#03532c'"
                        onmouseout="this.style.backgroundColor='#046A38'"
                    >
                        <span>✓</span>
                        <span>Guardar Contraseña y Continuar</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer Institucional -->
    <footer class="py-4 text-center text-xs text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        Municipalidad Distrital de Castilla &bull; Gerencia de Tecnologías de la Información &bull; 2026
    </footer>

</body>
</html>
