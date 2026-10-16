<div class="min-h-screen flex flex-col bg-[#f0f4f2] dark:bg-slate-950 font-sans">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <!-- Barra Superior Institucional -->
    <header style="background-color: #0b482b;" class="border-b border-emerald-900 text-white sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Izquierda: Escudo y Título Institucional -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="{{ route('soporte.portal') }}"
                   style="background-color: #0d5c37;"
                   class="w-9 h-9 rounded-lg border border-emerald-600/50 flex items-center justify-center text-white hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
                   title="Volver a Mesa de Ayuda">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>

                <div class="flex items-center space-x-2.5">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Escudo Oficial de Castilla"
                         class="h-9 w-auto object-contain drop-shadow-sm" />
                    <div class="flex flex-col">
                        <h1 class="font-black text-xs sm:text-sm tracking-wide text-white leading-tight uppercase flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 rounded bg-emerald-900/80 text-[10px] font-mono border border-emerald-700/60 font-bold">[MDC]</span>
                            <span>Mesa de Ayuda y Soporte TI</span>
                        </h1>
                        <p class="text-[11px] text-emerald-200 font-medium leading-tight">
                            Portal Ágil de Reporte de Incidencias TI
                        </p>
                    </div>
                </div>
            </div>

            <!-- Derecha: Botón Módulos -->
            <div>
                <a href="{{ route('portal.launcher') }}"
                   style="background-color: #0d5c37;"
                   class="inline-flex items-center px-4 py-1.5 rounded-full border border-emerald-500/60 text-white text-xs font-bold shadow-xs hover:opacity-90 transition-opacity cursor-pointer">
                    Módulos
                </a>
            </div>
        </div>
    </header>

    <style>
        @media (min-width: 1024px) {
            .side-by-side-container {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                gap: 1.5rem !important;
            }
            .side-by-side-left {
                flex: 0 0 calc(58% - 0.75rem) !important;
                width: calc(58% - 0.75rem) !important;
                max-width: calc(58% - 0.75rem) !important;
            }
            .side-by-side-right {
                flex: 0 0 calc(42% - 0.75rem) !important;
                width: calc(42% - 0.75rem) !important;
                max-width: calc(42% - 0.75rem) !important;
                position: sticky !important;
                top: 5.5rem !important;
            }
        }
    </style>

    <!-- Contenedor Principal: Lado a Lado (Izquierda: Formulario intacto, Derecha: Seguimiento y Visto Bueno) -->
    <main class="flex-1 py-6 sm:py-8 px-4 sm:px-6 w-full max-w-[1700px] mx-auto">
        <div class="side-by-side-container flex flex-col gap-6 items-start">

            <!-- ========================================================================= -->
            <!-- COLUMNA IZQUIERDA: FORMULARIO EXISTENTE SIN CAMBIAR NINGÚN CAMPO         -->
            <!-- ========================================================================= -->
            <div class="side-by-side-left w-full space-y-6">

                @if ($createdTicketNumber)
                    <!-- Pantalla de Éxito / Confirmación -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-8 text-center animate-fadeIn space-y-5 border border-emerald-100 dark:border-emerald-900/60">
                        <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto text-2xl font-bold shadow-sm">
                            ✓
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
                                Ticket Registrado con Éxito
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-3 font-mono">
                                {{ $createdTicketNumber }}
                            </h2>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 max-w-md mx-auto leading-relaxed">
                                Su reporte ha sido recibido por la Mesa de Ayuda TI. Ya es visible en el espacio de seguimiento del área y un técnico especializado atenderá su solicitud a la brevedad.
                            </p>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200 dark:border-slate-700 text-left text-xs space-y-1.5">
                            <p><span class="text-slate-500 dark:text-slate-400">Solicitante:</span> <strong class="text-slate-900 dark:text-white">{{ $applicantName }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Dependencia:</span> <strong class="text-slate-900 dark:text-white">{{ $officeName }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Asunto:</span> <strong class="text-slate-900 dark:text-white">{{ $subject }}</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Prioridad:</span> <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $priority }}</span></p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                            <button type="button"
                                    wire:click="resetForm"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors cursor-pointer inline-flex items-center justify-center gap-1.5">
                                + Registrar Otro Inconveniente
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Tarjeta Principal del Formulario (TODOS LOS CAMPOS CONSERVADOS INTACTOS) -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-md border border-slate-200/90 dark:border-slate-800 overflow-hidden">
                        <!-- Banner Verde Superior de la Tarjeta -->
                        <div style="background-color: #0b482b;" class="px-6 py-5 sm:px-7 sm:py-6 text-white border-b border-emerald-950">
                            <p class="text-[11px] font-black uppercase tracking-wider text-emerald-400">
                                FORMULARIO DE SOPORTE TÉCNICO (SRS RF-01 / RF-04)
                            </p>
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight mt-1">
                                Registrar Falla en su Puesto de Trabajo
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-200/95 mt-1">
                                Complete los siguientes pasos para que un técnico sea asignado a su oficina.
                            </p>
                        </div>

                        <!-- Mensaje de Error en Formulario -->
                        @if ($errorMessage)
                            <div class="m-6 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span>⚠️</span>
                                    <span>{{ $errorMessage }}</span>
                                </div>
                                <button type="button" wire:click="$set('errorMessage', null)" class="text-rose-600 font-bold cursor-pointer">&times;</button>
                            </div>
                        @endif

                        <!-- Cuerpo del Formulario -->
                        <form wire:submit.prevent="saveTicket" class="p-6 sm:p-7 space-y-6">

                            <!-- 1. DATOS DE IDENTIFICACIÓN (RF-02) -->
                            <div class="rounded-2xl border border-emerald-200/70 dark:border-emerald-900/40 bg-emerald-50/15 dark:bg-emerald-950/10 p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100 tracking-wide uppercase">
                                        1. DATOS DE IDENTIFICACIÓN (RF-02)
                                    </span>
                                    <button type="button"
                                            wire:click="autoDetect"
                                            class="px-3 py-1 rounded-md border border-emerald-400 dark:border-emerald-700 bg-white dark:bg-slate-900 text-emerald-700 dark:text-emerald-400 text-[11px] font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 transition cursor-pointer shadow-2xs">
                                        Detección Automática
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="applicant-name-input" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Nombre del Funcionario / Custodio *:
                                        </label>
                                        <input type="text"
                                               id="applicant-name-input"
                                               wire:model.live.debounce.300ms="applicantName"
                                               list="custodians_datalist"
                                               required
                                               placeholder="ADMINISTRADOR DEL SISTEMA SIGM"
                                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                        <datalist id="custodians_datalist">
                                            @foreach($allPeople as $personItem)
                                                <option value="{{ $personItem->full_name }}">{{ $personItem->position ? $personItem->position . ' — ' : '' }}{{ $personItem->office }}</option>
                                            @endforeach
                                        </datalist>
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <label for="office-name-select" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                                Oficina o Dependencia Municipal *:
                                            </label>
                                            @if(count($assignedOffices) > 1)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950/80 px-2 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    {{ count($assignedOffices) - 1 }} {{ (count($assignedOffices) - 1) === 1 ? 'dependencia encargada sin titular' : 'dependencias encargadas sin titular' }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="relative">
                                            <select id="office-name-select"
                                                    wire:model.live="officeName"
                                                    required
                                                    class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all shadow-sm cursor-pointer">
                                                @if(count($assignedOffices) > 0)
                                                    @foreach($assignedOffices as $item)
                                                        <option value="{{ $item['name'] }}">
                                                            {{ $item['name'] }} — [{{ $item['badge'] }}]
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled>Seleccione una oficina municipal...</option>
                                                    @foreach($offices as $off)
                                                        <option value="{{ $off->name }}">{{ $off->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        @php
                                            $selectedOfficeInfo = collect($assignedOffices)->firstWhere('name', $officeName);
                                        @endphp
                                        @if($selectedOfficeInfo)
                                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px]">
                                                @if($selectedOfficeInfo['is_primary'])
                                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        Oficina Principal Titular
                                                    </span>
                                                @elseif($selectedOfficeInfo['is_inherited'])
                                                    <span class="inline-flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Encargatura por Jerarquía (Sin responsable titular asignado)
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Anexo o Teléfono Móvil:
                                        </label>
                                        <input type="text"
                                               wire:model="applicantPhone"
                                               placeholder="Anexo 104"
                                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Correo Institucional (Opcional):
                                        </label>
                                        <input type="email"
                                               wire:model="applicantEmail"
                                               placeholder="admin@castilla.gob.pe"
                                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                    </div>
                                </div>
                            </div>

                            <!-- 2. ESCANEO DE CÓDIGO QR DEL EQUIPO (RF-03) CON CÁMARA MÓVIL Y BÚSQUEDA MANUAL -->
                            <div x-data="qrScanner($wire)" class="rounded-2xl border border-blue-200/90 dark:border-blue-900/50 bg-blue-50/20 dark:bg-blue-950/20 p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-blue-600 dark:text-blue-400 text-sm font-bold">📷</span>
                                        <span class="text-xs font-black text-slate-800 dark:text-slate-100 tracking-wide uppercase">
                                            2. ESCANEO DE CÓDIGO QR DEL EQUIPO (RF-03)
                                        </span>
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium hidden sm:inline">
                                        Sticker adherido al bien
                                    </span>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text"
                                           wire:model="qrCodeInput"
                                           wire:keydown.enter.prevent="lookupAsset"
                                           placeholder="INGRESE CÓDIGO O ESCANEE QR (EJ. MDC-TI-PC-0001)"
                                           class="flex-1 text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-mono placeholder:text-slate-400 uppercase tracking-wide focus:ring-2 focus:ring-emerald-500 focus:outline-none" />

                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="open()"
                                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Escanear Cámara</span>
                                        </button>

                                        <button type="button"
                                                wire:click="lookupAsset"
                                                style="background-color: #556275;"
                                                class="px-5 py-2.5 text-white hover:opacity-90 active:opacity-100 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                                            Buscar
                                        </button>
                                    </div>
                                </div>

                                @if ($assetLookupError)
                                    <p class="text-xs text-rose-600 font-semibold mt-1">{{ $assetLookupError }}</p>
                                @endif

                                @if ($selectedAssetId)
                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center justify-between text-xs">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-emerald-700 dark:text-emerald-400 font-bold">✓ Activo Vinculado:</span>
                                            <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $selectedAssetName }}</span>
                                        </div>
                                        <button type="button"
                                                wire:click="clearAsset"
                                                class="text-rose-600 hover:text-rose-700 font-bold cursor-pointer">
                                            Quitar
                                        </button>
                                    </div>
                                @endif

                                <!-- MODAL ESCÁNER QR CON CÁMARA (MÓVIL / ESCRITORIO) -->
                                <template x-teleport="body">
                                    <div x-show="isOpen"
                                         x-transition:enter="transition ease-out duration-250"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 z-[100] flex items-center justify-center p-3 sm:p-6 bg-slate-950/85 backdrop-blur-md"
                                         style="display: none;"
                                         @keydown.escape.window="close()">

                                        <div @click.away="close()"
                                             x-show="isOpen"
                                             x-transition:enter="transition ease-out duration-250"
                                             x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 translate-y-3"
                                             class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden flex flex-col">

                                            <!-- Modal Header -->
                                            <div class="p-4 sm:p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/70 dark:bg-slate-900/70">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wide">
                                                            Escanear Código QR
                                                        </h3>
                                                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                                            Sticker del activo municipal
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-1">
                                                    <!-- Switch Camera Button -->
                                                    <button type="button"
                                                            x-show="hasMultipleCameras"
                                                            @click="switchCamera()"
                                                            title="Cambiar Cámara"
                                                            class="p-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>

                                                    <!-- Close Button -->
                                                    <button type="button"
                                                            @click="close()"
                                                            class="p-2 text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Modal Body: Viewfinder Area -->
                                            <div class="p-4 sm:p-5 flex flex-col items-center">
                                                <div class="relative w-full aspect-square max-w-[320px] bg-slate-950 rounded-2xl overflow-hidden border-2 border-slate-700/60 shadow-inner flex items-center justify-center">
                                                    <!-- Html5Qrcode attaches here -->
                                                    <div id="qr-reader-container" class="w-full h-full"></div>

                                                    <!-- Loading Overlay -->
                                                    <div x-show="isStarting" class="absolute inset-0 bg-slate-950 flex flex-col items-center justify-center gap-2 text-slate-300 text-xs">
                                                        <svg class="animate-spin w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span class="font-medium">Iniciando cámara...</span>
                                                    </div>

                                                    <!-- Custom Viewfinder Overlay & Laser beam -->
                                                    <div x-show="isScanning && !errorMessage" class="pointer-events-none absolute inset-0 flex items-center justify-center">
                                                        <!-- Target Frame Corners -->
                                                        <div class="relative w-[72%] h-[72%] border border-blue-400/30 rounded-xl">
                                                            <div class="absolute -top-1 -left-1 w-5 h-5 border-t-3 border-l-3 border-blue-400 rounded-tl-lg"></div>
                                                            <div class="absolute -top-1 -right-1 w-5 h-5 border-t-3 border-r-3 border-blue-400 rounded-tr-lg"></div>
                                                            <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-3 border-l-3 border-blue-400 rounded-bl-lg"></div>
                                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-3 border-r-3 border-blue-400 rounded-br-lg"></div>

                                                            <!-- Laser Scan Line -->
                                                            <div class="qr-scan-line absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-blue-400 to-transparent shadow-[0_0_12px_#60a5fa]"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Error Display -->
                                                    <div x-show="errorMessage" class="absolute inset-0 bg-slate-950/95 p-5 flex flex-col items-center justify-center text-center gap-3">
                                                        <div class="w-12 h-12 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center text-xl">⚠️</div>
                                                        <p class="text-xs text-rose-300 font-medium leading-relaxed" x-text="errorMessage"></p>
                                                        <button type="button"
                                                                @click="initScanner()"
                                                                class="mt-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition">
                                                            Reintentar
                                                        </button>
                                                    </div>
                                                </div>

                                                <p class="mt-4 text-xs text-center text-slate-500 dark:text-slate-400 max-w-xs">
                                                    Apunte el lente hacia el código QR o código de barras adherido en el equipo municipal.
                                                </p>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/70 flex justify-end">
                                                <button type="button"
                                                        @click="close()"
                                                        class="w-full sm:w-auto px-5 py-2.5 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition cursor-pointer">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <style>
                                @keyframes qrScanBeam {
                                    0%, 100% { top: 6%; opacity: 0.9; }
                                    50% { top: 90%; opacity: 0.9; }
                                }
                                .qr-scan-line {
                                    animation: qrScanBeam 2.2s ease-in-out infinite;
                                }
                                #qr-reader-container video {
                                    border-radius: 0.875rem !important;
                                    object-fit: cover !important;
                                    width: 100% !important;
                                    height: 100% !important;
                                }
                            </style>

                            <!-- Tipo de Inconveniente o Falla (Categoría) -->
                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    Tipo de Inconveniente o Falla (Categoría):
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @php
                                        $categories = [
                                            [
                                                'id' => 'HARDWARE',
                                                'icon' => '🖥️',
                                                'label' => 'Equipos / Hardware',
                                                'desc' => 'PC, Monitor, Impresora, Teclado',
                                            ],
                                            [
                                                'id' => 'SOFTWARE',
                                                'icon' => '💻',
                                                'label' => 'Software y Aplicativos',
                                                'desc' => 'SIGM, Windows, Office, PDF',
                                            ],
                                            [
                                                'id' => 'PERMISOS',
                                                'icon' => '🔑',
                                                'label' => 'Permisos y Accesos',
                                                'desc' => 'Contraseña, Carpetas compartidas',
                                            ],
                                            [
                                                'id' => 'RED_INTERNET',
                                                'icon' => '🌐',
                                                'label' => 'Red e Internet',
                                                'desc' => 'Sin conexión, Cable roto, Wi-Fi',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($categories as $c)
                                        <button type="button"
                                                wire:click="setCategory('{{ $c['id'] }}')"
                                                class="flex flex-col items-center justify-center p-3 sm:p-4 rounded-xl border text-center transition-all cursor-pointer {{
                                                    $category === $c['id']
                                                        ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 ring-2 ring-emerald-500/20 text-emerald-800 dark:text-emerald-300'
                                                        : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300'
                                                }}">
                                            <span class="text-2xl mb-1.5">{{ $c['icon'] }}</span>
                                            <span class="font-bold text-xs leading-tight">{{ $c['label'] }}</span>
                                            <span class="text-[10px] text-slate-400 mt-0.5 truncate max-w-full">{{ $c['desc'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Nivel de Severidad / Impacto en sus labores -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    Nivel de Severidad / Impacto en sus labores:
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @php
                                        $priorities = [
                                            [
                                                'id' => 'BAJA',
                                                'icon' => '👤',
                                                'title' => 'Solo en mi equipo',
                                                'badge' => 'Baja',
                                                'desc' => 'Solo me afecta a mí. Puedo continuar con otras labores en mi puesto.',
                                                'active' => 'border-2 border-slate-600 bg-slate-100 text-slate-900 ring-2 ring-slate-400/20 dark:bg-slate-800 dark:text-white',
                                                'badgeActive' => 'bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200',
                                                'badgeInactive' => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400',
                                            ],
                                            [
                                                'id' => 'MEDIA',
                                                'icon' => '👥',
                                                'title' => 'Afecta a mi oficina',
                                                'badge' => 'Media',
                                                'desc' => 'Varios compañeros presentan la misma falla o lentitud en el área.',
                                                'active' => 'border-2 border-blue-500 bg-blue-50/80 text-blue-900 ring-2 ring-blue-500/20 dark:bg-blue-950/50 dark:text-blue-200',
                                                'badgeActive' => 'bg-blue-200 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
                                                'badgeInactive' => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400',
                                            ],
                                            [
                                                'id' => 'ALTA',
                                                'icon' => '🏛️',
                                                'title' => 'Atención al Público',
                                                'badge' => 'Alta',
                                                'desc' => 'Trámites de vecinos detenidos, caja, mesa de partes o servicio externo.',
                                                'active' => 'border-2 border-amber-500 bg-amber-50/80 text-amber-900 ring-2 ring-amber-500/20 dark:bg-amber-950/50 dark:text-amber-200',
                                                'badgeActive' => 'bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-300',
                                                'badgeInactive' => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400',
                                            ],
                                            [
                                                'id' => 'CRITICA',
                                                'icon' => '🚨',
                                                'title' => 'Área Paralizada',
                                                'badge' => 'Crítica',
                                                'desc' => 'Dependencia totalmente inoperativa, sin red, sin energía ni sistema.',
                                                'active' => 'border-2 border-rose-600 bg-rose-50/80 text-rose-900 ring-2 ring-rose-500/20 dark:bg-rose-950/50 dark:text-rose-200',
                                                'badgeActive' => 'bg-rose-200 dark:bg-rose-900 text-rose-800 dark:text-rose-300',
                                                'badgeInactive' => 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400',
                                            ],
                                        ];
                                    @endphp

                                    @foreach ($priorities as $p)
                                        <button type="button"
                                                wire:click="setPriority('{{ $p['id'] }}')"
                                                class="p-3 text-left rounded-xl transition-all cursor-pointer flex flex-col justify-between {{ $priority === $p['id'] ? $p['active'] : 'border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                            <div class="flex items-center justify-between w-full mb-1">
                                                <div class="flex items-center gap-1.5 font-bold text-xs">
                                                    <span class="text-base">{{ $p['icon'] }}</span>
                                                    <span>{{ $p['title'] }}</span>
                                                </div>
                                                <span class="text-[10px] font-mono px-1.5 py-0.5 rounded font-bold {{ $priority === $p['id'] ? $p['badgeActive'] : $p['badgeInactive'] }}">
                                                    {{ $p['badge'] }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] {{ $priority === $p['id'] ? 'text-slate-700 dark:text-slate-300 font-medium' : 'text-slate-400 dark:text-slate-500' }} leading-tight mt-0.5">
                                                {{ $p['desc'] }}
                                            </p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Asunto breve con sus propias palabras * -->
                            <div>
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1.5">
                                    Asunto breve con sus propias palabras *:
                                </label>
                                <input type="text"
                                       wire:model="subject"
                                       required
                                       placeholder="Ej. Mi monitor no enciende / La impresora mancha las resoluciones"
                                       class="w-full text-xs p-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                            </div>

                            <!-- Detalle del problema (¿Qué sucedió y cuándo?) * -->
                            <div>
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200 mb-1.5">
                                    Detalle del problema (¿Qué sucedió y cuándo?) *:
                                </label>
                                <textarea wire:model="description"
                                          required
                                          rows="3"
                                          placeholder="Describa si el equipo emite sonidos raros, si ocurrió tras un corte de luz o qué mensaje de error se muestra..."
                                          class="w-full text-xs p-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none min-h-[90px]"></textarea>
                            </div>

                            <!-- Foto o Captura de Pantalla -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    Foto o Captura de Pantalla:
                                </label>
                                <div class="flex items-center gap-3">
                                    <label class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold cursor-pointer transition shadow-2xs">
                                        <span class="text-sm">📷</span>
                                        <span>Subir Foto / Tomar Captura</span>
                                        <input type="file" wire:model="photo" accept="image/*" class="hidden" />
                                    </label>
                                    @if ($photo)
                                        <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                            <span>✓</span> Imagen seleccionada
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Botón de Envío -->
                            <div class="pt-2">
                                <button type="submit"
                                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-sm font-black shadow-lg shadow-emerald-600/30 transition-all cursor-pointer flex items-center justify-center gap-2">
                                    <span>🚀</span>
                                    <span>Enviar Ticket a Soporte Técnico</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>

            <!-- =============================================================================================== -->
            <!-- COLUMNA DERECHA: ESPACIO PARA SEGUIMIENTO DE TICKETS, TÉCNICO Y VISTO BUENO (LADO A LADO)      -->
            <!-- =============================================================================================== -->
            <div class="side-by-side-right w-full space-y-4">
                
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-md border border-slate-200/90 dark:border-slate-800 overflow-hidden">
                    
                    <!-- Encabezado del Espacio de Seguimiento -->
                    <div style="background-color: #0b482b;" class="px-5 py-4 text-white border-b border-emerald-950 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">📋</span>
                            <div>
                                <h3 class="text-xs sm:text-sm font-black tracking-wide text-white uppercase">
                                    Seguimiento de Tickets del Área
                                </h3>
                                <p class="text-[11px] text-emerald-200">
                                    Estado, asignación técnica y visto bueno
                                </p>
                            </div>
                        </div>

                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-900/80 border border-emerald-600/60 text-emerald-200 font-mono">
                            {{ $allCount }} Caso(s)
                        </span>
                    </div>

                    <!-- Dependencia Filtrada y Pestañas -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate max-w-[280px]">
                                🏢 Dependencia: <span class="text-emerald-700 dark:text-emerald-400 font-bold uppercase">{{ $officeName ?: 'OFICINA MUNICIPAL' }}</span>
                            </span>
                            <span class="text-[10px] text-slate-400">
                                En vivo
                            </span>
                        </div>

                        <!-- Filtros por Estado -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                            <button type="button"
                                    wire:click="setTrackingFilter('TODOS')"
                                    class="px-2.5 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'TODOS' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                Todos ({{ $allCount }})
                            </button>
                            <button type="button"
                                    wire:click="setTrackingFilter('ACTIVOS')"
                                    class="px-2.5 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'ACTIVOS' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                En Atención ({{ $activeCount }})
                            </button>
                            <button type="button"
                                    wire:click="setTrackingFilter('RESUELTOS')"
                                    class="px-2.5 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'RESUELTOS' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                Por Visto Bueno ({{ $resolvedCount }})
                            </button>
                            <button type="button"
                                    wire:click="setTrackingFilter('CERRADOS')"
                                    class="px-2.5 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'CERRADOS' ? 'bg-slate-700 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                Cerrados ({{ $closedCount }})
                            </button>
                        </div>

                        <!-- Buscador rápido -->
                        <div>
                            <input type="text"
                                   wire:model.live.debounce.250ms="trackingSearch"
                                   placeholder="Buscar por código, asunto o técnico..."
                                   class="w-full text-xs p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-emerald-500 placeholder:text-slate-400" />
                        </div>
                    </div>

                    <!-- Listado de Tarjetas de Seguimiento de Tickets -->
                    <div class="p-4 space-y-3.5 max-h-[640px] overflow-y-auto">
                        @forelse ($officeTickets as $ticket)
                            @php
                                $statusUpper = strtoupper($ticket->status);
                                $isResolved = in_array($statusUpper, ['RESUELTO', 'SOLUCIONADO']);
                                $isClosed = $statusUpper === 'CERRADO';

                                $badgeClass = match($statusUpper) {
                                    'RESUELTO', 'SOLUCIONADO' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-300',
                                    'EN_ATENCION', 'EN_PROCESO' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-300',
                                    'EN_LABORATORIO', 'EN_TALLER' => 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border-purple-300',
                                    'EN_PAUSA' => 'bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-300 border-orange-300',
                                    'CERRADO' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-300',
                                    default => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-300',
                                };

                                $statusLabel = match($statusUpper) {
                                    'RESUELTO', 'SOLUCIONADO' => 'Resuelto (Listo p/ Visto Bueno)',
                                    'CERRADO' => 'Cerrado (Conformidad Otorgada)',
                                    'EN_ATENCION', 'EN_PROCESO' => 'En Atención',
                                    'EN_LABORATORIO', 'EN_TALLER' => 'En Taller / Laboratorio',
                                    'EN_PAUSA' => 'En Pausa (Esperando insumo)',
                                    default => 'Abierto (En espera de técnico)',
                                };
                            @endphp

                            <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs space-y-2.5">
                                
                                <!-- Código de Ticket y Estado -->
                                <div class="flex items-center justify-between gap-1.5">
                                    <span class="font-mono font-black text-xs text-slate-900 dark:text-white">
                                        {{ $ticket->ticket_number }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full font-bold text-[10px] border {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <!-- Asunto y Descripción -->
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug">
                                        {{ $ticket->subject }}
                                    </h4>
                                    @if ($ticket->description)
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">
                                            {{ $ticket->description }}
                                        </p>
                                    @endif
                                </div>

                                <!-- ¿QUÉ TÉCNICO LO VA A ATENDER? -->
                                <div class="p-2.5 rounded-xl border {{ !empty($ticket->assigned_technician_name) ? 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700' : 'bg-blue-50/50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-900/40' }} text-xs space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                            Técnico Asignado de Atención
                                        </span>
                                        @if (!empty($ticket->assigned_technician_name))
                                            <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Asignado
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400">
                                                En cola
                                            </span>
                                        @endif
                                    </div>

                                    @if (!empty($ticket->assigned_technician_name))
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-sm">👨‍🔧</span>
                                                <strong class="text-slate-900 dark:text-white text-xs font-bold">
                                                    {{ $ticket->assigned_technician_name }}
                                                </strong>
                                            </div>
                                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-bold border border-emerald-200">
                                                📞 {{ $ticket->assigned_technician_phone ?? 'Anexo 104' }}
                                            </span>
                                        </div>

                                        @if ($ticket->technicalDetail && !empty($ticket->technicalDetail->real_diagnosis))
                                            <div class="text-[10px] text-slate-600 dark:text-slate-300 pt-0.5 border-t border-slate-200 dark:border-slate-700">
                                                <strong>Diagnóstico:</strong> {{ $ticket->technicalDetail->real_diagnosis }}
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-[11px] text-blue-800 dark:text-blue-300">
                                            ⏳ Su requerimiento está en cola. La Mesa de Ayuda TI asignará un técnico en breve para su atención.
                                        </p>
                                    @endif
                                </div>

                                <!-- DAR EL VISTO BUENO PARA CERRAR EL TICKET -->
                                <div class="pt-0.5">
                                    @if ($ticket->user_conformity)
                                        <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs flex items-center justify-between gap-2">
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1 font-bold text-emerald-800 dark:text-emerald-300 text-[11px]">
                                                    <span>✅</span>
                                                    <span>Visto Bueno Otorgado (Conforme)</span>
                                                </div>
                                                <p class="text-[10px] text-emerald-900/80 dark:text-emerald-200">
                                                    Calificación: @for($i = 0; $i < ($ticket->user_rating ?? 5); $i++) ⭐ @endfor &bull; {{ $ticket->user_feedback ?? 'Servicio Conforme' }}
                                                </p>
                                            </div>
                                            <span class="text-[10px] font-mono text-slate-400">
                                                {{ $ticket->closed_at?->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                                            <button type="button"
                                                    wire:click="openConformityModal({{ $ticket->id }})"
                                                    class="w-full sm:w-auto flex-1 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-bold text-xs shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                                <span>✅</span>
                                                <span>Dar Visto Bueno / Cerrar Ticket</span>
                                            </button>

                                            <span class="text-[10px] text-slate-400 text-center sm:text-right shrink-0">
                                                {{ $ticket->created_at?->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 space-y-1.5">
                                <span class="text-3xl block">📋</span>
                                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                    No hay tickets para esta dependencia
                                </h4>
                                <p class="text-[11px] text-slate-400 max-w-xs mx-auto">
                                    Al enviar una incidencia desde el formulario de la izquierda, aparecerá aquí inmediatamente para realizar su seguimiento en vivo.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Panel Informativo ODT Inferior -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-600 dark:text-slate-400 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-1">
                        <div>
                            <span>🕒 <strong>Atención TI:</strong> Lun-Vie 8:00 AM - 4:00 PM</span>
                        </div>
                        <div>
                            <span>📍 Palacio Municipal &bull; Anexo 104 / 102</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- MODAL: DAR EL VISTO BUENO PARA CERRAR EL TICKET -->
    @if ($isConformityModalOpen && $conformityTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             @keydown.escape.window="$wire.closeConformityModal()">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden animate-in fade-in zoom-in-95 duration-150">
                
                <!-- Encabezado del Modal -->
                <div class="px-6 py-4 bg-emerald-800 text-white flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="font-mono font-bold text-sm bg-emerald-950 px-2 py-0.5 rounded border border-emerald-700">
                            {{ $conformityTicket->ticket_number }}
                        </span>
                        <h3 class="font-bold text-xs text-white">Visto Bueno y Cierre de Ticket</h3>
                    </div>
                    <button type="button"
                            wire:click="closeConformityModal"
                            class="p-1 hover:bg-emerald-700 rounded text-emerald-200 hover:text-white cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="p-6 space-y-4 text-xs">
                    
                    <!-- Ficha Resumen de la Atención -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700 space-y-1.5">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Resumen de la Incidencia:</span>
                        <h4 class="font-bold text-slate-900 dark:text-white text-xs">
                            {{ $conformityTicket->subject }}
                        </h4>
                        <div class="grid grid-cols-2 gap-2 text-[11px] pt-1 text-slate-600 dark:text-slate-300">
                            <div>
                                <span class="text-slate-400">Oficina:</span>
                                <strong>{{ $conformityTicket->office_name }}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400">Técnico que atendió:</span>
                                <strong class="text-emerald-700 dark:text-emerald-400">{{ $conformityTicket->assigned_technician_name ?? 'Personal Soporte ODT' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de Conformidad -->
                    <div class="space-y-2">
                        <label class="block font-bold text-slate-800 dark:text-slate-200">
                            ¿El inconveniente fue solucionado en su puesto de trabajo? <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button"
                                    wire:click="setConformity(true)"
                                    class="p-3 rounded-xl border text-left transition-all cursor-pointer {{ $formUserConformity ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-500 text-emerald-900 dark:text-emerald-200 ring-2 ring-emerald-500/20 font-bold' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50' }}">
                                <span class="block text-base">✅ SÍ, CONFORME</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-0.5">El equipo ya funciona con normalidad</span>
                            </button>

                            <button type="button"
                                    wire:click="setConformity(false)"
                                    class="p-3 rounded-xl border text-left transition-all cursor-pointer {{ ! $formUserConformity ? 'bg-rose-50 dark:bg-rose-950/60 border-rose-500 text-rose-900 dark:text-rose-200 ring-2 ring-rose-500/20 font-bold' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50' }}">
                                <span class="block text-base">⚠️ CON OBSERVACIÓN</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 block mt-0.5">Aún presenta fallas o requiere revisión</span>
                            </button>
                        </div>
                    </div>

                    <!-- Calificación de la Atención (Estrellas) -->
                    @if ($formUserConformity)
                        <div class="space-y-1.5 p-3 rounded-xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60">
                            <label class="block font-bold text-amber-900 dark:text-amber-200 text-xs">
                                Califica la atención y rapidez del técnico:
                            </label>
                            <div class="flex items-center gap-2 pt-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            wire:click="setRating({{ $i }})"
                                            class="text-2xl transition-transform hover:scale-125 cursor-pointer focus:outline-none">
                                        {{ $i <= $formUserRating ? '⭐' : '☆' }}
                                    </button>
                                @endfor
                                <span class="text-xs font-bold text-amber-900 dark:text-amber-300 ml-2">
                                    {{ $formUserRating }} de 5 Estrellas
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Comentario o Feedback del Usuario -->
                    <div class="space-y-1">
                        <label class="block font-bold text-slate-800 dark:text-slate-200 text-xs">
                            Comentario o Conformidad de Cierre:
                        </label>
                        <textarea wire:model="formUserFeedback"
                                  rows="2"
                                  placeholder="Indique detalles de la solución o de la observación..."
                                  class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                    </div>

                    <!-- Botones de Acción del Modal -->
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button"
                                wire:click="closeConformityModal"
                                class="px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-bold transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="button"
                                wire:click="saveUserConformity"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md transition-all cursor-pointer flex items-center gap-1.5">
                            <span>✅</span>
                            <span>Confirmar y Cerrar Ticket</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
