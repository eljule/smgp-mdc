<div class="space-y-6 pb-12">
    <!-- Notificación Flotante con Rebote -->
    <x-floating-toast :message="$alertMessage" :type="$alertType ?? 'success'" />

    <!-- Encabezado del Tab / Header Principal -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <div>
            <div class="flex items-center space-x-2">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Tabla Maestra de Personas
                </h2>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold">
                    {{ $total }} registros
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Clasificación fundamental del SIGM: <strong>Personal</strong> (servidores municipales) y <strong>Administrados</strong> (ciudadanos con trámites en línea o presenciales).
            </p>
        </div>

        <button
            type="button"
            wire:click="openCreateModal"
            class="px-4 py-2.5 rounded-lg bg-[#00965e] hover:bg-[#007f50] text-white font-semibold text-xs sm:text-sm shadow-xs transition-all hover:scale-105 flex items-center space-x-1.5 cursor-pointer shrink-0"
        >
            <span class="text-base leading-none font-bold">+</span>
            <span>Registrar Persona</span>
        </button>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs">
        <!-- Pestañas de Tipo / Clasificación -->
        <div class="flex items-center space-x-1.5 bg-slate-100 dark:bg-slate-800 p-1 rounded-lg text-xs w-full sm:w-auto">
            <button
                type="button"
                wire:click="setFilter('TODOS')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer {{ $filterType === 'TODOS'
                    ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                Todos ({{ $counts['total'] }})
            </button>
            <button
                type="button"
                wire:click="setFilter('PERSONAL')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer flex items-center justify-center gap-1.5 {{ $filterType === 'PERSONAL'
                    ? 'bg-white dark:bg-slate-900 text-emerald-800 dark:text-emerald-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>💼</span>
                <span>Personal Municipal ({{ $counts['personal'] }})</span>
            </button>
            <button
                type="button"
                wire:click="setFilter('ADMINISTRADO')"
                class="px-3 py-1.5 rounded-md font-semibold transition-colors flex-1 sm:flex-initial cursor-pointer flex items-center justify-center gap-1.5 {{ $filterType === 'ADMINISTRADO'
                    ? 'bg-white dark:bg-slate-900 text-blue-800 dark:text-blue-300 shadow-xs font-bold'
                    : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                }}"
            >
                <span>🏛️</span>
                <span>Administrados / Ciudadanos ({{ $counts['administrados'] }})</span>
            </button>
        </div>

        <!-- Buscador -->
        <div class="flex items-center space-x-2 w-full sm:w-80">
            <input
                type="text"
                placeholder="Buscar por DNI, RUC, nombre..."
                wire:model.live.debounce.300ms="search"
                class="w-full px-3 py-1.5 text-xs rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            />
            <button
                type="button"
                class="px-3.5 py-1.5 rounded-lg bg-[#00965e] hover:bg-[#007f50] text-white text-xs font-semibold shadow-xs cursor-pointer"
            >
                Buscar
            </button>
        </div>
    </div>

    <!-- Tabla Principal de Personas -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">DOCUMENTO</th>
                        <th class="py-3 px-4">CLASIFICACIÓN</th>
                        <th class="py-3 px-4">NOMBRES / RAZÓN SOCIAL</th>
                        <th class="py-3 px-4">CONTACTO</th>
                        <th class="py-3 px-4">DETALLE INSTITUCIONAL / TRÁMITE</th>
                        <th class="py-3 px-4 text-center">ESTADO</th>
                        <th class="py-3 px-4 text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($people as $person)
                        @php
                            $isPersonal = ($person['type'] ?? 'PERSONAL') === 'PERSONAL';
                            $isCesado = ($person['laborStatus'] ?? 'ACTIVO') === 'CESADO';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors {{ $isCesado ? 'bg-slate-50/50 dark:bg-slate-900/40 opacity-75' : '' }}">
                            <!-- Documento -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                <span class="text-[10px] text-slate-400 font-normal mr-1">
                                    {{ $person['documentType'] ?? 'DNI' }}:
                                </span>
                                {{ $person['documentNumber'] }}
                            </td>

                            <!-- Clasificación -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if ($isPersonal)
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#e6f7ef] dark:bg-emerald-950/60 text-[#059669] dark:text-emerald-300 border border-emerald-300/60 dark:border-emerald-800">
                                        <span>💼</span>
                                        <span>Personal</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border border-blue-300/60 dark:border-blue-800">
                                        <span>🏛️</span>
                                        <span>Administrado</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Nombres / Razón Social -->
                            <td class="py-3.5 px-4 font-bold text-slate-900 dark:text-white uppercase">
                                {{ $person['fullName'] }}
                            </td>

                            <!-- Contacto -->
                            <td class="py-3.5 px-4 space-y-0.5 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                <div class="flex items-center space-x-1 text-[11px]">
                                    <span class="text-purple-500">📧</span>
                                    <span class="truncate max-w-[160px] text-slate-500 dark:text-slate-400">{{ $person['email'] ?: '—' }}</span>
                                </div>
                                <div class="flex items-center space-x-1 text-[11px]">
                                    <span class="text-rose-500">📞</span>
                                    <span class="text-slate-500 dark:text-slate-400">{{ $person['phone'] ?: '—' }}</span>
                                </div>
                            </td>

                            <!-- Detalle Institucional / Trámite -->
                            <td class="py-3.5 px-4 max-w-xs">
                                @if ($isPersonal)
                                    <div class="font-bold text-slate-800 dark:text-slate-200 uppercase leading-snug">
                                        {{ $person['position'] ?: 'SERVIDOR MUNICIPAL' }}
                                    </div>
                                    <div class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium truncate mt-0.5">
                                        {{ $person['office'] ?: '—' }}
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            {{ $person['laborCondition'] ?? 'CAS' }}
                                        </span>
                                    </div>
                                @else
                                    <div class="text-slate-500 dark:text-slate-400 text-xs italic">
                                        Ciudadano / Contribuyente
                                    </div>
                                    @if (! empty($person['allowsOnlineAccess']))
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            ✓ Habilitado Trámites Digitales
                                        </span>
                                    @endif
                                @endif
                            </td>

                            <!-- Estado -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if ($isCesado)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 dark:text-rose-400">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Cesado</span>
                                    </span>
                                    @if (! empty($person['departureDate']))
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            {{ $person['departureDate'] }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Activo</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button
                                        type="button"
                                        wire:click="openEditModal({{ $person['id'] }})"
                                        class="px-2.5 py-1 rounded-lg border border-blue-200 dark:border-blue-800/80 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/50 font-semibold text-[11px] transition-colors cursor-pointer flex items-center space-x-1"
                                    >
                                        <span>✏️</span>
                                        <span>Editar</span>
                                    </button>

                                    @if ($isPersonal)
                                        @if ($isCesado)
                                            <button
                                                type="button"
                                                wire:click="openCeaseDetailModal({{ $person['id'] }})"
                                                class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold text-[11px] transition-colors cursor-pointer flex items-center space-x-1"
                                            >
                                                <span>📋</span>
                                                <span>Ver Cese</span>
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="openCeaseModal({{ $person['id'] }})"
                                                class="px-2.5 py-1 rounded-lg border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-semibold text-[11px] transition-colors cursor-pointer flex items-center space-x-1"
                                            >
                                                <span>🔴</span>
                                                <span>Cese / Baja</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No se encontraron registros de personas para el criterio especificado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Barra de Paginación -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
            <!-- Izquierda: Contador y Selector por Página -->
            <div class="flex items-center gap-3">
                <span>
                    Mostrando <strong class="text-slate-800 dark:text-slate-200">{{ $from }}</strong> a <strong class="text-slate-800 dark:text-slate-200">{{ $to }}</strong> de <strong class="text-slate-800 dark:text-slate-200">{{ $total }}</strong> personas
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="text-[11px]">Filas por pág:</span>
                    <select
                        wire:model.live="perPage"
                        class="rounded-md border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-2 py-1 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                    >
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <!-- Derecha: Navegación Numérica de Páginas -->
            <div class="flex items-center space-x-1">
                <button
                    type="button"
                    wire:click="gotoPage({{ $currentPage - 1 }})"
                    @disabled($currentPage <= 1)
                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer"
                >
                    &lsaquo; Anterior
                </button>

                @for ($p = 1; $p <= $totalPages; $p++)
                    <button
                        type="button"
                        wire:click="gotoPage({{ $p }})"
                        class="w-7 h-7 rounded-md font-bold text-xs flex items-center justify-center transition-colors cursor-pointer {{ $p === $currentPage
                            ? 'bg-[#00965e] text-white shadow-xs'
                            : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800'
                        }}"
                    >
                        {{ $p }}
                    </button>
                @endfor

                <button
                    type="button"
                    wire:click="gotoPage({{ $currentPage + 1 }})"
                    @disabled($currentPage >= $totalPages)
                    class="px-2.5 py-1 rounded-md border border-slate-200 dark:border-slate-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer"
                >
                    Siguiente &rsaquo;
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. MODAL DE FORMULARIO: REGISTRO / EDICIÓN DE PERSONA                      -->
    <!-- ========================================================================= -->
    @include('livewire.admin.partials.person-form-modal')

