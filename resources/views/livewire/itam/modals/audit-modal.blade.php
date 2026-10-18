<!-- ========================================================================= -->
    <!-- MODAL 11: APERTURA DE INVENTARIO FÍSICO ANUAL (SRS RF-11)                 -->
    <!-- ========================================================================= -->
    @if ($isAuditModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp flex flex-col">
                <!-- Header con degradado Slate-900 -> Indigo-950 -> Blue-900 idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 text-white flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📋</span>
                        <div>
                            <h2 class="text-base font-bold">
                                Apertura de Inventario Físico Anual (RF-11)
                            </h2>
                            <p class="text-xs text-indigo-200">
                                Generación de fotografía (snapshot) del inventario por año fiscal
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isAuditModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Cuerpo del Modal -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <form wire:submit="saveAudit" class="space-y-4 max-w-2xl mx-auto py-2">
                        <!-- Requisito RF-11 Callout -->
                        <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 rounded-xl text-xs text-blue-900 dark:text-blue-200 leading-relaxed">
                            <strong>Requisito RF-11 (Cierres y Aperturas de Inventario Anual):</strong> Al crear la auditoría se congelará una fotografía (snapshot) de todos los activos, sus ubicaciones actuales y custodios para contrastar contra el levantamiento en campo.
                        </div>

                        <!-- Año Fiscal -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Año Fiscal de Inventario <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="number"
                                wire:model="formAuditYear"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Título / Denominación -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Título / Denominación de la Auditoría <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="formAuditTitle"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                required
                            />
                        </div>

                        <!-- Notas / Base Legal -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Notas / Base Legal / Resolución de Inicio
                            </label>
                            <textarea
                                rows="2"
                                wire:model="formAuditNotes"
                                placeholder="Ej. Conforme a Resolución de Alcaldía N° 045-2026-MDC/A..."
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"
                            ></textarea>
                        </div>

                        <!-- Botones de Acción del Formulario -->
                        <div class="flex justify-end space-x-2 pt-2">
                            <button
                                type="button"
                                wire:click="$set('isAuditModalOpen', false)"
                                class="px-4 py-2 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 text-xs font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                            >
                                Tomar Snapshot & Aperturar Inventario
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer del Modal -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end flex-shrink-0">
                    <button
                        type="button"
                        wire:click="$set('isAuditModalOpen', false)"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-medium rounded-lg transition cursor-pointer"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
