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
                                    <span>{{ $t->assigned_technician_display ?: ($t->assigned_technician_name ?? 'En asignación de Sistemas') }}</span>
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
