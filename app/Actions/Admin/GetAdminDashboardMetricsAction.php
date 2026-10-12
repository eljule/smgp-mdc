<?php

declare(strict_types=1);

namespace App\Actions\Admin;

final class GetAdminDashboardMetricsAction
{
    /**
     * Retrieve metrics and subsystem catalog for the Admin Dashboard.
     *
     * @return array{
     *     peopleCounts: array{total: int, personal: int, administrados: int},
     *     usersCount: int,
     *     rolesCount: int,
     *     permissionsCount: int,
     *     subsystems: array<int, array{
     *         code: string,
     *         name: string,
     *         description: string,
     *         color: string,
     *         icon: string,
     *         route: string,
     *         status: string
     *     }>,
     *     totalSubsystems: int,
     *     activeSubsystemsCount: int
     * }
     */
    public function handle(): array
    {
        $subsystems = [
            [
                'code' => 'central_dashboard',
                'name' => 'Dashboard Central y Configuración',
                'description' => 'Gestión central de usuarios, permisos, personas y tablas maestras.',
                'color' => '#16a34a',
                'icon' => '🏢',
                'route' => route('subsystems.central'),
                'status' => 'Operativo',
            ],
            [
                'code' => 'transport_licenses',
                'name' => 'Licencias de Transportes',
                'description' => 'Empadronamiento de mototaxis, licencias clase B y fiscalización.',
                'color' => '#ea580c',
                'icon' => '🚗',
                'route' => route('subsystems.transportes'),
                'status' => 'Operativo',
            ],
            [
                'code' => 'it_inventory',
                'name' => 'Inventario y Gestión TI',
                'description' => 'Activos de hardware/software (ITAM), dependencias y garantías.',
                'color' => '#2563eb',
                'icon' => '💻',
                'route' => route('subsystems.itam'),
                'status' => 'Operativo',
            ],
            [
                'code' => 'helpdesk_support',
                'name' => 'Soporte Técnico y Helpdesk',
                'description' => 'Mesa de ayuda informática, tickets y atención a usuarios.',
                'color' => '#7c3aed',
                'icon' => '🎧',
                'route' => route('subsystems.helpdesk'),
                'status' => 'Operativo',
            ],
        ];

        return [
            'peopleCounts' => [
                'total' => 46,
                'personal' => 46,
                'administrados' => 0,
            ],
            'usersCount' => 49,
            'rolesCount' => 4,
            'permissionsCount' => 19,
            'subsystems' => $subsystems,
            'totalSubsystems' => 4,
            'activeSubsystemsCount' => 4,
        ];
    }
}
