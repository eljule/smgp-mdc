@php
    $sedeName = $node['sede'];
    $sedeBadgeColor = \App\Models\Sede::getBadgeClasses($sedeName);
    $hasChildren = ! empty($node['children']);
@endphp
<div
    x-data="{ expanded: true }"
    class="border border-slate-200 dark:border-slate-800 rounded-xl p-3 bg-slate-50/50 dark:bg-slate-800/30 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all"
>
    <div class="flex items-start md:items-center justify-between gap-2">
        <div class="flex items-center space-x-2.5 flex-1">
            @if ($hasChildren)
                <button
                    type="button"
                    @click="expanded = !expanded"
                    class="text-xs p-1 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-bold cursor-pointer"
                >
                    <span x-show="expanded">▼</span>
                    <span x-show="!expanded" x-cloak>►</span>
                </button>
            @endif

            <span class="font-mono font-bold text-xs text-emerald-700 dark:text-emerald-400 bg-white dark:bg-slate-900 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700">
                {{ $node['code'] }}
            </span>
            <span class="font-mono font-black text-xs text-slate-800 dark:text-slate-200">
                [{{ $node['sigla'] }}]
            </span>
            <span class="font-bold text-xs text-slate-900 dark:text-white uppercase">
                {{ $node['name'] }}
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $sedeBadgeColor }}">
                📍 {{ $node['sede'] }}
            </span>
            <button
                type="button"
                wire:click="openEditModal({{ $node['id'] }})"
                class="p-1 hover:bg-slate-200 dark:hover:bg-slate-700 rounded text-xs text-amber-600 dark:text-amber-400 transition-colors cursor-pointer"
                title="Editar"
            >
                ✏️
            </button>
        </div>
    </div>

    @if ($hasChildren)
        <div
            x-show="expanded"
            class="pl-6 pt-3 mt-2 border-l-2 border-emerald-300 dark:border-emerald-700 space-y-2"
        >
            @foreach ($node['children'] as $child)
                @include('livewire.admin.office-tree-node', ['node' => $child])
            @endforeach
        </div>
    @endif
</div>
