<div class="space-y-6 animate-fadeIn">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                        <span>Catálogo de Software Institucional & Licencias</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300">
                            {{ $softwareList->count() }} Títulos
                        </span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Gestión de sistemas operativos, suites ofimáticas, antivirus y herramientas especializadas municipales (RF-02).
                    </p>
                </div>
                <button type="button"
                        wire:click="openSoftwareModal"
                        class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md transition-all flex items-center space-x-1.5 cursor-pointer">
                    <span>+</span>
                    <span>Registrar Software</span>
                </button>
            </div>

            <!-- Cuadrícula de Software -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse ($softwareList as $sw)
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-cyan-50 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-300 border border-cyan-200">
                                    {{ $sw->license_type ?: 'LICENCIA' }}
                                </span>
                                <span class="text-xs font-mono font-bold text-slate-500">
                                    v{{ $sw->version ?: '1.0' }}
                                </span>
                            </div>
                            <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">
                                {{ $sw->name }}
                            </h4>
                            <p class="text-[11px] text-slate-400">
                                Fabricante: {{ $sw->developer ?? ($sw->publisher ?? 'Institucional') }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase block font-semibold">Licencias Asignadas</span>
                                <span class="text-base font-black text-slate-900 dark:text-white">
                                    {{ $sw->assets_count ?? 0 }} / {{ $sw->total_licenses ?: 'ILIMITADO' }}
                                </span>
                            </div>
                            <button type="button"
                                    wire:click="openSoftwareModal({{ $sw->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                                ✏️ Editar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">💿</span>
                        No hay títulos de software registrados.
                    </div>
                @endforelse
            </div>
        </div>
