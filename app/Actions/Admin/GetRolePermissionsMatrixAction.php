<?php

declare(strict_types=1);

namespace App\Actions\Admin;

final class GetRolePermissionsMatrixAction
{
    /**
     * Institutional roles definition.
     *
     * @var array<string, array<string, mixed>>
     */
    public const ROLES = [
        'admin_central' => [
            'code' => 'admin_central',
            'name' => 'Administrador Central',
            'isSystem' => true,
            'type' => 'Rol del Sistema',
            'icon' => '🛡️',
            'description' => 'Control absoluto del SIGM: configuración global, personas, usuarios y mantenimiento.',
        ],
        'transport_operator' => [
            'code' => 'transport_operator',
            'name' => 'Operador de Transportes',
            'isSystem' => false,
            'type' => 'Personalizado',
            'icon' => '🚗',
            'description' => 'Atención ciudadana para licencias de conducir clase B y empadronamiento de mototaxis.',
        ],
        'municipal_staff' => [
            'code' => 'municipal_staff',
            'name' => 'Personal General / Reportante',
            'isSystem' => false,
            'type' => 'Personalizado',
            'icon' => '🎧',
            'description' => 'Colaborador municipal con facultades para registrar solicitudes de soporte técnico.',
        ],
        'itam_tech' => [
            'code' => 'itam_tech',
            'name' => 'Técnico de Soporte e ITAM',
            'isSystem' => false,
            'type' => 'Personalizado',
            'icon' => '💻',
            'description' => 'Gestión operativa de activos informáticos, marcas, modelos y atención de tickets de soporte.',
        ],
    ];

    /**
     * Default permission assignments per role.
     *
     * @var array<string, array<int, string>>
     */
    public const DEFAULT_ASSIGNMENTS = [
        'admin_central' => [
            'admin.people.manage',
            'admin.people.view',
            'admin.roles.manage',
            'admin.tables.maintain',
            'admin.users.manage',
            'admin.users.view',
            'helpdesk.kb.manage',
            'helpdesk.tickets.assign',
            'helpdesk.tickets.create',
            'helpdesk.tickets.resolve',
            'helpdesk.tickets.view',
            'itam.assets.assign',
            'itam.assets.manage',
            'itam.assets.view',
            'itam.catalogs.manage',
            'transport.licenses.issue',
            'transport.licenses.view',
            'transport.vehicles.manage',
            'transport.sanctions.manage',
        ],
        'transport_operator' => [
            'transport.licenses.issue',
            'transport.licenses.view',
            'transport.vehicles.manage',
            'transport.sanctions.manage',
            'helpdesk.tickets.create',
        ],
        'municipal_staff' => [
            'helpdesk.tickets.create',
            'helpdesk.tickets.view',
        ],
        'itam_tech' => [
            'itam.assets.assign',
            'itam.assets.manage',
            'itam.assets.view',
            'itam.catalogs.manage',
            'helpdesk.kb.manage',
            'helpdesk.tickets.assign',
            'helpdesk.tickets.create',
            'helpdesk.tickets.resolve',
            'helpdesk.tickets.view',
            'admin.tables.maintain',
        ],
    ];

    /**
     * Retrieve all role assignments and catalog definition.
     *
     * @return array<string, mixed>
     */
    public function handle(): array
    {
        return [
            'roles' => self::ROLES,
            'assignments' => self::DEFAULT_ASSIGNMENTS,
            'totalPermissions' => 19,
        ];
    }
}
