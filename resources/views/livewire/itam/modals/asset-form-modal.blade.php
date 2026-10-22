<!-- ========================================================================= -->
    <!-- MODAL 4: REGISTRAR / EDITAR ACTIVO TECNOLÓGICO                            -->
    <!-- ========================================================================= -->
    @if ($isAssetModalOpen)
        @php
            $filteredModels = $formBrandId ? $models->where('brand_id', $formBrandId) : collect();
            $currentCategory = $categories->firstWhere('id', $formCategoryId);
            $currentSchema = $currentCategory?->custom_fields_schema ?? [];
            $selectedCatCode = $currentCategory?->code ?? 'PC';
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-5xl max-h-[92vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header con Gradiente Institucional -->
                <div class="px-6 py-4 border-b border-emerald-800 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-[#0d4f2f] via-[#105d38] to-[#0a3d24] text-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-xl shadow-inner">
                            {{ $currentCategory?->icon ?? '💻' }}
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold flex items-center gap-2">
                                <span>{{ $editingAssetId ? 'Editar Activo Tecnológico' : 'Registrar Nuevo Activo Tecnológico' }}</span>
                                @if ($editingAssetId)
                                    <span class="font-mono text-xs px-2 py-0.5 rounded bg-emerald-500/30 border border-emerald-400/30 text-emerald-200">
                                        {{ $formComputerCode }}
                                    </span>
                                @endif
                            </h2>
                            <p class="text-xs text-emerald-200/90">
                                Inventario TI Municipal &bull; Formulario adaptativo de especificaciones técnicas
                            </p>
                        </div>
                    </div>

                    <button type="button"
                            wire:click="$set('isAssetModalOpen', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-emerald-200 hover:text-white hover:bg-white/10 transition-colors cursor-pointer text-lg">
                        &times;
                    </button>
                </div>

                <!-- Formulario con Scroll Interno -->
                <form wire:submit="saveAsset" class="flex-1 overflow-y-auto p-6 space-y-6">
                    @if ($errors->any())
                        <div class="p-3 text-xs font-semibold text-rose-700 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50 rounded-xl">
                            Por favor complete los campos obligatorios requeridos (*) antes de guardar.
                        </div>
                    @endif
                    <!-- Sección 01: Clasificación y Categoría -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-4">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                                <span>01</span> CLASIFICACIÓN Y CATEGORÍA DEL ACTIVO
                            </h3>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                * Cambiar la categoría adaptará automáticamente las especificaciones técnicas
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Categoría Tecnológica -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Categoría Tecnológica *
                                </label>
                                <select wire:model.live="formCategoryId"
                                        @disabled($editingAssetId)
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-60 cursor-pointer">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }} ({{ $cat->code }})</option>
                                    @endforeach
                                </select>
                                @if ($currentCategory)
                                    <p class="mt-1 text-[11px] text-slate-500 dark:text-slate-400">
                                        Código proyectado: <span class="font-mono font-bold text-emerald-700 dark:text-emerald-400">MDC-TI-{{ $selectedCatCode }}-XXXX</span>
                                    </p>
                                @endif
                            </div>

                            <!-- Marca -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Marca *
                                    </label>
                                    <button type="button"
                                            wire:click="$toggle('showAddBrand')"
                                            class="text-[11px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold cursor-pointer">
                                        + Nueva Marca
                                    </button>
                                </div>
                                @if ($showAddBrand)
                                    <div class="flex gap-1.5">
                                        <input type="text"
                                               wire:model="newBrandName"
                                               placeholder="Nombre marca (ej. Asus)"
                                               class="flex-1 text-xs px-2.5 py-1.5 rounded-lg border border-emerald-400 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                        <button type="button"
                                                wire:click="quickAddBrand"
                                                class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 cursor-pointer">
                                            Guardar
                                        </button>
                                        <button type="button"
                                                wire:click="$set('showAddBrand', false)"
                                                class="px-2 py-1 text-xs text-slate-500 hover:text-slate-700 cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                @else
                                    <select wire:model.live="formBrandId"
                                            class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer">
                                        <option value="">-- Seleccionar Marca --</option>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('formBrandId')
                                    <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Modelo -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Modelo *
                                    </label>
                                    @if ($formBrandId)
                                        <button type="button"
                                                wire:click="$toggle('showAddModel')"
                                                class="text-[11px] text-emerald-700 dark:text-emerald-400 hover:underline font-semibold cursor-pointer">
                                            + Nuevo Modelo
                                        </button>
                                    @endif
                                </div>
                                @if ($showAddModel)
                                    <div class="flex gap-1.5">
                                        <input type="text"
                                               wire:model="newModelName"
                                               placeholder="Nombre modelo (ej. ProBook 450)"
                                               class="flex-1 text-xs px-2.5 py-1.5 rounded-lg border border-emerald-400 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                        <button type="button"
                                                wire:click="quickAddModel"
                                                class="px-2.5 py-1 text-xs font-semibold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 cursor-pointer">
                                            Guardar
                                        </button>
                                        <button type="button"
                                                wire:click="$set('showAddModel', false)"
                                                class="px-2 py-1 text-xs text-slate-500 hover:text-slate-700 cursor-pointer">
                                            ✕
                                        </button>
                                    </div>
                                @else
                                    <select wire:model="formModelId"
                                            @disabled(!$formBrandId)
                                            class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white disabled:opacity-50 cursor-pointer">
                                        <option value="">
                                            {{ !$formBrandId ? 'Primero seleccione una marca' : '-- Seleccionar Modelo --' }}
                                        </option>
                                        @foreach ($filteredModels as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('formModelId')
                                    <p class="text-[11px] text-rose-500 font-medium mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sección 02: Identificación Institucional, Estado y Custodia -->
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                            <span>02</span> IDENTIFICACIÓN INSTITUCIONAL, ESTADO Y CUSTODIA
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Código Patrimonial (SBN) -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Código Patrimonial (SBN)
                                </label>
                                <input type="text"
                                       wire:model="formPatrimonialCode"
                                       placeholder="Ej. 740895000105"
                                       class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Número de Serie -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Número de Serie
                                </label>
                                <input type="text"
                                       wire:model="formSerialNumber"
                                       placeholder="Ej. S/N 8920FA4401"
                                       class="w-full text-xs font-mono px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Color del Equipo -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Color del Equipo
                                </label>
                                <input type="text"
                                       wire:model="formColor"
                                       placeholder="Ej. Negro, Plata, Gris"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Fecha de Adquisición -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Fecha de Adquisición
                                </label>
                                <input type="date"
                                       wire:model="formAcquisitionDate"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer">
                            </div>

                            <!-- Estado Operativo -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Estado Operativo *
                                </label>
                                <select wire:model="formStatus"
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium cursor-pointer">
                                    <option value="OPERATIVO">🟢 Operativo</option>
                                    <option value="EN_MANTENIMIENTO">🟡 En Mantenimiento</option>
                                    <option value="EN_CUSTODIA">🔵 En Custodia / Almacén TI</option>
                                    <option value="EN_DESUSO">⚪ En Desuso</option>
                                    <option value="PARA_BAJA">🔴 Para Baja Técnica</option>
                                </select>
                            </div>

                            <!-- Condición Física -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Condición Física *
                                </label>
                                <select wire:model="formPhysicalCondition"
                                        class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium cursor-pointer">
                                    <option value="NUEVO">✨ Nuevo / Sellado</option>
                                    <option value="BUENO">👍 Bueno</option>
                                    <option value="REGULAR">⚠️ Regular</option>
                                    <option value="MALO">❌ Malo / Deteriorado</option>
                                </select>
                            </div>

                            <!-- Dependencia / Oficina -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Dependencia / Oficina
                                </label>
                                <input type="text"
                                       list="modal-municipal-offices"
                                       wire:model.live.debounce.300ms="formOffice"
                                       placeholder="Oficina de Desarrollo Tecnológico"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <datalist id="modal-municipal-offices">
                                    @foreach ($offices as $off)
                                        <option value="[{{ $off->acronym }}] {{ $off->name }} - {{ $off->sede }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Custodio Asignado -->
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block">
                                        Custodio Asignado (Personal)
                                    </label>
                                    @if ($formAssignedPersonId)
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Personal Vinculado
                                        </span>
                                    @endif
                                </div>
                                <input type="text"
                                       list="modal-custodians"
                                       wire:model.live.debounce.300ms="formAssignedPersonName"
                                       placeholder="Buscar por nombre o DNI (o dejar en blanco para Almacén TI)..."
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                <datalist id="modal-custodians">
                                    <option value="-- Sin Custodio Asignado (Almacén TI) --"></option>
                                    @foreach ($people as $p)
                                        <option value="{{ $p->full_name }} ({{ $p->document_number }})">{{ $p->position ? $p->position . ' — ' : '' }}{{ $p->office }}</option>
                                    @endforeach
                                </datalist>
                            </div>

                            <!-- Proveedor / Contratista -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Proveedor / Contratista
                                </label>
                                <input type="text"
                                       wire:model="formSupplier"
                                       placeholder="Ej. Deltron, Compured, Lenovo Perú"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            </div>

                            <!-- Vencimiento Garantía -->
                            <div>
                                <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 block mb-1">
                                    Vencimiento Garantía
                                </label>
                                <input type="date"
                                       wire:model="formWarrantyEndDate"
                                       class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white cursor-pointer">
                            </div>

                            <!-- Disponible para Préstamos -->
                            <div class="flex items-center gap-2 pt-5 md:col-span-2">
                                <input type="checkbox"
                                       id="isLoanableCheckModal"
                                       wire:model="formIsLoanable"
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 cursor-pointer">
                                <label for="isLoanableCheckModal" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer select-none">
                                    Disponible para Préstamo / Cesión Temporal (RF-17)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 03: Especificaciones Técnicas Dinámicas -->
                    <div class="p-5 rounded-xl border border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/20 dark:bg-emerald-950/10 space-y-4">
                        <div class="flex items-center justify-between border-b border-emerald-200/60 dark:border-emerald-900/40 pb-2 flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                    03
                                </span>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">
                                        Especificaciones Técnicas Dinámicas
                                    </h3>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                        Campos adaptados para la categoría <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $currentCategory?->name ?? 'Seleccionada' }}</span>
                                    </p>
                                </div>
                            </div>

                            <span class="text-[11px] px-2.5 py-1 rounded-full font-bold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                                {{ count($currentSchema) }} Parámetros configurados
                            </span>
                        </div>

                        @if (empty($currentSchema))
                            <div class="p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-center">
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Esta categoría no tiene campos técnicos específicos configurados. Los activos usarán únicamente los datos generales.
                                </p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($currentSchema as $field)
                                    @php
                                        $fieldKey = $field['key'] ?? '';
                                        $fieldType = $field['type'] ?? 'text';
                                        $fieldLabel = $field['label'] ?? ucfirst($fieldKey);
                                        $isRequired = $field['required'] ?? false;
                                        $placeholder = $field['placeholder'] ?? '';
                                        $rawOpts = $field['options'] ?? [];
                                        if (is_string($rawOpts)) {
                                            $options = array_values(array_filter(array_map('trim', explode(',', $rawOpts))));
                                        } elseif (!empty($field['options_str'])) {
                                            $options = array_values(array_filter(array_map('trim', explode(',', $field['options_str']))));
                                        } elseif (is_array($rawOpts)) {
                                            $options = array_values($rawOpts);
                                        } else {
                                            $options = [];
                                        }
                                        $isFullWidth = in_array($fieldType, ['textarea', 'tags', 'list', 'array']);
                                    @endphp
                                    <div class="space-y-1.5 {{ $isFullWidth ? 'md:col-span-2' : '' }}" wire:key="spec-field-{{ $fieldKey }}">
                                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                            <span>{{ $fieldLabel }}</span>
                                            @if ($isRequired)
                                                <span class="text-rose-500 font-bold" title="Campo obligatorio">*</span>
                                            @endif
                                            @if (!empty($field['unit']))
                                                <span class="text-slate-400 text-[10px] font-normal">({{ $field['unit'] }})</span>
                                            @endif
                                        </label>

                                        @if ($fieldType === 'text')
                                            <input type="text"
                                                   wire:model="formSpecifications.{{ $fieldKey }}"
                                                   placeholder="{{ $placeholder ?: ('Ingrese ' . strtolower($fieldLabel)) }}"
                                                   class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none">
                                        @elseif ($fieldType === 'number')
                                            <input type="number"
                                                   wire:model="formSpecifications.{{ $fieldKey }}"
                                                   placeholder="{{ $placeholder ?: '0' }}"
                                                   class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none">
                                        @elseif ($fieldType === 'select')
                                            <select wire:model="formSpecifications.{{ $fieldKey }}"
                                                    class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none cursor-pointer">
                                                <option value="">-- Seleccione una opción --</option>
                                                @foreach ($options as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        @elseif ($fieldType === 'boolean')
                                            <label class="flex items-center gap-3 p-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800/40 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                                <input type="checkbox"
                                                       wire:model="formSpecifications.{{ $fieldKey }}"
                                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-600 cursor-pointer">
                                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300">
                                                    {{ $placeholder ?: 'Sí / Habilitado' }}
                                                </span>
                                            </label>
                                        @elseif ($fieldType === 'textarea')
                                            <textarea rows="2"
                                                      wire:model="formSpecifications.{{ $fieldKey }}"
                                                      placeholder="{{ $placeholder ?: ('Ingrese ' . strtolower($fieldLabel)) }}"
                                                      class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none resize-none"></textarea>
                                        @elseif (in_array($fieldType, ['tags', 'list', 'array']))
                                            <div x-data="{
                                                     items: @entangle('formSpecifications.' . $fieldKey),
                                                     newTag: '',
                                                     get tagList() {
                                                         if (!this.items) return [];
                                                         if (Array.isArray(this.items)) return this.items;
                                                         if (typeof this.items === 'string') {
                                                             try {
                                                                 const parsed = JSON.parse(this.items);
                                                                 if (Array.isArray(parsed)) return parsed;
                                                             } catch (e) {}
                                                             return this.items.trim() ? this.items.split(',').map(s => s.trim()).filter(Boolean) : [];
                                                         }
                                                         return [];
                                                     },
                                                     addTag(val) {
                                                         const tag = (val !== undefined ? val : this.newTag).trim();
                                                         if (!tag) return;
                                                         let current = this.tagList.slice();
                                                         if (!current.includes(tag)) {
                                                             current.push(tag);
                                                             this.items = current;
                                                         }
                                                         if (val === undefined) {
                                                             this.newTag = '';
                                                         }
                                                     },
                                                     removeTag(tagToRemove) {
                                                         let current = this.tagList.filter(t => t !== tagToRemove);
                                                         this.items = current;
                                                     },
                                                     toggleTag(val) {
                                                         if (this.tagList.includes(val)) {
                                                             this.removeTag(val);
                                                         } else {
                                                             this.addTag(val);
                                                         }
                                                     }
                                                 }"
                                                 class="space-y-2">
                                                <!-- Box Contenedor de Elementos Agregados -->
                                                <div class="flex flex-wrap gap-1.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 min-h-[42px] items-center">
                                                    <template x-if="tagList.length === 0">
                                                        <span class="text-xs text-slate-400 dark:text-slate-500 italic select-none">
                                                            Ningún elemento agregado aún. Escriba abajo o seleccione de las sugerencias.
                                                        </span>
                                                    </template>
                                                    <template x-for="(tag, index) in tagList" :key="tag">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold shadow-2xs animate-fadeIn">
                                                            <span x-text="tag"></span>
                                                            <button type="button"
                                                                    @click.stop="removeTag(tag)"
                                                                    class="text-emerald-600 dark:text-emerald-400 hover:text-rose-500 dark:hover:text-rose-400 font-bold text-sm leading-none transition-colors ml-0.5 cursor-pointer"
                                                                    title="Eliminar elemento">
                                                                &times;
                                                            </button>
                                                        </span>
                                                    </template>
                                                </div>

                                                <!-- Fila de Entrada de Texto + Botón Agregar -->
                                                <div class="flex items-center gap-2">
                                                    <input type="text"
                                                           x-model="newTag"
                                                           @keydown.enter.prevent="addTag()"
                                                           placeholder="{{ $placeholder ?: ('Escriba ' . strtolower($fieldLabel) . ' y presione Enter o &quot;+ Agregar&quot;') }}"
                                                           class="flex-1 text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none" />
                                                    <button type="button"
                                                            @click="addTag()"
                                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1 shadow-xs transition-colors shrink-0 cursor-pointer">
                                                        <span>+ Agregar</span>
                                                    </button>
                                                </div>

                                                <!-- Opciones Sugeridas -->
                                                @if (!empty($options))
                                                    <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
                                                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-wider uppercase shrink-0 select-none">
                                                            SUGERIDOS:
                                                        </span>
                                                        @foreach ($options as $opt)
                                                            <button type="button"
                                                                    @click="toggleTag('{{ addslashes($opt) }}')"
                                                                    :class="tagList.includes('{{ addslashes($opt) }}')
                                                                        ? 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-700 font-bold shadow-2xs'
                                                                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-300 dark:hover:bg-emerald-950/40'"
                                                                    class="px-2.5 py-1 rounded-md text-[11px] border transition-colors flex items-center gap-1 cursor-pointer">
                                                                <span x-text="tagList.includes('{{ addslashes($opt) }}') ? '✓' : '+'"></span>
                                                                <span>{{ $opt }}</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <input type="text"
                                                   wire:model="formSpecifications.{{ $fieldKey }}"
                                                   placeholder="{{ $placeholder ?: ('Ingrese ' . strtolower($fieldLabel)) }}"
                                                   class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Sección 04: Observaciones y Notas Adicionales -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5 flex-wrap gap-1">
                            <label class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                Observaciones y Notas Adicionales
                            </label>
                            <button type="button"
                                    wire:click="markForVerification"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 dark:hover:bg-amber-900/80 border border-amber-300 dark:border-amber-700 transition-colors shadow-xs cursor-pointer"
                                    title="Inserta nota de advertencia y preselecciona catálogo genérico si aplica">
                                <span>⚠️</span>
                                <span>Marcar para Verificación Posterior</span>
                            </button>
                        </div>
                        <textarea rows="2"
                                  wire:model="formNotes"
                                  placeholder="Detalles sobre accesorios incluidos, estado del precinto de seguridad, etc."
                                  class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none"></textarea>
                    </div>

                    <!-- Pie del Modal -->
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        <button type="button"
                                wire:click="$set('isAssetModalOpen', false)"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer">
                            <span>✓</span>
                            <span>{{ $editingAssetId ? 'Actualizar Activo' : 'Guardar y Generar Código Informático' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
