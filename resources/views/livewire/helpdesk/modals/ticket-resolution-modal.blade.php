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
                        <select wire:model="formTechnicalStatus" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200">
                            <option value="EN_ATENCION">EN ATENCIÓN</option>
                            <option value="EN_LABORATORIO">EN LABORATORIO O TALLER</option>
                            <option value="RESUELTO">RESUELTO</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Diagnóstico Verificado *</label>
                        <textarea wire:model="formRealDiagnosis" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Solución Técnica Aplicada *</label>
                        <textarea wire:model="formSolutionApplied" rows="2" class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200" required></textarea>
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
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold">
                            Guardar Atención
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
