@props([
    'offices' => null,
    'label' => null,
    'placeholder' => null,
    'mode' => 'form', // "form" | "filter"
    'valueField' => 'name', // "name" | "full_name" | "id"
    'align' => null, // "left" | "right"
    'allowClear' => false,
    'applicantAutofill' => false,
    'required' => false,
    'id' => null,
])

@php
    $componentId = $id ?? ('office-select-' . uniqid());
    $wireModel = $attributes->wire('model');
    $wirePropName = $wireModel->value();
    $isLive = $wireModel->hasModifier('live');

    if ($placeholder === null) {
        $placeholder = ($mode === 'filter')
            ? 'Todas las oficinas / dependencias...'
            : '-- Seleccione una Oficina o Dependencia --';
    }

    if ($mode === 'filter') {
        $allowClear = true;
    }

    $align = $align ?? 'left';

    $rawOffices = $offices ?? \App\Models\Office::with('parent')->orderBy('name')->get();

    $mappedOffices = collect($rawOffices)->map(function ($o) {
        if (is_array($o)) {
            $name = $o['name'] ?? '';
            $acronym = $o['acronym'] ?? '';
            $sede = $o['sede'] ?? '';
            $code = $o['code'] ?? '';
            $id = $o['id'] ?? null;
            $fullName = !empty($acronym) ? "[{$acronym}] {$name}" : $name;
            return [
                'id' => $id,
                'name' => $name,
                'acronym' => $acronym,
                'code' => $code,
                'sede' => $sede,
                'fullName' => $fullName,
                'label' => $fullName . ($sede ? " - {$sede}" : ''),
                'applicant_name' => $o['applicant_name'] ?? '',
                'applicant_phone' => $o['applicant_phone'] ?? '',
                'applicant_email' => $o['applicant_email'] ?? '',
                'is_inherited' => (bool) ($o['is_inherited'] ?? false),
                'inherited_from' => $o['inherited_from'] ?? '',
            ];
        }

        $name = $o->name ?? '';
        $acronym = $o->acronym ?? '';
        $sede = $o->sede ?? '';
        $code = $o->code ?? '';
        $fullName = !empty($acronym) ? "[{$acronym}] {$name}" : $name;

        return [
            'id' => $o->id,
            'name' => $name,
            'acronym' => $acronym,
            'code' => $code,
            'sede' => $sede,
            'fullName' => $fullName,
            'label' => $fullName . ($sede ? " - {$sede}" : ''),
            'applicant_name' => $o->applicant_name ?? '',
            'applicant_phone' => $o->applicant_phone ?? '',
            'applicant_email' => $o->applicant_email ?? '',
            'is_inherited' => (bool) ($o->is_inherited ?? false),
            'inherited_from' => $o->inherited_from ?? '',
        ];
    })->values();
@endphp

