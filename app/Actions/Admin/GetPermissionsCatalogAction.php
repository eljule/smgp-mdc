<?php

declare(strict_types=1);

namespace App\Actions\Admin;

final class GetPermissionsCatalogAction
{
    /**
     * Complete repository of granular permissions organized by subsystem.
     *
     * @var array<string, array<string, mixed>>
     */
    private const SUBSYSTEMS_DATA = [
        'central_dashboard' => [
            'id' => 'central_dashboard',
            'name' => 'Dashboard Central y Configuración',
            'code' => 'central_dashboard',
            'color' => 'emerald',
            'colorHex' => '#16a34a',
            'borderClass' => 'border-l-emerald-600',
            'iconBg' => 'bg-emerald-600',
            'badgeBg' => 'bg-emerald-700',
            'dotClass' => 'text-emerald-500',
            'permissions' => [
                [
                    'code' => 'admin.people.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Registrar y Actualizar Personas',
                    'description' => 'Alta y edición de fichas de servidores públicos y administrados con trámite.',
                    'scope' => 'PERSONAS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'admin.people.view',
                    'tag' => 'Lectura / Consulta',
                    'tagColor' => 'blue',
                    'name' => 'Consultar Padrón de Personas',
                    'description' => 'Ver datos de identificación de personal municipal y ciudadanos administrados.',
                    'scope' => 'PERSONAS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'admin.roles.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Gestionar Roles y Asignación de Permisos',
                    'description' => 'Crear roles institucionales y asignar permisos granulares por subsistema.',
                    'scope' => 'ROLES_Y_PERMISOS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'admin.tables.maintain',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Mantenimiento de Tablas Maestras',
                    'description' => 'Configuración de catálogo de oficinas, modalidades laborales y subsistemas.',
                    'scope' => 'CONFIGURACION',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'admin.users.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Crear y Gestionar Usuarios',
                    'description' => 'Crear cuentas, modificar contraseñas y desactivar accesos.',
                    'scope' => 'USUARIOS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'admin.users.view',
                    'tag' => 'Lectura / Consulta',
                    'tagColor' => 'blue',
                    'name' => 'Consultar Usuarios del Sistema',
                    'description' => 'Visualizar el listado y detalle de cuentas de usuario activas en el SIGM.',
                    'scope' => 'USUARIOS',
                    'status' => 'Activo en SIGM',
                ],
            ],
        ],
        'helpdesk_support' => [
            'id' => 'helpdesk_support',
            'name' => 'Soporte Técnico y Helpdesk',
            'code' => 'helpdesk_support',
            'color' => 'purple',
            'colorHex' => '#7c3aed',
            'borderClass' => 'border-l-purple-600',
            'iconBg' => 'bg-purple-600',
            'badgeBg' => 'bg-purple-700',
            'dotClass' => 'text-purple-500',
            'permissions' => [
                [
                    'code' => 'helpdesk.kb.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Base de Conocimiento y Guías',
                    'description' => 'Creación de manuales y soluciones documentadas a problemas frecuentes.',
                    'scope' => 'BASE_CONOCIMIENTO',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'helpdesk.tickets.assign',
                    'tag' => 'Modificación / Flujo',
                    'tagColor' => 'amber',
                    'name' => 'Asignación de Tickets a Técnicos',
                    'description' => 'Coordinación y delegación de incidencias al personal de soporte TI.',
                    'scope' => 'TICKETS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'helpdesk.tickets.create',
                    'tag' => 'Creación / Registro',
                    'tagColor' => 'emerald',
                    'name' => 'Generar Ticket de Soporte Técnico',
                    'description' => 'Permite a cualquier trabajador reportar fallas de hardware, software o red.',
                    'scope' => 'TICKETS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'helpdesk.tickets.resolve',
                    'tag' => 'Modificación / Flujo',
                    'tagColor' => 'amber',
                    'name' => 'Diagnóstico y Cierre de Tickets (SLA)',
                    'description' => 'Registrar solución técnica aplicada, tiempo de atención y conformidad.',
                    'scope' => 'TICKETS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'helpdesk.tickets.view',
                    'tag' => 'Lectura / Consulta',
                    'tagColor' => 'blue',
                    'name' => 'Consultar Tickets de Incidencias',
                    'description' => 'Ver bandeja de solicitudes de soporte técnico registradas por dependencias.',
                    'scope' => 'TICKETS',
                    'status' => 'Activo en SIGM',
                ],
            ],
        ],
        'it_inventory' => [
            'id' => 'it_inventory',
            'name' => 'Inventario y Gestión TI (ITAM)',
            'code' => 'it_inventory',
            'color' => 'blue',
            'colorHex' => '#2563eb',
            'borderClass' => 'border-l-blue-600',
            'iconBg' => 'bg-blue-600',
            'badgeBg' => 'bg-blue-700',
            'dotClass' => 'text-blue-500',
            'permissions' => [
                [
                    'code' => 'itam.assets.assign',
                    'tag' => 'Modificación / Flujo',
                    'tagColor' => 'amber',
                    'name' => 'Asignación de Activos a Servidores',
                    'description' => 'Vincular equipos a personal y generar actas digitales de asignación.',
                    'scope' => 'ASIGNACIONES',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'itam.assets.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Alta y Edición de Equipos Informáticos',
                    'description' => 'Registrar nuevos activos tecnológicos, seriales, características y garantías.',
                    'scope' => 'ACTIVOS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'itam.assets.view',
                    'tag' => 'Lectura / Consulta',
                    'tagColor' => 'blue',
                    'name' => 'Consultar Inventario de Activos TI',
                    'description' => 'Visualizar equipos de cómputo, laptops, servidores, impresoras y software.',
                    'scope' => 'ACTIVOS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'itam.catalogs.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Catálogos de Marcas y Modelos',
                    'description' => 'Mantenimiento de marcas, modelos y categorías de hardware municipal.',
                    'scope' => 'CATALOGOS',
                    'status' => 'Activo en SIGM',
                ],
            ],
        ],
        'transport_management' => [
            'id' => 'transport_management',
            'name' => 'Licencias de Transportes',
            'code' => 'transport_management',
            'color' => 'amber',
            'colorHex' => '#ea580c',
            'borderClass' => 'border-l-amber-600',
            'iconBg' => 'bg-amber-600',
            'badgeBg' => 'bg-amber-700',
            'dotClass' => 'text-amber-500',
            'permissions' => [
                [
                    'code' => 'transport.licenses.issue',
                    'tag' => 'Creación / Registro',
                    'tagColor' => 'emerald',
                    'name' => 'Emisión de Licencias de Conducir Clase B',
                    'description' => 'Registro y emisión de licencias de conducir para mototaxis y motocicletas.',
                    'scope' => 'LICENCIAS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'transport.licenses.view',
                    'tag' => 'Lectura / Consulta',
                    'tagColor' => 'blue',
                    'name' => 'Consultar Registro de Licencias',
                    'description' => 'Búsqueda y verificación de expedientes de licencias de conducir emitidas.',
                    'scope' => 'LICENCIAS',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'transport.vehicles.manage',
                    'tag' => 'Gestión Integral',
                    'tagColor' => 'purple',
                    'name' => 'Empadronamiento de Vehículos Menores',
                    'description' => 'Alta, edición y control de padrón de mototaxis y asociaciones autorizadas.',
                    'scope' => 'EMPADRONAMIENTO',
                    'status' => 'Activo en SIGM',
                ],
                [
                    'code' => 'transport.sanctions.manage',
                    'tag' => 'Modificación / Flujo',
                    'tagColor' => 'amber',
                    'name' => 'Fiscalización y Actas de Control',
                    'description' => 'Registro de infracciones, actas de fiscalización y medidas preventivas.',
                    'scope' => 'FISCALIZACION',
                    'status' => 'Activo en SIGM',
                ],
            ],
        ],
    ];

