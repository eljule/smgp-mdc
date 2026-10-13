<div class="space-y-6">
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                <a href="{{ route('subsystems.itam') }}" class="hover:underline">ITAM</a>
                <span>•</span>
                <span>Licenciamiento</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2 mt-1">
                <span>💿</span>
                <span>Licencias de Software Institucional</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Catálogo de sistemas operativos, suites ofimáticas y software especializado instalado en los equipos de la MDC.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Software / Versión</th>
                        <th class="py-3.5 px-4">Desarrollador</th>
                        <th class="py-3.5 px-4">Tipo de Licencia</th>
                        <th class="py-3.5 px-4 text-center">Licencias Totales</th>
                        <th class="py-3.5 px-4 text-center">Instalaciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse ($softwareList as $sw)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $sw->name }}</p>
                                <p class="text-xs text-slate-500">{{ $sw->version ?: 'Última versión' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-800 dark:text-slate-200">{{ $sw->developer ?: 'Comercial' }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $sw->license_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $sw->total_licenses }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $sw->assets_count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">No hay licencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
