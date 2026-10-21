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
                                        @if ($t->assigned_technician_display ?: $t->assigned_technician_name)
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ $t->assigned_technician_display ?: $t->assigned_technician_name }}
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
                                        @php
                                            $canManageRow = $this->canManageTicket($t);
                                            $canReassignRow = $this->canReassignTickets();
                                        @endphp

                                        @if (strtoupper($t->status) === 'ABIERTO' && $canManageRow)
                                            <button type="button"
                                                    wire:click="takeTicket({{ $t->id }})"
                                                    class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px] font-bold shadow-xs transition-colors cursor-pointer"
                                                    title="Tomar Ticket para atención inmediata (RF-07)">
                                                ⚡ Tomar
                                            </button>
                                        @elseif (strtoupper($t->status) === 'EN_PAUSA' && $canManageRow)
                                            <button type="button"
                                                    wire:click="resumeTicket({{ $t->id }})"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-bold shadow-xs transition-colors cursor-pointer"
                                                    title="Reanudar atención técnica">
                                                ▶️ Reanudar
                                            </button>
                                        @elseif (in_array(strtoupper($t->status), ['EN_ATENCION', 'EN_LABORATORIO']) && $canManageRow)
                                            <button type="button"
                                                    wire:click="openPauseModal({{ $t->id }})"
                                                    class="px-2 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded text-[11px] font-bold transition-colors cursor-pointer"
                                                    title="Pausar atención técnica">
                                                ⏸️ Pausar
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

                                        @if (!in_array($t->status, ['RESUELTO', 'CERRADO']) && $canReassignRow)
                                            <button type="button"
                                                    wire:click="openReassignModal({{ $t->id }})"
                                                    class="px-2 py-1 bg-indigo-50 hover:bg-indigo-600 hover:text-white text-indigo-700 dark:bg-indigo-950/60 dark:hover:bg-indigo-600 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 rounded text-[11px] font-bold transition-colors cursor-pointer inline-flex items-center gap-1"
                                                    title="Traspasar ticket a otro técnico (Jefatura)">
                                                <span>⇄</span>
                                                <span>Traspasar</span>
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
