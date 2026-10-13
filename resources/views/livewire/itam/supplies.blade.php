<div class="space-y-6">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.itam') }}" class="hover:underline">ITAM</a>
                <span>•</span>
                <span>Almacén de Insumos</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>📦</span>
                <span>Insumos y Consumibles TI</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Control de inventario de tóners, bobinas de red, conectores, repuestos y alertas automáticas de stock crítico.
            </p>
        </div>
        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer">
            <span>+</span>
            <span>Nuevo Insumo</span>
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">🔍</span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por código, nombre o ubicación..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <select wire:model.live="category" class="w-full sm:w-48 px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-700 dark:text-slate-200">
            <option value="TODAS">Todas las Categorías</option>
            <option value="TONER_TINTA">Tóner & Tinta</option>
            <option value="CABLEADO_RED">Cableado de Red</option>
            <option value="CONECTORES">Conectores</option>
            <option value="REPUESTOS_HARDWARE">Repuestos Hardware</option>
        </select>
    </div>

    <!-- Tabla de Insumos -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Código / Nombre</th>
                        <th class="py-3.5 px-4">Categoría</th>
                        <th class="py-3.5 px-4 text-center">Stock Actual</th>
                        <th class="py-3.5 px-4">Ubicación / Modelos</th>
                        <th class="py-3.5 px-4 text-right">Ajuste Rápido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($supplies as $supply)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $supply->code }}</span>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $supply->name }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-xs text-slate-500 font-semibold">{{ str_replace('_', ' ', $supply->category) }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($supply->stock <= $supply->min_stock)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/50 dark:text-rose-300">
                                        ⚠️ {{ $supply->stock }} {{ $supply->unit }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                                        {{ $supply->stock }} {{ $supply->unit }}
                                    </span>
                                @endif
                                <p class="text-[10px] text-slate-400 mt-0.5">Mínimo: {{ $supply->min_stock }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $supply->location ?: 'Almacén Central' }}</p>
                                <p class="text-[11px] text-slate-500 truncate max-w-xs">{{ $supply->compatible_models ?: 'Uso general' }}</p>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="adjustStock({{ $supply->id }}, -1)" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center cursor-pointer">
                                        -
                                    </button>
                                    <button wire:click="adjustStock({{ $supply->id }}, 1)" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center cursor-pointer">
                                        +
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                No hay insumos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nuevo Insumo -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Nuevo Insumo TI</h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>
                <form wire:submit="saveSupply" class="space-y-3 text-xs sm:text-sm">
                    <div>
                        <label class="block font-semibold mb-1">Código</label>
                        <input wire:model="formCode" type="text" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-mono" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Nombre del Insumo</label>
                        <input wire:model="formName" type="text" placeholder="Ej. Tóner HP LaserJet 58A" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold mb-1">Stock Inicial</label>
                            <input wire:model="formStock" type="number" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Stock Mínimo (Alerta)</label>
                            <input wire:model="formMinStock" type="number" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-3">
                        <button wire:click="closeCreateModal" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold">Cancelar</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
