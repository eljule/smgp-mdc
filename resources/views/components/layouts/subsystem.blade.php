<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIGM Castilla - Plataforma Modular de Gestión Municipal">
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
<body class="flex flex-col min-h-screen bg-slate-100 text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-100 font-sans selection:bg-emerald-600 selection:text-white">

    <div x-data="{
        sidebarCollapsed: false,
        mobileSidebarOpen: false
    }"
    @resize.window="if (window.innerWidth >= 768) mobileSidebarOpen = false"
    @keydown.window.escape="mobileSidebarOpen = false"
    class="min-h-screen flex flex-col w-full">
        <!-- 1. Cabecera Institucional Unificada -->
        <x-subsystem-header
            :moduleName="$moduleName ?? 'Panel de Administración'"
            :subsystem="$subsystem ?? 'admin'"
        />

        <!-- 2. Cuerpo Principal (Sidebar Colapsable Reutilizable + Área de Contenido) -->
        @php
            $currentTab = request()->query('tab');
            if (! $currentTab) {
                if (($subsystem ?? '') === 'itam') {
                    $currentTab = 'inventory';
                } elseif (($subsystem ?? '') === 'helpdesk') {
                    $currentTab = 'tickets';
                } else {
                    $currentTab = $activeTab ?? 'dashboard';
                }
            }
        @endphp
        <div class="flex-1 flex overflow-hidden relative">
            <!-- Backdrop Oscuro para Menú Móvil -->
            <div
                x-show="mobileSidebarOpen"
                x-cloak
                @click="mobileSidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden"
                aria-hidden="true"
            ></div>

            <x-subsystem-sidebar
                :subsystem="$subsystem ?? 'admin'"
                :activeTab="$currentTab"
                :counts="$counts ?? []"
            />

            <!-- 3. Contenido Central Dinámico del Módulo -->
            <main class="flex-1 w-full min-w-0 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
