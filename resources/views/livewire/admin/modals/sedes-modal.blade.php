<!-- 6. MODAL DE GESTIÓN Y CONFIGURACIÓN DE SEDES -->
    <!-- ========================================== -->
    @if ($showSedesModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-4xl w-full max-h-[92vh] flex flex-col shadow-2xl overflow-hidden">
                <!-- Cabecera del Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center justify-center text-lg font-bold">
                            📍
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">
                                Catálogo de Sedes Municipales y Configuración de Colores
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Gestione los locales físicos de la municipalidad y configure el color de sus insignias para el organigrama y catálogo.
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeSedesModal"
                        class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 text-xl font-bold rounded-lg cursor-pointer"
                    >
                        ✕
                    </button>
                </div>

                <!-- Contenido del Modal (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">

                    <!-- Formulario de Inserción / Edición de Sede -->
                    @if ($showSedeForm)
                        <div class="p-5 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/20 dark:bg-emerald-950/20 space-y-4 animate-fadeIn">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                                    <span>{{ $selectedSedeId ? '✏️ Modificar Sede Municipal' : '➕ Registrar Nueva Sede Municipal' }}</span>
                                </h4>
                                <button
                                    type="button"
                                    wire:click="cancelSedeForm"
                                    class="text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-semibold cursor-pointer"
                                >
                                    Cancelar
                                </button>
                            </div>

                            @if ($sedeErrorMessage)
                                <div class="p-3 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl font-semibold">
                                    ⚠️ {{ $sedeErrorMessage }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Nombre Oficial de la Sede *:
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="sedeFormName"
                                        placeholder="Ej. NUEVA SEDE AGENCIA MUNICIPAL TACALÁ"
                                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                        required
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Código / Sigla:
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="sedeFormCode"
                                        placeholder="Ej. TACALA_01"
                                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-mono uppercase focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                    />
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        Dirección o Referencia Física:
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="sedeFormAddress"
                                        placeholder="Ej. Av. Guardia Civil s/n frente al Parque Principal"
                                        class="w-full text-xs p-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                    />
                                </div>
                            </div>

                            <!-- Selector de Paleta de Colores -->
                            <div class="space-y-2 pt-2 border-t border-slate-200 dark:border-slate-800">
                                <label class="block text-xs font-bold text-slate-800 dark:text-slate-200">
                                    🎨 Configuración de Color Distintivo:
                                </label>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Seleccione el color de identificación que se mostrará en las etiquetas del organigrama en árbol y en el listado de dependencias.
                                </p>

                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 pt-1">
                                    @foreach ($availableColors as $cKey => $cData)
                                        <button
                                            type="button"
                                            wire:click="setSedeColor('{{ $cKey }}')"
                                            class="p-2 rounded-xl border text-left transition-all cursor-pointer flex items-center space-x-2 {{ $sedeFormColor === $cKey
                                                ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white dark:bg-slate-900 shadow-sm font-bold'
                                                : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 hover:bg-white text-slate-700 dark:text-slate-300'
                                            }}"
                                        >
                                            <span class="w-4 h-4 rounded-full flex-shrink-0 border border-black/10 shadow-2xs" style="background-color: {{ $cData['hex'] }};"></span>
                                            <span class="text-[11px] truncate flex-1">{{ explode(' ', $cData['label'])[0] }}</span>
                                            @if ($sedeFormColor === $cKey)
                                                <span class="text-emerald-600 text-xs font-bold">✓</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Vista previa del Badge -->
                                <div class="pt-2 flex items-center gap-3">
                                    <span class="text-xs text-slate-500 font-semibold">Vista previa de insignia:</span>
                                    <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-xs font-bold border {{ $currentSedeBadgePreview }}">
                                        <span>📍</span>
                                        <span>{{ $sedeFormName ? strtoupper($sedeFormName) : 'NOMBRE DE LA SEDE' }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Botones del Formulario -->
                            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200 dark:border-slate-800">
                                <button
                                    type="button"
                                    wire:click="cancelSedeForm"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl cursor-pointer"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="button"
                                    wire:click="saveSede"
                                    class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-all cursor-pointer flex items-center gap-1.5"
                                >
                                    <span>💾</span>
                                    <span>{{ $selectedSedeId ? 'Guardar Cambios' : 'Registrar Sede' }}</span>
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Barra Superior con Botón de Inserción -->
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">
                                Mostrando <strong>{{ count($sedesModels) }}</strong> sedes institucionales registradas
                            </span>
                            <button
                                type="button"
                                wire:click="openCreateSedeForm"
                                class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer"
                            >
                                <span>➕</span>
                                <span>Registrar Nueva Sede</span>
                            </button>
                        </div>
                    @endif

                    <!-- Listado / Tabla de Sedes Registradas -->
                    <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                    <th class="py-3 px-4">Sede / Distintivo de Color</th>
                                    <th class="py-3 px-3">Código</th>
                                    <th class="py-3 px-4">Dirección / Referencia</th>
                                    <th class="py-3 px-3 text-center">Dependencias</th>
                                    <th class="py-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                                @forelse ($sedesModels as $sedeItem)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                                        <!-- Badge con Color Configurado -->
                                        <td class="py-3 px-4 font-semibold">
                                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sedeItem->badge_class }}">
                                                <span>📍</span>
                                                <span class="truncate max-w-[240px]">{{ $sedeItem->name }}</span>
                                            </span>
                                        </td>

                                        <!-- Código -->
                                        <td class="py-3 px-3 font-mono text-[11px] text-slate-500">
                                            {{ $sedeItem->code ?: '—' }}
                                        </td>

                                        <!-- Dirección -->
                                        <td class="py-3 px-4 text-slate-600 dark:text-slate-400 text-[11px] truncate max-w-[200px]">
                                            {{ $sedeItem->address ?: 'Distrito de Castilla' }}
                                        </td>

                                        <!-- Cantidad de Dependencias -->
                                        <td class="py-3 px-3 text-center font-bold">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                {{ $sedeItem->offices_count }} oficinas
                                            </span>
                                        </td>

                                        <!-- Acciones -->
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end space-x-1">
                                                <button
                                                    type="button"
                                                    wire:click="openEditSedeForm({{ $sedeItem->id }})"
                                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-amber-600 dark:text-amber-400 transition-colors cursor-pointer"
                                                    title="Modificar sede y color"
                                                >
                                                    ✏️
                                                </button>
                                                <button
                                                    type="button"
                                                    wire:click="deleteSede({{ $sedeItem->id }})"
                                                    wire:confirm="¿Está seguro de eliminar esta sede municipal? Las dependencias vinculadas se reasignarán automáticamente a la Sede Principal."
                                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-rose-600 dark:text-rose-400 transition-colors cursor-pointer"
                                                    title="Eliminar sede"
                                                >
                                                    🗑️
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                            No hay sedes registradas en este momento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer del Modal -->
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex justify-end">
                    <button
                        type="button"
                        wire:click="closeSedesModal"
                        class="px-5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-200/80 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 rounded-xl transition-colors cursor-pointer"
                    >
                        Cerrar Ventana
                    </button>
                </div>
            </div>
        </div>
    @endif
