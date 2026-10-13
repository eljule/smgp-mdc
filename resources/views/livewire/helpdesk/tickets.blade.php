<div class="space-y-6 pb-12" x-data="{ qrScanner: false }">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />


    <!-- ========================================================================= -->
    <!-- PESTAÑA 1: BANDEJA GENERAL DE TICKETS (RF-06, RF-07, RF-12)               -->
    <!-- ========================================================================= -->
    @if ($activeTab === 'tickets')
        <div class="space-y-6 animate-fadeIn">
            <!-- Tarjetas Superiores de Métricas y Contadores (ITIL Lifecycle) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 block">TOTAL TICKETS</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $metrics['totalTickets'] ?? $tickets->total() }}</span>
                </div>
                <div class="bg-blue-50/70 dark:bg-blue-950/30 p-4 rounded-xl shadow-xs border border-blue-200 dark:border-blue-900">
                    <span class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 block">1. ABIERTOS</span>
                    <span class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ $metrics['abiertos'] ?? 0 }}</span>
                </div>
                <div class="bg-indigo-50/70 dark:bg-indigo-950/30 p-4 rounded-xl shadow-xs border border-indigo-200 dark:border-indigo-900">
                    <span class="text-[10px] uppercase font-bold text-indigo-600 dark:text-indigo-400 block">2. EN ATENCIÓN</span>
                    <span class="text-2xl font-black text-indigo-700 dark:text-indigo-300">{{ $metrics['enAtencion'] ?? 0 }}</span>
                </div>
                <div class="bg-purple-50/70 dark:bg-purple-950/30 p-4 rounded-xl shadow-xs border border-purple-200 dark:border-purple-900">
                    <span class="text-[10px] uppercase font-bold text-purple-600 dark:text-purple-400 block">4. EN TALLER</span>
                    <span class="text-2xl font-black text-purple-700 dark:text-purple-300">{{ $metrics['enLaboratorio'] ?? 0 }}</span>
                </div>
                <div class="bg-emerald-50/70 dark:bg-emerald-950/30 p-4 rounded-xl shadow-xs border border-emerald-200 dark:border-emerald-900">
                    <span class="text-[10px] uppercase font-bold text-emerald-600 dark:text-emerald-400 block">5. RESUELTOS</span>
                    <span class="text-2xl font-black text-emerald-700 dark:text-emerald-300">{{ $metrics['resueltos'] ?? 0 }}</span>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/80 p-4 rounded-xl shadow-xs border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 block">6. CERRADOS</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-slate-200">{{ $metrics['cerrados'] ?? 0 }}</span>
                </div>
            </div>

            <!-- Barra Unificada de Búsqueda, Filtros y Acción Directa -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
                <div class="flex-1 flex flex-wrap gap-2.5 items-center">
                    <div class="relative flex-1 min-w-[240px]">
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Buscar por código, asunto, solicitante, dependencia o activo..."
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-900 dark:text-white placeholder:text-slate-400">
                        <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <select wire:model.live="status"
                            class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                        <option value="TODOS">Todos los Estados</option>
                        <option value="ABIERTO">1. Abierto / Pendiente</option>
                        <option value="EN_ATENCION">2. En Atención</option>
                        <option value="EN_PAUSA">3. En Pausa</option>
                        <option value="EN_LABORATORIO">4. En Taller / Lab</option>
                        <option value="RESUELTO">5. Resuelto</option>
                        <option value="CERRADO">6. Cerrado</option>
                    </select>

                    <select wire:model.live="priority"
                            class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                        <option value="TODAS">Todas las Prioridades</option>
                        <option value="CRITICA">Crítica</option>
                        <option value="ALTA">Alta</option>
                        <option value="MEDIA">Media</option>
                        <option value="BAJA">Baja</option>
                    </select>

                    <select wire:model.live="category"
                            class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                        <option value="TODAS">Todas las Categorías</option>
                        <option value="HARDWARE">Hardware</option>
                        <option value="SOFTWARE">Software</option>
                        <option value="RED_INTERNET">Red / Internet</option>
                        <option value="PERMISOS">Permisos</option>
                        <option value="OTROS">Otros</option>
                    </select>
                </div>

                <div class="flex space-x-2 shrink-0">
                    <button type="button"
                            wire:click="openCreateModal"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition-colors flex items-center space-x-1.5 cursor-pointer">
                        <span>+</span>
                        <span>Registrar Incidencia</span>
                    </button>
                </div>
            </div>

            <!-- Tabla Principal de Tickets -->
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-800 uppercase tracking-wider font-semibold text-[10px]">
                            <tr>
                                <th class="p-3">CÓDIGO</th>
                                <th class="p-3">PRIORIDAD</th>
                                <th class="p-3">ASUNTO & ACTIVO</th>
                                <th class="p-3">SOLICITANTE & DEPENDENCIA</th>
                                <th class="p-3">ESTADO CICLO DE VIDA</th>
                                <th class="p-3">TÉCNICO ASIGNADO</th>
                                <th class="p-3 text-right">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                            @forelse ($tickets as $t)
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <!-- 1. Código & Fecha -->
                                    <td class="p-3 font-mono font-bold text-emerald-700 dark:text-emerald-400 whitespace-nowrap">
                                        {{ $t->ticket_number }}
                                        <div class="text-[10px] text-slate-400 font-sans font-normal">
                                            {{ $t->created_at ? $t->created_at->format('d/m/Y') : '11/10/2026' }}
                                        </div>
                                    </td>

                                    <!-- 2. Prioridad & Categoría -->
                                    <td class="p-3 whitespace-nowrap">
                                        @php
                                            $pUpper = strtoupper($t->priority);
                                            $pClass = match($pUpper) {
                                                'CRITICA' => 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300',
                                                'ALTA' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                                'MEDIA' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $pClass }}">
                                            {{ $pUpper }}
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-0.5 uppercase">{{ $t->category }}</div>
                                    </td>

                                    <!-- 3. Asunto & Activo QR -->
                                    <td class="p-3 max-w-xs">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200 truncate" title="{{ $t->subject }}">
                                            {{ $t->subject }}
                                        </div>
                                        @if ($t->asset_computer_code)
                                            <span class="inline-block mt-0.5 text-[10px] font-mono text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 px-1.5 py-0.5 rounded border border-blue-200 dark:border-blue-900">
                                                🏷️ {{ $t->asset_computer_code }}
                                            </span>
                                        @else
                                            <span class="text-[10px] text-slate-400">Sin activo QR</span>
                                        @endif
                                    </td>

                                    <!-- 4. Solicitante & Dependencia -->
                                    <td class="p-3">
                                        <div class="font-medium text-slate-800 dark:text-slate-200">
                                            {{ $t->applicant_name }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 truncate max-w-[220px]" title="{{ $t->office_name }}">
                                            {{ $t->office_name }}
                                        </div>
                                    </td>

                                    <!-- 5. Estado Ciclo de Vida -->
                                    <td class="p-3 whitespace-nowrap">
                                        @php
                                            $stUpper = strtoupper($t->status);
                                            $stClass = match($stUpper) {
                                                'ABIERTO' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                                'EN_ATENCION' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300',
                                                'EN_PAUSA' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                                'EN_LABORATORIO', 'EN_TALLER' => 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300',
                                                'RESUELTO' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
                                                'CERRADO' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
                                                default => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
                                            };
                                            $stLabel = match($stUpper) {
                                                'ABIERTO' => '1. Abierto',
                                                'EN_ATENCION' => '2. En Atención',
                                                'EN_PAUSA' => '3. En Pausa',
                                                'EN_LABORATORIO', 'EN_TALLER' => '4. En Taller',
                                                'RESUELTO' => '5. Resuelto',
                                                'CERRADO' => '6. Cerrado',
                                                'CANCELADO' => '7. Cancelado',
                                                default => $t->status,
                                            };
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold {{ $stClass }}">
                                            {{ $stLabel }}
                                        </span>
                                    </td>

                                    <!-- 6. Técnico Asignado -->
                                    <td class="p-3 whitespace-nowrap">
                                        @if ($t->assigned_technician_name)
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ $t->assigned_technician_name }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 font-mono">
                                                    {{ $t->assigned_technician_phone ?? 'Anexo 104' }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Sin asignar</span>
                                        @endif
                                    </td>

                                    <!-- 7. Acciones -->
                                    <td class="p-3 text-right whitespace-nowrap space-x-1.5">
                                        @if (strtoupper($t->status) === 'ABIERTO')
                                            <button type="button"
                                                    wire:click="takeTicket({{ $t->id }})"
                                                    class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px] font-bold shadow-xs transition-colors cursor-pointer"
                                                    title="Tomar Ticket para atención inmediata (RF-07)">
                                                ⚡ Tomar
                                            </button>
                                        @endif

                                        @if ($t->technicalDetail?->is_definitive_decommission)
                                            <button type="button"
                                                    wire:click="openDecommissionModal({{ $t->id }})"
                                                    class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded text-[11px] font-bold transition-colors cursor-pointer"
                                                    title="Ver Acta Oficial de Baja Patrimonial (RF-11)">
                                                📄 Acta Baja
                                            </button>
                                        @endif

                                        <button type="button"
                                                wire:click="openDetailModal({{ $t->id }})"
                                                class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-200 rounded text-[11px] font-medium transition-colors cursor-pointer">
                                            Ver Ficha
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400 italic">
                                        No se encontraron tickets con los filtros seleccionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Barra de Paginación -->
                <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-3">
                        <span>
                            Mostrando {{ $tickets->firstItem() ?? 0 }} a {{ $tickets->lastItem() ?? 0 }} de {{ $tickets->total() }} tickets
                        </span>
                        <div class="flex items-center gap-1.5">
                            <span class="text-slate-400">Filas por pág:</span>
                            <select wire:model.live="perPage" class="px-2 py-1 rounded-md bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 focus:outline-none cursor-pointer">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="previousPage" @disabled($tickets->onFirstPage())
                                class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-40 disabled:pointer-events-none cursor-pointer">
                            &lt; Anterior
                        </button>
                        @for ($page = 1; $page <= $tickets->lastPage(); $page++)
                            <button wire:click="gotoPage({{ $page }})"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-bold transition-colors cursor-pointer {{ $page === $tickets->currentPage() ? 'bg-emerald-600 text-white' : 'border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                {{ $page }}
                            </button>
                        @endfor
                        <button wire:click="nextPage" @disabled(! $tickets->hasMorePages())
                                class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 disabled:opacity-40 disabled:pointer-events-none cursor-pointer">
                            Siguiente &gt;
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- ========================================================================= -->
    <!-- PESTAÑA 2: MIS TICKETS (USUARIO SOLICITANTE)                              -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'my_tickets')
        <div class="space-y-6 animate-fadeIn">
            <!-- Banner Encabezado Portal Solicitante -->
            <div class="bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] p-6 rounded-2xl text-white shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">
                        PORTAL DEL SOLICITANTE (RF-01 / RF-05)
                    </span>
                    <h2 class="text-xl font-bold mt-1">Seguimiento de Tickets y Visto Bueno</h2>
                    <p class="text-xs text-emerald-100/90 mt-1 max-w-xl leading-relaxed">
                        Consulte en tiempo real el estado de atención de las incidencias reportadas en su área, el contacto del técnico asignado y otorgue la conformidad para el cierre definitivo.
                    </p>
                </div>
                <button
                    wire:click="openCreateModal"
                    type="button"
                    class="px-5 py-2.5 bg-white text-emerald-950 hover:bg-emerald-50 rounded-xl text-xs font-bold shadow-md transition-all whitespace-nowrap cursor-pointer">
                    + Reportar Falla Técnica
                </button>
            </div>

            <!-- Lista de Tickets del Solicitante -->
            <div class="space-y-4">
                @forelse ($myTickets as $t)
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:shadow-md transition-shadow">
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-mono font-bold text-sm text-emerald-700 dark:text-emerald-400">
                                    {{ $t->ticket_number }}
                                </span>
                                @php
                                    $badgeClasses = match($t->status) {
                                        'RESUELTO' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
                                        'EN_ATENCION' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300',
                                        'EN_LABORATORIO', 'EN_TALLER' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                        'EN_PAUSA' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                        'CERRADO' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
                                        default => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClasses }}">
                                    {{ $t->status }}
                                </span>
                                <span class="text-xs text-slate-400 font-mono">
                                    {{ $t->created_at ? $t->created_at->format('d/m/Y') : '11/10/2026' }}
                                </span>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 dark:text-white">{{ $t->subject }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $t->description }}</p>

                            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-600 dark:text-slate-400 pt-1">
                                <div>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">Técnico: </span>
                                    <span>{{ $t->assigned_technician_name ?? 'En asignación de Sistemas' }}</span>
                                </div>
                                @if ($t->assigned_technician_phone)
                                    <div>
                                        <span class="font-semibold text-slate-700 dark:text-slate-300">Contacto: </span>
                                        <span class="text-indigo-600 dark:text-indigo-400 font-mono font-bold">
                                            📞 {{ $t->assigned_technician_phone }}
                                        </span>
                                    </div>
                                @endif
                                @if ($t->asset_computer_code)
                                    <div>
                                        <span class="font-semibold text-slate-700 dark:text-slate-300">Bien: </span>
                                        <span class="font-mono text-blue-600">{{ $t->asset_computer_code }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Botón de Conformidad y Acciones -->
                        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2 self-stretch md:self-auto justify-end">
                            @if ($t->status === 'RESUELTO')
                                <button
                                    type="button"
                                    wire:click="openConformityModal({{ $t->id }})"
                                    class="w-full sm:w-auto px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors flex items-center justify-center space-x-1.5 cursor-pointer"
                                >
                                    <span>⭐</span>
                                    <span>Dar Visto Bueno (Conformidad)</span>
                                </button>
                            @endif

                            @if ($t->technicalDetail?->is_definitive_decommission)
                                <button
                                    type="button"
                                    wire:click="openDecommissionModal({{ $t->id }})"
                                    class="w-full sm:w-auto px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-colors text-center cursor-pointer"
                                >
                                    📄 Acta de Baja
                                </button>
                            @endif

                            <button
                                type="button"
                                wire:click="openDetailModal({{ $t->id }})"
                                class="w-full sm:w-auto px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-200 rounded-xl text-xs font-semibold transition-colors text-center cursor-pointer"
                            >
                                Ver Detalle
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-slate-900 p-12 text-center rounded-2xl border border-slate-200 dark:border-slate-800 text-slate-400">
                        <span class="text-4xl block mb-2">📋</span>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                            No tiene tickets registrados actualmente.
                        </p>
                        <p class="text-xs mt-1">
                            Si experimenta problemas con su computadora, impresora o conexión de red, pulse en "Reportar Falla Técnica".
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    <!-- ========================================================================= -->
    <!-- PESTAÑA 3: CARGAS DE TRABAJO (RF-13)                                      -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'workload')
        <div class="space-y-6 animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800">
                <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 block mb-1">
                    PANEL DE SUPERVISIÓN Y BALANCEO (RF-13)
                </span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Cargas de Trabajo del Equipo de Soporte Técnico
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                    Monitoreo en tiempo real de la distribución de tickets activos entre el personal operativo, evitando cuellos de botella y sobrecarga en técnicos específicos.
                </p>
            </div>

            <!-- Tarjetas por Técnico -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($metrics['techniciansWorkload'] ?? [] as $tech)
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                                    {{ $tech['technicianName'] }}
                                </h3>
                                <p class="text-[11px] text-slate-400">Oficina de Informática y Sistemas</p>
                            </div>
                            @php
                                $statusBg = match($tech['status']) {
                                    'DISPONIBLE' => 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300',
                                    'OCUPADO' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                    default => 'bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusBg }}">
                                {{ $tech['status'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-2 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl text-center">
                            <div>
                                <span class="text-[10px] text-slate-400 block">En Proceso</span>
                                <span class="text-lg font-black text-indigo-700 dark:text-indigo-400">
                                    {{ $tech['inProgressCount'] }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block">Resueltos</span>
                                <span class="text-lg font-black text-emerald-700 dark:text-emerald-400">
                                    {{ $tech['resolvedCount'] }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block">Satisfacción</span>
                                <span class="text-lg font-black text-amber-600 dark:text-amber-400">
                                    ★ {{ $tech['averageRating'] }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-2">
                                Tickets Asignados Actualmente:
                            </span>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @forelse ($tech['assignedTickets'] as $t)
                                    <div
                                        wire:click="openDetailModal({{ $t->id }})"
                                        class="p-2 bg-slate-50 dark:bg-slate-800 rounded-lg text-xs cursor-pointer hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors flex justify-between items-center"
                                    >
                                        <div class="truncate mr-2">
                                            <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ $t->ticket_number }}
                                            </span> - {{ $t->subject }}
                                        </div>
                                        @php
                                            $badgeBg = match($t->status) {
                                                'RESUELTO' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
                                                'CERRADO' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300',
                                                'EN_ATENCION' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300',
                                                'EN_LABORATORIO', 'EN_TALLER' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                                'EN_PAUSA' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                                default => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                            };
                                        @endphp
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold shrink-0 {{ $badgeBg }}">
                                            {{ $t->status }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic p-2">Sin tickets asignados actualmente.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- PESTAÑA 4: BASE DE CONOCIMIENTOS TI (RF-14)                               -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'knowledge')
        <div class="space-y-6 animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-amber-600 block mb-1">
                        KNOWLEDGE BASE TI (RF-14)
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Guías Estandarizadas de Solución Técnica
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                        Repositorio institucional de fallas comunes y procedimientos paso a paso para agilizar la resolución de incidencias en dependencias municipales.
                    </p>
                </div>
                <button type="button"
                        wire:click="openKbModal"
                        class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md transition-colors whitespace-nowrap cursor-pointer">
                    + Publicar Nueva Guía
                </button>
            </div>

            <!-- Buscador y Filtro de Base de Conocimiento -->
            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-xs border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text"
                           placeholder="Buscar guías por palabra clave (atasco de papel, red, reinicios, Active Directory)..."
                           wire:model.live.debounce.300ms="kbSearch"
                           class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-900 dark:text-white" />
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <select wire:model.live="kbCategory"
                        class="p-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <option value="ALL">Todas las Categorías</option>
                    <option value="HARDWARE">Hardware</option>
                    <option value="SOFTWARE">Software</option>
                    <option value="RED_INTERNET">Red / Internet</option>
                    <option value="PERMISOS">Permisos / Accesos</option>
                    <option value="OTROS">Otros</option>
                </select>
            </div>

            <!-- Cuadrícula de Artículos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($kbArticles as $art)
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 space-y-3 hover:shadow-md transition-shadow flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                    {{ $art->category }}
                                </span>
                                <span class="text-[11px] text-slate-400">
                                    👁 {{ $art->views_count }} vistas | 👍 {{ $art->helpful_count }} votos
                                </span>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-snug">
                                {{ $art->title }}
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 mt-1">
                                {{ $art->summary }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400">
                                Por: {{ $art->author_technician_name ?? 'Mesa Técnica' }}
                            </span>
                            <button type="button"
                                    wire:click="openViewKbModal({{ $art->id }})"
                                    class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                                Leer Solución Completa →
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12 text-slate-400 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800">
                        No se encontraron guías que coincidan con la búsqueda.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- PESTAÑA 5: LECTOR QR DE EQUIPOS (RF-03)                                   -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'qr_scanner')
        <div class="space-y-6 animate-fadeIn max-w-2xl mx-auto">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 text-center space-y-3">
                <span class="text-4xl block">🏷️</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Consulta Rápida por Código QR Adherido (RF-03)
                </h2>
                <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                    Ingrese el código informático municipal o escanee el sticker QR adherido a la computadora o periférico para consultar su estado y reportar fallas en un solo clic.
                </p>

                <form wire:submit.prevent="handleLookupQr" class="flex space-x-2 pt-2">
                    <input type="text"
                           wire:model="qrCodeInput"
                           placeholder="EJ. MDC-TI-PC-0001 O 740895000101"
                           class="flex-1 p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-mono uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none dark:text-white placeholder:text-slate-400">
                    <button type="submit"
                            style="background-color: #52a78b;"
                            class="px-5 py-2.5 text-white rounded-xl text-xs font-bold shadow-md hover:brightness-95 transition-all cursor-pointer whitespace-nowrap">
                        Consultar Bien
                    </button>
                </form>

                @if ($qrError)
                    <p class="text-xs text-red-600 dark:text-red-400 italic pt-1">{{ $qrError }}</p>
                @endif
            </div>

            @if ($scannedAsset)
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-4 animate-fadeIn">
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <span class="font-mono text-xs font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
                                {{ $scannedAsset->computer_code }}
                            </span>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white mt-1">
                                {{ $scannedAsset->category?->name ?? 'Equipo Informático' }} - {{ $scannedAsset->brand?->name }} {{ $scannedAsset->model?->name }}
                            </h3>
                            <p class="text-xs text-slate-500">
                                Código Patrimonial SBN: <strong class="font-mono">{{ $scannedAsset->patrimonial_code ?? 'N/A' }}</strong>
                            </p>
                        </div>

                        @php
                            $statusBg = match ($scannedAsset->status) {
                                'OPERATIVO' => 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300',
                                'EN_MANTENIMIENTO' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                'DE_BAJA' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300',
                                default => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusBg }}">
                            {{ $scannedAsset->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Dependencia Asignada:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $scannedAsset->office ?? 'En Almacén' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Funcionario Custodio:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $scannedAsset->assignedPerson?->full_name ?? 'Sin asignar' }}</span>
                        </div>
                    </div>

                    @if ($scannedAsset->childAssets && $scannedAsset->childAssets->count() > 0)
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-3">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide block mb-1.5">
                                Periféricos Vinculados (Padre-Hijo):
                            </span>
                            <ul class="text-xs space-y-1 text-slate-600 dark:text-slate-400">
                                @foreach ($scannedAsset->childAssets as $c)
                                    <li>
                                        • <strong class="font-mono">{{ $c->computer_code }}</strong>: {{ $c->category?->name }} ({{ $c->model?->name ?? $c->brand?->name }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="pt-2 flex justify-end">
                        <button type="button"
                                wire:click="openCreateModal({{ $scannedAsset->id }})"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors cursor-pointer">
                            🚀 Reportar Incidencia sobre este Activo
                        </button>
                    </div>
                </div>
            @endif
        </div>

    <!-- ========================================================================= -->
    <!-- PESTAÑA 6: MÉTRICAS Y TIEMPOS DE ATENCIÓN (RF-13, RNF-01)                 -->
    <!-- ========================================================================= -->
    @elseif ($activeTab === 'metrics')
        <div class="space-y-6 animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800">
                <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 block mb-1">
                    Indicadores de Rendimiento y Calidad de Servicio (RNF-01 / RF-13)
                </span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Métricas Operativas de Soporte Técnico
                </h2>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Evaluación de tiempos cronometrados de atención, tasa de resolución municipal y nivel de satisfacción de los usuarios.
                </p>
            </div>

            <!-- 4 KPIs Clave -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tiempo de Respuesta Promedio</span>
                    <div class="text-2xl font-black text-blue-600 dark:text-blue-400">
                        {{ $metrics['averageResponseTimeMinutes'] ?? 1 }} min
                    </div>
                    <p class="text-[11px] text-slate-500">Desde reporte hasta inicio de atención técnica</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tasa de Resolución</span>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                        {{ $metrics['resolutionRatePercent'] ?? 33.3 }}%
                    </div>
                    <p class="text-[11px] text-slate-500">Casos resueltos con éxito por el equipo</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Satisfacción del Usuario</span>
                    <div class="text-2xl font-black text-amber-500">
                        ★ {{ $metrics['averageSatisfactionRating'] ?? 5 }} / 5.0
                    </div>
                    <p class="text-[11px] text-slate-500">Calificación en actas de conformidad</p>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-1">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Tiempo Promedio de Solución</span>
                    <div class="text-2xl font-black text-purple-600 dark:text-purple-400">
                        {{ $metrics['averageResolutionTimeHours'] ?? 36 }} horas
                    </div>
                    <p class="text-[11px] text-slate-500">Resolución efectiva y cierre definitivo</p>
                </div>
            </div>

            <!-- Desglose por Categoría y Prioridad -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                        Distribución por Categoría de Falla
                    </h3>
                    <div class="space-y-2 text-xs">
                        @foreach (($metrics['byCategory'] ?? ['HARDWARE' => 4, 'SOFTWARE' => 0, 'RED_INTERNET' => 1, 'PERMISOS' => 1, 'OTROS' => 0]) as $cat => $count)
                            @php
                                $total = $metrics['totalTickets'] ?? 6;
                                $pct = $total > 0 ? ($count / $total) * 100 : 0;
                            @endphp
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $cat }}</span>
                                    <span class="text-slate-500 font-mono font-bold">{{ $count }}</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full"
                                         style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-3">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                        Distribución por Nivel de Severidad (Prioridad)
                    </h3>
                    <div class="space-y-2 text-xs">
                        @foreach (($metrics['byPriority'] ?? ['BAJA' => 0, 'MEDIA' => 3, 'ALTA' => 2, 'CRITICA' => 1]) as $prio => $count)
                            @php
                                $total = $metrics['totalTickets'] ?? 6;
                                $pct = $total > 0 ? ($count / $total) * 100 : 0;
                                $barBg = match ($prio) {
                                    'CRITICA' => 'bg-red-600',
                                    'ALTA' => 'bg-amber-500',
                                    'MEDIA' => 'bg-blue-500',
                                    default => 'bg-slate-400',
                                };
                            @endphp
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $prio }}</span>
                                    <span class="text-slate-500 font-mono font-bold">{{ $count }}</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barBg }} rounded-full"
                                         style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 1: CREAR TICKET                                                     -->
    <!-- ========================================================================= -->
    @if ($isCreateModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[94vh] flex flex-col overflow-hidden animate-scaleUp">
                <!-- Cabecera Móvil y Web -->
                <div class="bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] px-6 py-4 text-white flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="font-bold text-base md:text-lg flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 mr-2 inline-block"></span>
                            Reportar Falla Técnica (Mesa de Ayuda)
                        </h3>
                        <p class="text-xs text-emerald-200 mt-0.5">
                            Atención inmediata de la Subgerencia de Informática y Sistemas - MDC
                        </p>
                    </div>
                    <button type="button"
                            wire:click="$set('isCreateModalOpen', false)"
                            class="text-emerald-200 hover:text-white p-1 rounded-md cursor-pointer transition-colors text-xl font-bold">
                        ✕
                    </button>
                </div>

                <!-- Formulario de Registro -->
                <form wire:submit.prevent="saveTicket" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <!-- 1. DATOS DEL SOLICITANTE Y UBICACIÓN (DETECCIÓN AUTOMÁTICA) -->
                    <div class="bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-300 dark:border-emerald-800 rounded-xl p-3.5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-emerald-900 dark:text-emerald-300 uppercase tracking-wider">
                                1. DATOS DEL SOLICITANTE Y UBICACIÓN (DETECCIÓN AUTOMÁTICA)
                            </span>
                            <span class="text-[10px] text-emerald-700 dark:text-emerald-300 bg-white dark:bg-slate-800 px-2 py-0.5 rounded border border-emerald-300 dark:border-emerald-700 font-semibold">
                                Sesión Activa
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Nombre y Apellidos del Solicitante *:
                                </label>
                                <input type="text"
                                       wire:model="formApplicantName"
                                       required
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Oficina o Dependencia Municipal *:
                                </label>
                                <input type="text"
                                       wire:model="formOfficeName"
                                       required
                                       list="offices-list"
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white">
                                <datalist id="offices-list">
                                    @foreach ($offices as $off)
                                        <option value="{{ $off->name }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Anexo Telefónico o Celular:
                                </label>
                                <input type="text"
                                       wire:model="formApplicantPhone"
                                       placeholder="Anexo 115"
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Correo Institucional (Opcional):
                                </label>
                                <input type="email"
                                       wire:model="formApplicantEmail"
                                       placeholder="usuario@castilla.gob.pe"
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- 2. ESCANEO DE CÓDIGO QR O CÓDIGO DE ACTIVO (OPCIONAL) -->
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-3.5 space-y-2 bg-slate-50/50 dark:bg-slate-800/40">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                2. ESCANEO DE CÓDIGO QR O CÓDIGO DE ACTIVO (OPCIONAL)
                            </span>
                            <span class="text-[10px] text-slate-400">Sticker adherido al equipo</span>
                        </div>

                        <div class="flex gap-2">
                            <input type="text"
                                   wire:model="qrCodeInput"
                                   placeholder="ESCANEE CON CÁMARA O INGRESE CÓDIGO (EJ. MDC-TI-PC-0001)"
                                   class="flex-1 text-xs p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 uppercase font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white">
                            <button type="button"
                                    wire:click="lookupAsset"
                                    class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition-colors cursor-pointer shrink-0"
                                    style="background-color: #64748b; color: #ffffff;">
                                Buscar Activo
                            </button>
                        </div>

                        @if ($qrError)
                            <p class="text-[11px] text-rose-500 italic">{{ $qrError }}</p>
                        @endif

                        @if ($formAssetComputerCode)
                            <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg text-xs text-emerald-900 dark:text-emerald-200 flex justify-between items-center">
                                <div>
                                    <div class="font-bold font-mono">
                                        ✓ {{ $formAssetComputerCode }} - {{ $formAssetCategory }}
                                    </div>
                                </div>
                                <button type="button"
                                        wire:click="clearAsset"
                                        class="text-xs text-rose-600 dark:text-rose-400 hover:underline cursor-pointer ml-2 font-medium">
                                    Quitar
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- 3. Categoría de la Falla: -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            3. Categoría de la Falla:
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                            @php
                                $categories = [
                                    ['id' => 'HARDWARE', 'label' => 'Hardware', 'desc' => 'PC, Monitor, Impres...'],
                                    ['id' => 'SOFTWARE', 'label' => 'Software', 'desc' => 'SIGM, Windows, Off...'],
                                    ['id' => 'RED_INTERNET', 'label' => 'Red / Internet', 'desc' => 'Sin conexión, cable'],
                                    ['id' => 'PERMISOS', 'label' => 'Permisos', 'desc' => 'Acceso a carpetas'],
                                    ['id' => 'OTROS', 'label' => 'Otros', 'desc' => 'Consultas y asesoría'],
                                ];
                            @endphp
                            @foreach ($categories as $cat)
                                <button type="button"
                                        wire:click="$set('formCategory', '{{ $cat['id'] }}')"
                                        class="p-2.5 rounded-xl border text-left transition-all cursor-pointer {{ $formCategory === $cat['id'] ? 'border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200 ring-2 ring-emerald-500/20 shadow-xs' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-300' }}">
                                    <div class="font-bold text-xs">{{ $cat['label'] }}</div>
                                    <div class="text-[10px] text-slate-400 truncate">{{ $cat['desc'] }}</div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Nivel de Prioridad / Impacto en sus Labores: -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Nivel de Prioridad / Impacto en sus Labores:
                        </label>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button"
                                    wire:click="$set('formPriority', 'BAJA')"
                                    class="py-2 px-1 text-center rounded-xl border text-xs font-bold transition-all cursor-pointer {{ $formPriority === 'BAJA' ? 'border-2 border-slate-500 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white ring-2 ring-slate-400/20' : 'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                Baja
                            </button>
                            <button type="button"
                                    wire:click="$set('formPriority', 'MEDIA')"
                                    class="py-2 px-1 text-center rounded-xl border text-xs font-bold transition-all cursor-pointer {{ $formPriority === 'MEDIA' ? 'border-2 border-blue-500 bg-blue-50/60 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 ring-2 ring-blue-400/20 shadow-xs' : 'border-blue-200 dark:border-blue-900/60 text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/20' }}">
                                Media
                            </button>
                            <button type="button"
                                    wire:click="$set('formPriority', 'ALTA')"
                                    class="py-2 px-1 text-center rounded-xl border text-xs font-bold transition-all cursor-pointer {{ $formPriority === 'ALTA' ? 'border-2 border-amber-500 bg-amber-50/60 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 ring-2 ring-amber-400/20 shadow-xs' : 'border-amber-200 dark:border-amber-900/60 text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/20' }}">
                                Alta
                            </button>
                            <button type="button"
                                    wire:click="$set('formPriority', 'CRITICA')"
                                    class="py-2 px-1 text-center rounded-xl border text-xs font-bold transition-all cursor-pointer {{ $formPriority === 'CRITICA' ? 'border-2 border-rose-500 bg-rose-50/60 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 ring-2 ring-rose-400/20 shadow-xs' : 'border-rose-200 dark:border-rose-900/60 text-rose-700 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20' }}">
                                Crítica
                            </button>
                        </div>
                    </div>

                    <!-- Asunto / Resumen breve del problema *: -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Asunto / Resumen breve del problema *:
                        </label>
                        <input type="text"
                               required
                               wire:model="formSubject"
                               placeholder="Ej. Mi computadora no enciende tras corte de energía / Sin acceso a internet"
                               class="w-full text-xs p-2.5 border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white placeholder:text-slate-400">
                    </div>

                    <!-- Detalle del inconveniente (¿Cómo y cuándo ocurrió la falla?) *: -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Detalle del inconveniente (¿Cómo y cuándo ocurrió la falla?) *:
                        </label>
                        <textarea required
                                  rows="3"
                                  wire:model="formDescription"
                                  placeholder="Describa el comportamiento anómalo, mensajes de error que aparezcan en pantalla o ruidos del equipo..."
                                  class="w-full text-xs p-2.5 border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none text-slate-800 dark:text-white placeholder:text-slate-400"></textarea>
                    </div>

                    <!-- Adjunto Multimedia (Captura de pantalla o foto de la falla): -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Adjunto Multimedia (Captura de pantalla o foto de la falla):
                        </label>
                        <div class="flex items-center space-x-3">
                            <label class="cursor-pointer inline-flex items-center px-3.5 py-2 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-xs transition-colors">
                                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Tomar Foto / Subir Imagen</span>
                                <input type="file" wire:model="formEvidencePhoto" accept="image/*" class="hidden">
                            </label>
                            @if ($formEvidencePhoto)
                                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">✓ Archivo seleccionado</span>
                            @endif
                        </div>
                    </div>

                    <!-- Botones Footer -->
                    <div class="pt-3 flex justify-end space-x-2 border-t border-slate-200 dark:border-slate-800">
                        <button type="button"
                                wire:click="$set('isCreateModalOpen', false)"
                                class="px-4 py-2.5 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-xs cursor-pointer">
                            Registrar Ticket de Soporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 2: DETALLE DEL TICKET                                               -->
    <!-- ========================================================================= -->
    @if ($isDetailModalOpen && $selectedTicketForDetail)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-scaleUp">
                <!-- Encabezado Institucional -->
                <div class="bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] px-6 py-4 text-white flex justify-between items-center border-b border-emerald-800 shrink-0">
                    <div class="flex items-center flex-wrap gap-2.5">
                        <span class="font-mono font-bold text-lg text-emerald-300 mr-1">{{ $selectedTicketForDetail->ticket_number }}</span>
                        @php
                            $stUpper = strtoupper($selectedTicketForDetail->status);
                            $stLabel = match($stUpper) {
                                'ABIERTO' => '1. Abierto / Pendiente',
                                'EN_ATENCION' => '2. En Atención Técnica',
                                'EN_PAUSA' => '3. En Pausa / En Espera',
                                'EN_LABORATORIO', 'EN_TALLER' => '4. En Taller / Laboratorio',
                                'RESUELTO' => '5. Resuelto (Visto Bueno Pendiente)',
                                'CERRADO' => '6. Cerrado Formalmente',
                                'CANCELADO' => '7. Cancelado',
                                default => $selectedTicketForDetail->status,
                            };
                            $stBg = match($stUpper) {
                                'ABIERTO' => 'bg-blue-100 text-blue-900',
                                'EN_ATENCION' => 'bg-indigo-100 text-indigo-900',
                                'EN_PAUSA' => 'bg-amber-100 text-amber-900',
                                'EN_LABORATORIO', 'EN_TALLER' => 'bg-purple-100 text-purple-900',
                                'RESUELTO' => 'bg-emerald-100 text-emerald-900',
                                'CERRADO' => 'bg-white text-slate-900',
                                default => 'bg-white text-slate-900',
                            };
                            $prUpper = strtoupper($selectedTicketForDetail->priority);
                            $prBg = match($prUpper) {
                                'CRITICA' => 'bg-red-100 text-red-800',
                                'ALTA' => 'bg-amber-100 text-amber-800',
                                'MEDIA' => 'bg-blue-100 text-blue-800',
                                default => 'bg-slate-100 text-slate-700',
                            };
                            $prLabel = match($prUpper) {
                                'CRITICA' => 'Crítica',
                                'ALTA' => 'Alta',
                                'MEDIA' => 'Media',
                                default => 'Baja',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold shadow-xs {{ $stBg }}">
                            {{ $stLabel }}
                        </span>
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold {{ $prBg }}">
                            {{ $prLabel }}
                        </span>
                    </div>
                    <button type="button"
                            wire:click="$set('isDetailModalOpen', false)"
                            class="text-emerald-200 hover:text-white p-1 rounded-md cursor-pointer transition-colors text-xl font-bold">
                        ✕
                    </button>
                </div>

                <!-- Barra de Acciones Operativas y Técnico Asignado -->
                <div class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 px-6 py-3 flex flex-wrap items-center justify-between gap-2 text-xs shrink-0">
                    <div class="flex items-center space-x-2">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Técnico Asignado:</span>
                        @if ($selectedTicketForDetail->assigned_technician_name)
                            <strong class="font-bold text-slate-800 dark:text-slate-100">{{ $selectedTicketForDetail->assigned_technician_name }}</strong>
                            <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-mono">
                                {{ $selectedTicketForDetail->assigned_technician_phone ?? 'Anexo 104' }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Sin asignar (En cola abierta)</span>
                        @endif
                    </div>

                    <div class="flex items-center flex-wrap gap-2">
                        @if ($selectedTicketForDetail->status === 'ABIERTO')
                            <button type="button"
                                    wire:click="takeTicket({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                ⚡ Tomar Ticket / Atender
                            </button>
                            <button type="button"
                                    wire:click="openReassignModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 rounded-lg font-medium transition-colors cursor-pointer">
                                Asignar a Técnico
                            </button>
                        @elseif (in_array(strtoupper($selectedTicketForDetail->status), ['EN_ATENCION', 'EN_LABORATORIO', 'EN_TALLER']))
                            <button type="button"
                                    wire:click="openTechnicalModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                ✓ Diagnóstico & Solución
                            </button>
                            <button type="button"
                                    wire:click="openSuppliesModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium shadow-xs transition-colors cursor-pointer">
                                📦 Insumos Almacén ({{ $selectedTicketForDetail->supplies->count() }})
                            </button>
                            <button type="button"
                                    wire:click="openProvisionalModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium shadow-xs transition-colors cursor-pointer">
                                🔄 Préstamo Provisional ({{ $selectedTicketForDetail->loans->count() }})
                            </button>
                        @elseif (strtoupper($selectedTicketForDetail->status) === 'RESUELTO')
                            <button type="button"
                                    wire:click="openConformityModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                ⭐ Otorgar Visto Bueno (Cierre)
                            </button>
                        @endif

                        @if ($selectedTicketForDetail->technicalDetail?->is_definitive_decommission)
                            <button type="button"
                                    wire:click="openDecommissionModal({{ $selectedTicketForDetail->id }})"
                                    class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold shadow-xs transition-colors cursor-pointer">
                                📄 Ver Acta de Baja (PDF)
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Cuerpo del Modal con Scroll -->
                <div class="p-6 overflow-y-auto space-y-4 text-xs text-slate-700 dark:text-slate-300 flex-1">
                    <!-- 1. Datos del Solicitante y Activo Tecnológico Vinculado -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] border-b border-slate-200 dark:border-slate-700 pb-1.5">
                                DATOS DEL SOLICITANTE
                            </h4>
                            <p><span class="text-slate-500 dark:text-slate-400">Funcionario:</span> <strong class="text-slate-900 dark:text-white">{{ $selectedTicketForDetail->applicant_name }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Dependencia:</span> <strong class="text-slate-900 dark:text-white">{{ $selectedTicketForDetail->office_name }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Contacto:</span> {{ $selectedTicketForDetail->applicant_phone ?: 'Anexo 115' }}</p>
                            <p><span class="text-slate-500 dark:text-slate-400">Correo:</span> {{ $selectedTicketForDetail->applicant_email ?: 'wsanchez@castilla.gob.pe' }}</p>
                        </div>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] border-b border-slate-200 dark:border-slate-700 pb-1.5">
                                ACTIVO TECNOLÓGICO VINCULADO (QR)
                            </h4>
                            @if ($selectedTicketForDetail->asset_computer_code)
                                <p><span class="text-slate-500 dark:text-slate-400">Código Informático:</span> <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $selectedTicketForDetail->asset_computer_code }}</span></p>
                                <p><span class="text-slate-500 dark:text-slate-400">Categoría:</span> {{ $selectedTicketForDetail->asset_category ?: ($selectedTicketForDetail->category === 'HARDWARE' ? 'Impresora y Multifuncional' : $selectedTicketForDetail->category) }}</p>
                                <p><span class="text-slate-500 dark:text-slate-400">Estado en Inventario:</span> <span class="font-semibold text-emerald-600 dark:text-emerald-400">Registrado en Base ITAM</span></p>
                            @else
                                <p class="text-slate-400 italic py-2">
                                    No se vinculó un activo específico con código QR (Falla general de servicio o software).
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- 2. Incidencia Reportada -->
                    <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 space-y-2 bg-white dark:bg-slate-900">
                        <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] border-b border-slate-200 dark:border-slate-800 pb-1.5 flex justify-between items-center">
                            <span>INCIDENCIA REPORTADA</span>
                            <span class="text-[10px] text-slate-400 font-mono">REGISTRADO: {{ $selectedTicketForDetail->created_at ? $selectedTicketForDetail->created_at->format('d/m/Y, H:i:s') : '11/10/2026, 7:40:49' }}</span>
                        </h4>
                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $selectedTicketForDetail->subject }}</div>
                        <div class="text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60 whitespace-pre-line">
                            {{ $selectedTicketForDetail->description }}
                        </div>
                        @if ($selectedTicketForDetail->evidence_photo_url)
                            <div class="pt-2">
                                <span class="font-semibold block mb-1 text-slate-600 dark:text-slate-400">Foto o Captura de Evidencia:</span>
                                <img src="{{ $selectedTicketForDetail->evidence_photo_url }}"
                                     alt="Evidencia"
                                     class="max-h-56 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs object-contain">
                            </div>
                        @endif
                    </div>

                    <!-- 3. Diagnóstico y Solución Técnica Aplicada (RF-08) -->
                    @if ($selectedTicketForDetail->technicalDetail)
                        <div class="border border-emerald-300 dark:border-emerald-800 bg-white dark:bg-slate-900 rounded-xl p-4 space-y-3">
                            <h4 class="font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-300 text-[11px] border-b border-emerald-100 dark:border-emerald-900 pb-1.5 flex justify-between items-center">
                                <span>DIAGNÓSTICO Y SOLUCIÓN TÉCNICA APLICADA (RF-08)</span>
                                @if ($selectedTicketForDetail->technicalDetail->is_definitive_decommission)
                                    <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 text-[10px] font-bold">
                                        Baja Técnica: {{ $selectedTicketForDetail->technicalDetail->decommission_act_number }}
                                    </span>
                                @endif
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">Diagnóstico / Causa Raíz:</span>
                                    <div class="text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                        {{ $selectedTicketForDetail->technicalDetail->real_diagnosis }}
                                    </div>
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300 block mb-1">Solución Técnica Implementada:</span>
                                    <div class="text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                        {{ $selectedTicketForDetail->technicalDetail->solution_applied }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 4. Visto Bueno de Cierre por el Solicitante (RF-05) -->
                    @if ($selectedTicketForDetail->user_conformity !== null || $selectedTicketForDetail->status === 'CERRADO')
                        <div class="border border-blue-200 dark:border-blue-800 bg-white dark:bg-slate-900 rounded-xl p-4 space-y-2">
                            <h4 class="font-bold uppercase tracking-wider text-blue-800 dark:text-blue-300 text-[11px] border-b border-blue-100 dark:border-blue-900 pb-1.5 flex justify-between items-center">
                                <span>VISTO BUENO DE CIERRE POR EL SOLICITANTE (RF-05)</span>
                                <span class="text-blue-600 dark:text-blue-400 font-bold">
                                    ★ {{ $selectedTicketForDetail->user_rating ?? 5 }}/5 ESTRELLAS
                                </span>
                            </h4>
                            <p class="text-slate-700 dark:text-slate-300 font-medium">
                                Conformidad: <strong class="text-emerald-600 dark:text-emerald-400">✓ Solución Aprobada y Conforme</strong>
                            </p>
                            <div class="text-slate-600 dark:text-slate-300 italic bg-slate-50/60 dark:bg-slate-800 p-3 rounded-xl border border-slate-200 dark:border-slate-700 leading-relaxed">
                                “{{ $selectedTicketForDetail->user_feedback ?: 'Excelente atención. El ingeniero limpió los rodillos de arrastre y ahora jala el papel membretado con normalidad. Muy rápido.' }}”
                            </div>
                        </div>
                    @endif

                    <!-- 5. Insumos y Préstamos Provisionales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 bg-white dark:bg-slate-900 space-y-1">
                            <h5 class="font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide text-[10px] mb-1.5 border-b border-slate-100 dark:border-slate-800 pb-1">
                                INSUMOS DESCARGADOS DE ALMACÉN ({{ $selectedTicketForDetail->supplies->count() }})
                            </h5>
                            @if ($selectedTicketForDetail->supplies->isEmpty())
                                <p class="text-slate-400 italic text-[11px]">Sin consumo de repuestos en almacén.</p>
                            @else
                                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($selectedTicketForDetail->supplies as $s)
                                        <li class="py-1.5 flex justify-between items-center text-[11px]">
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $s->supply_name }}</span>
                                            <strong class="text-amber-700 dark:text-amber-400 font-mono">{{ $s->quantity }} unid.</strong>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 bg-white dark:bg-slate-900 space-y-1">
                            <h5 class="font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide text-[10px] mb-1.5 border-b border-slate-100 dark:border-slate-800 pb-1">
                                ACTIVOS PROVISIONALES DE RESERVA ({{ $selectedTicketForDetail->loans->count() }})
                            </h5>
                            @if ($selectedTicketForDetail->loans->isEmpty())
                                <p class="text-slate-400 italic text-[11px]">Sin equipos de sustitución temporal.</p>
                            @else
                                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @foreach ($selectedTicketForDetail->loans as $l)
                                        <li class="py-1.5 flex justify-between items-center text-[11px]">
                                            <span class="font-medium text-slate-800 dark:text-slate-200">{{ $l->temporary_asset_code }}</span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $l->status === 'PRESTADO' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ $l->status }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- 6. Línea de Tiempo de Auditoría (RNF-04) -->
                    @if ($selectedTicketForDetail->auditLogs && $selectedTicketForDetail->auditLogs->isNotEmpty())
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-800/40">
                            <h4 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] mb-2.5">
                                HISTORIAL DE AUDITORÍA Y TRAZABILIDAD (RNF-04)
                            </h4>
                            <div class="space-y-2">
                                @foreach ($selectedTicketForDetail->auditLogs as $log)
                                    <div class="flex items-start space-x-2.5 text-[11px] border-l-2 border-emerald-500 pl-2.5 py-0.5">
                                        <span class="font-mono text-slate-400 whitespace-nowrap">
                                            {{ $log->timestamp ? $log->timestamp->format('H:i:s') : ($log->created_at ? $log->created_at->format('H:i:s') : '') }}
                                        </span>
                                        <div class="flex-1">
                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $log->action }}:</span>
                                            <span class="text-slate-600 dark:text-slate-400">{{ $log->notes ?: $log->new_status }}</span>
                                            <span class="text-slate-400 block text-[10px]">Por: {{ $log->performed_by_user_name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pie de Modal -->
                <div class="px-6 py-3 bg-slate-50 dark:bg-slate-800/80 border-t border-slate-200 dark:border-slate-800 flex justify-end shrink-0">
                    <button type="button"
                            wire:click="$set('isDetailModalOpen', false)"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-xs cursor-pointer">
                        Cerrar Ficha
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 3: ATENCIÓN Y CIERRE TÉCNICO                                        -->
    <!-- ========================================================================= -->
    @if ($isTechnicalModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 animate-scaleUp">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        Atención Técnica y Evaluación
                    </h3>
                    <button wire:click="$set('isTechnicalModalOpen', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form wire:submit.prevent="saveTechnicalDetail" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Estado Técnico *</label>
                        <select wire:model="formTechnicalStatus" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200">
                            <option value="EN_ATENCION">EN ATENCIÓN</option>
                            <option value="EN_LABORATORIO">EN LABORATORIO O TALLER</option>
                            <option value="RESUELTO">RESUELTO</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Diagnóstico Verificado *</label>
                        <textarea wire:model="formRealDiagnosis" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Solución Técnica Aplicada *</label>
                        <textarea wire:model="formSolutionApplied" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required></textarea>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-amber-900 dark:text-amber-200">
                            <input type="checkbox" wire:model="formIsDecommission" class="rounded text-amber-600">
                            <span>Declarar Baja Patrimonial Definitiva (Daño irreparable)</span>
                        </label>
                        @if ($formIsDecommission)
                            <textarea wire:model="formDecommissionReason" rows="2" placeholder="Motivo técnico de baja..." class="w-full px-2 py-1 bg-white rounded border border-amber-300"></textarea>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isTechnicalModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold">
                            Guardar Atención
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 4: INSUMOS DE ALMACÉN EN TICKET                                     -->
    <!-- ========================================================================= -->
    @if ($isSuppliesModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 animate-scaleUp">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        Consumo de Insumos / Repuestos ITAM
                    </h3>
                    <button wire:click="$set('isSuppliesModalOpen', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form wire:submit.prevent="saveTicketSupply" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Insumo del Almacén *</label>
                        <select wire:model="selectedSupplyId" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required>
                            @foreach ($supplies as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }} (Stock: {{ $sup->stock }} {{ $sup->unit }}s)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Cantidad Consumida *</label>
                        <input type="number" wire:model="formSupplyQuantity" min="1" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 font-bold" required>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Detalle de Instalación</label>
                        <input type="text" wire:model="formSupplyNotes" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isSuppliesModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold">
                            Descontar de Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 5: MECANISMO DE CONFORMIDAD DE CIERRE (RF-05)                       -->
    <!-- ========================================================================= -->
    @if ($isConformityModalOpen && $conformityTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden animate-scaleUp">
                <!-- Encabezado Institucional -->
                <div class="bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] px-6 py-4 text-white flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg leading-tight">Mecanismo de Conformidad de Cierre (RF-05)</h3>
                        <p class="text-xs text-emerald-100 mt-0.5">Ticket: {{ $conformityTicket->ticket_number }} - {{ $conformityTicket->subject }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="$set('isConformityModalOpen', false)"
                        class="text-emerald-200 hover:text-white cursor-pointer transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario de Conformidad -->
                <form wire:submit.prevent="saveUserConformity" class="p-6 space-y-4 text-xs">
                    <!-- Resumen Técnico -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 space-y-1.5">
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Técnico que atendió:</span> {{ $conformityTicket->assigned_technician_name ?: 'Ing. Carlos Mendoza (Soporte TI)' }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Diagnóstico:</span> {{ $conformityTicket->technicalDetail?->real_diagnosis ?: 'Cortocircuito irreversible en etapa de regulación VRM de placa madre e integrado puente sur calcinado.' }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Solución aplicada:</span> {{ $conformityTicket->technicalDetail?->solution_applied ?: 'Equipo tecnológicamente obsoleto sin disponibilidad de placas socket compatibles. Se emite baja técnica.' }}</p>
                    </div>

                    <!-- Pregunta de Visto Bueno -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            ¿El equipo o servicio se encuentra funcionando correctamente?
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                wire:click="setConformity(true)"
                                class="flex items-center justify-center p-3 rounded-xl border text-xs font-medium transition-all cursor-pointer {{ $formUserConformity ? 'bg-green-50 dark:bg-emerald-950/40 border-green-500 text-green-700 dark:text-green-300 shadow-xs ring-2 ring-green-400' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}"
                            >
                                <svg class="w-4 h-4 mr-1.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Sí, Doy mi Visto Bueno</span>
                            </button>

                            <button
                                type="button"
                                wire:click="setConformity(false)"
                                class="flex items-center justify-center p-3 rounded-xl border text-xs font-medium transition-all cursor-pointer {{ !$formUserConformity ? 'bg-red-50 dark:bg-rose-950/40 border-red-500 text-red-700 dark:text-red-300 shadow-xs ring-2 ring-red-400' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}"
                            >
                                <svg class="w-4 h-4 mr-1.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>No, Aún presenta falla</span>
                            </button>
                        </div>
                    </div>

                    <!-- Calificación con Estrellas (RF-05) -->
                    @if ($formUserConformity)
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Calificación de Atención del Técnico:
                            </label>
                            <div class="flex items-center space-x-2">
                                @foreach ([1, 2, 3, 4, 5] as $star)
                                    <button
                                        key="{{ $star }}"
                                        type="button"
                                        wire:click="setRating({{ $star }})"
                                        class="focus:outline-none transition-transform hover:scale-110 cursor-pointer"
                                    >
                                        <svg
                                            class="w-7 h-7 {{ $star <= $formUserRating ? 'text-amber-400 fill-amber-400' : 'text-slate-300 dark:text-slate-600' }}"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                @endforeach
                                @php
                                    $ratingLabel = match($formUserRating) {
                                        5 => 'Excelente',
                                        4 => 'Muy Bueno',
                                        3 => 'Bueno',
                                        2 => 'Regular',
                                        default => 'Malo',
                                    };
                                @endphp
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400 ml-2">
                                    {{ $ratingLabel }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Comentarios / Agradecimiento -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            {{ $formUserConformity ? 'Comentarios / Agradecimiento:' : 'Detalle de por qué no está conforme:' }}
                        </label>
                        <textarea
                            wire:model="formUserFeedback"
                            rows="3"
                            required
                            class="w-full text-xs p-2.5 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            placeholder="{{ $formUserConformity ? 'Ej. Solución verificada satisfactoriamente...' : 'Describa el inconveniente que aún persiste...' }}"
                        ></textarea>
                    </div>

                    <!-- Botones Footer -->
                    <div class="pt-2 flex justify-end space-x-2">
                        <button
                            type="button"
                            wire:click="$set('isConformityModalOpen', false)"
                            class="px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 text-xs font-bold text-white rounded-xl transition-colors shadow-xs cursor-pointer {{ $formUserConformity ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700' }}"
                        >
                            {{ $formUserConformity ? 'Confirmar y Cerrar Ticket' : 'Devolver Ticket a Atención' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 6: REASIGNAR TÉCNICO                                                -->
    <!-- ========================================================================= -->
    @if ($isReassignModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 animate-scaleUp">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        Reasignar Ticket a Técnico ODT
                    </h3>
                    <button wire:click="$set('isReassignModalOpen', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form wire:submit.prevent="saveReassign" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nuevo Técnico Asignado *</label>
                        <select wire:model="reassignUserId" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required>
                            @foreach ($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->full_name }} ({{ $tech->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Motivo de Reasignación</label>
                        <textarea wire:model="reassignNotes" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isReassignModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold">
                            Confirmar Reasignación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 7: ACTA OFICIAL DE BAJA PATRIMONIAL (IMPRIMIBLE)                    -->
    <!-- ========================================================================= -->
    @if ($isDecommissionModalOpen && $decommissionTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-fadeIn">
                <!-- Barra superior de controles (no imprimible) -->
                <div class="flex items-center justify-between px-6 py-3.5 bg-slate-900 text-white print:hidden shrink-0">
                    <div class="flex items-center space-x-2">
                        <span class="h-3 w-3 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="font-semibold text-sm">
                            Documento Oficial: Informe Técnico de Baja Patrimonial (SRS RF-11 / RNF-05)
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="inline-flex items-center px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm cursor-pointer"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Imprimir / Guardar PDF
                        </button>
                        <button
                            type="button"
                            wire:click="$set('isDecommissionModalOpen', false)"
                            class="text-slate-400 hover:text-white p-1 rounded-md transition-colors cursor-pointer text-xl leading-none"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Vista del documento imprimible en formato A4 -->
                <div class="p-8 md:p-12 overflow-y-auto font-serif text-slate-800 bg-white print:p-0 print:m-0 flex-1">
                    <!-- Encabezado Municipal -->
                    <div class="border-b-2 border-slate-900 pb-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="w-20 h-20 shrink-0 flex items-center justify-center bg-blue-50 border border-blue-200 rounded-lg text-blue-900 font-bold text-xs text-center p-1 font-sans">
                                ESCUDO CASTILLA
                            </div>
                            <div class="text-center flex-1 px-4">
                                <h1 class="text-xl font-bold tracking-tight text-slate-900 uppercase font-serif">
                                    MUNICIPALIDAD DISTRITAL DE CASTILLA
                                </h1>
                                <p class="text-xs text-slate-600 font-sans uppercase font-medium tracking-wide">
                                    PROVINCIA Y DEPARTAMENTO DE PIURA - PERÚ
                                </p>
                                <p class="text-xs text-slate-500 font-sans">
                                    Gerencia de Administración y Finanzas | Subgerencia de Informática y Sistemas
                                </p>
                            </div>
                            <div class="w-24 text-right flex flex-col items-end">
                                <div class="text-[10px] font-mono text-slate-500 font-sans">N° EXPEDIENTE</div>
                                <div class="text-xs font-mono font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-200">
                                    {{ $decommissionTicket->technicalDetail?->decommission_act_number ?? 'ACTA-BAJA-2026-0001' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Título Principal del Informe Técnico -->
                    <div class="text-center my-6">
                        <h2 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-300 pb-2 inline-block font-serif">
                            INFORME TÉCNICO DE DECLARATORIA DE INOPERATIVIDAD Y BAJA PATRIMONIAL
                        </h2>
                        <div class="text-xs text-slate-500 font-sans mt-1">
                            Remitido a la Subgerencia de Control Patrimonial y Margesí de Bienes
                        </div>
                    </div>

                    <!-- Tabla de Metadatos Generales -->
                    <div class="bg-slate-50 border border-slate-300 rounded-md p-4 mb-6 font-sans text-xs">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <span class="text-slate-500 block">Fecha de Emisión:</span>
                                <span class="font-semibold text-slate-800">
                                    {{ now()->translatedFormat('d \d\e F \d\e Y') }}
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Ticket Helpdesk Origen:</span>
                                <span class="font-semibold text-blue-700 font-mono">{{ $decommissionTicket->ticket_number }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Oficina / Dependencia:</span>
                                <span class="font-semibold text-slate-800">{{ $decommissionTicket->office_name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Técnico Dictaminante:</span>
                                <span class="font-semibold text-slate-800">{{ $decommissionTicket->assigned_technician_name ?? 'Ing. Carlos Mendoza (Soporte TI)' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 1. Identificación del Equipo Tecnológico Evaluado -->
                    <div class="mb-6 font-sans">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-l-4 border-red-600 pl-2 mb-3">
                            1. IDENTIFICACIÓN DEL EQUIPO TECNOLÓGICO EVALUADO
                        </h3>
                        <table class="w-full text-xs border border-slate-300">
                            <tbody>
                                <tr class="border-b border-slate-200 bg-slate-100/50">
                                    <td class="p-2.5 font-semibold text-slate-600 w-1/4 border-r border-slate-200">Código Informático:</td>
                                    <td class="p-2.5 font-mono font-bold text-blue-800 w-1/4 border-r border-slate-200">
                                        {{ $decommissionTicket->asset_computer_code ?? 'N/A (Periférico / Insumo)' }}
                                    </td>
                                    <td class="p-2.5 font-semibold text-slate-600 w-1/4 border-r border-slate-200">Categoría de Activo:</td>
                                    <td class="p-2.5 text-slate-800 w-1/4">
                                        {{ $decommissionTicket->asset_category ?? $decommissionTicket->category }}
                                    </td>
                                </tr>
                                <tr class="border-b border-slate-200">
                                    <td class="p-2.5 font-semibold text-slate-600 border-r border-slate-200">Asunto Reportado:</td>
                                    <td class="p-2.5 text-slate-800 border-r border-slate-200 font-medium" colspan="3">
                                        {{ $decommissionTicket->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-semibold text-slate-600 border-r border-slate-200">Usuario Custodio:</td>
                                    <td class="p-2.5 text-slate-800 border-r border-slate-200">
                                        {{ $decommissionTicket->applicant_name }}
                                    </td>
                                    <td class="p-2.5 font-semibold text-slate-600 border-r border-slate-200">Estado Técnico Final:</td>
                                    <td class="p-2.5 font-bold text-red-600">INOPERATIVO / PROCEDE A BAJA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- 2. Diagnóstico Técnico de Laboratorio y Verificación de Falla -->
                    <div class="mb-6 font-sans">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-l-4 border-red-600 pl-2 mb-3">
                            2. DIAGNÓSTICO TÉCNICO DE LABORATORIO Y VERIFICACIÓN DE FALLA
                        </h3>
                        <div class="border border-slate-300 rounded p-3 text-xs text-slate-700 bg-slate-50/50 leading-relaxed">
                            <p class="font-semibold text-slate-900 mb-1">Causa Raíz Verificada:</p>
                            <p class="mb-3">
                                {{ $decommissionTicket->technicalDetail?->real_diagnosis ?? 'Cortocircuito irreversible en etapa de regulación VRM de placa madre e integrado puente sur calcinado.' }}
                            </p>
                            <p class="font-semibold text-slate-900 mb-1">Acción Técnica Realizada y Justificación de Baja:</p>
                            <p>
                                {{ $decommissionTicket->technicalDetail?->solution_applied ?? 'Equipo tecnológicamente obsoleto sin disponibilidad de placas socket compatibles. Se emite baja técnica.' }}
                            </p>
                        </div>
                    </div>

                    <!-- 3. Causal Técnica y Destino Final del Bien -->
                    <div class="mb-8 font-sans">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-l-4 border-red-600 pl-2 mb-3">
                            3. CAUSAL TÉCNICA Y DESTINO FINAL DEL BIEN
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="border border-slate-300 rounded p-3">
                                <span class="font-semibold text-slate-800 block mb-1">Causal de Baja (Directiva SBN):</span>
                                <span class="text-slate-600 leading-relaxed">
                                    {{ $decommissionTicket->technicalDetail?->decommission_reason ?? 'Daño físico irreversible por quemadura interna de circuitería principal y costo de reposición no costo-efectivo.' }}
                                </span>
                            </div>
                            <div class="border border-slate-300 rounded p-3">
                                <span class="font-semibold text-slate-800 block mb-1">Destino Final Recomendado:</span>
                                <span class="text-slate-600 leading-relaxed">
                                    {{ $decommissionTicket->technicalDetail?->decommission_destination ?? 'Almacén de Control Patrimonial / Chatarreo Municipal' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bloque Legal de Conformidad -->
                    <div class="text-[11px] text-slate-500 font-sans italic border-t border-slate-300 pt-3 mb-10 leading-tight">
                        Se emite el presente Informe Técnico de acuerdo con las normativas de la Dirección General de Abastecimiento (DGA) y la Superintendencia Nacional de Bienes Estatales (SBN) para los fines de desincorporación física y contable del bien en el Inventario Patrimonial de la Municipalidad Distrital de Castilla.
                    </div>

                    <!-- Bloque de Firmas Cuádruple -->
                    <div class="grid grid-cols-3 gap-8 pt-8 font-sans text-center text-xs">
                        <div class="border-t border-slate-800 pt-2">
                            <div class="font-bold text-slate-900">{{ $decommissionTicket->assigned_technician_name ?? 'Ing. Carlos Mendoza (Soporte TI)' }}</div>
                            <div class="text-[10px] text-slate-600">Técnico de Soporte TI</div>
                            <div class="text-[10px] text-slate-400">Oficina de Informática y Sistemas</div>
                        </div>
                        <div class="border-t border-slate-800 pt-2">
                            <div class="font-bold text-slate-900">Ing. Jefe de Sistemas</div>
                            <div class="text-[10px] text-slate-600">Jefe de la Unidad de TI (V° B°)</div>
                            <div class="text-[10px] text-slate-400">Municipalidad Distrital de Castilla</div>
                        </div>
                        <div class="border-t border-slate-800 pt-2">
                            <div class="font-bold text-slate-900">Control Patrimonial</div>
                            <div class="text-[10px] text-slate-600">Recepción y Baja de Margesí</div>
                            <div class="text-[10px] text-slate-400">Gerencia de Administración y Finanzas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 8: BASE DE CONOCIMIENTOS (RF-14: VISUALIZACIÓN / PUBLICACIÓN)       -->
    <!-- ========================================================================= -->
    @if ($isKbModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-scaleUp">
                <!-- Encabezado del Modal -->
                <div class="p-4 px-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">
                            {{ $kbModalMode === 'CREATE' ? 'Nueva Guía Técnica de Solución (RF-14)' : 'Guía Estandarizada de Soporte TI (RF-14)' }}
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            {{ $kbModalMode === 'CREATE' ? 'Documente el procedimiento estandarizado para resolución de incidencias comunes.' : 'Procedimiento operativo de solución técnica para dependencias municipales.' }}
                        </p>
                    </div>
                    <button type="button" wire:click="$set('isKbModalOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg font-bold cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                @if ($kbModalMode === 'CREATE')
                    <form wire:submit.prevent="saveKbArticle" class="p-6 space-y-4 overflow-y-auto text-xs">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Título de la Guía *:
                                </label>
                                <input type="text"
                                       required
                                       wire:model="formArticleTitle"
                                       placeholder="Ej. Solución a caída de red local..."
                                       class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Categoría *:
                                </label>
                                <select wire:model="formArticleCategory"
                                        class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    <option value="HARDWARE">HARDWARE</option>
                                    <option value="SOFTWARE">SOFTWARE</option>
                                    <option value="RED_INTERNET">RED / INTERNET</option>
                                    <option value="PERMISOS">PERMISOS / ACCESOS</option>
                                    <option value="OTROS">OTROS</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Resumen Breve del Problema *:
                            </label>
                            <input type="text"
                                   required
                                   wire:model="formArticleSummary"
                                   placeholder="Breve descripción ejecutiva de la incidencia que resuelve..."
                                   class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Síntomas Comunes que presenta el usuario:
                            </label>
                            <textarea wire:model="formArticleSymptoms"
                                      rows="2"
                                      placeholder="Ej. LED parpadea en naranja, mensaje de error en pantalla..."
                                      class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Guía de Solución Paso a Paso *:
                            </label>
                            <textarea wire:model="formArticleSteps"
                                      required
                                      rows="6"
                                      placeholder="1. Desconectar el equipo de la corriente...&#10;2. Retirar la compuerta posterior...&#10;3. Limpiar con alcohol isopropílico..."
                                      class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg font-mono text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Etiquetas / Tags (separados por coma):
                            </label>
                            <input type="text"
                                   wire:model="formArticleTags"
                                   placeholder="Impresora, EcoTank, Atasco, Rodillos"
                                   class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                        </div>

                        <div class="pt-2 flex justify-end space-x-2 border-t border-slate-200 dark:border-slate-800">
                            <button type="button"
                                    wire:click="$set('isKbModalOpen', false)"
                                    class="px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm cursor-pointer">
                                Publicar en Base de Conocimientos
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-6 space-y-4 overflow-y-auto">
                        @if ($selectedKbArticle)
                            <div class="border-b border-slate-200 dark:border-slate-800 pb-3">
                                <div class="flex items-center space-x-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        {{ $selectedKbArticle->category }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        Vistas: {{ $selectedKbArticle->views_count }} | Autor: {{ $selectedKbArticle->author_technician_name ?? 'Soporte TI' }}
                                    </span>
                                </div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $selectedKbArticle->title }}</h2>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">{{ $selectedKbArticle->summary }}</p>
                            </div>

                            @if ($selectedKbArticle->symptoms)
                                <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 rounded-lg p-3 text-xs">
                                    <span class="font-semibold text-amber-900 dark:text-amber-300 block mb-1">Síntomas Reportados:</span>
                                    <p class="text-amber-800 dark:text-amber-200">{{ $selectedKbArticle->symptoms }}</p>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-2">
                                    Procedimiento de Solución Estandarizado:
                                </h4>
                                <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 text-xs text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed font-sans">
                                    {{ $selectedKbArticle->solution_steps }}
                                </div>
                            </div>

                            @if ($selectedKbArticle->tags && count($selectedKbArticle->tags) > 0)
                                <div class="flex flex-wrap gap-1.5 pt-2">
                                    @foreach ($selectedKbArticle->tags as $t)
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] rounded-full">
                                            #{{ $t }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                            wire:click="voteHelpful({{ $selectedKbArticle->id }})"
                                            @disabled($kbHasVoted)
                                            class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium transition-all {{ $kbHasVoted ? 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 cursor-pointer' }}">
                                        <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                        </svg>
                                        {{ $kbHasVoted ? '¡Gracias por tu voto!' : '¿Te fue útil esta solución?' }}
                                    </button>
                                    <span class="text-[11px] text-slate-400">
                                        ({{ $selectedKbArticle->helpful_count }} votos de utilidad)
                                    </span>
                                </div>

                                <button type="button"
                                        wire:click="$set('isKbModalOpen', false)"
                                        class="px-4 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded transition-colors cursor-pointer">
                                    Cerrar
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
