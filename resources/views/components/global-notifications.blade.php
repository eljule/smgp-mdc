<!-- Contenedor Global de Notificaciones Flotantes con Rebote Institucional -->
@if (session('success'))
    <x-floating-toast :message="session('success')" type="success" />
@elseif (session('status'))
    <x-floating-toast :message="session('status')" type="success" />
@elseif (session('error'))
    <x-floating-toast :message="session('error')" type="error" />
@elseif (session('warning'))
    <x-floating-toast :message="session('warning')" type="warning" />
@elseif (session('info'))
    <x-floating-toast :message="session('info')" type="info" />
@endif

<div
    x-data="{
        toasts: [],
        add(message, type = 'success', duration = 6000) {
            if (!message) return;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type, show: true });
            setTimeout(() => this.remove(id), duration);
        },
        remove(id) {
            const idx = this.toasts.findIndex(t => t.id === id);
            if (idx !== -1) {
                this.toasts[idx].show = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 350);
            }
        }
    }"
    @notify.window="add($event.detail?.message || $event.detail, $event.detail?.type || 'success', $event.detail?.duration || 6000)"
    @toast.window="add($event.detail?.message || $event.detail, $event.detail?.type || 'success', $event.detail?.duration || 6000)"
    @alert.window="add($event.detail?.message || $event.detail, $event.detail?.type || 'success', $event.detail?.duration || 6000)"
    class="fixed top-20 right-6 sm:right-8 z-50 flex flex-col space-y-2 pointer-events-none"
    style="z-index: 9999;"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            x-show="t.show"
            x-transition:leave="transition ease-in duration-300 transform translate-x-12 opacity-0 scale-95"
            class="pointer-events-auto flex items-center space-x-2.5 px-5 py-2.5 rounded-xl sm:rounded-2xl text-white text-xs sm:text-sm font-semibold shadow-xl border backdrop-blur-xs select-none animate-toast-bounce cursor-pointer"
            :class="{
                'bg-[#046A38] border-emerald-400/40 shadow-emerald-950/25': t.type === 'success' || !t.type,
                'bg-[#be123c] border-rose-400/40 shadow-rose-950/25': t.type === 'error' || t.type === 'danger',
                'bg-[#b45309] border-amber-400/40 shadow-amber-950/25': t.type === 'warning',
                'bg-[#0369a1] border-sky-400/40 shadow-sky-950/25': t.type === 'info',
            }"
            @click="remove(t.id)"
            role="alert"
        >
            <span class="font-bold text-sm shrink-0" x-text="t.type === 'error' || t.type === 'danger' ? '✕' : (t.type === 'warning' ? '⚠️' : (t.type === 'info' ? 'ℹ️' : '✓'))"></span>
            <span class="leading-snug" x-text="t.message"></span>
            <button
                type="button"
                @click.stop="remove(t.id)"
                class="ml-2 text-white/70 hover:text-white font-bold text-base leading-none cursor-pointer p-0.5"
            >&times;</button>
        </div>
    </template>
</div>
