<div class="space-y-6">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.itam') }}" class="hover:underline">ITAM</a>
                <span>•</span>
                <span>Préstamos</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>📋</span>
                <span>Préstamos y Reservas de Equipos</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Gestión de equipos multimedia, laptops de comisión, proyectores y sonido para sesiones y eventos municipales.
            </p>
        </div>
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer">
            <span>+</span>
            <span>Nuevo Préstamo</span>
        </button>
    </div>

    <!-- Tabla de Préstamos -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Código / Motivo</th>
                        <th class="py-3.5 px-4">Solicitante / Dependencia</th>
                        <th class="py-3.5 px-4">Equipos Prestados</th>
                        <th class="py-3.5 px-4">Fechas</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($loans as $loan)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $loan->loan_code }}</span>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $loan->event_reason }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $loan->borrower_name }}</p>
                                <p class="text-xs text-slate-500">{{ $loan->requesting_office }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @foreach ($loan->items as $item)
                                    <span class="inline-block px-2 py-0.5 rounded text-[11px] bg-slate-100 dark:bg-slate-800 font-mono font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                        {{ $item->asset->computer_code ?? 'Equipo' }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-xs text-slate-600 dark:text-slate-400">Desde: {{ $loan->start_date }}</p>
                                <p class="text-xs text-slate-500">Hasta: {{ $loan->expected_return_date }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $loan->status === 'DEVUELTO' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if ($loan->status !== 'DEVUELTO')
                                    <button wire:click="returnLoan({{ $loan->id }})" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-colors">
                                        Devolver
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">No hay registros de préstamos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Registrar Préstamo -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Registrar Préstamo de Equipo</h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>
                <form wire:submit="saveLoan" class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <label class="block font-semibold mb-1">Dependencia Solicitante</label>
                        <select wire:model="formRequestingOffice" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                            <option value="">Seleccione oficina...</option>
                            @foreach ($offices as $off)
                                <option value="{{ $off->name }}">{{ $off->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Nombre del Responsable / Solicitante</label>
                        <input wire:model="formBorrowerName" type="text" placeholder="Ej. Juan Pérez" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Motivo del Evento / Sesión</label>
                        <textarea wire:model="formEventReason" rows="2" placeholder="Ej. Sesión Ordinaria de Concejo Municipal" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required></textarea>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Equipos a Prestar</label>
                        <select wire:model="formSelectedAssetIds" multiple class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl h-24" required>
                            @foreach ($availableAssets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->computer_code }} - {{ $asset->brand->name ?? '' }} {{ $asset->model->name ?? '' }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Mantenga presionado Ctrl para seleccionar varios equipos.</p>
                    </div>
                    <div class="flex justify-end gap-2 pt-3">
                        <button wire:click="closeCreateModal" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold">Cancelar</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold">Entregar Préstamo</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
