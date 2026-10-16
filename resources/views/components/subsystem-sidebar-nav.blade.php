        @if ($subsystem === 'admin')
            <div>
                <div x-show="!sidebarCollapsed"
                    class="px-3 mb-2 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    MANTENIMIENTO Y CONTROL
                </div>
                <nav class="space-y-1">
                    <!-- 1. Panel General -->
                    <a href="{{ route('subsystems.central') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'dashboard' ? $activeClass : $inactiveClass }}"
                        title="Panel General">
                        <span class="text-lg shrink-0">📊</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Panel General</span>
                    </a>

                    <!-- 2. Personas -->
                    <a href="{{ route('admin.people') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'people' ? $activeClass : $inactiveClass }}"
                        title="Personas">
                        <span class="text-lg shrink-0">👥</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Personas</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                {{ $peopleCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 3. Usuarios -->
                    <a href="{{ route('admin.users') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'users' ? $activeClass : $inactiveClass }}"
                        title="Usuarios">
                        <span class="text-lg shrink-0">🔐</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Usuarios</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                {{ $usersCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 4. Roles -->
                    <a href="{{ route('admin.roles') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'roles' ? $activeClass : $inactiveClass }}"
                        title="Roles">
                        <span class="text-lg shrink-0">🛒</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Roles</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                {{ $rolesCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 5. Permisos -->
                    <a href="{{ route('admin.permissions') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'permissions' ? $activeClass : $inactiveClass }}"
                        title="Permisos">
                        <span class="text-lg shrink-0">🔑</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Permisos</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-300">
                                {{ $permissionsCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 6. Asignar Permisos -->
                    <a href="{{ route('admin.matrix') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'assignment' || $activeTab === 'matrix' ? $activeClass : $inactiveClass }}"
                        title="Asignar Permisos">
                        <span class="text-lg shrink-0">⚡</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Asignar Permisos</span>
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300 tracking-wider">
                                MATRIZ
                            </span>
                        </div>
                    </a>

                    <!-- 7. Oficinas & Áreas -->
                    <a href="{{ route('admin.offices') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'offices' ? $activeClass : $inactiveClass }}"
                        title="Oficinas & Áreas">
                        <span class="text-lg shrink-0">🏛️</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Oficinas & Áreas</span>
                    </a>

                    <!-- 8. Subsistemas SIGM -->
                    <a href="{{ route('admin.subsystems') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'subsystems' ? $activeClass : $inactiveClass }}"
                        title="Subsistemas SIGM">
                        <span class="text-lg shrink-0">⚙️</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Subsistemas SIGM</span>
                    </a>
                </nav>
            </div>

            <!-- ========================================== -->
            <!-- 2. ITAM (11 PESTAÑAS)                      -->
            <!-- ========================================== -->
        @elseif ($subsystem === 'itam')
            <!-- Grupo 1: Operaciones ITAM (SRS) -->
            <div>
                <div x-show="!sidebarCollapsed"
                    class="px-3 mb-2 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Operaciones ITAM (SRS)
                </div>
                <nav class="space-y-1">
                    <!-- 1. Inventario de Activos -->
                    <a href="{{ url('/itam?tab=inventory') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'inventory' ? $activeClass : $inactiveClass }}"
                        title="Inventario General de Activos (Padre-Hijo)">
                        <span class="text-lg shrink-0">🖥️</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Inventario Activos</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                {{ $assetsCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 2. Movimientos & Actas -->
                    <a href="{{ url('/itam?tab=movements') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'movements' ? $activeClass : $inactiveClass }}"
                        title="Asignaciones, Traslados y Generación de Actas">
                        <span class="text-lg shrink-0">🔄</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Movimientos & Actas</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-100 dark:bg-indigo-900/60 text-indigo-800 dark:text-indigo-200">
                                {{ $movementsCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 3. Mantenimientos -->
                    <a href="{{ url('/itam?tab=maintenance') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'maintenance' ? $activeClass : $inactiveClass }}"
                        title="Órdenes de Trabajo de Mantenimiento Preventivo y Correctivo">
                        <span class="text-lg shrink-0">🛠️</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Mantenimientos</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200">
                                {{ $maintenanceCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 4. Insumos & Stock -->
                    <a href="{{ url('/itam?tab=supplies') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'supplies' ? $activeClass : $inactiveClass }}"
                        title="Control de Stock de Tóners, Cables de Red y Conectores">
                        <span class="text-lg shrink-0">📦</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left min-w-0">
                            <span class="truncate">Insumos & Stock</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 shrink-0">
                                {{ $suppliesCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 5. Préstamos & Reservas -->
                    <a href="{{ url('/itam?tab=loans') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'loans' ? $activeClass : $inactiveClass }}"
                        title="Gestión de Cesión Temporal de Proyectores y Laptops">
                        <span class="text-lg shrink-0">⏱️</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left min-w-0">
                            <span class="truncate">Préstamos & Reservas</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 shrink-0">
                                {{ $loansCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 6. Auditoría Anual -->
                    <a href="{{ url('/itam?tab=audits') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'audits' ? $activeClass : $inactiveClass }}"
                        title="Cierres Anuales, Snapshots y Conciliación Física">
                        <span class="text-lg shrink-0">📋</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Auditoría Anual</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200">
                                {{ $auditsCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 7. Software & Licencias -->
                    <a href="{{ url('/itam?tab=software') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'software' ? $activeClass : $inactiveClass }}"
                        title="Software Institucional y Control de Licencias">
                        <span class="text-lg shrink-0">💿</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Software & Licencias</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-cyan-100 dark:bg-cyan-900/60 text-cyan-800 dark:text-cyan-200">
                                {{ $softwareCount }}
                            </span>
                        </div>
                    </a>
                </nav>
            </div>

            <!-- Grupo 2: Catálogos Parametrizables -->
            <div>
                <div x-show="!sidebarCollapsed"
                    class="px-3 mb-2 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Catálogos Parametrizables
                </div>
                <nav class="space-y-1">
                    <!-- 8. Categorías & Esquemas -->
                    <a href="{{ url('/itam?tab=categories') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'categories' ? $activeClass : $inactiveClass }}"
                        title="Categorías y Esquemas Dinámicos JSONB">
                        <span class="text-lg shrink-0">⚙️</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Categorías & Esquemas</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $categoriesCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 9. Marcas & Modelos -->
                    <a href="{{ url('/itam?tab=catalogs') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'catalogs' ? $activeClass : $inactiveClass }}"
                        title="Catálogo Homologado de Marcas y Modelos">
                        <span class="text-lg shrink-0">🏷️</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Marcas & Modelos</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $brandsCount }}/{{ $modelsCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 10. Dashboard KPIs -->
                    <a href="{{ url('/itam?tab=metrics') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'metrics' ? $activeClass : $inactiveClass }}"
                        title="Dashboard de Métricas ITAM">
                        <span class="text-lg shrink-0">📊</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Dashboard KPIs</span>
                    </a>

                    <!-- 11. Lector QR / Búsqueda -->
                    <a href="{{ url('/itam?tab=qr_lookup') }}" wire:navigate
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-colors cursor-pointer {{ $activeTab === 'qr_lookup' ? $activeClass : $inactiveClass }}"
                        title="Buscador por Código Informático / Lector QR">
                        <span class="text-lg shrink-0">🔍</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Lector QR / Búsqueda</span>
                            <span
                                class="text-[9px] bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200 px-1.5 py-0.5 rounded font-black uppercase">
                                QR
                            </span>
                        </div>
                    </a>
                </nav>
            </div>
        @elseif ($subsystem === 'helpdesk')
            <div>
                <div x-show="!sidebarCollapsed"
                    class="px-3 mb-2 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Mesa de Ayuda (SRS)
                </div>
                <nav class="space-y-1">
                    <!-- 1. Bandeja de Tickets -->
                    <a href="{{ url('/helpdesk?tab=tickets') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'tickets' ? $activeClass : $inactiveClass }}"
                        title="Bandeja General de Tickets">
                        <span class="text-lg shrink-0">📋</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Bandeja de Tickets</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                {{ $ticketsCount }}
                            </span>
                        </div>
                    </a>                    
                    <!-- 2. Cargas de Trabajo -->
                    <a href="{{ url('/helpdesk?tab=workload') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'workload' ? $activeClass : $inactiveClass }}"
                        title="Balanceo de Cargas de Trabajo de Técnicos">
                        <span class="text-lg shrink-0">📊</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Cargas de Trabajo</span>
                    </a>

                    <!-- 3. Mis Tickets (Usuario) -->
                    <a href="{{ url('/helpdesk?tab=my_tickets') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'my_tickets' ? $activeClass : $inactiveClass }}"
                        title="Seguimiento Ágil y Visto Bueno del Solicitante">
                        <span class="text-lg shrink-0">📱</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Mis Tickets (Usuario)</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">
                                {{ $myTicketsCount }}
                            </span>
                        </div>
                    </a>
                    
                    <!-- 4. Base Conocimientos -->
                    <a href="{{ url('/helpdesk?tab=knowledge') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'knowledge' || $activeTab === 'kb' ? $activeClass : $inactiveClass }}"
                        title="Guías de Solución Técnica Estandarizadas">
                        <span class="text-lg shrink-0">📚</span>
                        <div x-show="!sidebarCollapsed" class="flex-1 flex items-center justify-between text-left">
                            <span class="truncate">Base Conocimientos</span>
                            <span
                                class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300">
                                {{ $kbCount }}
                            </span>
                        </div>
                    </a>

                    <!-- 5. Lector QR Equipos -->
                    <a href="{{ url('/helpdesk?tab=qr_scanner') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'qr_scanner' ? $activeClass : $inactiveClass }}"
                        title="Escaneo o Búsqueda de Bien por QR Adherido">
                        <span class="text-lg shrink-0">🏷️</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Lector QR Equipos</span>
                    </a>

                    <!-- 6. Métricas & SLA -->
                    <a href="{{ url('/helpdesk?tab=metrics') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'metrics' || $activeTab === 'dashboard' ? $activeClass : $inactiveClass }}"
                        title="Tiempos de Respuesta, Resolución y Satisfacción">
                        <span class="text-lg shrink-0">📈</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Métricas & SLA</span>
                    </a>
                </nav>
            </div>

            <!-- ========================================== -->
            <!-- 4. TRANSPORTES                             -->
            <!-- ========================================== -->
        @elseif ($subsystem === 'transportes')
            <div>
                <div x-show="!sidebarCollapsed"
                    class="px-3 mb-2 text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    GESTIÓN DE TRANSPORTES
                </div>
                <nav class="space-y-1">
                    <a href="{{ route('subsystems.transportes') }}"
                        class="w-full flex items-center space-x-3 px-3.5 py-2.5 text-xs sm:text-sm transition-all cursor-pointer {{ $activeTab === 'dashboard' ? $activeClass : $inactiveClass }}"
                        title="Licencias y Empadronamiento">
                        <span class="text-lg shrink-0">🚗</span>
                        <span x-show="!sidebarCollapsed" class="truncate">Licencias y Tránsito</span>
                    </a>
                </nav>
            </div>
        @endif
