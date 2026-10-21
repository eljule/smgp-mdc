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

            <!-- ========================================================================= -->
            <!-- BANDEJA DE TICKETS SIN ATENDER (RF-13 BALANCEO INTELIGENTE)               -->
            <!-- ========================================================================= -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xs border border-slate-200 dark:border-slate-800 relative z-20">
                <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-amber-500/5 via-slate-50 to-indigo-500/5 dark:from-amber-950/20 dark:via-slate-900 dark:to-indigo-950/20 rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg font-bold shrink-0">
                            📥
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-sm text-slate-900 dark:text-white">
                                    Tickets Sin Atender en Cola
                                </h3>
                                @if(count($unassignedTickets) > 0)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 animate-pulse">
                                        {{ count($unassignedTickets) }} pendientes
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        0 pendientes
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                Incidencias en espera de asignación. Asigne al técnico disponible o con menor carga de trabajo para balancear el equipo.
                            </p>
                        </div>
                    </div>

                    @if(count($unassignedTickets) > 0 && $leastLoadedTechnician)
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button"
                                    wire:click="autoAssignAllUnattended"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all shadow-xs cursor-pointer">
                                <span>⚡</span>
                                <span>Balancear Todos Automáticamente</span>
                            </button>
                        </div>
                    @endif
                </div>

                @if(count($unassignedTickets) > 0)
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($unassignedTickets as $t)
                            <div class="p-4 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-3 {{ $loop->last ? 'rounded-b-2xl' : '' }}">
                                <div class="space-y-1 min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-2 py-0.5 rounded">
                                            {{ $t->ticket_number }}
                                        </span>
                                        @php
                                            $priorityBadge = match($t->priority) {
                                                'CRITICA' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300',
                                                'ALTA' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                                'MEDIA' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                                                default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                                            };
                                        @endphp
                                        <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded {{ $priorityBadge }}">
                                            Prioridad: {{ $t->priority ?? 'MEDIA' }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">
                                            • {{ $t->created_at ? $t->created_at->diffForHumans() : 'Reciente' }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">
                                        {{ $t->subject }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                        📍 {{ $t->office_name ?? $t->office?->name ?? 'Dependencia no especificada' }} • 👤 {{ $t->applicant_name }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if($leastLoadedTechnician)
                                        <button type="button"
                                                wire:click="assignTicketToLeastLoaded({{ $t->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-xs cursor-pointer"
                                                title="Asignar al técnico con menor carga actual ({{ $leastLoadedTechnician['technicianName'] }})">
                                            <span>⚡ Asignar a {{ $leastLoadedTechnician['technicianName'] }}</span>
                                            <span class="text-[10px] bg-emerald-800/80 px-1.5 py-0.5 rounded font-mono">
                                                {{ $leastLoadedTechnician['inProgressCount'] }} activos
                                            </span>
                                        </button>
                                    @endif

                                    <!-- Selector para asignar a otro técnico específico -->
                                    <div class="relative" x-data="{ open: false, dropUp: false }" x-init="$watch('open', value => { if (value) { const rect = $el.getBoundingClientRect(); dropUp = (window.innerHeight - rect.bottom) < 220; } })">
                                        <button type="button"
                                                @click="open = !open"
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-medium transition-colors cursor-pointer flex items-center gap-1">
                                            <span>Otro</span>
                                            <span class="text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''">▾</span>
                                        </button>
                                        <div x-show="open"
                                             @click.outside="open = false"
                                             x-cloak
                                             :class="dropUp ? 'bottom-full mb-1.5' : 'top-full mt-1.5'"
                                             class="absolute right-0 w-56 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 p-1.5 z-50 text-xs">
                                            <span class="block px-2.5 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 mb-1">
                                                Asignar a:
                                            </span>
                                            <div class="space-y-0.5">
                                                @foreach($metrics['techniciansWorkload'] ?? [] as $tech)
                                                    @php
                                                        $techDisplayUser = $tech['username'] ?? null;
                                                        if (!$techDisplayUser && preg_match('/\(([^)]+)\)$/', (string)$tech['technicianName'], $m)) {
                                                            $techDisplayUser = trim($m[1]);
                                                        }
                                                        $techDisplayUser = $techDisplayUser ?: $tech['technicianName'];
                                                    @endphp
                                                    <button type="button"
                                                            wire:click="assignTicketToTechnician({{ $t->id }}, '{{ addslashes($tech['technicianName']) }}')"
                                                            @click="open = false"
                                                            class="w-full text-left px-2.5 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-between cursor-pointer transition-colors"
                                                            title="{{ $tech['technicianName'] }}">
                                                        <span class="truncate font-mono font-semibold text-slate-800 dark:text-slate-200">
                                                            {{ $techDisplayUser }}
                                                        </span>
                                                        <span class="text-[10px] font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-1.5 py-0.5 rounded ml-2 shrink-0">
                                                            {{ $tech['inProgressCount'] }} activos
                                                        </span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-6 text-center">
                        <span class="text-2xl block mb-1">🎉</span>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                            No hay tickets sin atender en cola
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                            Todas las incidencias están debidamente asignadas y en atención por el personal técnico.
                        </p>
                    </div>
                @endif
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
                            <div class="flex items-center gap-2">
                                @if($tech['inProgressCount'] > 0 && $this->canReassignTickets())
                                    <button type="button"
                                            wire:click="openBulkTransferModal('{{ addslashes($tech['technicianName']) }}')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-600 hover:text-white dark:bg-indigo-950/60 dark:text-indigo-300 dark:hover:bg-indigo-600 border border-indigo-200 dark:border-indigo-800 transition-all cursor-pointer shadow-xs active:scale-95"
                                            title="Traspasar carga pendiente de {{ $tech['technicianName'] }} por contingencia o descanso médico">
                                        <span>⇄</span>
                                        <span>Traspasar Carga</span>
                                        <span class="text-[10px] bg-indigo-200/80 dark:bg-indigo-900 px-1.5 py-0.2 rounded font-mono font-black">
                                            {{ $tech['inProgressCount'] }}
                                        </span>
                                    </button>
                                @endif
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
                                        class="p-2 bg-slate-50 dark:bg-slate-800 rounded-lg text-xs cursor-pointer hover:bg-indigo-50 dark:hover:bg-slate-700 transition-colors flex justify-between items-center group"
                                    >
                                        <div class="truncate mr-2 min-w-0">
                                            <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                                {{ $t->ticket_number }}
                                            </span> - {{ $t->subject }}
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            @if(!in_array($t->status, ['RESUELTO', 'CERRADO']) && $this->canReassignTickets())
                                                <button type="button"
                                                        wire:click.stop="openReassignModal({{ $t->id }})"
                                                        class="opacity-0 group-hover:opacity-100 focus:opacity-100 px-1.5 py-0.5 rounded text-[10px] font-bold text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-300 dark:hover:bg-indigo-600 border border-indigo-200 dark:border-indigo-800 transition-all cursor-pointer flex items-center gap-0.5"
                                                        title="Traspasar ticket {{ $t->ticket_number }} a otro técnico (Jefatura)">
                                                    <span>⇄</span>
                                                    <span>Traspasar</span>
                                                </button>
                                            @endif
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
