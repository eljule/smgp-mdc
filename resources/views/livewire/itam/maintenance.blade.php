<div class="space-y-6">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.itam') }}" class="hover:underline">ITAM</a>
                <span>•</span>
                <span>Mantenimiento TI</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>🛠️</span>
                <span>Órdenes de Mantenimiento</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Control de mantenimiento preventivo y correctivo, registro de fallas, técnico asignado y actividades realizadas.
            </p>
        </div>
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer">
            <span>+</span>
            <span>Nueva Orden de Trabajo</span>
        </button>
    </div>

    <!-- Tabla de Órdenes -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Código / Tipo</th>
                        <th class="py-3.5 px-4">Equipo Informático</th>
                        <th class="py-3.5 px-4">Técnico Asignado</th>
                        <th class="py-3.5 px-4">Fecha Programada</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $order->code }}</span>
                                <p class="text-[11px] font-bold text-slate-500 uppercase">{{ $order->type }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $order->asset->computer_code ?? 'Equipo' }}</p>
                                <p class="text-xs text-slate-500">{{ $order->asset->brand->name ?? '' }} {{ $order->asset->model->name ?? '' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $order->technician_name }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $order->scheduled_date }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $order->status === 'COMPLETADO' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if ($order->status !== 'COMPLETADO')
                                    <button wire:click="completeOrder({{ $order->id }})" class="px-2.5 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-colors">
                                        Completar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">No hay órdenes de mantenimiento registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Registrar Orden -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Nueva Orden de Mantenimiento</h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>
                <form wire:submit="saveOrder" class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <label class="block font-semibold mb-1">Equipo a Intervenir</label>
                        <select wire:model="formAssetId" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                            <option value="">Seleccione equipo...</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->computer_code }} - {{ $asset->brand->name ?? '' }} {{ $asset->model->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold mb-1">Tipo</label>
                            <select wire:model="formType" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                                <option value="PREVENTIVO">PREVENTIVO</option>
                                <option value="CORRECTIVO">CORRECTIVO</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Prioridad</label>
                            <select wire:model="formPriority" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                                <option value="BAJA">BAJA</option>
                                <option value="MEDIA">MEDIA</option>
                                <option value="ALTA">ALTA</option>
                                <option value="URGENTE">URGENTE</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Técnico Responsable</label>
                        <input wire:model="formTechnicianName" type="text" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-3">
                        <button wire:click="closeCreateModal" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold">Cancelar</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold">Programar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
