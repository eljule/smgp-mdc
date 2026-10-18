<!-- MODAL 8: BASE DE CONOCIMIENTOS (RF-14: VISUALIZACIÓN / PUBLICACIÓN)       -->
    <!-- ========================================================================= -->
    @if ($isKbModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-scaleUp">
                <!-- Encabezado del Modal -->
                <div class="p-4 px-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-800/50">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">
                            {{ $kbModalMode === 'CREATE' ? 'Nueva Guía Técnica de Solución (RF-14)' : 'Guía Estandarizada de Soporte TI (RF-14)' }}
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            {{ $kbModalMode === 'CREATE' ? 'Documente el procedimiento estandarizado para resolución de incidencias comunes.' : 'Procedimiento operativo de solución técnica para dependencias municipales.' }}
                        </p>
                    </div>
                    <button type="button" wire:click="$set('isKbModalOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg font-bold cursor-pointer">
                        ✕
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                @if ($kbModalMode === 'CREATE')
                    <form wire:submit.prevent="saveKbArticle" class="p-6 space-y-4 overflow-y-auto text-xs">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Título de la Guía *:
                                </label>
                                <input type="text"
                                       required
                                       wire:model="formArticleTitle"
                                       placeholder="Ej. Solución a caída de red local..."
                                       class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Categoría *:
                                </label>
                                <select wire:model="formArticleCategory"
                                        class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    <option value="HARDWARE">HARDWARE</option>
                                    <option value="SOFTWARE">SOFTWARE</option>
                                    <option value="RED_INTERNET">RED / INTERNET</option>
                                    <option value="PERMISOS">PERMISOS / ACCESOS</option>
                                    <option value="OTROS">OTROS</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Resumen Breve del Problema *:
                            </label>
                            <input type="text"
                                   required
                                   wire:model="formArticleSummary"
                                   placeholder="Breve descripción ejecutiva de la incidencia que resuelve..."
                                   class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Síntomas Comunes que presenta el usuario:
                            </label>
                            <textarea wire:model="formArticleSymptoms"
                                      rows="2"
                                      placeholder="Ej. LED parpadea en naranja, mensaje de error en pantalla..."
                                      class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Guía de Solución Paso a Paso *:
                            </label>
                            <textarea wire:model="formArticleSteps"
                                      required
                                      rows="6"
                                      placeholder="1. Desconectar el equipo de la corriente...&#10;2. Retirar la compuerta posterior...&#10;3. Limpiar con alcohol isopropílico..."
                                      class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg font-mono text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Etiquetas / Tags (separados por coma):
                            </label>
                            <input type="text"
                                   wire:model="formArticleTags"
                                   placeholder="Impresora, EcoTank, Atasco, Rodillos"
                                   class="w-full p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                        </div>

                        <div class="pt-2 flex justify-end space-x-2 border-t border-slate-200 dark:border-slate-800">
                            <button type="button"
                                    wire:click="$set('isKbModalOpen', false)"
                                    class="px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm cursor-pointer">
                                Publicar en Base de Conocimientos
                            </button>
                        </div>
                    </form>
                @else
                    <div class="p-6 space-y-4 overflow-y-auto">
                        @if ($selectedKbArticle)
                            <div class="border-b border-slate-200 dark:border-slate-800 pb-3">
                                <div class="flex items-center space-x-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        {{ $selectedKbArticle->category }}
                                    </span>
                                    <span class="text-xs text-slate-400">
                                        Vistas: {{ $selectedKbArticle->views_count }} | Autor: {{ $selectedKbArticle->author_technician_name ?? 'Soporte TI' }}
                                    </span>
                                </div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $selectedKbArticle->title }}</h2>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">{{ $selectedKbArticle->summary }}</p>
                            </div>

                            @if ($selectedKbArticle->symptoms)
                                <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 rounded-lg p-3 text-xs">
                                    <span class="font-semibold text-amber-900 dark:text-amber-300 block mb-1">Síntomas Reportados:</span>
                                    <p class="text-amber-800 dark:text-amber-200">{{ $selectedKbArticle->symptoms }}</p>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide mb-2">
                                    Procedimiento de Solución Estandarizado:
                                </h4>
                                <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 text-xs text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed font-sans">
                                    {{ $selectedKbArticle->solution_steps }}
                                </div>
                            </div>

                            @if ($selectedKbArticle->tags && count($selectedKbArticle->tags) > 0)
                                <div class="flex flex-wrap gap-1.5 pt-2">
                                    @foreach ($selectedKbArticle->tags as $t)
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] rounded-full">
                                            #{{ $t }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                            wire:click="voteHelpful({{ $selectedKbArticle->id }})"
                                            @disabled($kbHasVoted)
                                            class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium transition-all {{ $kbHasVoted ? 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 cursor-pointer' }}">
                                        <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                        </svg>
                                        {{ $kbHasVoted ? '¡Gracias por tu voto!' : '¿Te fue útil esta solución?' }}
                                    </button>
                                    <span class="text-[11px] text-slate-400">
                                        ({{ $selectedKbArticle->helpful_count }} votos de utilidad)
                                    </span>
                                </div>

                                <button type="button"
                                        wire:click="$set('isKbModalOpen', false)"
                                        class="px-4 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded transition-colors cursor-pointer">
                                    Cerrar
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
