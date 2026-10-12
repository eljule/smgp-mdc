<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Sede extends Model
{
    use HasFactory;

    protected $table = 'core_sedes';

    protected $fillable = [
        'name',
        'code',
        'address',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @var array<string, string>|null
     */
    private static ?array $cachedColors = null;

    public const COLOR_PRESETS = [
        'emerald' => [
            'label' => 'Esmeralda (Institucional)',
            'hex' => '#10b981',
            'classes' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
        ],
        'blue' => [
            'label' => 'Azul (Seguridad y Central)',
            'hex' => '#3b82f6',
            'classes' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
        ],
        'indigo' => [
            'label' => 'Índigo (Rentas y Finanzas)',
            'hex' => '#6366f1',
            'classes' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
        ],
        'purple' => [
            'label' => 'Púrpura (Deporte y Recreación)',
            'hex' => '#a855f7',
            'classes' => 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800',
        ],
        'amber' => [
            'label' => 'Ámbar (Educación y Cultura)',
            'hex' => '#f59e0b',
            'classes' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
        ],
        'orange' => [
            'label' => 'Naranja (Maestranza y Servicios)',
            'hex' => '#f97316',
            'classes' => 'bg-orange-50 dark:bg-orange-950/40 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800',
        ],
        'rose' => [
            'label' => 'Rosa (Salud y Social / DEMUNA)',
            'hex' => '#f43f5e',
            'classes' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
        ],
        'teal' => [
            'label' => 'Turquesa (Depósitos y Almacenes)',
            'hex' => '#14b8a6',
            'classes' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-800',
        ],
        'cyan' => [
            'label' => 'Cian (Agencias y Desconcentradas)',
            'hex' => '#06b6d4',
            'classes' => 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 border-cyan-200 dark:border-cyan-800',
        ],
        'slate' => [
            'label' => 'Gris (Neutro / Operativo)',
            'hex' => '#64748b',
            'classes' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
        ],
    ];

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class, 'sede', 'name');
    }

    public static function availableColors(): array
    {
        return self::COLOR_PRESETS;
    }

    public static function clearColorCache(): void
    {
        self::$cachedColors = null;
    }

    public function getBadgeClassAttribute(): string
    {
        return self::COLOR_PRESETS[$this->color]['classes'] ?? self::COLOR_PRESETS['slate']['classes'];
    }

    public static function getBadgeClasses(?string $sedeName): string
    {
        if ($sedeName === null || trim($sedeName) === '') {
            return self::COLOR_PRESETS['slate']['classes'];
        }

        $clean = trim($sedeName);

        // Load colors map from DB if table exists
        if (self::$cachedColors === null) {
            self::$cachedColors = [];
            try {
                if (Schema::hasTable('core_sedes')) {
                    $records = self::pluck('color', 'name')->all();
                    foreach ($records as $name => $color) {
                        self::$cachedColors[$name] = self::COLOR_PRESETS[$color]['classes'] ?? self::COLOR_PRESETS['slate']['classes'];
                    }
                }
            } catch (\Throwable) {
                // Ignore DB error during initial boots
            }
        }

        if (isset(self::$cachedColors[$clean])) {
            return self::$cachedColors[$clean];
        }

        // Keyword-based fallback for new/unknown strings
        $upper = mb_strtoupper($clean);
        if (str_contains($upper, 'PRINCIPAL')) {
            return self::COLOR_PRESETS['emerald']['classes'];
        }
        if (str_contains($upper, 'BIBLIOTECA')) {
            return self::COLOR_PRESETS['amber']['classes'];
        }
        if (str_contains($upper, 'RENTAS') || str_contains($upper, 'TRIBUTARIA')) {
            return self::COLOR_PRESETS['indigo']['classes'];
        }
        if (str_contains($upper, 'MAESTRANZA') || str_contains($upper, 'SERVICIOS PÚBLICOS') || str_contains($upper, 'SERVICIOS')) {
            return self::COLOR_PRESETS['orange']['classes'];
        }
        if (str_contains($upper, 'COMPLEJO') || str_contains($upper, 'DEPORTIVO') || str_contains($upper, 'MINILICHI')) {
            return self::COLOR_PRESETS['purple']['classes'];
        }
        if (str_contains($upper, 'SOCIAL') || str_contains($upper, 'DEMUNA') || str_contains($upper, 'SALUD') || str_contains($upper, 'MATERNO')) {
            return self::COLOR_PRESETS['rose']['classes'];
        }
        if (str_contains($upper, 'DEPÓSITO') || str_contains($upper, 'DEPOSITO') || str_contains($upper, 'VEHÍCULOS') || str_contains($upper, 'ALMACEN')) {
            return self::COLOR_PRESETS['teal']['classes'];
        }
        if (str_contains($upper, 'SERENAZGO') || str_contains($upper, 'COSC') || str_contains($upper, 'SEGURIDAD') || str_contains($upper, 'TACALÁ')) {
            return self::COLOR_PRESETS['blue']['classes'];
        }

        return self::COLOR_PRESETS['slate']['classes'];
    }
}
