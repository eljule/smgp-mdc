@props([
    'message' => null,
    'type' => 'success',
    'duration' => 6000,
])

@if ($message)
    @php
        $bgColor = match ($type) {
            'error', 'danger' => 'bg-[#be123c] border-rose-400/40 shadow-rose-950/25',
            'warning' => 'bg-[#b45309] border-amber-400/40 shadow-amber-950/25',
            'info' => 'bg-[#0369a1] border-sky-400/40 shadow-sky-950/25',
            default => 'bg-[#046A38] border-emerald-400/40 shadow-emerald-950/25',
        };
        $icon = match ($type) {
            'error', 'danger' => '✕',
            'warning' => '⚠️',
            'info' => 'ℹ️',
            default => '✓',
        };
    @endphp

    <div
        wire:key="toast-{{ md5((string)$message . microtime()) }}"
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, {{ $duration }})"
        x-transition:leave="transition ease-in duration-300 transform translate-x-12 opacity-0 scale-95"
        class="fixed top-20 right-6 sm:right-8 z-50 flex items-center space-x-2.5 px-5 py-2.5 rounded-xl sm:rounded-2xl text-white text-xs sm:text-sm font-semibold shadow-xl border backdrop-blur-xs select-none animate-toast-bounce cursor-pointer {{ $bgColor }}"
        style="z-index: 9999;"
        @click="show = false"
        role="alert"
    >
        <span class="font-bold text-sm shrink-0">{{ $icon }}</span>
        <span class="leading-snug">{{ $message }}</span>
        <button
            type="button"
            @click.stop="show = false"
            class="ml-2 text-white/70 hover:text-white font-bold text-base leading-none cursor-pointer p-0.5 transition"
            title="Cerrar notificación"
        >
            &times;
        </button>
    </div>
@endif
