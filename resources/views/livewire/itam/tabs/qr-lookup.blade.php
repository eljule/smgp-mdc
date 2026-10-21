<div class="space-y-6 animate-fadeIn">
            <div class="p-8 rounded-2xl bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] text-white shadow-xl space-y-4">
                <div class="max-w-2xl">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-300">
                        Búsqueda Rápida de Ficha Técnica Patrimonial
                    </span>
                    <h3 class="text-2xl font-black mt-1">
                        Consulta Inmediata por Código Informático / Pistola QR
                    </h3>
                    <p class="text-xs text-emerald-100/90 mt-1">
                        Escanee con un lector de código de barras / QR la etiqueta física adherida al activo, o ingrese manualmente el código informático correlativo (ej. MDC-TI-PC-0001, MDC-TI-MON-0002).
                    </p>
                </div>

                <form wire:submit="handleQuickLookup" class="flex gap-2 max-w-md pt-2">
                    <input type="text"
                           wire:model="quickLookupCode"
                           placeholder="MDC-TI-PC-0001"
                           class="flex-1 text-xs font-mono font-bold px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-emerald-200/50 outline-none focus:ring-2 focus:ring-emerald-400">
                    <button type="submit"
                            class="px-6 py-3 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-md transition-colors cursor-pointer">
                        Consultar Ficha
                    </button>
                </form>

                @if ($lookupError)
                    <p class="text-xs font-medium text-rose-300 bg-rose-950/60 p-3.5 rounded-xl border border-rose-800">
                        {{ $lookupError }}
                    </p>
                @endif

                @if ($lookupResult)
                    <div class="p-5 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-base font-black text-emerald-300">
                                    {{ $lookupResult->computer_code }}
                                </span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase bg-emerald-500/30 text-emerald-200">
                                    {{ $lookupResult->status }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-white mt-1">
                                {{ $lookupResult->brand?->name }} {{ $lookupResult->model?->name }} ({{ $lookupResult->category?->name }})
                            </h4>
                            <p class="text-xs text-emerald-200/80">
                                📍 {{ $lookupResult->office ?: 'Almacén ODT' }} &bull; Custodio: {{ $lookupResult->assignedPerson ? $lookupResult->assignedPerson->full_name : 'Sin asignar' }}
                            </p>
                        </div>
                        <button type="button"
                                wire:click="openDetailModal({{ $lookupResult->id }})"
                                class="px-4 py-2.5 rounded-xl bg-white text-emerald-900 font-bold text-xs shadow-md hover:bg-emerald-50 transition-colors cursor-pointer whitespace-nowrap">
                            Ver Ficha Completa & QR
                        </button>
                    </div>
                @endif
            </div>
        </div>
