<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema | SIGM Castilla</title>

    <!-- Script anti-parpadeo para tema oscuro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }
            #login-qr-print-sheet, #login-qr-print-sheet * {
                visibility: visible !important;
            }
            #login-qr-print-sheet {
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                display: block !important;
                background: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 1.5rem !important;
                z-index: 99999 !important;
            }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-white dark:bg-slate-950 font-sans text-gray-800 dark:text-gray-100 antialiased selection:bg-emerald-600 selection:text-white transition-colors duration-200">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Panel Izquierdo: Branding Institucional Castilla -->
        <div class="relative hidden md:flex md:w-1/2 bg-[#046A38] text-white flex-col justify-between p-8 lg:p-12 overflow-hidden print:hidden">
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

        <!-- Panel Derecho: Formulario de Login y Mesa de Ayuda con Doble Pestaña -->
        <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-12 relative bg-white dark:bg-slate-900 transition-colors duration-200">
            <!-- Alternador de Tema Top Right -->
            <div class="flex justify-end print:hidden" x-data="themeSwitcher">
                <button type="button"
                        @click="toggle()"
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

            <!-- Contenedor Central con Tabs -->
            <div class="w-full max-w-md mx-auto my-auto space-y-6"
                 x-data="{
                    activeTab: new URLSearchParams(window.location.search).get('tab') === 'helpdesk' ? 'helpdesk' : 'auth',
                    showPass: false,
                    user: 'root',
                    dniInput: new URLSearchParams(window.location.search).get('dni') || '',
                    dniLoading: false,
                    dniError: null,
                    qrResult: null,
                    selectedOfficeManual: '',
                    downloading: false,

                    lookupDni() {
                        const dni = this.dniInput.trim();
                        if (!dni) {
                            this.dniError = 'Por favor ingrese su número de DNI.';
                            return;
                        }
                        if (!/^\d{8}$/.test(dni)) {
                            this.dniError = 'El DNI debe contener exactamente 8 dígitos numéricos.';
                            return;
                        }
                        this.dniError = null;
                        this.dniLoading = true;
                        this.qrResult = null;

                        fetch(`/api/helpdesk/dni-lookup?dni=${encodeURIComponent(dni)}`)
                            .then(r => r.json())
                            .then(data => {
                                this.dniLoading = false;
                                if (data.found) {
                                    this.qrResult = data;
                                    this.dniError = null;
                                } else {
                                    this.dniError = data.message || 'DNI no encontrado en el padrón municipal.';
                                }
                            })
                            .catch(() => {
                                this.dniLoading = false;
                                this.dniError = 'Error de conexión con el servidor. Intente nuevamente.';
                            });
                    },

                    selectManualOffice(officeName) {
                        if (!officeName) return;
                        this.dniLoading = true;
                        this.dniError = null;
                        this.qrResult = null;

                        fetch(`/api/helpdesk/office-qr?office=${encodeURIComponent(officeName)}`)
                            .then(r => r.json())
                            .then(data => {
                                this.dniLoading = false;
                                if (data.found) {
                                    this.qrResult = {
                                        found: true,
                                        person: {
                                            dni: this.dniInput || 'S/DNI',
                                            name: 'Personal de Dependencia',
                                            position: 'Colaborador Municipal',
                                            office: data.office
                                        },
                                        ticket_url: data.ticket_url,
                                        qr_url: data.qr_url
                                    };
                                }
                            })
                            .catch(() => {
                                this.dniLoading = false;
                            });
                    },

                    downloadImage(url, name) {
                        this.downloading = true;
                        fetch(url)
                            .then(r => r.blob())
                            .then(blob => {
                                const blobUrl = URL.createObjectURL(blob);
                                const a = document.createElement('a');
                                a.href = blobUrl;
                                a.download = 'QR_Oficina_' + (name || 'Oficina').replace(/[^a-zA-Z0-9_-]/g, '_') + '.png';
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                URL.revokeObjectURL(blobUrl);
                                this.downloading = false;
                            })
                            .catch(() => {
                                this.downloading = false;
                                window.open(url, '_blank');
                            });
                    },

                    printSign() {
                        window.print();
                    },

                    resetDni() {
                        this.qrResult = null;
                        this.dniInput = '';
                        this.dniError = null;
                        this.selectedOfficeManual = '';
                    },

                    init() {
                        if (this.activeTab === 'helpdesk' && this.dniInput && !this.qrResult) {
                            this.$nextTick(() => this.lookupDni());
                        }
                    }
                 }">

                <!-- Branding Cabecera Form -->
                <div class="text-center space-y-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Escudo de Castilla" class="h-14 w-auto mx-auto object-contain">
                    <span class="block text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">MUNICIPALIDAD DE CASTILLA</span>
                    
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight" x-show="activeTab === 'auth'">
                        Entre a su cuenta
                    </h1>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight" x-show="activeTab === 'helpdesk'" x-cloak>
                        Mesa de Ayuda TI
                    </h1>

                    <p class="text-xs text-gray-500 dark:text-gray-400" x-show="activeTab === 'auth'">
                        ¿No tienes una cuenta? <a href="#" class="text-emerald-700 dark:text-emerald-400 font-semibold hover:underline">Solicitar Registro de Usuario</a>
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400" x-show="activeTab === 'helpdesk'" x-cloak>
                        Reporte de incidencias y soporte técnico para su puesto de trabajo
                    </p>
                </div>

                @if (session('info'))
                    <div class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/80 text-amber-950 dark:text-amber-200 text-xs font-medium flex items-start gap-2.5 shadow-xs animate-in fade-in duration-200">
                        <span class="text-lg leading-none shrink-0 mt-0.5">ℹ️</span>
                        <div class="space-y-0.5">
                            <span class="font-bold text-amber-900 dark:text-amber-100 text-xs block">Acceso a Mesa de Ayuda</span>
                            <span class="text-[11px] leading-relaxed text-amber-800 dark:text-amber-300 block">{{ session('info') }}</span>
                        </div>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/80 text-amber-950 dark:text-amber-200 text-xs font-medium flex items-start gap-2.5 shadow-xs animate-in fade-in duration-200">
                        <span class="text-lg leading-none shrink-0 mt-0.5">⚠️</span>
                        <div class="space-y-0.5">
                            <span class="font-bold text-amber-900 dark:text-amber-100 text-xs block">Aviso Institucional</span>
                            <span class="text-[11px] leading-relaxed text-amber-800 dark:text-amber-300 block">{{ session('warning') }}</span>
                        </div>
                    </div>
                @endif

                <!-- SELECTOR DE PESTAÑAS (DUAL TABS) -->
                <div class="grid grid-cols-2 p-1 bg-gray-100 dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 text-xs font-bold">
                    <!-- Pestaña 1: Acceso a Módulos -->
                    <button
                        type="button"
                        @click="activeTab = 'auth'"
                        class="py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition-all cursor-pointer"
                        :class="activeTab === 'auth' ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-sm border border-gray-200/80 dark:border-slate-700' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200'"
                    >
                        <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Acceso Módulos</span>
                    </button>

                    <!-- Pestaña 2: Mesa de Ayuda -->
                    <button
                        type="button"
                        @click="activeTab = 'helpdesk'"
                        class="py-2.5 px-3 rounded-xl flex items-center justify-center gap-2 transition-all cursor-pointer"
                        :class="activeTab === 'helpdesk' ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-sm border border-gray-200/80 dark:border-slate-700' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200'"
                    >
                        <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Mesa de Ayuda</span>
                    </button>
                </div>

                <!-- CONTENIDO PESTAÑA 1: INICIAR SESIÓN A MÓDULOS AUTORIZADOS -->
                <div x-show="activeTab === 'auth'" class="space-y-4">
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
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
                                    class="block w-full px-3.5 py-2.5 pr-10 text-sm bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg shadow-xs focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 dark:focus:ring-emerald-500 font-medium"
                                >
                                <button
                                    type="button"
                                    @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none cursor-pointer"
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

                <!-- CONTENIDO PESTAÑA 2: INGRESO A GENERAR TICKET DE MESA DE AYUDA (POR DNI / QR) -->
                <div x-show="activeTab === 'helpdesk'" x-cloak class="space-y-4">
                    <!-- ESTADO 1: FORMULARIO SOLO PIDE EL DNI -->
                    <template x-if="!qrResult">
                        <div class="space-y-4">
                            <div class="p-4 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-800/60 text-xs text-emerald-900 dark:text-emerald-200 leading-relaxed space-y-1">
                                <p class="font-bold flex items-center gap-1.5 text-emerald-800 dark:text-emerald-300">
                                    <span>🎫</span>
                                    <span>Reporte Rápido de Incidencias en su Puesto</span>
                                </p>
                                <p class="text-[11px] opacity-90">
                                    Ingrese su DNI para identificar su oficina asignada, obtener el código QR institucional para escanear con su celular o imprimir el cartel de su dependencia.
                                </p>
                            </div>

                            <!-- Input DNI -->
                            <div class="space-y-1.5">
                                <label for="helpdesk_dni" class="block text-xs font-bold text-gray-700 dark:text-gray-300">
                                    DNI del Usuario / Servidor Municipal <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        id="helpdesk_dni"
                                        type="text"
                                        maxlength="8"
                                        x-model="dniInput"
                                        @keydown.enter.prevent="lookupDni()"
                                        placeholder="Ingrese 8 dígitos (ej. 10783864)"
                                        class="block w-full px-4 py-3 text-base font-mono font-bold tracking-wider bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl shadow-xs focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 dark:focus:ring-emerald-500"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Sugerencias de DNI para prueba rápida -->
                                <div class="pt-1 flex flex-wrap items-center gap-1.5 text-[10px]">
                                    <span class="text-gray-400 dark:text-gray-500 font-medium">Ejemplos:</span>
                                    <button type="button" @click="dniInput = '10783864'; lookupDni()" class="px-2 py-0.5 rounded-md bg-gray-100 hover:bg-emerald-50 dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-emerald-300 cursor-pointer">
                                        10783864 (Soporte TI)
                                    </button>
                                    <button type="button" @click="dniInput = '02821801'; lookupDni()" class="px-2 py-0.5 rounded-md bg-gray-100 hover:bg-emerald-50 dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-emerald-300 cursor-pointer">
                                        02821801 (Procuraduría)
                                    </button>
                                    <button type="button" @click="dniInput = '43268255'; lookupDni()" class="px-2 py-0.5 rounded-md bg-gray-100 hover:bg-emerald-50 dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:border-emerald-300 cursor-pointer">
                                        43268255 (Transportes)
                                    </button>
                                </div>
                            </div>

                            <!-- Mensaje de Error / Selección Alternativa -->
                            <template x-if="dniError">
                                <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs space-y-2">
                                    <div class="flex items-center gap-2 font-semibold">
                                        <span class="font-bold">✕</span>
                                        <span x-text="dniError"></span>
                                    </div>
                                    <div class="pt-1 border-t border-rose-200/60 dark:border-rose-800/60">
                                        <label class="block text-[11px] font-bold text-rose-900 dark:text-rose-200 mb-1">
                                            O elija su dependencia directamente para generar su ticket:
                                        </label>
                                        <select
                                            x-model="selectedOfficeManual"
                                            @change="selectManualOffice($event.target.value)"
                                            class="w-full text-xs p-2 rounded-lg bg-white dark:bg-slate-900 border border-rose-300 dark:border-rose-700 text-gray-900 dark:text-gray-100 font-medium">
                                            <option value="">-- Seleccionar Oficina Municipal --</option>
                                            @if (isset($offices) && is_array($offices))
                                                @foreach ($offices as $off)
                                                    <option value="{{ $off['name'] }}">{{ $off['name'] }} ({{ $off['sigla'] }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </template>

                            <!-- Botón Entrar -->
                            <button
                                type="button"
                                @click="lookupDni()"
                                :disabled="dniLoading"
                                class="w-full flex items-center justify-center gap-2 bg-[#046A38] hover:bg-emerald-800 disabled:opacity-50 text-white py-3 px-4 rounded-xl text-sm font-bold shadow-md transition-all cursor-pointer">
                                <span x-show="!dniLoading">Entrar</span>
                                <span x-show="dniLoading" x-cloak class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Verificando DNI...</span>
                                </span>
                            </button>

                            <!-- Acceso directo alternativo por oficinas -->
                            <div class="text-center pt-2">
                                <button
                                    type="button"
                                    @click="dniError = 'Seleccione su oficina a continuación:'; selectedOfficeManual = ''"
                                    class="text-xs text-emerald-700 dark:text-emerald-400 font-medium hover:underline cursor-pointer">
                                    ¿No recuerda su DNI? Elegir oficina de la lista &rarr;
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- ESTADO 2: DNI ENCONTRADO - MUESTRA QR PROPIO DE LA OFICINA -->
                    <template x-if="qrResult">
                        <div class="space-y-4 animate-in fade-in zoom-in-95 duration-200">
                            <!-- Tarjeta de Identificación del Servidor Municipal -->
                            <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-left space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/80 text-[10px] font-bold text-emerald-800 dark:text-emerald-300">
                                        ✓ Servidor Identificado
                                    </span>
                                    <span class="text-[11px] font-mono font-bold text-emerald-700 dark:text-emerald-400" x-text="'DNI: ' + qrResult.person.dni"></span>
                                </div>
                                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white uppercase leading-snug" x-text="qrResult.person.name"></h3>
                                <div class="text-xs text-emerald-950 dark:text-emerald-200 font-semibold flex items-center gap-1.5 pt-0.5">
                                    <span>🏢</span>
                                    <span x-text="qrResult.person.office"></span>
                                </div>
                            </div>

                            <!-- CONTENEDOR DEL CÓDIGO QR CLICABLE -->
                            <div class="text-center space-y-2">
                                <a :href="qrResult.ticket_url"
                                   class="group block relative p-4 bg-white dark:bg-slate-800 rounded-3xl border-2 border-slate-200 dark:border-slate-700 shadow-md hover:border-emerald-500 hover:shadow-xl transition-all cursor-pointer"
                                   title="Haga clic para abrir el formulario de ticket en este navegador">
                                    <div class="relative inline-block">
                                        <img :src="qrResult.qr_url"
                                             alt="Código QR de Oficina para Mesa de Ayuda"
                                             class="w-56 h-56 mx-auto object-contain transition-transform group-hover:scale-105 duration-200 select-none">
                                        
                                        <!-- Overlay badge al hacer hover -->
                                        <div class="absolute inset-x-0 bottom-1 opacity-0 group-hover:opacity-100 transition-opacity flex justify-center">
                                            <span class="px-2.5 py-1 bg-emerald-700 text-white text-[10px] font-bold rounded-full shadow-lg">
                                                🖱️ Clic para abrir aquí
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-2 text-center">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-400 group-hover:underline">
                                            <span>Abrir Formulario de Ticket</span>
                                            <span>&rarr;</span>
                                        </span>
                                    </div>
                                </a>

                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                                    📱 <strong>Escanee con su celular</strong> para enviar el ticket o <strong>haga clic en el QR</strong> para abrirlo directamente en esta computadora.
                                </p>
                            </div>

                            <!-- ACCIONES: IMPRIMIR QR Y DESCARGAR IMAGEN -->
                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <!-- Botón Imprimir QR -->
                                <button
                                    type="button"
                                    @click="printSign()"
                                    class="w-full flex items-center justify-center gap-2 py-2.5 px-3 bg-[#046A38] hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md transition-all cursor-pointer"
                                    title="Imprimir cartel para pegar en la oficina">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    <span>Imprimir QR</span>
                                </button>

                                <!-- Botón Descargar Imagen -->
                                <button
                                    type="button"
                                    @click="downloadImage(qrResult.qr_url, qrResult.person.office)"
                                    :disabled="downloading"
                                    class="w-full flex items-center justify-center gap-2 py-2.5 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-all cursor-pointer"
                                    title="Descargar imagen PNG para enviar por WhatsApp o correo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span x-show="!downloading">Descargar QR</span>
                                    <span x-show="downloading" x-cloak>Descargando...</span>
                                </button>
                            </div>

                            <!-- Botón para consultar otro DNI -->
                            <div class="text-center pt-2">
                                <button
                                    type="button"
                                    @click="resetDni()"
                                    class="text-xs text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors cursor-pointer">
                                    &larr; Consultar otro DNI o dependencia
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

            </div>

            <!-- Footer Derecho -->
            <div class="text-center text-[11px] text-gray-400 dark:text-gray-500 mt-6 print:hidden">
                Oficina de Desarrollo Tecnológico &bull; Municipalidad Distrital de Castilla
            </div>
        </div>
    </div>

    <!-- PLANTILLA IMPRIMIBLE INSTITUCIONAL PARA CARTEL DE OFICINA (SOLO VISIBLE AL IMPRIMIR) -->
    <div id="login-qr-print-sheet" class="hidden print:block fixed inset-0 bg-white text-black font-sans z-[99999]" x-data="{}" x-show="qrResult">
        <template x-if="qrResult">
            <div class="max-w-xl mx-auto border-4 border-[#046A38] rounded-3xl p-8 text-center space-y-5">
                <div class="flex items-center justify-center gap-4 border-b-2 border-emerald-800 pb-4">
                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto object-contain" alt="Castilla">
                    <div class="text-left">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">MUNICIPALIDAD DISTRITAL DE CASTILLA</h4>
                        <h1 class="text-2xl font-black text-slate-900">MESA DE AYUDA Y SOPORTE TI</h1>
                        <p class="text-xs text-slate-600 font-semibold">Subgerencia de Informática y Estadística &bull; ODT</p>
                    </div>
                </div>

                <div class="py-2">
                    <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-900 font-mono font-bold text-xs rounded-full uppercase mb-2">
                        DEPENDENCIA MUNICIPAL
                    </span>
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight" x-text="qrResult.person.office"></h2>
                    <p class="text-xs text-slate-600 font-medium mt-1">
                        Servidor asignado: <strong x-text="qrResult.person.name"></strong> &bull; DNI: <span x-text="qrResult.person.dni"></span>
                    </p>
                </div>

                <div class="p-4 bg-white border-2 border-slate-300 rounded-2xl inline-block shadow-sm">
                    <img :src="qrResult.qr_url" alt="QR Mesa de Ayuda" class="w-64 h-64 mx-auto object-contain">
                </div>

                <div class="bg-emerald-50 rounded-2xl p-4 text-left text-xs text-emerald-950 space-y-2 border border-emerald-200">
                    <h4 class="font-bold text-emerald-900 uppercase tracking-wide text-center text-sm mb-1">
                        ¿CÓMO GENERAR SU TICKET DE ATENCIÓN TÉCNICA?
                    </h4>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="p-2.5 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-base font-black text-emerald-700">1</span>
                            <span class="text-[11px] font-semibold text-slate-800">Abra la cámara del celular y apunte al QR</span>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-base font-black text-emerald-700">2</span>
                            <span class="text-[11px] font-semibold text-slate-800">Describa la falla en su equipo o sistema</span>
                        </div>
                        <div class="p-2.5 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-base font-black text-emerald-700">3</span>
                            <span class="text-[11px] font-semibold text-slate-800">Soporte TI atenderá su requerimiento</span>
                        </div>
                    </div>
                </div>

                <div class="text-[10px] text-slate-500 border-t border-slate-200 pt-3 flex justify-between items-center">
                    <span>Sistema Integrado de Gestión Municipal - SIGM Castilla</span>
                    <span class="font-bold">Pegar en un lugar visible de la oficina para uso común</span>
                </div>
            </div>
        </template>
    </div>

    @livewireScripts
</body>
</html>
