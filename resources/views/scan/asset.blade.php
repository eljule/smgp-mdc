<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Ficha Técnica: {{ $asset->computer_code }} | SIGM Castilla</title>

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
<body class="min-h-screen bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased flex flex-col justify-between selection:bg-emerald-600 selection:text-white">

    <!-- Header Móvil Institucional -->
    <header class="bg-[#0d4f2f] dark:bg-slate-900 text-white border-b border-emerald-900 sticky top-0 z-40 shadow-md">
        <div class="max-w-xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Escudo de Castilla"
                     class="h-9 w-auto object-contain drop-shadow" />
                <div class="flex flex-col">
                    <span class="text-[11px] sm:text-xs font-bold tracking-wider uppercase text-emerald-200 leading-tight">
                        MUNICIPALIDAD DE CASTILLA
                    </span>
                    <span class="text-xs sm:text-sm font-extrabold text-white leading-tight">
                        SIGM <span class="font-normal text-emerald-200">| Ficha Técnica QR</span>
                    </span>
                </div>
            </div>

            <!-- Botones Header (Tema y Portal) -->
            <div class="flex items-center gap-2">
                <!-- Theme Switcher -->
                <div x-data="themeSwitcher">
                    <button type="button"
                            @click="toggle()"
                            class="p-2 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 text-emerald-100 hover:text-white transition-colors focus:outline-none cursor-pointer"
                            title="Alternar modo oscuro">
                        <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>

                <a href="{{ route('portal.launcher') }}"
                   class="px-2.5 py-1.5 rounded-lg bg-emerald-800/80 hover:bg-emerald-700 text-[11px] font-semibold text-emerald-100 hover:text-white border border-emerald-600/40 transition-colors">
                    Portal
                </a>
            </div>
        </div>
    </header>

    @php
        $statusClasses = match ($asset->status) {
            'OPERATIVO', 'DISPONIBLE' => 'bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-800',
            'ASIGNADO' => 'bg-blue-100 dark:bg-blue-950/80 text-blue-800 dark:text-blue-300 border-blue-300 dark:border-blue-800',
            'MANTENIMIENTO', 'EN_MANTENIMIENTO' => 'bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border-amber-300 dark:border-amber-800',
            'EN_CUSTODIA' => 'bg-indigo-100 dark:bg-indigo-950/80 text-indigo-800 dark:text-indigo-300 border-indigo-300 dark:border-indigo-800',
            'EN_DESUSO', 'PARA_BAJA', 'BAJA' => 'bg-rose-100 dark:bg-rose-950/80 text-rose-800 dark:text-rose-300 border-rose-300 dark:border-rose-800',
            default => 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-300 border-slate-300',
        };

        $conditionClasses = match ($asset->physical_condition) {
            'EXCELENTE', 'BUENO', 'OPERATIVO' => 'text-emerald-600 dark:text-emerald-400 font-bold',
            'REGULAR' => 'text-amber-600 dark:text-amber-400 font-bold',
            'DEFECTUOSO', 'INOPERATIVO', 'MALO' => 'text-rose-600 dark:text-rose-400 font-bold',
            default => 'text-slate-600 dark:text-slate-400',
        };

        $categoryIcon = match (strtolower($asset->category?->name ?? '')) {
            'computadoras de escritorio', 'desktop', 'pc', 'all in one' => '🖥️',
            'laptops', 'portátiles', 'laptop' => '💻',
            'monitores', 'monitor', 'pantalla' => '🖥️',
            'impresoras', 'impresora', 'multifuncional' => '🖨️',
            'servidores', 'servidor' => '🗄️',
            'switches', 'routers', 'redes' => '🌐',
            'teclados', 'teclado', 'mouse' => '⌨️',
            default => '📦',
        };

        $specs = is_array($asset->specifications)
            ? $asset->specifications
            : (json_decode($asset->specifications ?? '[]', true) ?: []);
    @endphp

    <!-- Contenido Principal -->
    <main class="flex-1 max-w-xl w-full mx-auto p-4 sm:p-6 space-y-4">

        <!-- 1. Card Cabecera del Activo -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 relative overflow-hidden">
            <!-- Acento de color superior -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-700"></div>

            <div class="flex items-start justify-between gap-3 pt-1">
                <div class="flex items-start gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center text-3xl shrink-0 shadow-inner">
                        {{ $categoryIcon }}
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">
                            {{ $asset->category?->name ?? 'Activo Tecnológico' }}
                        </span>
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white leading-tight">
                            {{ $asset->brand?->name ?? '' }} {{ $asset->model?->name ?? 'Equipo sin modelo registrado' }}
                        </h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Color: <span class="font-medium text-slate-700 dark:text-slate-300">{{ $asset->color ?? 'Estándar' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Badge Estado -->
                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border shrink-0 {{ $statusClasses }}">
                    {{ str_replace('_', ' ', $asset->status ?? 'DISPONIBLE') }}
                </span>
            </div>

            <!-- Identificadores Clave (Códigos) -->
            <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800/80 grid grid-cols-2 gap-2 text-xs">
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 block">
                        Cód. Informático TI
                    </span>
                    <span class="font-mono font-extrabold text-emerald-700 dark:text-emerald-400 text-xs sm:text-sm">
                        {{ $asset->computer_code }}
                    </span>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 block">
                        Cód. Patrimonial SBN
                    </span>
                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm">
                        {{ $asset->patrimonial_code ?: 'SIN REGISTRAR' }}
                    </span>
                </div>
            </div>

            <!-- Serie y Condición Física -->
            <div class="mt-2 grid grid-cols-2 gap-2 text-xs">
                <div class="p-2 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                    <span class="text-[10px] text-slate-400 block font-medium">N° Serie:</span>
                    <span class="font-mono text-slate-700 dark:text-slate-300 text-[11px] font-semibold truncate block">
                        {{ $asset->serial_number ?: 'N/D' }}
                    </span>
                </div>
                <div class="p-2 rounded-lg bg-slate-50/70 dark:bg-slate-800/40">
                    <span class="text-[10px] text-slate-400 block font-medium">Condición Física:</span>
                    <span class="text-[11px] {{ $conditionClasses }} block uppercase">
                        {{ $asset->physical_condition ?? 'OPERATIVO' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Card Ubicación y Custodio Responsable -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                <span>📍</span>
                <span>Ubicación y Asignación Institucional</span>
            </h2>

            <div class="space-y-3 text-xs">
                <!-- Oficina -->
                <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                        🏢
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] text-slate-400 font-medium block">Dependencia / Oficina</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm block">
                            {{ $asset->office ?: 'ALMACÉN CENTRAL DE TI (SIN ASIGNACIÓN ACTIVA)' }}
                        </span>
                    </div>
                </div>

                <!-- Custodio / Funcionario Responsable -->
                <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-950/80 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0 font-bold text-xs uppercase">
                        {{ $asset->assignedPerson ? substr($asset->assignedPerson->first_name, 0, 1) . substr($asset->assignedPerson->paternal_surname, 0, 1) : '👤' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] text-slate-400 font-medium block">Funcionario / Custodio Responsable</span>
                        @if ($asset->assignedPerson)
                            <span class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm block">
                                {{ $asset->assignedPerson->full_name }}
                            </span>
                            @if ($asset->assignedPerson->position)
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block">
                                    Cargo: {{ $asset->assignedPerson->position }}
                                </span>
                            @endif
                            @if ($asset->assignedPerson->email || $asset->assignedPerson->phone)
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    {{ $asset->assignedPerson->email }} {{ $asset->assignedPerson->phone ? '• Tel: ' . $asset->assignedPerson->phone : '' }}
                                </span>
                            @endif
                        @else
                            <span class="font-semibold text-slate-500 dark:text-slate-400 text-xs italic block">
                                Sin trabajador asignado (En custodia de ODT)
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Card Especificaciones de Hardware -->
        @if (!empty($specs))
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <span>⚙️</span>
                    <span>Especificaciones Técnicas</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                    @foreach ($specs as $key => $val)
                        @if (!empty($val))
                            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 flex justify-between items-center gap-2">
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 uppercase font-semibold">
                                    {{ str_replace('_', ' ', $key) }}:
                                </span>
                                <span class="font-bold text-slate-800 dark:text-slate-200 text-right truncate">
                                    {{ is_array($val) ? json_encode($val) : $val }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4. Equipo Padre (Si este activo es componente de una PC principal) -->
        @if ($asset->parentAsset)
            <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 dark:from-emerald-950/30 dark:via-slate-900 dark:to-teal-950/30 p-4 rounded-3xl border border-emerald-200 dark:border-emerald-800/60 shadow-sm flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[10px] text-emerald-700 dark:text-emerald-400 uppercase font-bold tracking-wider block">
                        Parte de Equipo Principal
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate block">
                        {{ $asset->parentAsset->brand?->name }} {{ $asset->parentAsset->model?->name }}
                    </span>
                    <span class="text-[11px] font-mono text-emerald-600 dark:text-emerald-400">
                        Cód: {{ $asset->parentAsset->computer_code }}
                    </span>
                </div>

                <a href="{{ url('/scan/activo/' . $asset->parentAsset->computer_code) }}"
                   class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors shrink-0">
                    Ver CPU ↗
                </a>
            </div>
        @endif

        <!-- 5. Componentes y Periféricos Vinculados -->
        @if ($asset->childAssets && $asset->childAssets->count() > 0)
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <span>🔌</span>
                    <span>Periféricos y Componentes Conectados ({{ $asset->childAssets->count() }})</span>
                </h2>

                <div class="space-y-2 text-xs">
                    @foreach ($asset->childAssets as $child)
                        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 uppercase">
                                    {{ $child->category?->name ?? 'Periférico' }}
                                </span>
                                <span class="font-bold text-slate-800 dark:text-slate-200 text-xs block mt-1 truncate">
                                    {{ $child->brand?->name }} {{ $child->model?->name }}
                                </span>
                                <span class="font-mono text-[10px] text-slate-400">
                                    Cód: {{ $child->computer_code }} {{ $child->serial_number ? '| S/N: ' . $child->serial_number : '' }}
                                </span>
                            </div>

                            <a href="{{ url('/scan/activo/' . $child->computer_code) }}"
                               class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline shrink-0 p-1.5">
                                Ver Ficha &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 6. Software y Licencias -->
        @if ($asset->software && $asset->software->count() > 0)
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <span>💾</span>
                    <span>Software Corporativo Instalado</span>
                </h2>

                <div class="space-y-2 text-xs">
                    @foreach ($asset->software as $sw)
                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $sw->name }}</span>
                                <span class="text-[10px] text-slate-400 block">Tipo: {{ $sw->license_type ?? 'Corporativo' }} • Versión: {{ $sw->version ?? 'N/D' }}</span>
                            </div>
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                Autorizado
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 7. Historial Técnico Reciente -->
        @if ($asset->maintenanceOrders && $asset->maintenanceOrders->count() > 0)
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 space-y-3">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <span>🛠️</span>
                    <span>Últimas Intervenciones de Mantenimiento</span>
                </h2>

                <div class="space-y-2 text-xs">
                    @foreach ($asset->maintenanceOrders as $order)
                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between text-[11px] mb-1">
                                <span class="font-mono font-bold text-emerald-700 dark:text-emerald-400">{{ $order->order_number ?? 'OT' }}</span>
                                <span class="text-slate-400 text-[10px]">{{ $order->created_at?->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-slate-700 dark:text-slate-300 font-medium text-[11px] leading-tight">
                                {{ $order->work_description ?? $order->diagnostic ?? 'Mantenimiento Preventivo' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Botones de Acción Móvil -->
        <div class="pt-2 pb-6 space-y-2.5">
            <!-- Botón Primario: Reportar Incidencia -->
            <a href="{{ route('soporte.nuevo', ['asset' => $asset->computer_code]) }}"
               class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 active:scale-[0.99] text-white font-extrabold text-sm shadow-md transition-all flex items-center justify-center gap-2 text-center cursor-pointer">
                <span>⚠️</span>
                <span>Reportar Falla o Solicitar Soporte</span>
            </a>

            <!-- Botón Secundario: Portal de Mesa de Ayuda -->
            <div class="flex items-center gap-2">
                <a href="{{ route('soporte.portal') }}"
                   class="flex-1 py-2.5 px-3 rounded-xl bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs border border-slate-200 dark:border-slate-800 transition-colors text-center">
                    Mesa de Ayuda
                </a>

                <button type="button"
                        x-data="{ copied: false }"
                        @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                        class="py-2.5 px-3 rounded-xl bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs border border-slate-200 dark:border-slate-800 transition-colors flex items-center justify-center gap-1 cursor-pointer">
                    <span x-text="copied ? '✓ Copiado' : '🔗 Compartir'"></span>
                </button>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-4 text-center text-[11px] text-slate-400 dark:text-slate-500">
        <p>Municipalidad Distrital de Castilla &copy; {{ date('Y') }} &bull; ODT</p>
        <p class="text-[10px] text-slate-400/80">Sistema Integrado de Gestión Municipal (SIGM)</p>
    </footer>

</body>
</html>
