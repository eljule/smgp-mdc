@if ($showFormModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-fadeIn">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl relative max-h-[92vh] overflow-y-auto">
            <!-- Botón Cerrar -->
            <button
                type="button"
                wire:click="closeModal"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-2xl font-bold cursor-pointer transition-colors"
            >
                &times;
            </button>

            <!-- Encabezado -->
            <div class="mb-5">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">{{ $isEditing ? '✏️' : '👤' }}</span>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                        {{ $isEditing ? 'Modificar Datos de la Persona' : 'Registrar Nueva Persona en el SIGM' }}
                    </h3>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ $isEditing ? 'Actualice la información de la persona seleccionada en el sistema.' : 'Clasifique si la persona es un colaborador municipal o un ciudadano administrado.' }}
                </p>
            </div>

            <!-- Mensaje de Error dentro del modal -->
            @if ($modalError)
                <div class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center space-x-2">
                    <span>⚠️</span>
                    <span>{{ $modalError }}</span>
                </div>
            @endif

            <!-- Selector de Clasificación (Toggle Visual Grande) -->
            <div class="grid grid-cols-2 gap-3 mb-5 p-1.5 bg-slate-100 dark:bg-slate-900 rounded-xl">
                <button
                    type="button"
                    wire:click="$set('formType', 'PERSONAL')"
                    class="py-2.5 px-3 rounded-lg font-bold text-xs sm:text-sm flex items-center justify-center space-x-2 transition-all cursor-pointer {{ $formType === 'PERSONAL' ? 'bg-[#008f5d] text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}"
                >
                    <span>💼</span>
                    <span>Personal Municipal (Colaborador)</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('formType', 'ADMINISTRADO')"
                    class="py-2.5 px-3 rounded-lg font-bold text-xs sm:text-sm flex items-center justify-center space-x-2 transition-all cursor-pointer {{ $formType === 'ADMINISTRADO' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}"
                >
                    <span>🏛️</span>
                    <span>Administrado (Ciudadano)</span>
                </button>
            </div>

            <form wire:submit.prevent="savePerson" class="space-y-4 text-xs">
                <!-- Fila 1: Documento -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Tipo Documento *
                        </label>
                        <select
                            wire:model.live="formDocumentType"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-medium"
                        >
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="CE">Carnet de Extranjería</option>
                            <option value="PASAPORTE">Pasaporte</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Número de Documento *
                        </label>
                        <input
                            type="text"
                            required
                            wire:model="formDocumentNumber"
                            placeholder="Ej. 45821903"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-mono"
                        />
                    </div>
                </div>

                <!-- Fila 2: Nombres o Razón Social -->
                @if ($formDocumentType === 'RUC')
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Razón Social de la Empresa *
                        </label>
                        <input
                            type="text"
                            required
                            wire:model="formFirstName"
                            placeholder="Ej. Inversiones El Chira S.A.C."
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white uppercase font-medium"
                        />
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Nombres *
                            </label>
                            <input
                                type="text"
                                required
                                wire:model="formFirstName"
                                placeholder="EJ. JUAN ALBERTO"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white uppercase font-medium"
                            />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Apellido Paterno *
                            </label>
                            <input
                                type="text"
                                required
                                wire:model="formPaternalSurname"
                                placeholder="EJ. PÉREZ"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white uppercase font-medium"
                            />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Apellido Materno
                            </label>
                            <input
                                type="text"
                                wire:model="formMaternalSurname"
                                placeholder="EJ. MORALES"
                                class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white uppercase font-medium"
                            />
                        </div>
                    </div>
                @endif

                <!-- Fila 3: Contacto -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Correo Electrónico (Opcional)
                        </label>
                        <input
                            type="email"
                            wire:model="formEmail"
                            placeholder="correo@ejemplo.com"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white"
                        />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Teléfono / Celular (Opcional)
                        </label>
                        <input
                            type="tel"
                            wire:model="formPhone"
                            placeholder="Ej. 969123456"
                            class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white"
                        />
                    </div>
                </div>

                <!-- Fila 4: Domicilio -->
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Dirección de Residencia / Domicilio (Opcional)
                    </label>
                    <input
                        type="text"
                        wire:model="formAddress"
                        placeholder="Ej. Calle Junín N° 123 - Cercado de Castilla"
                        class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white"
                    />
                </div>

                <!-- Sección condicional: PERSONAL MUNICIPAL -->
                @if ($formType === 'PERSONAL')
                    <div class="pt-3 border-t border-slate-200 dark:border-slate-700 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-emerald-700 dark:text-emerald-400">
                                Datos del Puesto y Dependencia Municipal
                            </p>
                            <span class="text-[10px] text-slate-400 font-semibold">
                                Estructura Oficial MDC ({{ count($offices) }} dependencias)
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                    Oficina Asignada *
                                </label>
                                <select
                                    wire:model="formOffice"
                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white text-xs"
                                >
                                    @if ($formOffice && ! $offices->contains(fn ($off) => "[{$off->acronym}] {$off->name}" === $formOffice || $off->name === $formOffice))
                                        <option value="{{ $formOffice }}">{{ $formOffice }}</option>
                                    @endif
                                    @foreach ($offices as $off)
                                        <option value="[{{ $off->acronym }}] {{ $off->name }}">
                                            {{ $off->code }} - [{{ $off->acronym }}] {{ $off->name }} ({{ $off->sede }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                    Cargo Funcional *
                                </label>
                                <input
                                    type="text"
                                    required
                                    wire:model="formPosition"
                                    placeholder="EJ. TÉCNICO TI / INSPECTOR"
                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white uppercase font-medium"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                    Modalidad Laboral *
                                </label>
                                <select
                                    wire:model="formLaborCondition"
                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white"
                                >
                                    <option value="CAS">CAS</option>
                                    <option value="Nombrado D.L. 276">Nombrado D.L. 276</option>
                                    <option value="D.L. 728">D.L. 728</option>
                                    <option value="Locación de Servicios">Locación de Servicios</option>
                                    <option value="CAS - Confianza">CAS - Confianza</option>
                                    <option value="Practicante Pre/Profesional">Practicante Pre/Profesional</option>
                                    <option value="Sin modalidad">Sin modalidad</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                    Fecha de Ingreso (Opcional)
                                </label>
                                <input
                                    type="date"
                                    wire:model="formEntryDate"
                                    class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white"
                                />
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Sección condicional: ADMINISTRADO -->
                    <div class="pt-3 border-t border-slate-200 dark:border-slate-700">
                        <label class="flex items-center space-x-2 text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="formAllowsOnlineAccess"
                                class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500"
                            />
                            <span class="font-semibold">
                                Habilitar ciudadano para trámites en línea (Portal Ciudadano / Licencias / Rentas)
                            </span>
                        </label>
                        <p class="text-[11px] text-slate-400 mt-1 pl-6">
                            Esta opción preparará la ficha del administrado para crearle credenciales de acceso ciudadano en futuras fases.
                        </p>
                    </div>
                @endif

                <!-- Botones de Acción -->
                <div class="pt-4 flex justify-end space-x-2.5 border-t border-slate-200 dark:border-slate-700">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="px-5 py-2 rounded-xl border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold cursor-pointer transition-colors"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2 rounded-xl bg-[#008f5d] hover:bg-emerald-800 text-white font-bold shadow-md cursor-pointer transition-colors flex items-center space-x-2"
                    >
                        <span>{{ $isEditing ? 'Guardar Cambios' : 'Registrar Persona' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif