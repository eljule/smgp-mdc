<!-- ========================================================================= -->
    <!-- MODAL 2: CONFIGURACIÓN DE PARÁMETROS TÉCNICOS POR CATEGORÍA               -->
    <!-- ========================================================================= -->
    @if ($isCategorySchemaModalOpen && $categoryForSchema)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
            <div class="relative w-full max-w-4xl max-h-[90vh] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden animate-scaleUp">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-sm"
                             style="background-color: {{ $categoryForSchema->color ?? '#2563eb' }}20; color: {{ $categoryForSchema->color ?? '#2563eb' }};">
                            {{ $categoryForSchema->icon ?: '💻' }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>Configuración de Parámetros Técnicos</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold uppercase bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300">
                                    {{ $categoryForSchema->code }}
                                </span>
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Define las especificaciones dinámicas que se solicitarán al registrar activos de <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $categoryForSchema->name }}</span>.
                            </p>
                        </div>
                    </div>

                    <!-- Tab Switcher: Editor / Preview -->
                    <div class="flex items-center gap-2 bg-slate-200 dark:bg-slate-800 p-1 rounded-lg">
                        <button type="button"
                                wire:click="$set('schemaTab', 'editor')"
                                class="px-3 py-1 text-xs font-semibold rounded-md transition-all cursor-pointer {{ $schemaTab === 'editor' ? 'bg-white dark:bg-slate-700 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                            Editor de Esquema ({{ count($schemaFields) }})
                        </button>
                        <button type="button"
                                wire:click="$set('schemaTab', 'preview')"
                                class="px-3 py-1 text-xs font-semibold rounded-md transition-all cursor-pointer {{ $schemaTab === 'preview' ? 'bg-white dark:bg-slate-700 text-emerald-700 dark:text-emerald-400 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                            Vista Previa en Vivo
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    @if ($schemaErrorMsg)
                        <div class="p-3 text-xs font-medium text-rose-700 bg-rose-50 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50 rounded-xl flex items-center justify-between">
                            <span>{{ $schemaErrorMsg }}</span>
                            <button type="button" wire:click="$set('schemaErrorMsg', null)" class="text-rose-500 hover:text-rose-700 cursor-pointer">
                                ✕
                            </button>
                        </div>
                    @endif

                    @if ($schemaTab === 'editor')
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400 block">
                                        @if (empty($schemaFields))
                                            No hay parámetros definidos aún. Haz clic en "Agregar Parámetro" para comenzar.
                                        @else
                                            Se han configurado {{ count($schemaFields) }} atributos técnicos para esta categoría.
                                        @endif
                                    </span>
                                    @if (count($schemaFields) > 1)
                                        <span class="text-[11px] text-emerald-700 dark:text-emerald-400 font-medium flex items-center gap-1.5 mt-0.5">
                                            <span>💡</span>
                                            <span>Puedes reordenar los parámetros usando las flechas (↑ / ↓) o arrastrando las tarjetas.</span>
                                        </span>
                                    @endif
                                </div>

                                <button type="button"
                                        wire:click="addSchemaField"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm self-start sm:self-auto shrink-0 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Agregar Parámetro</span>
                                </button>
                            </div>

                            <!-- Lista de Parámetros -->
                            <div class="space-y-3" x-data="{ draggedId: null, overId: null }">
                                @php $cardNum = 0; $totalCount = count($schemaFields); @endphp
                                @foreach ($schemaFields as $fieldId => $field)
                                    @php $cardNum++; @endphp
                                    <div wire:key="schema-field-{{ $fieldId }}"
                                         x-data="{ 
                                             canDrag: false, 
                                             labelVal: @js($field['label'] ?? ''), 
                                             keyVal: @js($field['key'] ?? '') 
                                         }"
                                         :draggable="canDrag"
                                         x-on:dragstart="
                                             if (!canDrag) {
                                                 $event.preventDefault();
                                                 return false;
                                             }
                                             draggedId = '{{ $fieldId }}';
                                             $event.dataTransfer.effectAllowed = 'move';
                                             $event.dataTransfer.setData('text/plain', '{{ $fieldId }}');
                                         "
                                         x-on:dragover.prevent="overId = '{{ $fieldId }}'"
                                         x-on:dragleave="if (overId === '{{ $fieldId }}') overId = null"
                                         x-on:drop.prevent="
                                             const fromId = draggedId || $event.dataTransfer.getData('text/plain');
                                             if (fromId && fromId !== '{{ $fieldId }}') {
                                                 $wire.reorderSchemaField(fromId, '{{ $fieldId }}');
                                             }
                                             draggedId = null;
                                             overId = null;
                                             canDrag = false;
                                         "
                                         x-on:dragend="draggedId = null; overId = null; canDrag = false"
                                         x-on:mouseup.window="if (!draggedId) canDrag = false"
                                         :class="{
                                             'opacity-40 border-dashed border-emerald-500 bg-emerald-50/20 scale-[0.99]': draggedId === '{{ $fieldId }}',
                                             'border-emerald-500 bg-emerald-50/40 dark:bg-emerald-950/30 ring-2 ring-emerald-500/30 shadow-md': overId === '{{ $fieldId }}' && draggedId !== '{{ $fieldId }}'
                                         }"
                                         class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all space-y-3 cursor-default">
                                        <!-- Cabecera de la Tarjeta -->
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700/60 pb-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <!-- Icono Handle de Arrastre Exclusivo -->
                                                <div x-on:mouseenter="canDrag = true"
                                                     x-on:mouseleave="if (!draggedId && $event.buttons !== 1) canDrag = false"
                                                     x-on:mousedown="canDrag = true"
                                                     class="cursor-grab active:cursor-grabbing p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded hover:bg-slate-200/60 dark:hover:bg-slate-700/60 transition-colors select-none"
                                                     title="Arrastra desde este icono para reordenar la posición">
                                                    <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 8h16M4 16h16" />
                                                    </svg>
                                                </div>

                                                <span class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-300 shrink-0">
                                                    {{ $cardNum }}
                                                </span>
                                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-200"
                                                      x-text="(labelVal || '').trim() !== '' ? labelVal : 'Parámetro sin nombre'">
                                                    {{ $field['label'] ?: 'Parámetro sin nombre' }}
                                                </span>
                                                <code class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 font-mono">
                                                    key: <span x-text="keyVal || 'sin_clave'">{{ $field['key'] ?? '' }}</span>
                                                </code>
                                            </div>

                                            <div class="flex items-center gap-1.5">
                                                <!-- Mover Arriba -->
                                                <button type="button"
                                                        wire:click="moveSchemaField('{{ $fieldId }}', 'up')"
                                                        @if ($cardNum === 1) disabled @endif
                                                        class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-slate-400 transition-colors cursor-pointer"
                                                        title="Mover arriba">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                </button>

                                                <!-- Mover Abajo -->
                                                <button type="button"
                                                        wire:click="moveSchemaField('{{ $fieldId }}', 'down')"
                                                        @if ($cardNum === $totalCount) disabled @endif
                                                        class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-slate-200/70 dark:hover:bg-slate-700/70 disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-slate-400 transition-colors cursor-pointer"
                                                        title="Mover abajo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>

                                                <span class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></span>

                                                <!-- Eliminar -->
                                                <button type="button"
                                                        wire:click="removeSchemaField('{{ $fieldId }}')"
                                                        class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 p-1.5 rounded transition-colors cursor-pointer"
                                                        title="Eliminar parámetro">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Grid de Configuración -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                            <div>
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    Etiqueta Visible *
                                                </label>
                                                <input type="text"
                                                       wire:key="f-lbl-{{ $fieldId }}"
                                                       wire:model.live.debounce.250ms="schemaFields.{{ $fieldId }}.label"
                                                       x-model="labelVal"
                                                       placeholder="Ej. Procesador (CPU)"
                                                       class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                            </div>

                                            <div>
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    Clave Técnica (JSON key) *
                                                </label>
                                                <input type="text"
                                                       wire:key="f-key-{{ $fieldId }}"
                                                       wire:model.live.debounce.250ms="schemaFields.{{ $fieldId }}.key"
                                                       x-model="keyVal"
                                                       placeholder="Ej. processor, ram_gb"
                                                       class="w-full text-xs font-mono px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                            </div>

                                            <div>
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    Tipo de Dato
                                                </label>
                                                <select wire:key="f-typ-{{ $fieldId }}"
                                                        wire:model.live="schemaFields.{{ $fieldId }}.type"
                                                        class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                                    <option value="text">Texto simple</option>
                                                    <option value="number">Número</option>
                                                    <option value="select">Selección / Desplegable</option>
                                                    <option value="tags">Lista / Etiquetas dinámicas (Array)</option>
                                                    <option value="boolean">Booleano (Sí/No)</option>
                                                    <option value="textarea">Área de texto amplio</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center gap-3 pt-4 sm:pt-0">
                                                <label class="flex items-center gap-1.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                                    <input type="checkbox"
                                                           wire:key="f-req-{{ $fieldId }}"
                                                           wire:model="schemaFields.{{ $fieldId }}.required"
                                                           class="rounded text-emerald-600 focus:ring-emerald-500" />
                                                    <span>Obligatorio</span>
                                                </label>

                                                <div class="flex-1">
                                                    <input type="text"
                                                           wire:key="f-unt-{{ $fieldId }}"
                                                           wire:model="schemaFields.{{ $fieldId }}.unit"
                                                           placeholder="Unidad (GB, '', Watts)"
                                                           class="w-full text-xs px-2 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Opciones extra para Select / Tags -->
                                        @if (in_array($field['type'] ?? 'text', ['select', 'tags', 'list']))
                                            <div class="pt-2 border-t border-slate-200 dark:border-slate-800/80">
                                                <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-400 block mb-1">
                                                    {{ ($field['type'] ?? 'text') === 'select' ? 'Opciones de la lista desplegable (separadas por coma)' : 'Opciones sugeridas para agregar rápido (opcional, separadas por coma)' }}
                                                </label>
                                                <input type="text"
                                                       wire:key="f-opt-{{ $fieldId }}"
                                                       wire:model="schemaFields.{{ $fieldId }}.options_str"
                                                       placeholder="{{ ($field['type'] ?? 'text') === 'select' ? 'Ej. 4 GB, 8 GB, 16 GB, 32 GB, 64 GB' : 'Ej. Office 2021 LTSC, AutoCAD 2024, ArcGIS Pro' }}"
                                                       class="w-full text-xs px-2.5 py-1.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                            </div>
                                        @endif

                                        <!-- Placeholder / sugerencia -->
                                        <div>
                                            <input type="text"
                                                   wire:key="f-ph-{{ $fieldId }}"
                                                   wire:model="schemaFields.{{ $fieldId }}.placeholder"
                                                   placeholder="Texto de sugerencia / placeholder (opcional)"
                                                   class="w-full text-xs px-2.5 py-1 rounded border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Vista Previa en Vivo -->
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/40 text-xs text-emerald-800 dark:text-emerald-300">
                                Esta es la visualización exacta que verá el operador al registrar o editar activos pertenecientes a la categoría <strong>{{ $categoryForSchema->name }}</strong>.
                            </div>

                            <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                                @if (empty($schemaFields))
                                    <p class="text-xs text-slate-400 text-center py-6">
                                        No hay parámetros configurados para mostrar en la vista previa.
                                    </p>
                                @else
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($schemaFields as $pf)
                                            @php
                                                $pType = $pf['type'] ?? 'text';
                                                $isFullWidth = in_array($pType, ['textarea', 'tags', 'list', 'array']);
                                                $opts = !empty($pf['options_str']) ? array_filter(array_map('trim', explode(',', $pf['options_str']))) : ($pf['options'] ?? []);
                                            @endphp
                                            <div class="{{ $isFullWidth ? 'md:col-span-2' : '' }}">
                                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                                    {{ $pf['label'] }}
                                                    @if (!empty($pf['required'])) <span class="text-rose-500">*</span> @endif
                                                    @if (!empty($pf['unit'])) <span class="text-slate-400 text-[10px]">({{ $pf['unit'] }})</span> @endif
                                                </label>

                                                @if ($pType === 'select')
                                                    <select wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                            class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                                        <option value="">-- Seleccionar {{ $pf['label'] }} --</option>
                                                        @foreach ($opts as $opt)
                                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif ($pType === 'textarea')
                                                    <textarea rows="3"
                                                              wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                              placeholder="{{ ($pf['placeholder'] ?? '') ?: ('Ingrese ' . ($pf['label'] ?? '')) }}"
                                                              class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white resize-none"></textarea>
                                                @elseif ($pType === 'boolean')
                                                    <div class="flex items-center gap-2 pt-2">
                                                        <input type="checkbox"
                                                               wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                               class="rounded text-emerald-600 focus:ring-emerald-500" />
                                                        <span class="text-xs text-slate-700 dark:text-slate-300">{{ $pf['placeholder'] ?: 'Habilitado / Presente' }}</span>
                                                    </div>
                                                @elseif ($pType === 'number')
                                                    <input type="number"
                                                           wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                           placeholder="{{ $pf['placeholder'] ?: '0' }}"
                                                           class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white" />
                                                @elseif (in_array($pType, ['tags', 'list', 'array']))
                                                    <div x-data="{
                                                             items: @entangle('schemaPreviewValues.' . $pf['key']),
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
                                                        <div class="flex flex-wrap gap-1.5 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 min-h-[42px] items-center">
                                                            <template x-if="tagList.length === 0">
                                                                <span class="text-xs text-slate-400 dark:text-slate-500 italic select-none">
                                                                    Ningún elemento agregado aún. Escriba abajo o seleccione de las sugerencias.
                                                                </span>
                                                            </template>
                                                            <template x-for="(tag, index) in tagList" :key="tag">
                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold shadow-2xs">
                                                                    <span x-text="tag"></span>
                                                                    <button type="button"
                                                                            @click.stop="removeTag(tag)"
                                                                            class="text-emerald-600 dark:text-emerald-400 hover:text-rose-500 dark:hover:text-rose-400 font-bold text-sm leading-none transition-colors ml-0.5 cursor-pointer">&times;</button>
                                                                </span>
                                                            </template>
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            <input type="text"
                                                                   x-model="newTag"
                                                                   @keydown.enter.prevent="addTag()"
                                                                   placeholder="{{ ($pf['placeholder'] ?? '') ?: ('Escriba ' . strtolower($pf['label'] ?? '') . ' y presione Enter o &quot;+ Agregar&quot;') }}"
                                                                   class="flex-1 text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 outline-none" />
                                                            <button type="button"
                                                                    @click="addTag()"
                                                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1 shadow-xs transition-colors shrink-0 cursor-pointer">
                                                                <span>+ Agregar</span>
                                                            </button>
                                                        </div>

                                                        @if (!empty($opts))
                                                            <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
                                                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 tracking-wider uppercase shrink-0 select-none">
                                                                    SUGERIDOS:
                                                                </span>
                                                                @foreach ($opts as $opt)
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
                                                           wire:model.live="schemaPreviewValues.{{ $pf['key'] }}"
                                                           placeholder="{{ ($pf['placeholder'] ?? '') ?: ('Ingrese ' . ($pf['label'] ?? '')) }}"
                                                           class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white" />
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- JSON result preview -->
                            <div class="space-y-1">
                                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                    Valores resultantes en JSON (PostgreSQL JSONB `specifications`)
                                </span>
                                <pre class="p-3 rounded-xl bg-slate-950 text-emerald-400 text-xs font-mono overflow-x-auto max-h-40 border border-slate-800">{{ json_encode($schemaPreviewValues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/60">
                    <button type="button"
                            wire:click="$set('isCategorySchemaModalOpen', false)"
                            class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-xl transition-colors cursor-pointer">
                        Cancelar
                    </button>

                    <button type="button"
                            wire:click="saveCategorySchema"
                            class="inline-flex items-center gap-2 px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Guardar Esquema Técnico</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