<div id="{{ $componentId }}"
     class="relative w-full"
     x-data="{
        open: false,
        search: '',
        highlightedIndex: 0,
        mode: '{{ $mode }}',
        valueField: '{{ $valueField }}',
        applicantAutofill: {{ $applicantAutofill ? 'true' : 'false' }},
        allowClear: {{ $allowClear ? 'true' : 'false' }},
        localValue: '',
        offices: {{ \Illuminate\Support\Js::from($mappedOffices) }},

        get selectedValue() {
            @if ($wirePropName)
                return ($wire.{{ $wirePropName }} !== undefined && $wire.{{ $wirePropName }} !== null) 
                    ? String($wire.{{ $wirePropName }}) 
                    : '';
            @else
                return this.localValue;
            @endif
        },

        get selectedOffice() {
            const val = this.selectedValue;
            if (!val || val === '') return null;
            return this.offices.find(o => {
                if (this.valueField === 'id') {
                    return String(o.id) === String(val);
                }
                if (this.valueField === 'full_name' || this.valueField === 'fullName') {
                    return o.fullName === val || o.name === val || (`[${o.acronym}] ${o.name}` === val);
                }
                return o.name === val || o.fullName === val || (o.acronym && val === o.acronym);
            }) || null;
        },

        get selectedLabel() {
            const found = this.selectedOffice;
            if (found) {
                if (this.mode === 'filter') {
                    return found.acronym ? `[${found.acronym}] ${found.name}` : found.name;
                }
                if (this.valueField === 'full_name' || this.valueField === 'fullName') {
                    return found.fullName || found.name;
                }
                return found.sede ? `${found.name} - ${found.sede}` : (found.fullName || found.name);
            }
            if (this.selectedValue && this.selectedValue !== '') {
                return this.selectedValue;
            }
            return '{{ addslashes($placeholder) }}';
        },

        get filteredOffices() {
            if (!this.search || !this.search.trim()) {
                return this.offices;
            }
            const q = this.search.toLowerCase().trim();
            return this.offices.filter(o => 
                (o.name && o.name.toLowerCase().includes(q)) || 
                (o.acronym && o.acronym.toLowerCase().includes(q)) || 
                (o.sede && o.sede.toLowerCase().includes(q)) || 
                (o.code && o.code.toLowerCase().includes(q)) || 
                (o.fullName && o.fullName.toLowerCase().includes(q)) || 
                (o.label && o.label.toLowerCase().includes(q))
            );
        },

        isItemSelected(item) {
            if (!item) return false;
            const val = this.selectedValue;
            if (!val || val === '') return false;
            if (this.valueField === 'id') return String(item.id) === String(val);
            if (this.valueField === 'full_name' || this.valueField === 'fullName') {
                return item.fullName === val || item.name === val || (`[${item.acronym}] ${item.name}` === val);
            }
            return item.name === val || item.fullName === val;
        },

        selectOffice(item) {
            let val = '';
            if (item) {
                if (this.valueField === 'id') {
                    val = item.id;
                } else if (this.valueField === 'full_name' || this.valueField === 'fullName') {
                    val = item.fullName;
                } else {
                    val = item.name;
                }
            }

            @if ($wirePropName)
                $wire.set('{{ $wirePropName }}', val);
            @else
                this.localValue = val;
            @endif

            if (item && this.applicantAutofill) {
                if (item.applicant_name) $wire.set('formApplicantName', item.applicant_name);
                if (item.applicant_phone) $wire.set('formApplicantPhone', item.applicant_phone);
                if (item.applicant_email) $wire.set('formApplicantEmail', item.applicant_email);
            }

            this.$dispatch('office-selected', item);
            this.$dispatch('input', val);
            this.$dispatch('change', val);
            this.open = false;
            this.search = '';
            this.highlightedIndex = 0;
        },

        clearOffice(event) {
            if (event) event.stopPropagation();
            @if ($wirePropName)
                $wire.set('{{ $wirePropName }}', '');
            @else
                this.localValue = '';
            @endif
            this.$dispatch('office-selected', null);
            this.$dispatch('input', '');
            this.$dispatch('change', '');
            this.open = false;
            this.search = '';
            this.highlightedIndex = 0;
        },

        navigate(step) {
            const max = this.filteredOffices.length;
            if (max === 0) return;
            this.highlightedIndex = (this.highlightedIndex + step + max) % max;
            this.$nextTick(() => {
                const list = this.$refs.list;
                if (list && list.children[this.highlightedIndex]) {
                    list.children[this.highlightedIndex].scrollIntoView({ block: 'nearest' });
                }
            });
        },

        selectHighlighted() {
            if (this.filteredOffices.length > 0 && this.filteredOffices[this.highlightedIndex]) {
                this.selectOffice(this.filteredOffices[this.highlightedIndex]);
            }
        }
     }"
     @click.outside="open = false"
     @keydown.escape.stop="open = false">

    @if ($label)
        <label class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-0.5">
            {{ $label }}
        </label>
    @endif

    <!-- Botón Disparador del Selector -->
    <button type="button"
            @click="open = !open; if(open) { highlightedIndex = 0; $nextTick(() => $refs.officeSearchInput.focus({ preventScroll: true })); }"
            {{ $attributes->merge(['class' => ($mode === 'filter'
                ? 'w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-left flex items-center justify-between focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all cursor-pointer shadow-xs'
                : 'w-full p-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-left flex items-center justify-between focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all cursor-pointer text-xs'
            )]) }}
            :class="{ 'ring-2 ring-emerald-500 border-emerald-500': open }">
        
        <div class="flex items-center space-x-2 truncate min-w-0 pr-1">
            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="truncate font-medium text-slate-800 dark:text-white"
                  :class="{ 'text-slate-400 dark:text-slate-500 font-normal': (!selectedValue || selectedValue === '') }"
                  x-text="selectedLabel">
            </span>
        </div>

        <div class="flex items-center space-x-1.5 shrink-0 ml-1 text-slate-400">
            <!-- Botón Limpiar (si hay selección activa y allowClear está activo) -->
            <template x-if="allowClear && selectedValue && selectedValue !== ''">
                <span @click.stop="clearOffice($event)"
                      role="button"
                      title="Quitar filtro de oficina"
                      class="p-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </span>
            </template>

            <!-- Indicador Flecha -->
            <svg class="w-3.5 h-3.5 transition-transform duration-150"
                 :class="{ 'rotate-180 text-emerald-600': open }"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </button>

    @if ($required && $wirePropName)
        <input type="hidden" wire:model="{{ $wirePropName }}" required>
    @endif

    <!-- Desplegable Flotante con Buscador Integrado -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 -translate-y-1 scale-98"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-1 scale-98"
         style="display: none;"
         class="absolute z-50 mt-1 {{ $align === 'right' ? 'right-0' : 'left-0' }} {{ $mode === 'filter' ? 'w-72 sm:w-80' : 'w-full min-w-[280px]' }} max-w-[calc(100vw-2rem)] bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden">
        
        <!-- Input de Búsqueda Rápida -->
        <div class="p-2 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50 dark:bg-slate-900/50">
            <div class="relative">
                <input type="text"
                       x-ref="officeSearchInput"
                       x-model="search"
                       @keydown.arrow-down.prevent="navigate(1)"
                       @keydown.arrow-up.prevent="navigate(-1)"
                       @keydown.enter.prevent="selectHighlighted()"
                       placeholder="Escriba para buscar oficina, sigla o sede..."
                       class="w-full text-xs pl-7 pr-7 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                <svg class="w-3.5 h-3.5 absolute left-2 top-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <button type="button"
                        x-show="search.length > 0"
                        @click="search = ''; $refs.officeSearchInput.focus()"
                        class="absolute right-2 top-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs">
                    ✕
                </button>
            </div>
        </div>

        <!-- Lista de Resultados Filtrados -->
        <ul x-ref="list" class="max-h-60 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/40 text-xs">
            <!-- Opción "Todas las dependencias" cuando allowClear o mode="filter" -->
            <template x-if="allowClear || mode === 'filter'">
                <li @click="clearOffice($event)"
                    class="px-3 py-2 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-700/60 text-slate-600 dark:text-slate-300 transition-colors flex items-center justify-between font-medium"
                    :class="{ 'bg-slate-100 dark:bg-slate-800 font-bold text-emerald-700 dark:text-emerald-400': (!selectedValue || selectedValue === '') }">
                    <div class="flex items-center space-x-1.5">
                        <span class="text-slate-400">🏛️</span>
                        <span class="italic text-[11px]">-- Todas las dependencias / oficinas --</span>
                    </div>
                    <svg x-show="!selectedValue || selectedValue === ''" class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
            </template>

            <template x-for="(item, index) in filteredOffices" :key="item.id || item.name">
                <li @click="selectOffice(item)"
                    @mouseenter="highlightedIndex = index"
                    class="px-3 py-2 cursor-pointer transition-colors flex items-center justify-between"
                    :class="{
                        'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 font-semibold': isItemSelected(item),
                        'bg-slate-50 dark:bg-slate-800/80': highlightedIndex === index && !isItemSelected(item),
                        'hover:bg-emerald-50/50 dark:hover:bg-emerald-950/30': !isItemSelected(item)
                    }">
                    <div class="pr-2 min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <template x-if="item.acronym">
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 shrink-0"
                                      x-text="'[' + item.acronym + ']'"></span>
                            </template>
                            <span class="font-medium" x-text="item.name"></span>
                        </div>
                        <template x-if="item.sede">
                            <span class="text-[10px] text-slate-400 dark:text-slate-400 block mt-0.5" x-text="'Sede: ' + item.sede"></span>
                        </template>
                    </div>
                    <div class="flex items-center space-x-1 shrink-0 ml-2">
                        <template x-if="item.is_inherited">
                            <span class="text-[9px] px-1 py-0.5 rounded bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 font-medium">Jerarquía</span>
                        </template>
                        <svg x-show="isItemSelected(item)" class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </li>
            </template>
            <li x-show="filteredOffices.length === 0" class="p-4 text-center text-slate-400 italic text-xs">
                No se encontraron oficinas coincidentes
            </li>
        </ul>
    </div>
</div>
