<!-- ========================================================================= -->
    @if ($showCeaseDetailModal && $ceaseDetailData)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative space-y-4">
                <button type="button" wire:click="closeModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold cursor-pointer">
                    &times;
                </button>

                <h3 class="text-xl font-black text-slate-900 dark:text-white">
                    Historial de Cese: {{ $ceaseDetailData['fullName'] }}
                </h3>
                <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-xl space-y-1.5 text-xs">
                    <p><span class="text-slate-400">DNI:</span> <strong>{{ $ceaseDetailData['documentNumber'] }}</strong></p>
                    <p><span class="text-slate-400">Fecha de Cese:</span> <strong>{{ $ceaseDetailData['departureDate'] }}</strong></p>
                    <p><span class="text-slate-400">Motivo:</span> <strong class="text-rose-600">{{ $ceaseDetailData['cessationReason'] }}</strong></p>
                    <p><span class="text-slate-400">Última Dependencia:</span> <strong>{{ $ceaseDetailData['office'] }}</strong></p>
                </div>

                @if (count($ceaseDetailData['movements']) > 0)
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300">Actas de Retorno a Almacén Generadas</h4>
                    <div class="space-y-1 text-xs">
                        @foreach ($ceaseDetailData['movements'] as $mv)
                            <div class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-between font-mono">
                                <span class="font-bold text-emerald-700">{{ $mv['actaNumber'] }}</span>
                                <span class="text-slate-500">{{ $mv['assetCode'] }} &rarr; {{ $mv['toOffice'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="pt-3 flex justify-end border-t border-slate-200 dark:border-slate-700">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
