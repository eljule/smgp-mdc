<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        DB::table('core_permissions')->delete();
        DB::table('core_roles')->delete();

        $permissions = [
            [
                'id' => 1,
                'code' => 'admin.users.view',
                'name' => 'Consultar Usuarios del Sistema',
                'description' => 'Visualizar el listado y detalle de cuentas de usuario activas en el SIGM.',
                'subsystem_code' => 'central_dashboard',
                'category' => 'USUARIOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'code' => 'admin.users.manage',
                'name' => 'Crear y Gestionar Usuarios',
                'description' => 'Crear cuentas, modificar contraseñas y desactivar accesos.',
                'subsystem_code' => 'central_dashboard',
                'category' => 'USUARIOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'code' => 'admin.roles.manage',
                'name' => 'Gestionar Roles y Asignación de Permisos',
                'description' => 'Crear roles institucionales y asignar permisos granulares por subsistema.',
                'subsystem_code' => 'central_dashboard',
                'category' => 'ROLES_Y_PERMISOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'code' => 'admin.people.view',
                'name' => 'Consultar Padrón de Personas',
                'description' => 'Ver datos de identificación de personal municipal y ciudadanos administrados.',
                'subsystem_code' => 'central_dashboard',
                'category' => 'PERSONAS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 5,
                'code' => 'admin.people.manage',
                'name' => 'Registrar y Actualizar Personas',
                'description' => 'Alta y edición de fichas de servidores públicos y administrados con trámite.',
                'subsystem_code' => 'central_dashboard',
                'category' => 'PERSONAS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 6,
                'code' => 'admin.tables.maintain',
                'name' => 'Mantenimiento de Tablas Maestras',
                'description' => 'Configuración de catálogo de oficinas, modalidades laborales y subsistemas.',
                'subsystem_code' => 'central_dashboard',
                'category' => 'CONFIGURACION',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 7,
                'code' => 'transport.licenses.view',
                'name' => 'Consultar Licencias de Conducir',
                'description' => 'Búsqueda de expedientes y estados de licencias clase B (vehículos menores).',
                'subsystem_code' => 'transport_licenses',
                'category' => 'LICENCIAS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 8,
                'code' => 'transport.licenses.issue',
                'name' => 'Emisión y Revalidación de Licencias',
                'description' => 'Aprobación de trámites, registro de pagos y emisión física/digital de licencias.',
                'subsystem_code' => 'transport_licenses',
                'category' => 'LICENCIAS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 9,
                'code' => 'transport.vehicles.register',
                'name' => 'Empadronamiento de Vehículos Menores',
                'description' => 'Registro de unidades de mototaxis, empresas de transporte y habilitaciones.',
                'subsystem_code' => 'transport_licenses',
                'category' => 'VEHICULOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 10,
                'code' => 'transport.infractions.manage',
                'name' => 'Gestión de Infracciones y Sanciones',
                'description' => 'Registro de actas de control levantadas por inspectores de tránsito.',
                'subsystem_code' => 'transport_licenses',
                'category' => 'FISCALIZACION',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 11,
                'code' => 'itam.assets.view',
                'name' => 'Consultar Inventario de Activos TI',
                'description' => 'Visualizar equipos de cómputo, laptops, servidores, impresoras y software.',
                'subsystem_code' => 'it_inventory',
                'category' => 'ACTIVOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 12,
                'code' => 'itam.assets.manage',
                'name' => 'Alta y Edición de Equipos Informáticos',
                'description' => 'Registrar nuevos activos tecnológicos, seriales, características y garantías.',
                'subsystem_code' => 'it_inventory',
                'category' => 'ACTIVOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 13,
                'code' => 'itam.assets.assign',
                'name' => 'Asignación de Activos a Servidores',
                'description' => 'Vincular equipos a personal y generar actas digitales de asignación.',
                'subsystem_code' => 'it_inventory',
                'category' => 'ASIGNACIONES',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 14,
                'code' => 'itam.catalogs.manage',
                'name' => 'Catálogos de Marcas y Modelos',
                'description' => 'Mantenimiento de marcas, modelos y categorías de hardware municipal.',
                'subsystem_code' => 'it_inventory',
                'category' => 'CATALOGOS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 15,
                'code' => 'helpdesk.tickets.view',
                'name' => 'Consultar Tickets de Incidencias',
                'description' => 'Ver bandeja de solicitudes de soporte técnico registradas por dependencias.',
                'subsystem_code' => 'helpdesk_support',
                'category' => 'TICKETS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 16,
                'code' => 'helpdesk.tickets.create',
                'name' => 'Generar Ticket de Soporte Técnico',
                'description' => 'Permite a cualquier trabajador reportar fallas de hardware, software o red.',
                'subsystem_code' => 'helpdesk_support',
                'category' => 'TICKETS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 17,
                'code' => 'helpdesk.tickets.assign',
                'name' => 'Asignación de Tickets a Técnicos',
                'description' => 'Coordinación y delegación de incidencias al personal de soporte TI.',
                'subsystem_code' => 'helpdesk_support',
                'category' => 'TICKETS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 18,
                'code' => 'helpdesk.tickets.resolve',
                'name' => 'Diagnóstico y Cierre de Tickets (SLA)',
                'description' => 'Registrar solución técnica aplicada, tiempo de atención y conformidad.',
                'subsystem_code' => 'helpdesk_support',
                'category' => 'TICKETS',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 19,
                'code' => 'helpdesk.kb.manage',
                'name' => 'Base de Conocimiento y Guías',
                'description' => 'Creación de manuales y soluciones documentadas a problemas frecuentes.',
                'subsystem_code' => 'helpdesk_support',
                'category' => 'BASE_CONOCIMIENTO',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        foreach (array_chunk($permissions, 50) as $chunk) {
            DB::table('core_permissions')->insert($chunk);
        }

        $roles = [
            [
                'id' => 1,
                'code' => 'admin_central',
                'name' => 'Administrador Central',
                'description' => 'Control absoluto del SIGM: configuración global, personas, usuarios y mantenimiento.',
                'subsystem_code' => 'GLOBAL',
                'is_system' => true,
                'permission_codes' => '["admin.users.view", "admin.users.manage", "admin.roles.manage", "admin.people.view", "admin.people.manage", "admin.tables.maintain", "transport.licenses.view", "transport.licenses.issue", "transport.vehicles.register", "transport.infractions.manage", "itam.assets.view", "itam.assets.manage", "itam.assets.assign", "itam.catalogs.manage", "helpdesk.tickets.view", "helpdesk.tickets.create", "helpdesk.tickets.assign", "helpdesk.tickets.resolve", "helpdesk.kb.manage"]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 2,
                'code' => 'itam_tech',
                'name' => 'Técnico de Soporte e ITAM',
                'description' => 'Gestión operativa de activos informáticos, marcas, modelos y atención de tickets de soporte.',
                'subsystem_code' => 'it_inventory',
                'is_system' => false,
                'permission_codes' => '["admin.users.view", "itam.assets.view", "itam.assets.manage", "itam.assets.assign", "itam.catalogs.manage", "helpdesk.tickets.view", "helpdesk.tickets.create", "helpdesk.tickets.assign", "helpdesk.tickets.resolve", "helpdesk.kb.manage"]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'code' => 'transport_operator',
                'name' => 'Operador de Transportes',
                'description' => 'Atención ciudadana para licencias de conducir clase B y empadronamiento de mototaxis.',
                'subsystem_code' => 'transport_licenses',
                'is_system' => false,
                'permission_codes' => '["transport.licenses.view", "transport.licenses.issue", "transport.vehicles.register", "transport.infractions.manage", "helpdesk.tickets.create"]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 4,
                'code' => 'municipal_staff',
                'name' => 'Personal General / Reportante',
                'description' => 'Colaborador municipal con facultades para registrar solicitudes de soporte técnico.',
                'subsystem_code' => 'helpdesk_support',
                'is_system' => false,
                'permission_codes' => '["helpdesk.tickets.create", "helpdesk.tickets.view"]',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];
        foreach (array_chunk($roles, 50) as $chunk) {
            DB::table('core_roles')->insert($chunk);
        }

        if ($isPgsql) {
            DB::statement("SELECT setval(pg_get_serial_sequence('core_permissions', 'id'), coalesce(max(id), 0) + 1, false) FROM core_permissions;");
            DB::statement("SELECT setval(pg_get_serial_sequence('core_roles', 'id'), coalesce(max(id), 0) + 1, false) FROM core_roles;");
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }
}
