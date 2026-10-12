<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\CoreModule;
use Illuminate\Database\Eloquent\Collection;

final class GetSubsystemsCatalogAction
{
    /**
     * Display code aliases for technical scopes.
     *
     * @var array<string, string>
     */
    private const CODE_ALIASES = [
        'central' => 'CENTRAL_DASHBOARD',
        'transportes' => 'TRANSPORT_LICENSES',
        'itam' => 'IT_INVENTORY',
        'helpdesk' => 'HELPDESK_SUPPORT',
    ];

    /**
     * Emoji icon mapping for subsystem cards.
     *
     * @var array<string, string>
     */
    private const ICON_EMOJIS = [
        'central' => '🏢',
        'transportes' => '🚗',
        'itam' => '💻',
        'helpdesk' => '🎧',
    ];

    /**
     * Retrieve filtered list of subsystems.
     *
     * @return Collection<int, CoreModule>
     */
    public function execute(string $search = '', string $filter = 'todos'): Collection
    {
        $query = CoreModule::query()->orderBy('order', 'asc');

        if ($search !== '') {
            $term = mb_strtolower(trim($search));
            $query->where(function ($q) use ($term): void {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(code) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(route) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$term}%"]);
            });
        }

        if ($filter === 'operativos') {
            $query->where('status', 'active')->where('is_active', true);
        } elseif ($filter === 'mantenimiento') {
            $query->where('status', 'maintenance');
        } elseif ($filter === 'inactivos') {
            $query->where('is_active', false);
        }

        $modules = $query->get();

        // Enrich with view presentation properties
        $modules->transform(function (CoreModule $module): CoreModule {
            $module->setAttribute('display_code', self::CODE_ALIASES[$module->code] ?? strtoupper($module->code));
            $module->setAttribute('icon_emoji', self::ICON_EMOJIS[$module->code] ?? '⚙️');

            if ($module->code === 'transportes') {
                $module->description = 'Empadronamiento de vehículos menores, licencias de conducir y registro de mototaxis del distrito.';
                $module->status_message = 'Este subsistema se encuentra temporalmente suspendido por trabajos de mantenimiento y optimización.';
                $module->wait_time = 'Hasta nuevo aviso';
            } elseif ($module->code === 'helpdesk') {
                $module->route = '/soporte';
            }

            return $module;
        });

        return $modules;
    }

    /**
     * Retrieve summary counts for top banner.
     *
     * @return array<string, int>
     */
    public function getMetrics(): array
    {
        $all = CoreModule::query()->get();

        return [
            'total' => $all->count(),
            'activos' => $all->where('status.value', 'active')->where('is_active', true)->count(),
            'mantenimiento' => $all->where('status.value', 'maintenance')->count(),
            'inactivos' => $all->where('is_active', false)->count(),
        ];
    }
}
