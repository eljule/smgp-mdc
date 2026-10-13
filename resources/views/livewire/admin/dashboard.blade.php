<div class="space-y-6">
    <!-- Encabezado del Tab / Banner Principal -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div>
            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Resumen del Ecosistema SIGM
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Control centralizado de identidades, servidores municipales, ciudadanos y permisos de acceso.
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <button
                type="button"
                wire:click="openCreatePersonModal"
                class="px-3.5 py-2 rounded-lg bg-[#008f5d] hover:bg-emerald-800 text-white font-semibold text-xs shadow-xs transition-colors flex items-center space-x-1.5 cursor-pointer"
            >
                <span>+ Registrar Persona</span>
            </button>
            <button
                type="button"
                wire:click="openCreateUserModal"
                class="px-3.5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-xs transition-colors flex items-center space-x-1.5 cursor-pointer"
            >
                <span>+ Nuevo Usuario & Permisos</span>
            </button>
        </div>
    </div>

    <!-- Tarjetas de Métricas Clave (4 KPI Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Total Personas en Base de Datos -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Personas en Base de Datos
                </span>
                <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">
                    {{ $peopleCounts['total'] }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    {{ $peopleCounts['personal'] }} personal &bull; {{ $peopleCounts['administrados'] }} administrados
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xl font-bold">
                👥
            </div>
        </div>

        <!-- 2. Personal Municipal -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Personal Municipal
                </span>
                <p class="text-3xl font-black text-emerald-700 dark:text-emerald-400 mt-1">
                    {{ $peopleCounts['personal'] }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Colaboradores con modalidad laboral activa
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-xl font-bold">
                💼
            </div>
        </div>

        <!-- 3. Administrados (Ciudadanos) -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Ciudadanos Administrados
                </span>
                <p class="text-3xl font-black text-blue-700 dark:text-blue-400 mt-1">
                    {{ $peopleCounts['administrados'] }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Vecinos con trámites (Transporte / Rentas)
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 flex items-center justify-center text-xl font-bold">
                🏛️
            </div>
        </div>

        <!-- 4. Cuentas de Acceso (Usuarios) -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Cuentas de Acceso
                </span>
                <p class="text-3xl font-black text-purple-700 dark:text-purple-400 mt-1">
                    {{ $usersCount }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Usuarios con roles y permisos asignados
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center text-xl font-bold">
                🔐
            </div>
        </div>
    </div>

    <!-- Subsistemas Activos Integrados al SIGM -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    Subsistemas Integrados al SIGM
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Módulos orquestados en la plataforma con control de acceso por roles.
                </p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                {{ $activeSubsystemsCount }} de {{ $totalSubsystems }} Activos
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($subsystems as $mod)
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="text-2xl">{{ $mod['icon'] }}</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">
                                {{ $mod['name'] }}
                            </h4>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $mod['code'] }}</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                        {{ $mod['description'] }}
                    </p>
                    <div class="mt-3 pt-2 border-t border-slate-200 dark:border-slate-700/60 flex items-center justify-between text-[11px]">
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">● {{ $mod['status'] }}</span>
                        <a
                            href="{{ $mod['route'] }}"
                            class="text-xs text-slate-500 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium"
                        >
                            Abrir &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modales Compartidos: Registrar Persona y Nuevo Usuario & Permisos -->
    @include('livewire.admin.partials.person-form-modal')
    @include('livewire.admin.partials.user-form-modal')

    <!-- Notificación Flotante con Rebote -->
    <x-floating-toast :message="$alertMessage" :type="$alertType ?? 'success'" />
</div>
