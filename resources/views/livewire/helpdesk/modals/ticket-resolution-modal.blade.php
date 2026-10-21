<!-- MODAL 3: ATENCIÓN Y CIERRE TÉCNICO                                        -->
    <!-- ========================================================================= -->
    @if ($isTechnicalModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 animate-scaleUp">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                        Atención Técnica y Evaluación
                    </h3>
                    <button wire:click="$set('isTechnicalModalOpen', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form wire:submit.prevent="saveTechnicalDetail" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Estado Técnico *</label>
                        <select wire:model.live="formTechnicalStatus" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200">
                            <option value="EN_ATENCION">2. EN ATENCIÓN TÉCNICA</option>
                            <option value="EN_PAUSA">3. EN PAUSA / EN ESPERA</option>
                            <option value="EN_LABORATORIO">4. EN LABORATORIO O TALLER</option>
                            <option value="RESUELTO">5. RESUELTO</option>
                        </select>
                    </div>

                    @if ($formTechnicalStatus === 'EN_PAUSA')
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl space-y-2 border border-amber-200 dark:border-amber-800/60">
                            <label class="block font-bold text-amber-900 dark:text-amber-200">Motivo de la Pausa / En Espera *</label>
                            <div class="flex flex-wrap gap-1 text-[10px]">
                                <button type="button" wire:click="$set('formPauseReason', 'En espera de repuestos / insumos de almacén')" class="px-2 py-0.5 rounded border border-amber-300 dark:border-amber-700 bg-white dark:bg-slate-900 text-amber-900 dark:text-amber-200 hover:bg-amber-100">
                                    📦 Repuestos
                                </button>
                                <button type="button" wire:click="$set('formPauseReason', 'En espera de respuesta o disponibilidad del usuario')" class="px-2 py-0.5 rounded border border-amber-300 dark:border-amber-700 bg-white dark:bg-slate-900 text-amber-900 dark:text-amber-200 hover:bg-amber-100">
                                    👤 Usuario
                                </button>
                                <button type="button" wire:click="$set('formPauseReason', 'Derivado a garantía / soporte técnico de fabricante')" class="px-2 py-0.5 rounded border border-amber-300 dark:border-amber-700 bg-white dark:bg-slate-900 text-amber-900 dark:text-amber-200 hover:bg-amber-100">
                                    🏭 Garantía
                                </button>
                                <button type="button" wire:click="$set('formPauseReason', 'Pendiente de ventana de mantenimiento')" class="px-2 py-0.5 rounded border border-amber-300 dark:border-amber-700 bg-white dark:bg-slate-900 text-amber-900 dark:text-amber-200 hover:bg-amber-100">
                                    🌙 Mantenimiento
                                </button>
                            </div>
                            <textarea wire:model="formPauseReason" rows="2" placeholder="Especifique el motivo por el cual la atención queda en pausa..." class="w-full px-2 py-1.5 bg-white dark:bg-slate-900 rounded border border-amber-300 dark:border-amber-700 text-xs" required></textarea>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            @if ($formTechnicalStatus === 'EN_PAUSA')
                                Diagnóstico / Estado de la Falla *
                            @else
                                Diagnóstico Verificado *
                            @endif
                        </label>
                        <textarea wire:model="formRealDiagnosis" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" placeholder="Descripción de la falla técnica detectada..." required></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            @if ($formTechnicalStatus === 'EN_PAUSA')
                                Acciones Previas Realizadas / Pruebas (Opcional)
                            @else
                                Solución Técnica Aplicada *
                            @endif
                        </label>
                        <textarea wire:model="formSolutionApplied" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" placeholder="Detalle técnico de la solución o pruebas aplicadas..." {{ $formTechnicalStatus === 'EN_PAUSA' ? '' : 'required' }}></textarea>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer font-bold text-amber-900 dark:text-amber-200">
                            <input type="checkbox" wire:model="formIsDecommission" class="rounded text-amber-600">
                            <span>Declarar Baja Patrimonial Definitiva (Daño irreparable)</span>
                        </label>
                        @if ($formIsDecommission)
                            <textarea wire:model="formDecommissionReason" rows="2" placeholder="Motivo técnico de baja..." class="w-full px-2 py-1 bg-white rounded border border-amber-300"></textarea>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isTechnicalModalOpen', false)" class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-bold">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg font-bold text-white shadow-md transition-colors cursor-pointer flex items-center gap-1.5 {{ $formTechnicalStatus === 'EN_PAUSA' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                            @if ($formTechnicalStatus === 'EN_PAUSA')
                                <span>⏸️</span>
                                <span>Guardar y Pausar</span>
                            @elseif ($formTechnicalStatus === 'RESUELTO')
                                <span>✓</span>
                                <span>Guardar y Resolver</span>
                            @else
                                <span>💾</span>
                                <span>Guardar Atención</span>
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
