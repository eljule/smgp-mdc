@if ($showResetPasswordModal)
    <div
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 animate-fadeIn"
        wire:keydown.escape="closeResetPasswordModal"
    >
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-lg w-full p-6 shadow-2xl relative text-slate-800 dark:text-slate-100">
            <!-- Botón Cerrar -->
            <button
                type="button"
                wire:click="closeResetPasswordModal"
                class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                title="Cerrar modal"
            >
                ✕
            </button>

            <!-- Cabecera -->
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-11 h-11 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0">
                    🔑
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                        Resetear Contraseña de Usuario
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Asignación de clave provisional institucional
                    </p>
                </div>
            </div>

            <!-- Datos del Usuario Seleccionado -->
            <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 rounded-2xl mb-4 text-xs space-y-1">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400">Usuario de Ingreso:</span>
                    <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $resetUsername }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400">Servidor Municipal:</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $resetFullName }}</span>
                </div>
            </div>

            <!-- Advertencia / Regla Institucional -->
            <div class="p-3 bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-800 rounded-xl text-xs text-amber-900 dark:text-amber-200 mb-4 flex items-start space-x-2">
                <span class="text-base shrink-0">ℹ️</span>
                <p class="leading-relaxed">
                    Al confirmar, se establecerá esta <strong>clave provisional</strong>. La cuenta se <strong>desbloqueará automáticamente</strong> si estaba bloqueada, y el usuario estará <strong>obligado a cambiarla</strong> en su próximo inicio de sesión.
                </p>
            </div>

            @if ($resetPasswordError)
                <div class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-300 dark:border-rose-800 rounded-xl text-xs text-rose-800 dark:text-rose-200 mb-4 flex items-start space-x-2">
                    <span class="text-base shrink-0">❌</span>
                    <div class="flex-1 font-medium">{{ $resetPasswordError }}</div>
                </div>
            @endif

            <!-- Campo de Clave Provisional y Botón de Generar -->
            <div class="space-y-2 mb-6">
                <label for="reset-provisional-password" class="block font-bold text-xs text-slate-700 dark:text-slate-300">
                    Nueva Contraseña Provisional *
                </label>
                <div class="flex items-center space-x-2">
                    <input
                        type="text"
                        id="reset-provisional-password"
                        wire:model="resetNewPassword"
                        placeholder="Mínimo 6 caracteres"
                        class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none"
                    />
                    <button
                        type="button"
                        wire:click="generateRandomPassword"
                        class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold border border-slate-300 dark:border-slate-700 transition cursor-pointer flex items-center space-x-1"
                        title="Generar contraseña aleatoria segura"
                    >
                        <span>🎲</span>
                        <span class="hidden sm:inline">Aleatoria</span>
                    </button>
                </div>
                <p class="text-[11px] text-slate-400">
                    Proporcione esta clave al usuario por un canal seguro (personal o llamada oficial).
                </p>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-2.5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <button
                    type="button"
                    wire:click="closeResetPasswordModal"
                    class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold text-xs cursor-pointer transition"
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    wire:click="executeResetPassword"
                    class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-md transition cursor-pointer flex items-center space-x-1.5"
                >
                    <span>🔑</span>
                    <span>Confirmar y Resetear Clave</span>
                </button>
            </div>
        </div>
    </div>
@endif
