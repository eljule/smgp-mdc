<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    private const INITIAL_PERMISSIONS = [
        // central_dashboard
        [
            'code' => 'admin.users.view',
            'name' => 'Consultar Usuarios del Sistema',
            'description' => 'Visualizar el listado y detalle de cuentas de usuario activas en el SIGM.',
            'subsystem_code' => 'central_dashboard',
            'category' => 'USUARIOS',
        ],
        [
            'code' => 'admin.users.manage',
            'name' => 'Crear y Gestionar Usuarios',
            'description' => 'Crear cuentas, modificar contraseñas y desactivar accesos.',
            'subsystem_code' => 'central_dashboard',
            'category' => 'USUARIOS',
        ],
        [
            'code' => 'admin.roles.manage',
            'name' => 'Gestionar Roles y Asignación de Permisos',
            'description' => 'Crear roles institucionales y asignar permisos granulares por subsistema.',
            'subsystem_code' => 'central_dashboard',
            'category' => 'ROLES_Y_PERMISOS',
        ],
        [
            'code' => 'admin.people.view',
            'name' => 'Consultar Padrón de Personas',
            'description' => 'Ver datos de identificación de personal municipal y ciudadanos administrados.',
            'subsystem_code' => 'central_dashboard',
            'category' => 'PERSONAS',
        ],
        [
            'code' => 'admin.people.manage',
            'name' => 'Registrar y Actualizar Personas',
            'description' => 'Alta y edición de fichas de servidores públicos y administrados con trámite.',
            'subsystem_code' => 'central_dashboard',
            'category' => 'PERSONAS',
        ],
        [
            'code' => 'admin.tables.maintain',
            'name' => 'Mantenimiento de Tablas Maestras',
            'description' => 'Configuración de catálogo de oficinas, modalidades laborales y subsistemas.',
            'subsystem_code' => 'central_dashboard',
            'category' => 'CONFIGURACION',
        ],

        // transport_licenses
        [
            'code' => 'transport.licenses.view',
            'name' => 'Consultar Licencias de Conducir',
            'description' => 'Búsqueda de expedientes y estados de licencias clase B (vehículos menores).',
            'subsystem_code' => 'transport_licenses',
            'category' => 'LICENCIAS',
        ],
        [
            'code' => 'transport.licenses.issue',
            'name' => 'Emisión y Revalidación de Licencias',
            'description' => 'Aprobación de trámites, registro de pagos y emisión física/digital de licencias.',
            'subsystem_code' => 'transport_licenses',
            'category' => 'LICENCIAS',
        ],
        [
            'code' => 'transport.vehicles.register',
            'name' => 'Empadronamiento de Vehículos Menores',
            'description' => 'Registro de unidades de mototaxis, empresas de transporte y habilitaciones.',
            'subsystem_code' => 'transport_licenses',
            'category' => 'VEHICULOS',
        ],
        [
            'code' => 'transport.infractions.manage',
            'name' => 'Gestión de Infracciones y Sanciones',
            'description' => 'Registro de actas de control levantadas por inspectores de tránsito.',
            'subsystem_code' => 'transport_licenses',
            'category' => 'FISCALIZACION',
        ],

        // it_inventory (ITAM)
        [
            'code' => 'itam.assets.view',
            'name' => 'Consultar Inventario de Activos TI',
            'description' => 'Visualizar equipos de cómputo, laptops, servidores, impresoras y software.',
            'subsystem_code' => 'it_inventory',
            'category' => 'ACTIVOS',
        ],
        [
            'code' => 'itam.assets.manage',
            'name' => 'Alta y Edición de Equipos Informáticos',
            'description' => 'Registrar nuevos activos tecnológicos, seriales, características y garantías.',
            'subsystem_code' => 'it_inventory',
            'category' => 'ACTIVOS',
        ],
        [
            'code' => 'itam.assets.assign',
            'name' => 'Asignación de Activos a Servidores',
            'description' => 'Vincular equipos a personal y generar actas digitales de asignación.',
            'subsystem_code' => 'it_inventory',
            'category' => 'ASIGNACIONES',
        ],
        [
            'code' => 'itam.catalogs.manage',
            'name' => 'Catálogos de Marcas y Modelos',
            'description' => 'Mantenimiento de marcas, modelos y categorías de hardware municipal.',
            'subsystem_code' => 'it_inventory',
            'category' => 'CATALOGOS',
        ],

        // helpdesk_support
        [
            'code' => 'helpdesk.tickets.view',
            'name' => 'Consultar Tickets de Incidencias',
            'description' => 'Ver bandeja de solicitudes de soporte técnico registradas por dependencias.',
            'subsystem_code' => 'helpdesk_support',
            'category' => 'TICKETS',
        ],
        [
            'code' => 'helpdesk.tickets.create',
            'name' => 'Generar Ticket de Soporte Técnico',
            'description' => 'Permite a cualquier trabajador reportar fallas de hardware, software o red.',
            'subsystem_code' => 'helpdesk_support',
            'category' => 'TICKETS',
        ],
        [
            'code' => 'helpdesk.tickets.assign',
            'name' => 'Asignación de Tickets a Técnicos',
            'description' => 'Coordinación y delegación de incidencias al personal de soporte TI.',
            'subsystem_code' => 'helpdesk_support',
            'category' => 'TICKETS',
        ],
        [
            'code' => 'helpdesk.tickets.resolve',
            'name' => 'Diagnóstico y Cierre de Tickets (SLA)',
            'description' => 'Registrar solución técnica aplicada, tiempo de atención y conformidad.',
            'subsystem_code' => 'helpdesk_support',
            'category' => 'TICKETS',
        ],
        [
            'code' => 'helpdesk.kb.manage',
            'name' => 'Base de Conocimiento y Guías',
            'description' => 'Creación de manuales y soluciones documentadas a problemas frecuentes.',
            'subsystem_code' => 'helpdesk_support',
            'category' => 'BASE_CONOCIMIENTO',
        ],
    ];

    public function run(): void
    {
        $allCodes = [];
        foreach (self::INITIAL_PERMISSIONS as $p) {
            $allCodes[] = $p['code'];
            Permission::updateOrCreate(
                ['code' => $p['code']],
                array_merge($p, ['is_active' => true])
            );
        }

        $roles = [
            [
                'name' => 'Administrador Central',
                'code' => 'admin_central',
                'description' => 'Control absoluto del SIGM: configuración global, personas, usuarios y mantenimiento.',
                'subsystem_code' => 'GLOBAL',
                'is_system' => true,
                'permission_codes' => $allCodes,
                'is_active' => true,
            ],
            [
                'name' => 'Técnico de Soporte e ITAM',
                'code' => 'itam_tech',
                'description' => 'Gestión operativa de activos informáticos, marcas, modelos y atención de tickets de soporte.',
                'subsystem_code' => 'it_inventory',
                'is_system' => false,
                'permission_codes' => [
                    'admin.users.view',
                    'itam.assets.view',
                    'itam.assets.manage',
                    'itam.assets.assign',
                    'itam.catalogs.manage',
                    'helpdesk.tickets.view',
                    'helpdesk.tickets.create',
                    'helpdesk.tickets.assign',
                    'helpdesk.tickets.resolve',
                    'helpdesk.kb.manage',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Operador de Transportes',
                'code' => 'transport_operator',
                'description' => 'Atención ciudadana para licencias de conducir clase B y empadronamiento de mototaxis.',
                'subsystem_code' => 'transport_licenses',
                'is_system' => false,
                'permission_codes' => [
                    'transport.licenses.view',
                    'transport.licenses.issue',
                    'transport.vehicles.register',
                    'transport.infractions.manage',
                    'helpdesk.tickets.create',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Personal General / Reportante',
                'code' => 'municipal_staff',
                'description' => 'Colaborador municipal con facultades para registrar solicitudes de soporte técnico.',
                'subsystem_code' => 'helpdesk_support',
                'is_system' => false,
                'permission_codes' => ['helpdesk.tickets.create', 'helpdesk.tickets.view'],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(
                ['code' => $r['code']],
                $r
            );
        }
    }
}
