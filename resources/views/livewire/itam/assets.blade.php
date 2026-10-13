<div class="space-y-6">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.itam') }}" class="hover:underline">ITAM</a>
                <span>•</span>
                <span>Parque Tecnológico</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>🖥️</span>
                <span>Catálogo de Activos Tecnológicos</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Registro oficial de computadoras, servidores, impresoras, periféricos y equipos de red de Castilla.
            </p>
        </div>
        <button wire:click="openCreateModal" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition-colors cursor-pointer">
            <span>+</span>
            <span>Registrar Activo</span>
        </button>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <div class="relative flex-1 w-full">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm">🔍</span>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Buscar por código MDC, serie, marca, oficina o custodio..."
                class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
        </div>

        <select wire:model.live="categoryCode" class="w-full sm:w-48 px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="TODAS">Todas las Categorías</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->code }}">{{ $cat->icon }} {{ $cat->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="w-full sm:w-44 px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="TODOS">Todos los Estados</option>
            <option value="OPERATIVO">OPERATIVO</option>
            <option value="EN_MANTENIMIENTO">EN MANTENIMIENTO</option>
            <option value="INOPERATIVO">INOPERATIVO</option>
            <option value="PRESTADO">PRESTADO</option>
            <option value="BAJA">DADO DE BAJA</option>
        </select>
    </div>

    <!-- Tabla de Activos -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Código / Categoría</th>
                        <th class="py-3.5 px-4">Marca y Modelo</th>
                        <th class="py-3.5 px-4">Serie / Patrimonial</th>
                        <th class="py-3.5 px-4">Ubicación y Custodio</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($assets as $asset)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl shrink-0">{{ $asset->category->icon ?? '💻' }}</span>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white font-mono text-xs">{{ $asset->computer_code }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ $asset->category->name ?? 'General' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $asset->brand->name ?? 'S/M' }}</p>
                                <p class="text-xs text-slate-500">{{ $asset->model->name ?? 'Modelo Desconocido' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-mono text-xs text-slate-700 dark:text-slate-300">{{ $asset->serial_number ?: 'S/N' }}</p>
                                @if ($asset->patrimonial_code)
                                    <span class="inline-block mt-0.5 text-[10px] font-mono text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 px-1.5 py-0.5 rounded">
                                        PAT: {{ $asset->patrimonial_code }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-800 dark:text-slate-200 truncate max-w-xs">{{ $asset->office ?: 'Sin Asignar' }}</p>
                                <p class="text-[11px] text-slate-500 truncate max-w-xs">
                                    {{ $asset->assignedPerson ? $asset->assignedPerson->full_name : 'Custodia en Almacén ODT' }}
                                </p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $statusClasses = match($asset->status) {
                                        'OPERATIVO' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300',
                                        'EN_MANTENIMIENTO' => 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300',
                                        'INOPERATIVO' => 'bg-rose-100 text-rose-800 dark:bg-rose-950/50 dark:text-rose-300',
                                        'PRESTADO' => 'bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300',
                                        'BAJA' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClasses }}">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button wire:click="openDetailModal({{ $asset->id }})" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors cursor-pointer">
                                    Detalles
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <span class="text-3xl block mb-2">🔍</span>
                                No se encontraron activos que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($assets->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $assets->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Registrar Activo -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>➕</span>
                        <span>Registrar Activo Tecnológico</span>
                    </h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">✕</button>
                </div>

                <form wire:submit="saveAsset" class="p-6 space-y-4 overflow-y-auto flex-1 text-xs sm:text-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Código MDC *</label>
                            <input wire:model="formComputerCode" type="text" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-mono" required>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Código Patrimonial</label>
                            <input wire:model="formPatrimonialCode" type="text" placeholder="Ej. 7408770001" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Categoría *</label>
                            <select wire:model.live="formCategoryId" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                                <option value="">Seleccione...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Marca *</label>
                            <select wire:model.live="formBrandId" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                                <option value="">Seleccione...</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Modelo *</label>
                            <select wire:model="formModelId" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl" required>
                                <option value="">Seleccione...</option>
                                @foreach ($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Número de Serie</label>
                            <input wire:model="formSerialNumber" type="text" placeholder="Ej. 4CE1234XYZ" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Color</label>
                            <input wire:model="formColor" type="text" placeholder="Negro / Gris" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Dependencia / Oficina</label>
                            <select wire:model="formOffice" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                                <option value="">Seleccione oficina...</option>
                                @foreach ($offices as $off)
                                    <option value="{{ $off->name }}">{{ $off->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Personal Asignado</label>
                            <select wire:model="formAssignedPersonId" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                                <option value="">Custodia en Almacén ODT</option>
                                @foreach ($people as $p)
                                    <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Estado Operativo</label>
                            <select wire:model="formStatus" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                                <option value="OPERATIVO">OPERATIVO</option>
                                <option value="EN_MANTENIMIENTO">EN MANTENIMIENTO</option>
                                <option value="INOPERATIVO">INOPERATIVO</option>
                                <option value="PRESTADO">PRESTADO</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Condición Física</label>
                            <select wire:model="formPhysicalCondition" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl">
                                <option value="NUEVO">NUEVO</option>
                                <option value="BUENO">BUENO</option>
                                <option value="REGULAR">REGULAR</option>
                                <option value="MALO">MALO</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input wire:model="formIsLoanable" type="checkbox" id="isLoanable" class="rounded text-emerald-600 focus:ring-emerald-500">
                        <label for="isLoanable" class="text-xs font-semibold text-slate-700 dark:text-slate-300">¿Este equipo está disponible para préstamos temporales / eventos?</label>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-2">
                        <button wire:click="closeCreateModal" type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl font-semibold cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold cursor-pointer">
                            Guardar Activo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Detalle Activo -->
    @if ($showDetailModal && $selectedAsset)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $selectedAsset->computer_code }}</span>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span>{{ $selectedAsset->category->icon ?? '💻' }}</span>
                            <span>{{ $selectedAsset->brand->name ?? '' }} {{ $selectedAsset->model->name ?? '' }}</span>
                        </h3>
                    </div>
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">✕</button>
                </div>

                <div class="p-6 space-y-4 overflow-y-auto flex-1 text-xs sm:text-sm">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 dark:bg-slate-950/60 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Estado</span>
                            <p class="font-bold text-slate-900 dark:text-white">{{ $selectedAsset->status }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Patrimonial</span>
                            <p class="font-bold text-slate-900 dark:text-white font-mono">{{ $selectedAsset->patrimonial_code ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Serie</span>
                            <p class="font-bold text-slate-900 dark:text-white font-mono">{{ $selectedAsset->serial_number ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Oficina</span>
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $selectedAsset->office ?: 'Almacén' }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Custodio</span>
                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $selectedAsset->assignedPerson ? $selectedAsset->assignedPerson->full_name : 'No asignado' }}</p>
                        </div>
                    </div>

                    @if (!empty($selectedAsset->specifications))
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-1.5">
                                <span>⚙️</span>
                                <span>Especificaciones Técnicas</span>
                            </h4>
                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 dark:bg-slate-950/40 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                @foreach ($selectedAsset->specifications as $key => $val)
                                    <div>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold">{{ str_replace('_', ' ', $key) }}:</span>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200">{{ is_array($val) ? json_encode($val) : $val }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                    <button wire:click="closeDetailModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl font-semibold cursor-pointer">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
