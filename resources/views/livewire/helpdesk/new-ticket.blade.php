<div class="min-h-screen flex flex-col bg-[#f0f4f2] dark:bg-slate-950 font-sans">
    <!-- Barra Superior Institucional (Header Verde Pantalla Completa) -->
    <header style="background-color: #0b482b;" class="border-b border-emerald-900 text-white sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Izquierda: Botón Volver + Escudo + Branding -->
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="{{ route('soporte.portal') }}"
                   style="background-color: #0d5c37;"
                   class="w-9 h-9 rounded-lg border border-emerald-600/50 flex items-center justify-center text-white hover:opacity-90 transition-opacity cursor-pointer shadow-xs"
                   title="Volver al Portal de Soporte">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="flex items-center space-x-2.5">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="Escudo Oficial de Castilla"
                         class="h-9 w-auto object-contain drop-shadow-sm" />
                    <div class="flex flex-col">
                        <h1 class="font-black text-xs sm:text-sm tracking-wide text-white leading-tight uppercase">
                            MUNICIPALIDAD DISTRITAL DE CASTILLA
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

    <!-- Contenedor Principal Centrado -->
    <main class="flex-1 py-6 sm:py-8 px-4 sm:px-6 flex justify-center items-start">
        <div class="max-w-3xl w-full">

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
                            Su reporte ha sido recibido por la Mesa de Ayuda TI. Un técnico especializado atenderá su solicitud a la brevedad.
                        </p>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-xl border border-slate-200 dark:border-slate-700 text-left text-xs space-y-1.5">
                        <p><span class="text-slate-500 dark:text-slate-400">Solicitante:</span> <strong class="text-slate-900 dark:text-white">{{ $applicantName }}</strong></p>
                        <p><span class="text-slate-500 dark:text-slate-400">Dependencia:</span> <strong class="text-slate-900 dark:text-white">{{ $officeName }}</strong></p>
                        <p><span class="text-slate-500 dark:text-slate-400">Asunto:</span> <strong class="text-slate-900 dark:text-white">{{ $subject }}</strong></p>
                        <p><span class="text-slate-500 dark:text-slate-400">Prioridad:</span> <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ $priority }}</span></p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                        <a href="{{ url('/soporte?tab=my_tickets') }}"
                           class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors cursor-pointer inline-flex items-center justify-center gap-1.5">
                            <span>📱</span>
                            <span>Ir a Mis Tickets de Seguimiento</span>
                        </a>
                        <button type="button"
                                wire:click="resetForm"
                                class="px-5 py-2.5 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition-colors cursor-pointer">
                            + Registrar Otro Inconveniente
                        </button>
                    </div>
                </div>
            @else
                <!-- Tarjeta Principal del Formulario -->
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
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Nombre del Funcionario / Custodio *:
                                    </label>
                                    <input type="text"
                                           wire:model="applicantName"
                                           required
                                           placeholder="ADMINISTRADOR DEL SISTEMA SIGM"
                                           class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Oficina o Dependencia Municipal *:
                                    </label>
                                    <input type="text"
                                           wire:model="officeName"
                                           required
                                           placeholder="OFICINA DE REGIDORES"
                                           class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
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

                        <!-- 2. ESCANEO DE CÓDIGO QR DEL EQUIPO (RF-03) -->
                        <div class="rounded-2xl border border-blue-200/90 dark:border-blue-900/50 bg-blue-50/20 dark:bg-blue-950/20 p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-blue-600 dark:text-blue-400 text-sm font-bold">📷</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100 tracking-wide uppercase">
                                        2. ESCANEO DE CÓDIGO QR DEL EQUIPO (RF-03)
                                    </span>
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium">
                                    Sticker adherido al bien
                                </span>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="text"
                                       wire:model="qrCodeInput"
                                       wire:keydown.enter.prevent="lookupAsset"
                                       placeholder="INGRESE CÓDIGO O ESCANEE QR (EJ. MDC-TI-PC-0001)"
                                       class="flex-1 text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-mono placeholder:text-slate-400 uppercase tracking-wide focus:ring-2 focus:ring-emerald-500 focus:outline-none" />

                                <button type="button"
                                        wire:click="lookupAsset"
                                        style="background-color: #556275;"
                                        class="px-5 py-2.5 text-white hover:opacity-90 active:opacity-100 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer">
                                    Buscar
                                </button>
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
                        </div>

                        <!-- 3. Seleccione el Tipo de Inconveniente * -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                3. Seleccione el Tipo de Inconveniente *:
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                @php
                                    $categoriesList = [
                                        [
                                            'id' => 'HARDWARE',
                                            'label' => 'Hardware',
                                            'desc' => 'PC, Monitor, Imp...',
                                            'icon' => '🖥️',
                                        ],
                                        [
                                            'id' => 'SOFTWARE',
                                            'label' => 'Software',
                                            'desc' => 'SIGM, Windows, ...',
                                            'icon' => '💾',
                                        ],
                                        [
                                            'id' => 'RED_INTERNET',
                                            'label' => 'Red / Internet',
                                            'desc' => 'Sin señal, cables...',
                                            'icon' => '🌐',
                                        ],
                                        [
                                            'id' => 'PERMISOS',
                                            'label' => 'Permisos',
                                            'desc' => 'Acceso a carpetas',
                                            'icon' => '🔑',
                                        ],
                                        [
                                            'id' => 'OTROS',
                                            'label' => 'Otros',
                                            'desc' => 'Consultas y ases...',
                                            'icon' => '⚙️',
                                        ],
                                    ];
                                @endphp

                                @foreach ($categoriesList as $c)
                                    <button type="button"
                                            wire:click="setCategory('{{ $c['id'] }}')"
                                            class="p-3.5 rounded-xl border text-center transition-all cursor-pointer flex flex-col items-center justify-center {{ $category === $c['id']
                                                ? 'border-2 border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-100 ring-2 ring-emerald-500/20 shadow-xs font-bold'
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
                            <div class="grid grid-cols-4 gap-2.5 text-xs font-bold">
                                @php
                                    $priorities = [
                                        [
                                            'id' => 'BAJA',
                                            'label' => 'Baja',
                                            'active' => 'border-2 border-slate-600 bg-slate-100 text-slate-900 ring-2 ring-slate-400/20 dark:bg-slate-800 dark:text-white',
                                            'inactive' => 'border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50',
                                        ],
                                        [
                                            'id' => 'MEDIA',
                                            'label' => 'Media',
                                            'active' => 'border-2 border-blue-500 bg-blue-50/70 text-blue-700 ring-2 ring-blue-500/20 dark:bg-blue-950/50 dark:text-blue-300',
                                            'inactive' => 'border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-blue-50/40',
                                        ],
                                        [
                                            'id' => 'ALTA',
                                            'label' => 'Alta',
                                            'active' => 'border-2 border-amber-500 bg-amber-50/70 text-amber-800 ring-2 ring-amber-500/20 dark:bg-amber-950/50 dark:text-amber-300',
                                            'inactive' => 'border border-amber-200 dark:border-amber-800/80 bg-white dark:bg-slate-900 text-amber-700 dark:text-amber-400 hover:bg-amber-50/40',
                                        ],
                                        [
                                            'id' => 'CRITICA',
                                            'label' => 'Crítica',
                                            'active' => 'border-2 border-rose-600 bg-rose-50/70 text-rose-800 ring-2 ring-rose-500/20 dark:bg-rose-950/50 dark:text-rose-300',
                                            'inactive' => 'border border-rose-200 dark:border-rose-800/80 bg-white dark:bg-slate-900 text-rose-600 dark:text-rose-400 hover:bg-rose-50/40',
                                        ],
                                    ];
                                @endphp

                                @foreach ($priorities as $p)
                                    <button type="button"
                                            wire:click="setPriority('{{ $p['id'] }}')"
                                            class="py-2.5 px-2 text-center rounded-xl transition-all cursor-pointer {{ $priority === $p['id'] ? $p['active'] : $p['inactive'] }}">
                                        {{ $p['label'] }}
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
    </main>
</div>