<!-- ========================================================================= -->
    <!-- 2. MODAL DE CESE LABORAL Y ENTREGA DE CARGO                               -->
    <!-- ========================================================================= -->
    @if ($showCeaseModal && $ceasePersonInfo)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
            <div 
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-3xl w-full p-5 sm:p-6 shadow-2xl relative max-h-[92vh] overflow-y-auto space-y-3"
                style="scrollbar-width: thin; scrollbar-color: #94a3b8 #f1f5f9;"
            >
                
                <!-- Botón Cerrar Modal -->
                <button 
                    type="button" 
                    wire:click="closeModal" 
                    class="absolute top-4 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold leading-none cursor-pointer transition"
                    title="Cerrar modal"
                >
                    &times;
                </button>

                <!-- Header Principal del Modal -->
                <div class="flex items-center space-x-3 pr-8">
                    <!-- Icono squircle rosado con círculo rojo y blanco interior -->
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm" style="background-color: #ffe4e6; border: 1px solid #fecdd3;">
                        <svg class="w-5.5 h-5.5" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" fill="#f43f5e" fill-opacity="0.25"/>
                            <circle cx="12" cy="12" r="7" fill="#e11d48"/>
                            <circle cx="12" cy="12" r="2.8" fill="#ffffff"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-[17px] sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                            Cese Laboral y Entrega de Cargo
                        </h2>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 font-normal">
                            Procedimiento formal de desvinculación, bloqueo de credenciales y custodia de bienes
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 1: Colaborador Municipal a Cesar -->
                <div class="border border-slate-200/90 dark:border-slate-800 rounded-2xl p-3.5 sm:p-4 bg-white dark:bg-slate-800/40 shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-[#e11d48] tracking-wider uppercase">
                            COLABORADOR MUNICIPAL A CESAR
                        </span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold text-slate-700 bg-slate-100 dark:bg-slate-700/80 dark:text-slate-300 border border-slate-200/70 dark:border-slate-700">
                            {{ $ceasePersonInfo['laborCondition'] ?? 'CAS' }}
                        </span>
                    </div>

                    <div class="mt-1 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                        <div>
                            <h3 class="text-[14px] sm:text-[15px] font-extrabold text-slate-900 dark:text-white tracking-tight">
                                {{ mb_strtoupper($ceasePersonInfo['fullName']) }}
                            </h3>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                DNI: <span class="font-bold text-slate-800 dark:text-slate-200">{{ $ceasePersonInfo['documentNumber'] }}</span>
                                <span class="mx-1 text-slate-400">&bull;</span>
                                Cargo: <span class="font-bold text-slate-800 dark:text-slate-200 uppercase">{{ $ceasePersonInfo['position'] ?: 'SIN CARGO ASIGNADO' }}</span>
                            </div>
                        </div>
                        <div class="text-left sm:text-right sm:self-center mt-0.5 sm:mt-0">
                            <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 tracking-wide uppercase">
                                {{ !empty($ceasePersonInfo['officeAcronym']) ? '['.$ceasePersonInfo['officeAcronym'].'] ' : '' }}{{ mb_strtoupper($ceasePersonInfo['officeName'] ?? $ceasePersonInfo['office'] ?? '') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bloque 2 Columnas: Registro en BD & Cuenta SIGM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Tarjeta Izquierda: Registro en Base de Datos -->
                    <div class="border border-slate-200/90 dark:border-slate-800 rounded-2xl p-3.5 bg-white dark:bg-slate-800/40 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center space-x-1.5 mb-1.5">
                                <span class="text-sm">🗂️</span>
                                <h4 class="text-[12px] sm:text-[12.5px] font-bold text-slate-900 dark:text-slate-100">
                                    Registro en Base de Datos
                                </h4>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-2.5">
                                El registro <strong class="text-slate-800 dark:text-slate-200 font-bold">NO se elimina</strong> para proteger la auditoría histórica de tickets y resoluciones. El estado laboral pasará a <strong class="text-rose-600 dark:text-rose-400 font-bold">CESADO</strong>.
                            </p>
                        </div>
                        <div class="p-2 bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/60 rounded-xl text-[11px] text-emerald-800 dark:text-emerald-300 flex items-start space-x-1.5">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0 mt-0.5">✓</span>
                            <span class="leading-snug">
                                Podrá interactuar a futuro como <strong class="font-bold">Administrado (Ciudadano)</strong> sin duplicar su DNI.
                            </span>
                        </div>
                    </div>

                    <!-- Tarjeta Derecha: Cuenta de Acceso SIGM -->
                    <div class="border border-slate-200/90 dark:border-slate-800 rounded-2xl p-3.5 bg-white dark:bg-slate-800/40 shadow-sm">
                        <div class="flex items-center space-x-1.5 mb-1.5">
                            <span class="text-sm">🔐</span>
                            <h4 class="text-[12px] sm:text-[12.5px] font-bold text-slate-900 dark:text-slate-100">
                                Cuenta de Acceso SIGM
                            </h4>
                        </div>
                        @if ($ceasePersonInfo['hasUserAccount'])
                            <div class="text-[11px] text-slate-600 dark:text-slate-300 space-y-1.5">
                                <p class="leading-relaxed">
                                    Cuenta activa en el sistema: <strong class="font-mono text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded">{{ $ceasePersonInfo['username'] }}</strong>.
                                </p>
                                <div class="p-1.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-lg text-amber-800 dark:text-amber-300 text-[10.5px]">
                                    ⚠️ El acceso será <strong>inactivado automáticamente</strong> al confirmar el cese.
                                </div>
                            </div>
                        @else
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                                No registra cuenta de usuario activa en el sistema.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Tarjeta: Custodia de Activos Tecnológicos (ITAM) -->
                <div class="border border-slate-200/90 dark:border-slate-800 rounded-2xl p-3.5 bg-white dark:bg-slate-800/40 shadow-sm space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-sm">🖥️</span>
                            <h4 class="text-[12px] sm:text-[12.5px] font-bold text-slate-900 dark:text-slate-100">
                                Custodia de Activos Tecnológicos (ITAM)
                            </h4>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-50 border border-rose-200/80 text-rose-600 dark:bg-rose-950/40 dark:border-rose-800/60 dark:text-rose-400">
                            {{ count($ceaseAssets) }} activos en custodia
                        </span>
                    </div>

                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                        Los siguientes equipos están bajo la responsabilidad del colaborador. Se generará un <strong class="text-slate-800 dark:text-slate-200 font-bold">Acta Oficial de Movimiento (DEVOLUCIÓN A ALMACÉN)</strong> por cada bien para completar la entrega de cargo formal.
                    </p>

                    @if (count($ceaseAssets) > 0)
                        <div class="border border-slate-200/80 dark:border-slate-700/80 rounded-xl overflow-hidden shadow-sm">
                            <table class="w-full text-[11px] text-left">
                                <thead class="bg-slate-50/75 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-semibold text-[11px] border-b border-slate-200/80 dark:border-slate-700/80">
                                    <tr>
                                        <th class="py-2 px-3">Código</th>
                                        <th class="py-2 px-3">Categoría</th>
                                        <th class="py-2 px-3">Marca / Modelo</th>
                                        <th class="py-2 px-3 text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 bg-white dark:bg-slate-900/50">
                                    @foreach ($ceaseAssets as $ast)
                                        <tr>
                                            <td class="py-2 px-3">
                                                <span class="font-mono font-bold text-slate-900 dark:text-white block">{{ $ast['computerCode'] }}</span>
                                                @if (!empty($ast['patrimonialCode']))
                                                    <span class="text-[9.5px] text-slate-400 font-mono block">Pat: {{ $ast['patrimonialCode'] }}</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-3 text-slate-700 dark:text-slate-300 font-normal">
                                                {{ $ast['categoryName'] }}
                                            </td>
                                            <td class="py-2 px-3 text-slate-700 dark:text-slate-300 font-normal">
                                                {{ $ast['brandModel'] }}
                                            </td>
                                            <td class="py-2 px-3 text-center">
                                                <span class="px-2 py-0.5 rounded text-[9.5px] font-bold tracking-wide" style="background-color: #dcfce7; color: #15803d;">
                                                    {{ $ast['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-2 bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200/70 dark:border-emerald-800/50 rounded-xl text-[11px] text-emerald-800 dark:text-emerald-300 flex items-center space-x-1.5">
                            <span class="font-bold text-emerald-600">✓</span>
                            <span>El colaborador no tiene activos tecnológicos asignados a su custodia actualmente.</span>
                        </div>
                    @endif

                    <!-- Checkbox de Desvinculación y Almacén de Recepción -->
                    <div class="p-2.5 bg-slate-50/60 dark:bg-slate-800/50 rounded-xl border border-slate-200/90 dark:border-slate-700/80 space-y-1.5">
                        <label class="flex items-center space-x-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model.live="ceaseReturnAssets" class="w-3.5 h-3.5 rounded text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer" />
                            <span class="text-[11px] sm:text-[11.5px] font-bold text-slate-800 dark:text-white">
                                Desvincular bienes y retornar a Almacén / Stock Disponible
                            </span>
                        </label>
                        @if ($ceaseReturnAssets)
                            <div>
                                <label class="block text-[9.5px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">
                                    OFICINA / DEPÓSITO DE RECEPCIÓN
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="ceaseWarehouseOffice" 
                                    class="w-full px-3 py-1.5 text-[11px] rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm" 
                                />
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Formulario Formal de Cese (2 Columnas) -->
                <form wire:submit.prevent="saveCease" class="space-y-2.5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Fecha Oficial de Cese / Término *
                            </label>
                            <input 
                                type="date" 
                                wire:model="ceaseDepartureDate" 
                                required 
                                class="w-full px-3 py-1.5 text-[11px] rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm cursor-pointer" 
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Motivo del Cese *
                            </label>
                            <select 
                                wire:model="ceaseReason" 
                                class="w-full px-3 py-1.5 text-[11px] rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm cursor-pointer"
                            >
                                <option value="RENUNCIA VOLUNTARIA">RENUNCIA VOLUNTARIA</option>
                                <option value="TÉRMINO DE CONTRATO">TÉRMINO DE CONTRATO</option>
                                <option value="TÉRMINO DE CONTRATO CAS">TÉRMINO DE CONTRATO CAS</option>
                                <option value="DESTITUCIÓN">DESTITUCIÓN</option>
                                <option value="MUTUO DISENSO">MUTUO DISENSO</option>
                                <option value="JUBILACIÓN">JUBILACIÓN</option>
                                <option value="FALLECIMIENTO">FALLECIMIENTO</option>
                                <option value="OTRO MOTIVO INSTITUCIONAL">OTRO MOTIVO INSTITUCIONAL</option>
                            </select>
                        </div>
                    </div>

                    @if ($ceaseReason === 'OTRO MOTIVO INSTITUCIONAL')
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Especificar Motivo Institucional *
                            </label>
                            <input 
                                type="text" 
                                wire:model="ceaseCustomReason" 
                                required 
                                placeholder="Especifique la causal de cese..." 
                                class="w-full px-3 py-1.5 text-[11px] rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm" 
                            />
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Documento de Respaldo / Autorizado Por
                            </label>
                            <input 
                                type="text" 
                                wire:model="ceaseAuthorizedBy" 
                                placeholder="Ej. RESOLUCIÓN DE ALCALDÍA N° 120-2026-MDC" 
                                class="w-full px-3 py-1.5 text-[11px] rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 uppercase shadow-sm" 
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Observaciones / Notas
                            </label>
                            <input 
                                type="text" 
                                wire:model="ceaseNotes" 
                                placeholder="Notas u observaciones de entrega de cargo" 
                                class="w-full px-3 py-1.5 text-[11px] rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-sm" 
                            />
                        </div>
                    </div>

                    <!-- Footer de Acciones del Modal -->
                    <div class="pt-2.5 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px] hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer shadow-sm"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl text-white font-bold text-[11.5px] shadow-md shadow-rose-500/25 flex items-center space-x-2 transition cursor-pointer hover:opacity-95 active:scale-[0.98]"
                            style="background-color: #e11d48;"
                        >
                            <span class="w-3.5 h-3.5 rounded-full border-2 border-white/80 border-t-transparent animate-spin inline-block" wire:loading wire:target="saveCease"></span>
                            <svg class="w-3.5 h-3.5 text-white shrink-0" wire:loading.remove wire:target="saveCease" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="8" />
                            </svg>
                            <span>Confirmar Cese y Entrega de Cargo</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 3. MODAL DE DETALLE HISTÓRICO DE CESE                                     -->
    <!-- ========================================================================= -->
    @if ($showCeaseDetailModal && $ceaseDetailData)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative space-y-4">
                <button type="button" wire:click="closeModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold cursor-pointer">
                    &times;
                </button>

                <h3 class="text-xl font-black text-slate-900 dark:text-white">
                    Historial de Cese: {{ $ceaseDetailData['fullName'] }}
                </h3>
                <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-xl space-y-1.5 text-xs">
                    <p><span class="text-slate-400">DNI:</span> <strong>{{ $ceaseDetailData['documentNumber'] }}</strong></p>
                    <p><span class="text-slate-400">Fecha de Cese:</span> <strong>{{ $ceaseDetailData['departureDate'] }}</strong></p>
                    <p><span class="text-slate-400">Motivo:</span> <strong class="text-rose-600">{{ $ceaseDetailData['cessationReason'] }}</strong></p>
                    <p><span class="text-slate-400">Última Dependencia:</span> <strong>{{ $ceaseDetailData['office'] }}</strong></p>
                </div>

                @if (count($ceaseDetailData['movements']) > 0)
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">Actas de Retorno a Almacén Generadas</h4>
                    <div class="space-y-1 text-xs">
                        @foreach ($ceaseDetailData['movements'] as $mv)
                            <div class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-between font-mono">
                                <span class="font-bold text-emerald-700">{{ $mv['actaNumber'] }}</span>
                                <span class="text-slate-500">{{ $mv['assetCode'] }} &rarr; {{ $mv['toOffice'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pt-3 flex justify-end border-t border-slate-200 dark:border-slate-700">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
