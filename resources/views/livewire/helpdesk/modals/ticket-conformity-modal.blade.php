<!-- MODAL 5: MECANISMO DE CONFORMIDAD DE CIERRE (RF-05)                       -->
    <!-- ========================================================================= -->
    @if ($isConformityModalOpen && $conformityTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 overflow-y-auto">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden animate-scaleUp">
                <!-- Encabezado Institucional -->
                <div class="bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] px-6 py-4 text-white flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg leading-tight">Mecanismo de Conformidad de Cierre (RF-05)</h3>
                        <p class="text-xs text-emerald-100 mt-0.5">Ticket: {{ $conformityTicket->ticket_number }} - {{ $conformityTicket->subject }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="$set('isConformityModalOpen', false)"
                        class="text-emerald-200 hover:text-white cursor-pointer transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulario de Conformidad -->
                <form wire:submit.prevent="saveUserConformity" class="p-6 space-y-4 text-xs">
                    <!-- Resumen Técnico -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 space-y-1.5">
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Técnico que atendió:</span> {{ $conformityTicket->assigned_technician_display ?: ($conformityTicket->assigned_technician_name ?: 'Ing. Carlos Mendoza (Soporte TI)') }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Diagnóstico:</span> {{ $conformityTicket->technicalDetail?->real_diagnosis ?: 'Cortocircuito irreversible en etapa de regulación VRM de placa madre e integrado puente sur calcinado.' }}</p>
                        <p><span class="font-semibold text-slate-900 dark:text-slate-100">Solución aplicada:</span> {{ $conformityTicket->technicalDetail?->solution_applied ?: 'Equipo tecnológicamente obsoleto sin disponibilidad de placas socket compatibles. Se emite baja técnica.' }}</p>
                    </div>

                    <!-- Pregunta de Visto Bueno -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            ¿El equipo o servicio se encuentra funcionando correctamente?
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                wire:click="setConformity(true)"
                                class="flex items-center justify-center p-3 rounded-xl border text-xs font-medium transition-all cursor-pointer {{ $formUserConformity ? 'bg-green-50 dark:bg-emerald-950/40 border-green-500 text-green-700 dark:text-green-300 shadow-xs ring-2 ring-green-400' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}"
                            >
                                <svg class="w-4 h-4 mr-1.5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Sí, Doy mi Visto Bueno</span>
                            </button>

                            <button
                                type="button"
                                wire:click="setConformity(false)"
                                class="flex items-center justify-center p-3 rounded-xl border text-xs font-medium transition-all cursor-pointer {{ !$formUserConformity ? 'bg-red-50 dark:bg-rose-950/40 border-red-500 text-red-700 dark:text-red-300 shadow-xs ring-2 ring-red-400' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}"
                            >
                                <svg class="w-4 h-4 mr-1.5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>No, Aún presenta falla</span>
                            </button>
                        </div>
                    </div>

                    <!-- Calificación con Estrellas (RF-05) -->
                    @if ($formUserConformity)
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Calificación de Atención del Técnico:
                            </label>
                            <div class="flex items-center space-x-2">
                                @foreach ([1, 2, 3, 4, 5] as $star)
                                    <button
                                        key="{{ $star }}"
                                        type="button"
                                        wire:click="setRating({{ $star }})"
                                        class="focus:outline-none transition-transform hover:scale-110 cursor-pointer"
                                    >
                                        <svg
                                            class="w-7 h-7 {{ $star <= $formUserRating ? 'text-amber-400 fill-amber-400' : 'text-slate-300 dark:text-slate-600' }}"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                @endforeach
                                @php
                                    $ratingLabel = match($formUserRating) {
                                        5 => 'Excelente',
                                        4 => 'Muy Bueno',
                                        3 => 'Bueno',
                                        2 => 'Regular',
                                        default => 'Malo',
                                    };
                                @endphp
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400 ml-2">
                                    {{ $ratingLabel }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Comentarios / Agradecimiento -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            {{ $formUserConformity ? 'Comentarios / Agradecimiento:' : 'Detalle de por qué no está conforme:' }}
                        </label>
                        <textarea
                            wire:model="formUserFeedback"
                            rows="3"
                            required
                            class="w-full text-xs p-2.5 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                            placeholder="{{ $formUserConformity ? 'Ej. Solución verificada satisfactoriamente...' : 'Describa el inconveniente que aún persiste...' }}"
                        ></textarea>
                    </div>

                    <!-- Botones Footer -->
                    <div class="pt-2 flex justify-end space-x-2">
                        <button
                            type="button"
                            wire:click="$set('isConformityModalOpen', false)"
                            class="px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 text-xs font-bold text-white rounded-xl transition-colors shadow-xs cursor-pointer {{ $formUserConformity ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700' }}"
                        >
                            {{ $formUserConformity ? 'Confirmar y Cerrar Ticket' : 'Devolver Ticket a Atención' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
