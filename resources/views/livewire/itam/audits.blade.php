<div class="space-y-6">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.itam') }}" class="hover:underline">ITAM</a>
                <span>•</span>
                <span>Auditoría</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>📊</span>
                <span>Auditorías de Inventario Físico</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Conciliación y barrido físico de activos tecnológicos por sedes para la memoria anual institucional.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Año / Título</th>
                        <th class="py-3.5 px-4">Período</th>
                        <th class="py-3.5 px-4 text-center">Verificaciones</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($audits as $audit)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $audit->year }}</span>
                                <p class="font-bold text-slate-900 dark:text-white">{{ $audit->title }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $audit->start_date }} → {{ $audit->close_date ?: 'En curso' }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $audit->verifications->count() }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                                    {{ $audit->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">No hay procesos de auditoría anual registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
