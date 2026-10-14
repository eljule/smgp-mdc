<div class="min-h-screen flex flex-col bg-[#f0f4f2] dark:bg-slate-950 font-sans">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <!-- 1. BARRA SUPERIOR INSTITUCIONAL -->
    <header style="background-color: #0b482b;" class="border-b border-emerald-900 text-white sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Izquierda: [MDC] Mesa de Ayuda y Soporte TI | Municipalidad Distrital de Castilla -->
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
                            Municipalidad Distrital de Castilla
                        </p>
                    </div>
                </div>
            </div>

            <!-- Derecha: [ Inicio ] [ Salir ] -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('portal.launcher') }}"
                   style="background-color: #0d5c37;"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border border-emerald-500/60 text-white text-xs font-bold shadow-xs hover:bg-emerald-800 transition-colors cursor-pointer"
                   title="Ir al Portal Principal">
                    <span>🏠</span>
                    <span class="hidden sm:inline">Inicio</span>
                </a>

                <a href="{{ route('logout') }}"
                   style="background-color: #0d5c37;"
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border border-emerald-500/60 text-white text-xs font-bold shadow-xs hover:bg-rose-700 hover:border-rose-600 transition-colors cursor-pointer"
                   title="Cerrar Sesión">
                    <span>🚪</span>
                    <span class="hidden sm:inline">Salir</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 2. BANNER SUPERIOR INFORMATIVO -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2 w-full">
        <div class="bg-gradient-to-r from-[#0b482b] to-[#125c38] rounded-2xl p-5 sm:p-6 text-white shadow-md border border-emerald-900 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <img src="{{ asset('images/logo.png') }}" class="w-64 h-64 object-contain" alt="Castilla watermark">
            </div>

            <div class="relative z-10 space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-black uppercase tracking-wider text-emerald-300">
                        🟢 ATENCIÓN DE INCIDENCIAS Y REQUERIMIENTOS INFORMÁTICOS
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    PORTAL DE SOPORTE Y HELPDESK
                </h2>
                <p class="text-xs sm:text-sm text-emerald-100/95 max-w-3xl leading-relaxed">
                    Registra tu incidencia seleccionando tu Dependencia u Oficina, conoce en tiempo real qué técnico te atenderá y otorga tu visto bueno para el cierre conforme.
                </p>
            </div>
        </div>
    </div>

    <!-- 3. CONTENEDOR PRINCIPAL: DIVIDIDO EN DOS COLUMNAS -->
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 w-full">

        @if ($createdTicketNumber)
            <!-- Alerta de Ticket Creado con Éxito -->
            <div class="mb-6 bg-white dark:bg-slate-900 rounded-2xl shadow-md p-5 border border-emerald-300 dark:border-emerald-700/60 flex flex-col sm:flex-row items-center justify-between gap-4 animate-fadeIn">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 rounded-full flex items-center justify-center text-xl font-black shadow-xs shrink-0">
                        ✓
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-200">
                            Ticket Generado Exitosamente
                        </span>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white font-mono mt-0.5">
                            {{ $createdTicketNumber }}
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400">
                            Su incidencia está en cola de atención y ya es visible en la columna de seguimiento de su oficina.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                            wire:click="resetForm"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors cursor-pointer">
                        + Registrar Otro Inconveniente
                    </button>
                </div>
            </div>
        @endif

        <!-- GRID DE DOS COLUMNAS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ======================================================== -->
            <!-- COLUMNA 1: FORMULARIO DE REGISTRO (lg:col-span-6)        -->
            <!-- ======================================================== -->
            <div class="lg:col-span-6 space-y-4">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/90 dark:border-slate-800 overflow-hidden">
                    <!-- Cabecera de la Tarjeta del Formulario -->
                    <div class="px-5 py-4 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-amber-500 text-base">⚠️</span>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white tracking-tight">
                                Registrar Nueva Incidencia Técnica
                            </h3>
                        </div>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                            Formulario de Solicitud
                        </span>
                    </div>

                    <!-- Formulario de Registro -->
                    <form wire:submit="saveTicket" class="p-5 sm:p-6 space-y-4">
                        @if ($errorMessage)
                            <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl font-semibold flex items-center gap-2">
                                <span class="font-bold">✕</span>
                                <span>{{ $errorMessage }}</span>
                            </div>
                        @endif

                        <!-- 1. Dependencia u Oficina Requirente * -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                1. Dependencia u Oficina Requirente <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model.live="officeName"
                                        required
                                        class="w-full text-xs p-3 pl-9 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                                    <option value="">🏛️ -- SELECCIONA O BUSCA TU OFICINA --</option>
                                    @foreach ($offices as $off)
                                        <option value="{{ $off->name }}">
                                            {{ $off->name }} @if(!empty($off->acronym ?? $off->sigla)) ({{ $off->acronym ?? $off->sigla }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <span class="absolute left-3 top-3.5 text-xs text-slate-400">🏛️</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Al seleccionar la oficina, la columna derecha cargará el seguimiento de los tickets de esta área.
                            </p>
                        </div>

                        <!-- 2. Responsable / Solicitante de la Oficina (Auto) -->
                        <div class="space-y-2 p-3.5 bg-slate-50/80 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700/80">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    2. Responsable de la Oficina (Auto) <span class="text-red-500">*</span>
                                </label>
                                <span class="text-[10px] bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 font-bold px-2 py-0.5 rounded-full">
                                    Autodetectado
                                </span>
                            </div>

                            <!-- Nombre del responsable -->
                            <div>
                                <input type="text"
                                       wire:model="applicantName"
                                       required
                                       placeholder="JULIO ERNESTO CHAVEZ CRISANTO"
                                       class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-bold uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                            </div>

                            <!-- Sub-campos en Grid: Cargo, Anexo, Correo -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1 text-xs">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-0.5">Cargo / Puesto:</label>
                                    <input type="text"
                                           wire:model="applicantPosition"
                                           placeholder="ESPECIALISTA TECNOLÓGICO"
                                           class="w-full text-xs p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-medium uppercase focus:ring-1 focus:ring-emerald-500 focus:outline-none" />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-0.5">Anexo Telefónico:</label>
                                    <input type="text"
                                           wire:model="applicantPhone"
                                           placeholder="Anexo 102"
                                           class="w-full text-xs p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-medium focus:ring-1 focus:ring-emerald-500 focus:outline-none" />
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-0.5">Correo (Opcional):</label>
                                    <input type="email"
                                           wire:model="applicantEmail"
                                           placeholder="jchavez@castilla.gob.pe"
                                           class="w-full text-xs p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 font-medium focus:ring-1 focus:ring-emerald-500 focus:outline-none" />
                                </div>
                            </div>
                        </div>

                        <!-- 3. Selección Rápida de Categoría -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                3. Selección Rápida de Categoría:
                            </label>

                            <!-- Botones rápidos de categoría -->
                            <div class="grid grid-cols-4 gap-2">
                                <button type="button"
                                        wire:click="setCategory('HARDWARE')"
                                        class="py-2 px-1 text-center rounded-xl border transition-all cursor-pointer {{ $category === 'HARDWARE' ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-500 text-emerald-800 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50' }}">
                                    <span class="block text-base">🖥️</span>
                                    <span class="block text-[11px] font-bold mt-0.5">HW / Hardware</span>
                                </button>

                                <button type="button"
                                        wire:click="setCategory('SOFTWARE')"
                                        class="py-2 px-1 text-center rounded-xl border transition-all cursor-pointer {{ $category === 'SOFTWARE' ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-500 text-emerald-800 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50' }}">
                                    <span class="block text-base">💻</span>
                                    <span class="block text-[11px] font-bold mt-0.5">SIS / Sistemas</span>
                                </button>

                                <button type="button"
                                        wire:click="setCategory('PERMISOS')"
                                        class="py-2 px-1 text-center rounded-xl border transition-all cursor-pointer {{ $category === 'PERMISOS' ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-500 text-emerald-800 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50' }}">
                                    <span class="block text-base">🔑</span>
                                    <span class="block text-[11px] font-bold mt-0.5">ACC / Permisos</span>
                                </button>

                                <button type="button"
                                        wire:click="setCategory('RED_INTERNET')"
                                        class="py-2 px-1 text-center rounded-xl border transition-all cursor-pointer {{ $category === 'RED_INTERNET' ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-500 text-emerald-800 dark:text-emerald-300 font-bold ring-2 ring-emerald-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50' }}">
                                    <span class="block text-base">🌐</span>
                                    <span class="block text-[11px] font-bold mt-0.5">RED / Redes</span>
                                </button>
                            </div>

                            <!-- Fila con Selectores de Categoría y Urgencia -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-xs">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        • Categoría:
                                    </label>
                                    <select wire:model.live="category"
                                            class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                                        <option value="HARDWARE">Equipos / Hardware (PC, Monitor, Impresora)</option>
                                        <option value="SOFTWARE">Sistemas y Aplicativos (SIGM, Windows, Office)</option>
                                        <option value="PERMISOS">Permisos y Accesos (Carpetas, Cuentas)</option>
                                        <option value="RED_INTERNET">Red e Internet (Sin señal, Cables, Wi-Fi)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        • Urgencia:
                                    </label>
                                    <select wire:model.live="priority"
                                            class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer">
                                        <option value="BAJA">Solo me pasa a mí (Baja)</option>
                                        <option value="MEDIA">Afecta a mi oficina (Media)</option>
                                        <option value="ALTA">Bloquea atención al público (Alta)</option>
                                        <option value="CRITICA">Área u oficina paralizada (Crítica)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Activo Tecnológico Vinculado (Opcional - RF-03) -->
                        <div class="rounded-xl border border-blue-200 dark:border-blue-900/60 bg-blue-50/20 dark:bg-blue-950/20 p-3.5 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-1.5 font-bold text-slate-800 dark:text-slate-200">
                                    <span>📷</span>
                                    <span>Activo Tecnológico (Sticker QR / Código PC - Opcional):</span>
                                </div>
                                @if ($selectedAssetId)
                                    <button type="button" wire:click="clearAsset" class="text-rose-600 hover:underline font-bold text-[11px] cursor-pointer">
                                        Quitar
                                    </button>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <input type="text"
                                       wire:model="qrCodeInput"
                                       wire:keydown.enter.prevent="lookupAsset"
                                       placeholder="CÓDIGO O SERIE (EJ. MDC-TI-PC-0001)"
                                       class="flex-1 text-xs p-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-mono uppercase focus:ring-1 focus:ring-emerald-500 focus:outline-none" />
                                <button type="button"
                                        wire:click="lookupAsset"
                                        class="px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg text-xs font-bold cursor-pointer">
                                    Vincular
                                </button>
                            </div>

                            @if ($selectedAssetId)
                                <div class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                                    <span>✓ Activo vinculado:</span>
                                    <span class="font-mono text-slate-800 dark:text-slate-200">{{ $selectedAssetName }}</span>
                                </div>
                            @elseif ($assetLookupError)
                                <div class="text-[11px] font-semibold text-rose-600">
                                    {{ $assetLookupError }}
                                </div>
                            @endif
                        </div>

                        <!-- Asunto Breve * -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                • Asunto: <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="subject"
                                   required
                                   placeholder="Pantalla azul en PC / Impresora no enciende en caja"
                                   class="w-full text-xs p-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none font-medium" />
                        </div>

                        <!-- Detalle / Descripción * -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                • Detalle: <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="description"
                                      required
                                      rows="3"
                                      placeholder="El equipo se reinicia... Describa qué sucede al encender o qué mensaje de error aparece."
                                      class="w-full text-xs p-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none min-h-[85px]"></textarea>
                        </div>

                        <!-- Adjuntos / Foto de Evidencia -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                • Adjuntos:
                            </label>
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold cursor-pointer transition">
                                    <span>📷</span>
                                    <span>Seleccionar archivos...</span>
                                    <input type="file" wire:model="photo" accept="image/*" class="hidden" />
                                </label>
                                @if ($photo)
                                    <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                        <span>✓</span> Foto adjunta lista
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Botón de Envío Principal -->
                        <div class="pt-3">
                            <button type="submit"
                                    class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl text-sm font-black shadow-lg shadow-emerald-700/25 transition-all cursor-pointer flex items-center justify-center gap-2">
                                <span>🚀</span>
                                <span>Registrar y Generar Ticket de Soporte</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================================================================= -->
            <!-- COLUMNA 2: SEGUIMIENTO DE TICKETS DEL ÁREA, TÉCNICO Y VISTO BUENO (lg:col-span-6)       -->
            <!-- ========================================================================================= -->
            <div class="lg:col-span-6 space-y-4">
                
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200/90 dark:border-slate-800 overflow-hidden">
                    
                    <!-- Cabecera del Seguimiento -->
                    <div class="px-5 py-4 bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📋</span>
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900 dark:text-white tracking-tight">
                                    Seguimiento de Tickets del Área ({{ $allCount }})
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Estado, asignación técnica y visto bueno de atención
                                </p>
                            </div>
                        </div>

                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300">
                            En Vivo
                        </span>
                    </div>

                    <!-- Dependencia Activa y Filtros Rápidos -->
                    <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border-b border-emerald-100 dark:border-emerald-900/40 space-y-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[11px] font-bold text-emerald-900 dark:text-emerald-300 truncate max-w-[320px]">
                                🏢 {{ $officeName ?: 'Oficina Seleccionada' }}
                            </span>
                            <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                                Filtrado por dependencia
                            </span>
                        </div>

                        <!-- Pestañas de Filtro Rápido -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                            <button type="button"
                                    wire:click="setTrackingFilter('TODOS')"
                                    class="px-3 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'TODOS' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                Todos ({{ $allCount }})
                            </button>
                            <button type="button"
                                    wire:click="setTrackingFilter('ACTIVOS')"
                                    class="px-3 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'ACTIVOS' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                En Atención ({{ $activeCount }})
                            </button>
                            <button type="button"
                                    wire:click="setTrackingFilter('RESUELTOS')"
                                    class="px-3 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'RESUELTOS' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                Por Visto Bueno ({{ $resolvedCount }})
                            </button>
                            <button type="button"
                                    wire:click="setTrackingFilter('CERRADOS')"
                                    class="px-3 py-1 rounded-lg font-bold text-[11px] transition-colors cursor-pointer {{ $trackingStatusFilter === 'CERRADOS' ? 'bg-slate-700 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                                Cerrados ({{ $closedCount }})
                            </button>
                        </div>

                        <!-- Buscador en tiempo real -->
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
                                $isActive = in_array($statusUpper, ['ABIERTO', 'EN_ATENCION', 'EN_LABORATORIO', 'EN_PAUSA']);

                                $badgeClass = match($statusUpper) {
                                    'RESUELTO', 'SOLUCIONADO' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-300',
                                    'EN_ATENCION', 'EN_PROCESO' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-300',
                                    'EN_LABORATORIO', 'EN_TALLER' => 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border-purple-300',
                                    'EN_PAUSA' => 'bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-300 border-orange-300',
                                    'CERRADO' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-300',
                                    default => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-300',
                                };

                                $statusText = match($statusUpper) {
                                    'RESUELTO', 'SOLUCIONADO' => '5. Resuelto (Listo para Visto Bueno)',
                                    'CERRADO' => '6. Cerrado (Atención Concluida)',
                                    'EN_ATENCION', 'EN_PROCESO' => '2. En Atención (Técnico asignado)',
                                    'EN_LABORATORIO', 'EN_TALLER' => '3. En Taller / Laboratorio',
                                    'EN_PAUSA' => '4. En Pausa (Esperando repuesto)',
                                    default => '1. Abierto (En espera de asignación)',
                                };

                                $catBadge = match(strtoupper($ticket->category)) {
                                    'HARDWARE' => 'Hardware',
                                    'SOFTWARE' => 'Sistemas',
                                    'PERMISOS' => 'Permisos',
                                    'RED_INTERNET' => 'Redes',
                                    default => $ticket->category,
                                };
                            @endphp

                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs hover:shadow-xs transition-shadow space-y-3">
                                
                                <!-- Fila 1: Código de Ticket | Estado | Categoría -->
                                <div class="flex flex-wrap items-center justify-between gap-1.5 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-black text-sm text-slate-900 dark:text-white">
                                            {{ $ticket->ticket_number }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] border {{ $badgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>

                                    <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-semibold">
                                        {{ $catBadge }}
                                    </span>
                                </div>

                                <!-- Fila 2: Asunto y Descripción -->
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-snug">
                                        {{ $ticket->subject }}
                                    </h4>
                                    @if ($ticket->description)
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                            {{ $ticket->description }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Fila 3: ¿QUÉ TÉCNICO LO VA A ATENDER? -->
                                <div class="p-3 rounded-xl border {{ !empty($ticket->assigned_technician_name) ? 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700' : 'bg-blue-50/50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-900/40' }} text-xs space-y-1.5">
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
                                            <div class="flex items-center gap-2">
                                                <span class="text-base">👨‍🔧</span>
                                                <div>
                                                    <strong class="text-slate-900 dark:text-white text-xs block font-bold">
                                                        {{ $ticket->assigned_technician_name }}
                                                    </strong>
                                                    <span class="text-[10px] text-slate-500 dark:text-slate-400">
                                                        Soporte Técnico Especializado ODT
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-mono text-[10px] font-bold border border-emerald-200">
                                                    📞 {{ $ticket->assigned_technician_phone ?? 'Anexo 104' }}
                                                </span>
                                            </div>
                                        </div>

                                        @if ($ticket->technicalDetail && !empty($ticket->technicalDetail->real_diagnosis))
                                            <div class="pt-1 border-t border-slate-200 dark:border-slate-700 text-[11px] text-slate-600 dark:text-slate-300">
                                                <strong>Diagnóstico ODT:</strong> {{ $ticket->technicalDetail->real_diagnosis }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="flex items-start gap-2 text-[11px] text-blue-800 dark:text-blue-300">
                                            <span class="text-sm">⏳</span>
                                            <span>
                                                Su requerimiento está registrado en la cola de Mesa de Ayuda TI. Un técnico especializado será designado en breve para atender su oficina.
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Fila 4: DAR EL VISTO BUENO PARA CERRAR EL TICKET -->
                                <div class="pt-1">
                                    @if ($ticket->user_conformity)
                                        <!-- Ya cuenta con visto bueno del usuario -->
                                        <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs flex items-center justify-between gap-2">
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1.5 font-bold text-emerald-800 dark:text-emerald-300 text-[11px]">
                                                    <span>✅</span>
                                                    <span>Visto Bueno Otorgado (Conforme)</span>
                                                </div>
                                                <p class="text-[10px] text-emerald-900/80 dark:text-emerald-200">
                                                    Calificación: @for($i = 0; $i < ($ticket->user_rating ?? 5); $i++) ⭐ @endfor &bull; {{ $ticket->user_feedback ?? 'Servicio conforme' }}
                                                </p>
                                            </div>

                                            <span class="text-[10px] font-mono text-slate-400">
                                                {{ $ticket->closed_at?->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    @else
                                        <!-- Botón para otorgar visto bueno -->
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                                            <button type="button"
                                                    wire:click="openConformityModal({{ $ticket->id }})"
                                                    class="w-full sm:w-auto flex-1 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-bold text-xs shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                                <span>✅</span>
                                                <span>Dar Visto Bueno / Cerrar Ticket</span>
                                            </button>

                                            <span class="text-[10px] text-slate-400 text-center sm:text-right shrink-0">
                                                Por: {{ $ticket->applicant_name }} &bull; {{ $ticket->created_at?->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        @empty
                            <div class="py-10 text-center text-slate-400 space-y-2">
                                <span class="text-4xl block">📋</span>
                                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                    No hay tickets para esta dependencia
                                </h4>
                                <p class="text-[11px] text-slate-400 max-w-xs mx-auto">
                                    Al enviar su solicitud desde el formulario de la izquierda, aparecerá aquí inmediatamente para realizar el seguimiento en vivo y otorgar su visto bueno.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Panel Informativo ODT Inferior -->
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-600 dark:text-slate-400 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span>🕒 <strong>Atención TI:</strong> Lun-Vie 8:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>📍 Palacio Municipal - 2do Piso &bull; Anexo 104 / 102</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </main>

    <!-- 4. MODAL: DAR EL VISTO BUENO PARA CERRAR EL TICKET -->
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
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Resumen del Requerimiento:</span>
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

    <!-- 5. FOOTER INSTITUCIONAL -->
    <footer class="mt-auto border-t border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 py-4 text-center text-xs text-slate-500 dark:text-slate-400">
        Municipalidad Distrital de Castilla &copy; 2026 &bull; Desarrollado por la Oficina de Desarrollo Tecnológico (ODT)
    </footer>
</div>
