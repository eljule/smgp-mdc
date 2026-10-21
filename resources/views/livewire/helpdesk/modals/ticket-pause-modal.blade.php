<!-- MODAL: PAUSA DE ATENCIÓN TÉCNICA -->
@if ($isPauseModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full p-6 space-y-4 animate-scaleUp border border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center space-x-2.5">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center font-bold text-base">
                        ⏸️
                    </span>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                            Pausar Atención Técnica
                        </h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                            Pone el ticket en estado <strong class="text-amber-600 dark:text-amber-400">3. En Pausa / En Espera</strong>
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="$set('isPauseModalOpen', false)" class="text-slate-400 hover:text-slate-600 text-lg leading-none cursor-pointer">&times;</button>
            </div>

            <form wire:submit.prevent="saveTicketPause" class="space-y-4 text-xs">
                <!-- Explicación -->
                <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-900/60 rounded-xl text-amber-900 dark:text-amber-200 text-[11px] leading-relaxed">
                    💡 <strong>¿Cuándo usar la pausa?</strong> Cuando no sea posible continuar de inmediato con la intervención debido a factores externos (espera de repuestos, confirmación de la oficina solicitante o servicio técnico de garantía).
                </div>

                <!-- Opciones Rápidas de Motivo -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                        Motivos frecuentes de pausa:
                    </label>
                    <div class="grid grid-cols-1 gap-1.5">
                        <button type="button"
                                wire:click="$set('formPauseReason', 'En espera de repuestos / insumos de almacén')"
                                class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] transition-colors cursor-pointer {{ $formPauseReason === 'En espera de repuestos / insumos de almacén' ? 'bg-amber-500/10 border-amber-500 text-amber-800 dark:text-amber-300 font-bold' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100' }}">
                            📦 En espera de repuestos / insumos de almacén
                        </button>
                        <button type="button"
                                wire:click="$set('formPauseReason', 'En espera de respuesta o disponibilidad del usuario solicitante')"
                                class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] transition-colors cursor-pointer {{ $formPauseReason === 'En espera de respuesta o disponibilidad del usuario solicitante' ? 'bg-amber-500/10 border-amber-500 text-amber-800 dark:text-amber-300 font-bold' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100' }}">
                            👤 En espera de respuesta o disponibilidad del usuario
                        </button>
                        <button type="button"
                                wire:click="$set('formPauseReason', 'Derivado a servicio técnico externo / garantía de fabricante')"
                                class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] transition-colors cursor-pointer {{ $formPauseReason === 'Derivado a servicio técnico externo / garantía de fabricante' ? 'bg-amber-500/10 border-amber-500 text-amber-800 dark:text-amber-300 font-bold' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100' }}">
                            🏭 Derivado a servicio técnico externo / garantía de fabricante
                        </button>
                        <button type="button"
                                wire:click="$set('formPauseReason', 'Pendiente de ventana de mantenimiento fuera de horario laboral')"
                                class="text-left px-2.5 py-1.5 rounded-lg border text-[11px] transition-colors cursor-pointer {{ $formPauseReason === 'Pendiente de ventana de mantenimiento fuera de horario laboral' ? 'bg-amber-500/10 border-amber-500 text-amber-800 dark:text-amber-300 font-bold' : 'bg-slate-50 dark:bg-slate-800/60 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100' }}">
                            🌙 Pendiente de ventana de mantenimiento fuera de horario
                        </button>
                    </div>
                </div>

                <!-- Detalle Específico del Motivo -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Detalle del Motivo de Pausa *
                    </label>
                    <textarea wire:model="formPauseReason"
                              rows="3"
                              class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none"
                              placeholder="Especifique el motivo por el cual la atención queda pausada..."
                              required></textarea>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button"
                            wire:click="$set('isPauseModalOpen', false)"
                            class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200 cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold shadow-md cursor-pointer flex items-center gap-1.5">
                        <span>⏸️</span>
                        <span>Confirmar Pausa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
