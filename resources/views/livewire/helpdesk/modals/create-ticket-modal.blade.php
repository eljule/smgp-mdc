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
                            <!-- 1. Oficina o Dependencia Municipal (Componente Reutilizable de Búsqueda Inteligente) -->
                            <x-office-search-select
                                wire:model="formOfficeName"
                                :offices="$offices"
                                mode="form"
                                label="Oficina o Dependencia Municipal *:"
                                value-field="name"
                                applicant-autofill
                                placeholder="-- Seleccione una Oficina o Dependencia --"
                                required
                            />

                            <!-- 2. Nombres y Apellidos del Solicitante (No editable) -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Nombres y Apellidos del Solicitante *:
                                </label>
                                <input type="text"
                                       wire:model="formApplicantName"
                                       readonly
                                       tabindex="-1"
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-slate-100 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 cursor-not-allowed select-none focus:outline-none"
                                       title="Detectado automáticamente de la sesión activa (No modificable)">
                            </div>

                            <!-- 3. Anexo Telefónico o Celular (No editable) -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Anexo Telefónico o Celular:
                                </label>
                                <input type="text"
                                       wire:model="formApplicantPhone"
                                       readonly
                                       tabindex="-1"
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-slate-100 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 cursor-not-allowed select-none focus:outline-none"
                                       title="Detectado automáticamente de la sesión activa (No modificable)">
                            </div>

                            <!-- 4. Correo Institucional (Opcional) (No editable) -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
                                    Correo Institucional (Opcional):
                                </label>
                                <input type="email"
                                       wire:model="formApplicantEmail"
                                       readonly
                                       tabindex="-1"
                                       class="w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-slate-100 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 cursor-not-allowed select-none focus:outline-none"
                                       title="Detectado automáticamente de la sesión activa (No modificable)">
                            </div>

                            <!-- Aviso Informativo de Jerarquía Superior / Vacancia -->
                            <div x-show="selectedOffice && selectedOffice.is_inherited"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="col-span-1 md:col-span-2 p-2.5 rounded-lg bg-amber-50/90 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 flex items-start space-x-2.5 text-xs text-amber-900 dark:text-amber-200 shadow-sm"
                                 style="display: none;">
                                <div class="p-1 rounded bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="space-y-0.5">
                                    <div class="font-bold text-[11px] text-amber-900 dark:text-amber-200 flex items-center gap-1.5">
                                        <span>Custodia y Responsabilidad por Jerarquía Superior</span>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-200/80 dark:bg-amber-800 text-amber-900 dark:text-amber-100 font-semibold uppercase tracking-wider">Vacancia / Cese</span>
                                    </div>
                                    <div class="text-[10px] text-amber-800 dark:text-amber-300 leading-relaxed">
                                        El área seleccionada no registra actualmente un funcionario titular en estado activo. Por línea jerárquica y normativa interna, la custodia y gestión de incidencias de esta oficina recae temporalmente en la jefatura de <strong class="underline font-bold" x-text="selectedOffice ? selectedOffice.inherited_from : ''"></strong>.
                                    </div>
                                </div>
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
