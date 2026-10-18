<!-- 8. Modal de Código QR de Oficina para Mesa de Ayuda -->
    @if ($showQrModal && $selectedOfficeForQr)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-data="{
                copied: false,
                downloading: false,
                copyLink(url) {
                    navigator.clipboard.writeText(url).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2500);
                    });
                },
                downloadImage(url, name) {
                    this.downloading = true;
                    fetch(url)
                        .then(r => r.blob())
                        .then(blob => {
                            const blobUrl = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = blobUrl;
                            a.download = 'QR_Oficina_' + name.replace(/[^a-zA-Z0-9_-]/g, '_') + '.png';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(blobUrl);
                            this.downloading = false;
                        })
                        .catch(() => {
                            this.downloading = false;
                            window.open(url, '_blank');
                        });
                },
                printSign() {
                    window.print();
                }
             }"
             @keydown.escape.window="$wire.closeQrModal()">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden text-center animate-in fade-in zoom-in-95 duration-150">
                <!-- Header del Modal -->
                <div class="px-6 py-4 bg-emerald-700 dark:bg-emerald-800 text-white flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-left">
                        <span class="text-xl">📱</span>
                        <div>
                            <h3 class="font-bold text-sm leading-tight text-white">Código QR para Mesa de Ayuda TI</h3>
                            <p class="text-[11px] text-emerald-100">Cartel de Oficina y Reporte Móvil de Incidencias</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeQrModal" class="p-1 hover:bg-emerald-600/50 rounded-lg text-emerald-100 hover:text-white transition-colors cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Contenido -->
                <div class="p-6 space-y-4">
                    <!-- Datos de la Oficina -->
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-mono text-xs font-bold">
                            <span>Código: {{ $selectedOfficeForQr['code'] }}</span>
                            <span>&bull;</span>
                            <span>[{{ $selectedOfficeForQr['sigla'] }}]</span>
                        </div>
                        <h4 class="text-base font-black text-slate-900 dark:text-white uppercase leading-snug">
                            {{ $selectedOfficeForQr['name'] }}
                        </h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            📍 {{ $selectedOfficeForQr['sede'] }}
                        </p>
                    </div>

                    <!-- Código QR Centrado -->
                    <div class="p-4 bg-white rounded-2xl border-2 border-slate-200 dark:border-slate-700 shadow-inner inline-block mx-auto group relative">
                        <img src="{{ $selectedOfficeForQr['qrUrl'] }}"
                             alt="QR {{ $selectedOfficeForQr['name'] }}"
                             class="w-56 h-56 mx-auto object-contain select-none">
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed max-w-sm mx-auto">
                        Al escanear este código con la cámara de un celular, el trabajador ingresará directamente al formulario de tickets con esta oficina preseleccionada.
                    </p>

                    <!-- Enlace directo con botón de copiar -->
                    <div class="bg-slate-50 dark:bg-slate-800/80 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2 text-left">
                        <div class="truncate text-[11px] font-mono text-slate-600 dark:text-slate-300 select-all">
                            {{ $selectedOfficeForQr['ticketUrl'] }}
                        </div>
                        <button type="button"
                                @click="copyLink('{{ $selectedOfficeForQr['ticketUrl'] }}')"
                                class="shrink-0 px-2.5 py-1 text-xs font-bold rounded-lg transition-colors cursor-pointer"
                                :class="copied ? 'bg-emerald-600 text-white' : 'bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200'">
                            <span x-show="!copied">Copiar</span>
                            <span x-show="copied" x-cloak>¡Copiado! ✓</span>
                        </button>
                    </div>

                    <!-- Acciones Principales -->
                    <div class="grid grid-cols-2 gap-2.5 pt-2">
                        <!-- Imprimir Cartel -->
                        <button type="button"
                                @click="printSign()"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-[#046A38] hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-md transition-all cursor-pointer">
                            <span>🖨️</span>
                            <span>Imprimir Cartel QR</span>
                        </button>

                        <!-- Descargar Imagen -->
                        <button type="button"
                                @click="downloadImage('{{ $selectedOfficeForQr['qrUrl'] }}', '{{ $selectedOfficeForQr['sigla'] ?? 'OFICINA' }}')"
                                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all cursor-pointer"
                                :disabled="downloading">
                            <span>📥</span>
                            <span x-show="!downloading">Descargar Imagen</span>
                            <span x-show="downloading" x-cloak>Descargando...</span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ $selectedOfficeForQr['ticketUrl'] }}" target="_blank" class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold hover:underline flex items-center gap-1">
                            <span>Abrir formulario en otra pestaña</span>
                            <span>&rarr;</span>
                        </a>
                        <button type="button" wire:click="closeQrModal" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plantilla Imprimible A4 de Cartel QR de Oficina (Solo visible al imprimir) -->
        <div id="office-qr-print-sheet" class="hidden print:block fixed inset-0 bg-white text-black p-8 font-sans z-[9999]">
            <div class="max-w-2xl mx-auto border-4 border-[#046A38] rounded-3xl p-8 text-center space-y-6">
                <div class="flex items-center justify-center gap-4 border-b-2 border-emerald-800 pb-4">
                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto object-contain" alt="Castilla">
                    <div class="text-left">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800">MUNICIPALIDAD DISTRITAL DE CASTILLA</h4>
                        <h1 class="text-2xl font-black text-slate-900">MESA DE AYUDA Y SOPORTE TI</h1>
                        <p class="text-xs text-slate-600 font-semibold">Subgerencia de Informática y Estadística</p>
                    </div>
                </div>

                <div class="py-2">
                    <span class="inline-block px-4 py-1 bg-emerald-100 text-emerald-900 font-mono font-bold text-xs rounded-full uppercase mb-2">
                        DEPENDENCIA MUNICIPAL
                    </span>
                    <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight">
                        {{ $selectedOfficeForQr['name'] }}
                    </h2>
                    <p class="text-xs text-slate-600 font-medium mt-1">
                        Sede: {{ $selectedOfficeForQr['sede'] }} &bull; Código: {{ $selectedOfficeForQr['code'] }}
                    </p>
                </div>

                <div class="p-4 bg-white border-2 border-slate-300 rounded-2xl inline-block shadow-sm">
                    <img src="{{ $selectedOfficeForQr['qrUrl'] }}" alt="QR Mesa de Ayuda" class="w-72 h-72 mx-auto object-contain">
                </div>

                <div class="bg-emerald-50 rounded-2xl p-5 text-left text-xs text-emerald-950 space-y-2 border border-emerald-200">
                    <h4 class="font-bold text-emerald-900 uppercase tracking-wide text-center text-sm mb-2">
                        PASOS PARA REGISTRAR SU INCIDENCIA TÉCNICA
                    </h4>
                    <div class="grid grid-cols-3 gap-3 text-center">
                        <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-lg font-black text-emerald-700">1</span>
                            <span class="text-xs font-semibold text-slate-800">Apunte la cámara de su celular al código QR</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-lg font-black text-emerald-700">2</span>
                            <span class="text-xs font-semibold text-slate-800">Detalle el inconveniente en su equipo o sistema</span>
                        </div>
                        <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                            <span class="block text-lg font-black text-emerald-700">3</span>
                            <span class="text-xs font-semibold text-slate-800">Soporte TI atenderá su ticket a la brevedad</span>
                        </div>
                    </div>
                </div>

                <div class="text-[11px] text-slate-500 border-t border-slate-200 pt-3 flex justify-between items-center">
                    <span>Sistema Integrado de Gestión Municipal - SIGM Castilla</span>
                    <span class="font-bold">Colocar este cartel en un lugar visible de la oficina</span>
                </div>
            </div>
        </div>
    @endif
