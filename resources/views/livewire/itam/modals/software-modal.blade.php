<!-- ========================================================================= -->
    <!-- MODAL 12: REGISTRAR / EDITAR SOFTWARE INSTITUCIONAL (SRS RF-02)           -->
    <!-- ========================================================================= -->
    @if ($isSoftwareModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden animate-scaleUp">
                <!-- Header con degradado Sky -> Indigo idéntico al diseño de referencia -->
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-sky-600 to-indigo-700 text-white">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">💿</span>
                        <div>
                            <h2 class="text-base font-bold">
                                {{ $editingSoftwareId ? 'Editar Software Institucional' : 'Registrar Software Institucional' }}
                            </h2>
                            <p class="text-xs text-sky-100">
                                Catálogo de Sistemas Operativos, Suites Ofimáticas y Licencias (RF-02)
                            </p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('isSoftwareModalOpen', false)" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer text-lg leading-none">
                        ✕
                    </button>
                </div>

                <!-- Formulario -->
                <form wire:submit="saveSoftware" class="p-6 space-y-4">
                    <!-- Nombre del Software / Paquete -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Nombre del Software / Paquete <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="formSoftwareName"
                            placeholder="Ej. Windows 11 Pro 64-bit / Microsoft Office 2021 LTSC / AutoCAD 2024"
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            required
                        />
                    </div>

                    <!-- Versión y Desarrollador -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Versión / Edición
                            </label>
                            <input
                                type="text"
                                wire:model="formSoftwareVersion"
                                placeholder="Ej. 23H2 / Professional"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Desarrollador / Proveedor
                            </label>
                            <input
                                type="text"
                                wire:model="formSoftwareDeveloper"
                                placeholder="Ej. Microsoft / Autodesk / Adobe"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                            />
                        </div>
                    </div>

                    <!-- Tipo de Licenciamiento y Cantidad -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Tipo de Licenciamiento <span class="text-rose-500">*</span>
                            </label>
                            <select
                                wire:model="formSoftwareLicenseType"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                                required
                            >
                                <option value="OEM">OEM (Preinstalado con el equipo)</option>
                                <option value="VOLUMEN">Volumen Institucional / Campus</option>
                                <option value="RETAIL">Retail / Caja</option>
                                <option value="SUSCRIPCION">Suscripción Anual / Mensual</option>
                                <option value="OPEN_SOURCE">Open Source / Libre (GPL, MIT, Apache)</option>
                                <option value="PROPIETARIA">Licencia Perpetua Propietaria</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                Cantidad de Licencias
                            </label>
                            <input
                                type="number"
                                min="1"
                                wire:model="formSoftwareTotalLicenses"
                                class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                                required
                            />
                        </div>
                    </div>

                    <!-- Clave de Activación -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Clave de Activación / Serial (Key)
                        </label>
                        <input
                            type="text"
                            wire:model="formSoftwareLicenseKey"
                            placeholder="XXXXX-XXXXX-XXXXX-XXXXX-XXXXX"
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg font-mono focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                    </div>

                    <!-- Fecha de Expiración -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Fecha de Expiración / Renovación
                        </label>
                        <input
                            type="date"
                            wire:model="formSoftwareExpirationDate"
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none"
                        />
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">
                            Dejar en blanco para licencias perpetuas / OEM.
                        </p>
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                            Observaciones
                        </label>
                        <textarea
                            rows="2"
                            wire:model="formSoftwareNotes"
                            placeholder="Detalles del contrato de software, número de orden de compra..."
                            class="w-full px-3 py-2 text-xs border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-sky-500 focus:outline-none resize-none"
                        ></textarea>
                    </div>

                    <!-- Botones Cancelar / Registrar Software -->
                    <div class="pt-3 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                        <button
                            type="button"
                            wire:click="$set('isSoftwareModalOpen', false)"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-lg transition cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg shadow transition cursor-pointer"
                        >
                            {{ $editingSoftwareId ? 'Guardar Cambios' : 'Registrar Software' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
