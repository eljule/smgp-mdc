<!-- ========================================================================= -->
    <!-- MODAL 6: PROGRAMAR / REGISTRAR MANTENIMIENTO (RF-13, RF-16)              -->
    <!-- ========================================================================= -->
    @if ($isMaintenanceModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Encabezado Naranja / Ámbar con Icono de Herramientas -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-amber-600 to-orange-700 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🛠️</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">Programar / Registrar Mantenimiento</h2>
                            <p class="text-xs text-amber-100">
                                @if ($selectedMaintenanceAsset)
                                    {{ $selectedMaintenanceAsset->computer_code }} — {{ $selectedMaintenanceAsset->brand?->name }} {{ $selectedMaintenanceAsset->model?->name }} ({{ $selectedMaintenanceAsset->office ?: 'Almacén Central TI' }})
                                @else
                                    Seleccione un equipo tecnológico para continuar
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            wire:click="closeMaintenanceModal"
                            class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none"
                            title="Cerrar">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveMaintenance" class="p-6 space-y-4">
                    @if ($errors->any())
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs rounded-xl">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Selector de Activo si no está predefinido -->
                    @if (! $selectedMaintenanceAsset)
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Activo Informático <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="maintenanceAssetId"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                    required>
                                <option value="">-- Seleccionar Equipo del Inventario --</option>
                                @foreach ($assets as $ast)
                                    <option value="{{ $ast->id }}">
                                        [{{ $ast->computer_code }}] {{ $ast->brand?->name }} {{ $ast->model?->name }} — {{ $ast->office ?: 'Almacén Central' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Fila 1: Tipo de Mantenimiento & Prioridad -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tipo de Mantenimiento <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="maintenanceType"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                                <option value="PREVENTIVO">Preventivo (Programado / Rutinario)</option>
                                <option value="CORRECTIVO">Correctivo (Falla Operativa / Reparación)</option>
                                <option value="DIAGNOSTICO">Inspección de Diagnóstico</option>
                                <option value="SOFTWARE">Mantenimiento de Software / SO</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Prioridad <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="maintenancePriority"
                                    class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                                <option value="MEDIA">Media</option>
                                <option value="ALTA">Alta</option>
                                <option value="BAJA">Baja</option>
                                <option value="CRITICA">Urgente / Crítica</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 2: Fecha Programada & Técnico Responsable -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Fecha Programada <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   wire:model="maintenanceScheduledDate"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                   required />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Técnico Responsable
                            </label>
                            <input type="text"
                                   placeholder="Técnico de Soporte TI"
                                   wire:model="maintenanceTechnician"
                                   class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                   required />
                        </div>
                    </div>

                    <!-- Fila Opcional: Falla Reportada (si es Correctivo) -->
                    @if ($maintenanceType === 'CORRECTIVO')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Falla Reportada / Síntomas <span class="text-rose-500">*</span>
                            </label>
                            <textarea rows="2"
                                      placeholder="Ej. Pantalla azul al iniciar Windows, ventilador ruidoso, no enciende..."
                                      wire:model="maintenanceFailureReported"
                                      class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"
                                      required></textarea>
                        </div>
                    @endif

                    <!-- Fila 3: Notas / Instrucciones -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Notas / Instrucciones
                        </label>
                        <textarea rows="3"
                                  placeholder="Detalles sobre el acceso al área, requerimientos técnicos..."
                                  wire:model="maintenanceNotes"
                                  class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none"></textarea>
                    </div>

                    <!-- Pie del Modal con Nota RF-16 y Botones -->
                    <div class="pt-3 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">
                            * Conforme al RF-16, se emitirá la Ficha Técnica / Hoja de Servicio Oficial.
                        </span>
                        <div class="flex space-x-2">
                            <button type="button"
                                    wire:click="closeMaintenanceModal"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow-sm transition cursor-pointer flex items-center gap-1.5">
                                <span wire:loading.remove wire:target="saveMaintenance">Guardar Orden</span>
                                <span wire:loading wire:target="saveMaintenance">Guardando...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
