<div class="space-y-6 pb-24 animate-fadeIn">
    <!-- Notificación Flotante con Rebote -->
    <x-floating-toast :message="$flashMessage ?? $alertMessage ?? null" :type="$flashType ?? $alertType ?? 'success'" />

    <!-- 1. Header de la Matriz -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2.5">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Matriz de Asignación de Permisos por Rol
                </h2>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold border border-emerald-300/40">
                    Control Granular
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Seleccione el rol a configurar y active o desactive los permisos por módulo de manera interactiva.
            </p>
        </div>

        <button
            type="button"
            wire:click="saveMatrix"
            @disabled(! $hasChanges)
            class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all flex items-center space-x-2 {{ $hasChanges
                ? 'bg-emerald-600 hover:bg-emerald-700 text-white animate-pulse cursor-pointer'
                : 'bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed'
            }}"
        >
            <span>💾</span>
            <span>Guardar Permisos del Rol</span>
        </button>
    </div>

    <!-- 2. Selector Visual de Rol -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">
            SELECCIONE EL ROL INSTITUCIONAL A CONFIGURAR:
        </label>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
            @foreach ($roles as $code => $role)
                @php
                    $isSelected = $selectedRoleCode === $code;
                    $rolePermsCount = count($assignments[$code] ?? []);
                @endphp
                <button
                    key="{{ $code }}"
                    type="button"
                    wire:click="selectRole('{{ $code }}')"
                    class="p-3 rounded-xl border text-left transition-all cursor-pointer {{ $isSelected
                        ? 'border-emerald-500 bg-emerald-50/80 dark:bg-emerald-950/40 ring-2 ring-emerald-500/40 shadow-sm'
                        : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700'
                    }}"
                >
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-xs text-slate-900 dark:text-white truncate">
                            {{ $role['name'] }}
                        </span>
                        @if ($role['isSystem'])
                            <span class="text-[10px]" title="Rol del Sistema">
                                🔒
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-400 font-mono">
                        <span>{{ $role['code'] }}</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $isSelected ? $grantedCount : $rolePermsCount }} de {{ $totalPermissions }}
                        </span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- 3. Barra Informativa del Rol Activo -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-2xl font-bold shadow-inner shrink-0">
                🎭
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="text-base font-black text-slate-900 dark:text-white">
                        {{ $activeRole['name'] }}
                    </h3>
                    <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        {{ $activeRole['code'] }}
                    </span>
                    @if ($activeRole['isSystem'])
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300">
                            Rol del Sistema
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xl">
                    {{ $activeRole['description'] ?? 'Configuración de privilegios de acceso para este perfil.' }}
                </p>
            </div>
        </div>

        <!-- Métricas y Acciones Rápidas -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 shrink-0">
            <div class="text-right sm:pr-4 sm:border-r border-slate-200 dark:border-slate-800">
                <div class="text-sm font-black text-slate-900 dark:text-white">
                    {{ $grantedCount }} / {{ $totalPermissions }} permisos
                </div>
                <span class="text-[11px] text-slate-400 font-medium">
                    {{ $coveragePercentage }}% de cobertura de plataforma
                </span>
            </div>

            <div class="flex items-center space-x-2">
                <button
                    type="button"
                    wire:click="selectAll(@js($allCodes))"
                    class="px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 font-bold text-xs hover:bg-emerald-100 transition-colors cursor-pointer"
                    title="Marcar todos los permisos del sistema"
                >
                    ✓ Marcar Todos
                </button>
                <button
                    type="button"
                    wire:click="deselectAll"
                    class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold text-xs hover:bg-slate-200 transition-colors cursor-pointer"
                    title="Desmarcar todos los permisos"
                >
                    ✕ Desmarcar Todos
                </button>
            </div>
        </div>
    </div>

    <!-- 4. Módulos y Permisos Granulares -->
    <div class="space-y-6">
        @foreach ($subsystems as $subKey => $subsystem)
            @php
                $subPermCodes = array_column($subsystem['permissions'], 'code');
                $subGrantedCount = count(array_intersect($subPermCodes, $activePermissions));
                $isAllGroupSelected = $subGrantedCount === count($subsystem['permissions']);
                $colorHex = match ($subKey) {
                    'central_dashboard' => '#16a34a',
                    'transport_management' => '#ea580c',
                    'it_inventory' => '#2563eb',
                    'helpdesk_support' => '#7c3aed',
                    default => '#16a34a',
                };
                $subIcon = match ($subKey) {
                    'central_dashboard' => '🏢',
                    'transport_management' => '🚗',
                    'it_inventory' => '💻',
                    'helpdesk_support' => '🎧',
                    default => '⚙️',
                };
            @endphp
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <!-- Cabecera del Módulo -->
                <div
                    class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800"
                    style="border-left-color: {{ $colorHex }}; border-left-width: 6px;"
                >
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">{{ $subIcon }}</span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                {{ $subsystem['name'] }}
                            </h3>
                            <span class="text-[11px] font-medium text-slate-500">
                                {{ $subGrantedCount }} de {{ count($subsystem['permissions']) }} privilegios concedidos
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button
                            type="button"
                            wire:click="enableSubsystem(@js($subPermCodes))"
                            @disabled($isAllGroupSelected)
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 disabled:opacity-50 transition-colors cursor-pointer"
                        >
                            ✓ Todo el módulo
                        </button>
                        <button
                            type="button"
                            wire:click="disableSubsystem(@js($subPermCodes))"
                            @disabled($subGrantedCount === 0)
                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 disabled:opacity-50 transition-colors cursor-pointer"
                        >
                            ✕ Ninguno
                        </button>
                    </div>
                </div>

                <!-- Grid de Permisos con Switch Interactivo -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    @foreach ($subsystem['permissions'] as $perm)
                        @php
                            $isChecked = in_array($perm['code'], $activePermissions, true);
                        @endphp
                        <div
                            wire:click="togglePermission('{{ $perm['code'] }}')"
                            class="p-4 rounded-xl border cursor-pointer transition-all flex flex-col justify-between select-none {{ $isChecked
                                ? 'border-emerald-500 bg-emerald-50/70 dark:bg-emerald-950/30 ring-1 ring-emerald-500 shadow-xs'
                                : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-slate-300 dark:hover:border-slate-700 opacity-70 hover:opacity-100'
                            }}"
                        >
                            <div>
                                <!-- Cabecera con Switch -->
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="font-mono font-bold text-[11px] text-slate-800 dark:text-slate-200 px-2 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 truncate">
                                        {{ $perm['code'] }}
                                    </span>

                                    <!-- Toggle Switch Visual -->
                                    <div
                                        class="w-9 h-5 flex items-center rounded-full p-0.5 transition-colors duration-200 ease-in-out shrink-0 {{ $isChecked ? 'bg-emerald-600' : 'bg-slate-300 dark:bg-slate-600' }}"
                                    >
                                        <div
                                            class="bg-white w-4 h-4 rounded-full shadow-md transform transition-transform duration-200 ease-in-out {{ $isChecked ? 'translate-x-4' : 'translate-x-0' }}"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Nombre del Permiso -->
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white mb-1 leading-snug">
                                    {{ $perm['name'] ?? $perm['code'] }}
                                </h4>

                                <!-- Descripción -->
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed mb-3">
                                    {{ $perm['description'] ?? 'Sin descripción adicional' }}
                                </p>
                            </div>

                            <!-- Estado en el pie -->
                            <div class="pt-2 border-t border-slate-200/60 dark:border-slate-700/60 flex items-center justify-between text-[10px]">
                                <span
                                    class="font-bold uppercase tracking-wider {{ $isChecked ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400' }}"
                                >
                                    {{ $isChecked ? '✓ HABILITADO' : '○ DENEGADO' }}
                                </span>
                                <span class="font-mono text-slate-400 uppercase font-semibold">
                                    {{ $perm['scope'] ?? $perm['category'] ?? 'GENERAL' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- 5. Barra Flotante Inferior de Guardado (Sticky Action Bar) -->
    <div class="fixed bottom-4 left-4 right-4 sm:left-72 sm:right-8 z-40">
        <div class="bg-slate-900/95 dark:bg-slate-800/95 text-white backdrop-blur-md px-5 py-3.5 rounded-2xl shadow-2xl border border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3 text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <div>
                    <span class="font-bold text-slate-200">
                        Rol: {{ $activeRole['name'] }} &bull; 
                    </span>
                    <span class="text-emerald-400 font-bold">
                        {{ $grantedCount }} permisos seleccionados
                    </span>
                    @if ($hasChanges)
                        <span class="ml-2 text-amber-300 font-medium animate-pulse">
                            (¡Cambios sin guardar!)
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                @if ($hasChanges)
                    <button
                        type="button"
                        wire:click="resetMatrix"
                        class="px-3.5 py-1.5 rounded-xl border border-slate-600 hover:bg-slate-800 text-xs font-semibold text-slate-300 transition-colors cursor-pointer"
                    >
                        Restablecer
                    </button>
                @endif
                <button
                    type="button"
                    wire:click="saveMatrix"
                    @disabled(! $hasChanges)
                    class="px-5 py-2 rounded-xl text-xs font-bold transition-all shadow-md flex items-center space-x-2 cursor-pointer {{ $hasChanges
                        ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-900/50'
                        : 'bg-slate-800 text-slate-500 cursor-not-allowed border border-slate-700'
                    }}"
                >
                    <span>{{ $hasChanges ? '💾 Guardar Asignación' : '✓ Permisos al día' }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
