<div class="space-y-6 animate-fadeIn max-w-2xl mx-auto">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 text-center space-y-3">
                <span class="text-4xl block">🏷️</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    Consulta Rápida por Código QR Adherido (RF-03)
                </h2>
                <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                    Ingrese el código informático municipal o escanee el sticker QR adherido a la computadora o periférico para consultar su estado y reportar fallas en un solo clic.
                </p>

                <form wire:submit.prevent="handleLookupQr" class="flex space-x-2 pt-2">
                    <input type="text"
                           wire:model="qrCodeInput"
                           placeholder="EJ. MDC-TI-PC-0001 O 740895000101"
                           class="flex-1 p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-mono uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none dark:text-white placeholder:text-slate-400">
                    <button type="submit"
                            style="background-color: #52a78b;"
                            class="px-5 py-2.5 text-white rounded-xl text-xs font-bold shadow-md hover:brightness-95 transition-all cursor-pointer whitespace-nowrap">
                        Consultar Bien
                    </button>
                </form>

                @if ($qrError)
                    <p class="text-xs text-red-600 dark:text-red-400 italic pt-1">{{ $qrError }}</p>
                @endif
            </div>

            @if ($scannedAsset)
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 space-y-4 animate-fadeIn">
                    <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <span class="font-mono text-xs font-bold text-emerald-700 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
                                {{ $scannedAsset->computer_code }}
                            </span>
                            <h3 class="font-bold text-base text-slate-900 dark:text-white mt-1">
                                {{ $scannedAsset->category?->name ?? 'Equipo Informático' }} - {{ $scannedAsset->brand?->name }} {{ $scannedAsset->model?->name }}
                            </h3>
                            <p class="text-xs text-slate-500">
                                Código Patrimonial SBN: <strong class="font-mono">{{ $scannedAsset->patrimonial_code ?? 'N/A' }}</strong>
                            </p>
                        </div>

                        @php
                            $statusBg = match ($scannedAsset->status) {
                                'OPERATIVO' => 'bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300',
                                'EN_MANTENIMIENTO' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
                                'DE_BAJA' => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300',
                                default => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusBg }}">
                            {{ $scannedAsset->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Dependencia Asignada:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $scannedAsset->office ?? 'En Almacén' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Funcionario Custodio:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $scannedAsset->assignedPerson?->full_name ?? 'Sin asignar' }}</span>
                        </div>
                    </div>

                    @if ($scannedAsset->childAssets && $scannedAsset->childAssets->count() > 0)
                        <div class="border border-slate-200 dark:border-slate-800 rounded-xl p-3">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide block mb-1.5">
                                Periféricos Vinculados (Padre-Hijo):
                            </span>
                            <ul class="text-xs space-y-1 text-slate-600 dark:text-slate-400">
                                @foreach ($scannedAsset->childAssets as $c)
                                    <li>
                                        • <strong class="font-mono">{{ $c->computer_code }}</strong>: {{ $c->category?->name }} ({{ $c->model?->name ?? $c->brand?->name }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="pt-2 flex justify-end">
                        <button type="button"
                                wire:click="openCreateModal({{ $scannedAsset->id }})"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition-colors cursor-pointer">
                            🚀 Reportar Incidencia sobre este Activo
                        </button>
                    </div>
                </div>
            @endif
        </div>
