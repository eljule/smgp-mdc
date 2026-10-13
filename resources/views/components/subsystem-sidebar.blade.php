@props([
    'subsystem' => 'admin',
    'activeTab' => 'dashboard',
    'counts' => [],
])

@php
    $peopleCount = $counts['people'] ?? 46;
    $usersCount = $counts['users'] ?? 49;
    $rolesCount = $counts['roles'] ?? 4;
    $permissionsCount = $counts['permissions'] ?? 19;
    if ($subsystem === 'itam') {
        $assetsCount = $counts['assets'] ?? \App\Models\Itam\Asset::count();
        $movementsCount = $counts['movements'] ?? \App\Models\Itam\AssetMovement::count();
        $maintenanceCount = $counts['maintenance'] ?? \App\Models\Itam\MaintenanceOrder::count();
        $suppliesCount = $counts['supplies'] ?? \App\Models\Itam\Supply::count();
        $criticalSuppliesCount = $counts['critical_supplies'] ?? \App\Models\Itam\Supply::whereRaw('stock <= min_stock')->count();
        $loansCount = $counts['loans'] ?? \App\Models\Itam\AssetLoan::count();
        $delayedLoansCount = $counts['delayed_loans'] ?? \App\Models\Itam\AssetLoan::where('status', 'ACTIVO')->where('expected_return_date', '<', now())->count();
        $auditsCount = $counts['audits'] ?? \App\Models\Itam\InventoryAudit::count();
        $softwareCount = $counts['software'] ?? \App\Models\Itam\Software::count();
        $categoriesCount = $counts['categories'] ?? \App\Models\Itam\AssetCategory::count();
        $brandsCount = $counts['brands'] ?? \App\Models\Itam\AssetBrand::count();
        $modelsCount = $counts['models'] ?? \App\Models\Itam\AssetModel::count();
    } else {
        $assetsCount = $counts['assets'] ?? 10;
        $movementsCount = $counts['movements'] ?? 2;
        $maintenanceCount = $counts['maintenance'] ?? 2;
        $suppliesCount = $counts['supplies'] ?? 3;
        $criticalSuppliesCount = $counts['critical_supplies'] ?? 0;
        $loansCount = $counts['loans'] ?? 1;
        $delayedLoansCount = $counts['delayed_loans'] ?? 0;
        $auditsCount = $counts['audits'] ?? 1;
        $softwareCount = $counts['software'] ?? 5;
        $categoriesCount = $counts['categories'] ?? 9;
        $brandsCount = $counts['brands'] ?? 8;
        $modelsCount = $counts['models'] ?? 18;
    }
    $ticketsCount = $counts['tickets'] ?? \App\Models\Helpdesk\Ticket::count();
    $myTicketsCount = $counts['my_tickets'] ?? \App\Models\Helpdesk\Ticket::count();
    $kbCount = $counts['kb'] ?? \App\Models\Helpdesk\KnowledgeArticle::count();

    $activeClass = "bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border-r-4 border-emerald-600 rounded-lg font-bold";
    $inactiveClass = "text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg font-medium";

    $subsystemTitle = match($subsystem) {
        'admin' => 'Módulo de Administración',
        'itam' => 'Módulo de Inventario y Gestión Activos TI',
        'helpdesk' => 'Módulo de Soporte Técnico y Helpdesk',
        'transportes' => 'Módulo de Licencias y Transportes',
        default => 'Municipalidad Distrital de Castilla',
    };
@endphp

<!-- ========================================================================= -->
<!-- 1. BARRA LATERAL ESCRITORIO (Solo visible en pantallas md y superiores)   -->
<!--    100% estática en el flujo flex, sin solapamientos, sin botón de cierre -->
<!-- ========================================================================= -->
<aside
    class="hidden md:flex flex-col justify-between shrink-0 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-all duration-300 select-none overflow-y-auto"
    :class="sidebarCollapsed ? 'w-18' : 'w-64'">
    <div class="p-3 space-y-5">
        @include('components.subsystem-sidebar-nav')
    </div>

    <!-- Pie de Sidebar Institucional Escritorio -->
    <div class="p-3 border-t border-slate-200/80 dark:border-slate-800 text-[11px] text-slate-400 select-none">
        <div x-show="!sidebarCollapsed" class="space-y-0.5">
            <p class="font-bold text-slate-700 dark:text-slate-300">
                SIGM - Castilla
            </p>
            <p class="text-[10px] text-slate-500 dark:text-slate-400">
                {{ $subsystemTitle }}
            </p>
            <p class="text-[10px] text-slate-400 font-mono">v13.19.0 &bull; ODT</p>
        </div>
        <div x-show="sidebarCollapsed" class="text-center font-bold text-xs text-slate-400">
            🏛️
        </div>
    </div>
</aside>

<!-- ========================================================================= -->
<!-- 2. DRAWER MÓVIL OFF-CANVAS (Solo pantallas móviles < md)                  -->
<!-- ========================================================================= -->
<div class="md:hidden" x-show="mobileSidebarOpen" x-cloak>
    <aside
        x-show="mobileSidebarOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between shrink-0 select-none overflow-y-auto shadow-2xl"
    >
        <div class="p-3 space-y-5" @click="if ($event.target.closest('a')) mobileSidebarOpen = false">
            <!-- Cabecera Móvil del Menú con Botón Cerrar (Solo Móvil) -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Escudo Oficial de Castilla"
                        class="h-7 w-auto object-contain drop-shadow"
                    />
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-tight">
                            Menú de Navegación
                        </span>
                        <span class="text-[10px] text-emerald-700 dark:text-emerald-400 font-semibold">
                            SIGM &bull; Castilla
                        </span>
                    </div>
                </div>

                <button
                    type="button"
                    @click="mobileSidebarOpen = false"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer"
                    title="Cerrar menú"
                    aria-label="Cerrar menú"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Menú con etiquetas siempre visibles en móvil -->
            <div x-data="{ sidebarCollapsed: false }">
                @include('components.subsystem-sidebar-nav')
            </div>
        </div>

        <!-- Pie de Sidebar Institucional Móvil -->
        <div class="p-3 border-t border-slate-200/80 dark:border-slate-800 text-[11px] text-slate-400 select-none">
            <div class="space-y-0.5">
                <p class="font-bold text-slate-700 dark:text-slate-300">
                    SIGM - Castilla
                </p>
                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                    {{ $subsystemTitle }}
                </p>
                <p class="text-[10px] text-slate-400 font-mono">v13.19.0 &bull; ODT</p>
            </div>
        </div>
    </aside>
</div>