    /**
     * Retrieve permissions grouped by subsystem with search and subsystem filter.
     *
     * @return array<string, mixed>
     */
    public function handle(string $search = '', string $subsystemFilter = 'TODOS'): array
    {
        $all = self::SUBSYSTEMS_DATA;
        $trimmedSearch = mb_strtolower(trim($search));

        $filteredSubsystems = [];
        $totalMatched = 0;

        foreach ($all as $key => $subsystem) {
            // Check subsystem filter
            if ($subsystemFilter !== 'TODOS' && $key !== $subsystemFilter) {
                continue;
            }

            $permissions = $subsystem['permissions'];

            // Apply search term filter
            if ($trimmedSearch !== '') {
                $permissions = array_values(array_filter($permissions, function (array $perm) use ($trimmedSearch): bool {
                    return str_contains(mb_strtolower($perm['code']), $trimmedSearch)
                        || str_contains(mb_strtolower($perm['name']), $trimmedSearch)
                        || str_contains(mb_strtolower($perm['description']), $trimmedSearch)
                        || str_contains(mb_strtolower($perm['scope']), $trimmedSearch);
                }));
            }

            if (! empty($permissions)) {
                $subsystem['permissions'] = $permissions;
                $filteredSubsystems[$key] = $subsystem;
                $totalMatched += count($permissions);
            }
        }

        return [
            'subsystems' => $filteredSubsystems,
            'total' => $totalMatched,
            'counts' => [
                'total' => 19,
                'central_dashboard' => 6,
                'helpdesk_support' => 5,
                'it_inventory' => 4,
                'transport_management' => 4,
            ],
        ];
    }
}
