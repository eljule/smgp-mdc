<div>
    <!-- Banner Superior / Hero Section con textura de puntos institucional -->
    <section class="bg-[#046A38] text-white py-10 px-4 sm:px-6 lg:px-8 border-b border-emerald-900/50 relative overflow-hidden"
             style="background-image: radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.15) 2%, transparent 0%), radial-gradient(circle at 75px 75px, rgba(255, 255, 255, 0.15) 2%, transparent 0%); background-size: 100px 100px;">
        <div class="max-w-7xl mx-auto text-center relative z-10 space-y-3">
            <!-- Badge Sesión Activa -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-950/60 border border-emerald-700/60 text-xs font-medium text-emerald-100 shadow-sm backdrop-blur-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Sesión Activa: {{ $userInfo['name'] }} ({{ $userInfo['role'] }})</span>
            </div>

            <!-- Título Principal Institucional -->
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-wide uppercase text-white drop-shadow-xs">
                SISTEMA INTEGRADO DE GESTIÓN MUNICIPAL
            </h1>

            <!-- Subtítulo -->
            <p class="max-w-2xl mx-auto text-xs sm:text-sm md:text-base text-emerald-100/90 font-normal leading-relaxed">
                {{ $userInfo['description'] }}
            </p>
        </div>
    </section>

    <!-- Área de Subsistemas Autorizados -->
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
        <!-- Cabecera de Sección -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    Subsistemas Autorizados
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Acceso concedido a {{ $modules->count() }} subsistemas conforme a las políticas de seguridad de la ODT.
                </p>
            </div>

            <!-- Badge Contador de Módulos Asignados -->
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-700 dark:text-slate-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $modules->count() }} de {{ $totalActiveCount }} módulos asignados</span>
                </span>
            </div>
        </div>

        <!-- Grid de Cards (Horizontal en desktop) -->
        @if ($modules->isEmpty())
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-12 text-center shadow-xs">
                <p class="text-base font-semibold text-slate-700 dark:text-slate-200">
                    No se encontraron módulos autorizados para su usuario.
                </p>
                <p class="text-xs text-slate-500 mt-2">
                    Comuníquese con la Oficina de Desarrollo Tecnológico (ODT) para solicitar asignación de subsistemas.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($modules as $module)
                    @php
                        $isMaintenance = $module->status->value === 'maintenance';
                        $isHelpdesk = $module->code === 'helpdesk';
                        $accentColorClass = match ($module->accent_color->value ?? 'emerald') {
                            'emerald' => 'border-t-[#16a34a]',
                            'amber' => 'border-t-[#ea580c]',
                            'blue' => 'border-t-[#2563eb]',
                            'purple' => 'border-t-[#7c3aed]',
                            default => 'border-t-emerald-600',
                        };
                        $cardBorderClass = $isMaintenance
                            ? 'border-amber-300 dark:border-amber-700/80 ring-1 ring-amber-400/30'
                            : 'border-slate-200/90 dark:border-slate-800';
                    @endphp

                    <div wire:key="module-{{ $module->id }}"
                         class="group bg-white dark:bg-slate-900 border {{ $cardBorderClass }} rounded-2xl shadow-xs hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-6 flex flex-col justify-between border-t-4 {{ $accentColorClass }}">
                        <div>
                            <!-- Header de la tarjeta / Badge de Mantenimiento si aplica -->
                            <div class="h-6 flex justify-end">
                                @if ($isMaintenance)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/80 border border-amber-300 dark:border-amber-800 text-[10px] font-bold text-amber-800 dark:text-amber-300 shadow-xs animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>Mantenimiento</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Ilustración Gráfica Centrada según el código del módulo -->
                            <div class="my-3 flex items-center justify-center relative">
                                @if ($module->code === 'central')
                                    <svg class="w-20 h-20 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="42" fill="#DCFCE7"/>
                                        <rect x="24" y="24" width="22" height="22" rx="4" fill="#16A34A"/>
                                        <rect x="52" y="24" width="24" height="14" rx="4" fill="#86EFAC"/>
                                        <rect x="52" y="44" width="24" height="32" rx="4" fill="#22C55E"/>
                                        <rect x="24" y="52" width="22" height="24" rx="4" fill="#4ADE80"/>
                                        <path d="M28 32H38M28 38H34" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M56 56H68M56 62H64M56 68H62" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                @elseif ($module->code === 'transportes')
                                    <svg class="w-20 h-20 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="42" fill="#FFEDD5"/>
                                        <rect x="26" y="32" width="44" height="28" rx="6" fill="#EA580C"/>
                                        <rect x="30" y="36" width="16" height="12" rx="2" fill="#FED7AA"/>
                                        <rect x="50" y="36" width="16" height="12" rx="2" fill="#FED7AA"/>
                                        <circle cx="34" cy="65" r="9" fill="#431407"/>
                                        <circle cx="34" cy="65" r="4" fill="#F97316"/>
                                        <circle cx="64" cy="65" r="9" fill="#431407"/>
                                        <circle cx="64" cy="65" r="4" fill="#F97316"/>
                                        <path d="M70 42H76C78 42 79 44 79 46V54H70V42Z" fill="#C2410C"/>
                                        <line x1="20" y1="74" x2="80" y2="74" stroke="#FB923C" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                    <div class="absolute -bottom-1 -right-1 bg-amber-500 text-white rounded-full p-1.5 shadow-md text-xs leading-none">
                                        ⚙️
                                    </div>
                                @elseif ($module->code === 'itam')
                                    <svg class="w-20 h-20 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="42" fill="#DBEAFE"/>
                                        <rect x="25" y="24" width="50" height="14" rx="3" fill="#2563EB"/>
                                        <rect x="25" y="42" width="50" height="14" rx="3" fill="#1D4ED8"/>
                                        <rect x="25" y="60" width="50" height="14" rx="3" fill="#1E40AF"/>
                                        <circle cx="32" cy="31" r="2" fill="#60A5FA"/>
                                        <circle cx="38" cy="31" r="2" fill="#4ADE80"/>
                                        <circle cx="32" cy="49" r="2" fill="#60A5FA"/>
                                        <circle cx="38" cy="49" r="2" fill="#4ADE80"/>
                                        <circle cx="32" cy="67" r="2" fill="#60A5FA"/>
                                        <circle cx="38" cy="67" r="2" fill="#FACC15"/>
                                        <line x1="48" y1="31" x2="68" y2="31" stroke="#93C5FD" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="48" y1="49" x2="68" y2="49" stroke="#93C5FD" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="48" y1="67" x2="68" y2="67" stroke="#93C5FD" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                @elseif ($module->code === 'helpdesk')
                                    <svg class="w-20 h-20 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="50" cy="50" r="42" fill="#F3E8FF"/>
                                        <path d="M32 48C32 38.0589 40.0589 30 50 30C59.9411 30 68 38.0589 68 48V56" stroke="#7C3AED" stroke-width="5" stroke-linecap="round"/>
                                        <rect x="28" y="46" width="8" height="16" rx="4" fill="#6D28D9"/>
                                        <rect x="64" y="46" width="8" height="16" rx="4" fill="#6D28D9"/>
                                        <path d="M66 58C66 65 60 70 52 70H48" stroke="#7C3AED" stroke-width="3.5" stroke-linecap="round"/>
                                        <circle cx="45" cy="70" r="4" fill="#A855F7"/>
                                        <circle cx="50" cy="46" r="8" fill="#DDD6FE"/>
                                        <path d="M47 46L49 48L53 44" stroke="#6D28D9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @else
                                    <div class="w-20 h-20 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Título Centrado del Módulo -->
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white text-center leading-snug">
                                {{ $module->name }}
                            </h2>

                            <!-- Descripción Centrada -->
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 text-center leading-relaxed font-normal mt-2.5 px-1">
                                @if ($isMaintenance)
                                    ⚠️ {{ $module->status_message ?? $module->description }}
                                @else
                                    {{ $module->description }}
                                @endif
                            </p>

                            <!-- Badge Tiempo de Espera si está en mantenimiento -->
                            @if ($isMaintenance && $module->wait_time)
                                <div class="mt-3 text-center">
                                    <span class="inline-block px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-[11px] font-semibold text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        ⏱️ Espera aprox: {{ $module->wait_time }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Botones de Acción -->
                        <div class="pt-6 {{ $module->secondary_action ? 'space-y-2' : '' }}">
                            @if ($isMaintenance)
                                <a href="{{ url('/mantenimiento?code=' . $module->code) }}"
                                   class="w-full bg-[#d97706] hover:bg-[#b45309] text-white py-2.5 px-4 rounded-lg font-semibold text-sm flex items-center justify-center gap-2 shadow-xs transition-colors">
                                    <span>En Mantenimiento (Ver Espera)</span>
                                    <span class="text-base leading-none">&rarr;</span>
                                </a>
                            @else
                                <a href="{{ url($module->route) }}"
                                   class="w-full bg-[#046A38] hover:bg-[#03512b] text-white py-2.5 px-4 rounded-lg font-semibold text-sm flex items-center justify-center gap-2 shadow-xs transition-colors">
                                    <span>{{ $isHelpdesk ? 'Ingresar al Panel' : 'Ingresar al Sistema' }}</span>
                                    <span class="text-base leading-none">&rarr;</span>
                                </a>
                            @endif

                            @if (! $isMaintenance && $module->secondary_action)
                                <a href="{{ url('/soporte/nuevo-ticket') }}"
                                   class="w-full bg-white hover:bg-purple-50/80 text-purple-700 dark:text-purple-300 dark:bg-slate-800 dark:hover:bg-purple-950/50 border border-purple-200 dark:border-purple-800/80 py-2.5 px-4 rounded-lg font-semibold text-sm flex items-center justify-center gap-2 shadow-2xs transition-colors cursor-pointer">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <span>{{ $module->secondary_action['label'] ?? 'Generar Ticket de Soporte' }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal Interactivo: Generar Ticket de Soporte (Manejado con Alpine.js) -->
    <x-ui.modal name="ticket-modal">
        <div class="space-y-4">
            <!-- Header Modal -->
            <div class="flex items-start justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center border border-purple-100 dark:border-purple-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">Generar Ticket de Soporte</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Mesa de partes TI - Municipalidad Distrital de Castilla</p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="$dispatch('close-modal', 'ticket-modal')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Cuerpo del Modal -->
            <div class="py-2 space-y-3 text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                <p>
                    Está a punto de ingresar al portal directo de incidencias informáticas. Como colaborador municipal, podrá:
                </p>
                <ul class="space-y-1.5 pl-4 list-disc text-gray-500 dark:text-gray-400">
                    <li>Reportar fallas de hardware (impresoras, computadoras, redes).</li>
                    <li>Solicitar asistencia técnica para software y aplicativos SIGM.</li>
                    <li>Consultar el estado de atención y tiempos de respuesta de la ODT.</li>
                </ul>
            </div>

            <!-- Footer / Acciones del Modal -->
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                <x-ui.button
                    variant="secondary"
                    size="md"
                    @click="$dispatch('close-modal', 'ticket-modal')">
                    Cancelar
                </x-ui.button>

                <x-ui.button
                    variant="primary"
                    size="md"
                    href="{{ url('/soporte') }}"
                    class="bg-purple-700 hover:bg-purple-800 focus-visible:ring-purple-500">
                    <span>Continuar al Formulario de Tickets &rarr;</span>
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal>
</div>
