<?php

declare(strict_types=1);

namespace App\Actions\Admin;

final class GetRolesCatalogAction
{
    /**
     * Institutional roles catalog in SIGM Castilla.
     *
     * @var array<int, array<string, mixed>>
     */
    private const ROLES_DATA = [
        [
            'id' => 1,
            'name' => 'Administrador Central',
            'code' => 'admin_central',
            'icon' => '🌐',
            'iconBgHex' => '#0d948820',
            'iconColorHex' => '#0d9488',
            'scopeKey' => 'global',
            'scopeLabel' => 'Multisubsistema (Global)',
            'scopeIcon' => '🌐',
            'scopeColorHex' => '#0d9488',
            'description' => 'Control absoluto del SIGM: configuración global, personas, usuarios y mantenimiento.',
            'grantedPermissions' => 19,
            'totalPermissions' => 19,
            'percentage' => 100,
            'isSystem' => true,
            'type' => 'Sistema',
            'status' => 'Activo',
        ],
        [
            'id' => 2,
            'name' => 'Operador de Transportes',
            'code' => 'transport_operator',
            'icon' => '🚗',
            'iconBgHex' => '#ea580c20',
            'iconColorHex' => '#ea580c',
            'scopeKey' => 'transportes',
            'scopeLabel' => 'Licencias de Transporte',
            'scopeIcon' => '🚗',
            'scopeColorHex' => '#ea580c',
            'description' => 'Atención ciudadana para licencias de conducir clase B y empadronamiento de mototaxis.',
            'grantedPermissions' => 5,
            'totalPermissions' => 19,
            'percentage' => 26,
            'isSystem' => false,
            'type' => 'Personalizado',
            'status' => 'Activo',
        ],
        [
            'id' => 3,
            'name' => 'Personal General / Reportante',
            'code' => 'municipal_staff',
            'icon' => '🎧',
            'iconBgHex' => '#7c3aed20',
            'iconColorHex' => '#7c3aed',
            'scopeKey' => 'helpdesk',
            'scopeLabel' => 'Soporte y Helpdesk',
            'scopeIcon' => '🎧',
            'scopeColorHex' => '#7c3aed',
            'description' => 'Colaborador municipal con facultades para registrar solicitudes de soporte técnico.',
            'grantedPermissions' => 2,
            'totalPermissions' => 19,
            'percentage' => 11,
            'isSystem' => false,
            'type' => 'Personalizado',
            'status' => 'Activo',
        ],
        [
            'id' => 4,
            'name' => 'Técnico de Soporte e ITAM',
            'code' => 'itam_tech',
            'icon' => '💻',
            'iconBgHex' => '#2563eb20',
            'iconColorHex' => '#2563eb',
            'scopeKey' => 'itam',
            'scopeLabel' => 'Inventario TI (ITAM)',
            'scopeIcon' => '💻',
            'scopeColorHex' => '#2563eb',
            'description' => 'Gestión operativa de activos informáticos, marcas, modelos y atención de tickets de soporte.',
            'grantedPermissions' => 9,
            'totalPermissions' => 19,
            'percentage' => 47,
            'isSystem' => false,
            'type' => 'Personalizado',
            'status' => 'Activo',
        ],
        [
            'id' => 5,
            'name' => 'Jefe de Oficina / Soporte TI',
            'code' => 'helpdesk_leader',
            'icon' => '👔',
            'iconBgHex' => '#4f46e520',
            'iconColorHex' => '#4f46e5',
            'scopeKey' => 'helpdesk',
            'scopeLabel' => 'Soporte y Helpdesk',
            'scopeIcon' => '🎧',
            'scopeColorHex' => '#4f46e5',
            'description' => 'Jefatura de mesa de ayuda: supervisión operativa, coordinación, delegación y transferencia de tickets.',
            'grantedPermissions' => 10,
            'totalPermissions' => 19,
            'percentage' => 53,
            'isSystem' => false,
            'type' => 'Personalizado',
            'status' => 'Activo',
        ],
    ];

    /**
     * Retrieve paginated roles catalog with search and scope filters.
     *
     * @return array<string, mixed>
     */
    public function handle(
        string $search = '',
        string $scopeFilter = 'TODOS',
        int $page = 1,
        int $perPage = 10
    ): array {
        $all = self::ROLES_DATA;

        try {
            $dbRoles = \App\Models\Role::all()->keyBy('code');
            if ($dbRoles->isNotEmpty()) {
                foreach ($all as &$r) {
                    $c = $r['code'];
                    if (isset($dbRoles[$c])) {
                        $perms = (array) ($dbRoles[$c]->permission_codes ?? []);
                        $count = count($perms);
                        $r['grantedPermissions'] = $count;
                        $r['percentage'] = (int) round(($count / 19) * 100);
                    }
                }
                unset($r);

                foreach ($dbRoles as $code => $dbRole) {
                    $found = false;
                    foreach ($all as $ex) {
                        if ($ex['code'] === $code) {
                            $found = true;
                            break;
                        }
                    }
                    if (! $found) {
                        $perms = (array) ($dbRole->permission_codes ?? []);
                        $count = count($perms);
                        $all[] = [
                            'id' => $dbRole->id,
                            'name' => $dbRole->name,
                            'code' => $dbRole->code,
                            'icon' => '👔',
                            'iconBgHex' => '#4f46e520',
                            'iconColorHex' => '#4f46e5',
                            'scopeKey' => $dbRole->subsystem_code === 'helpdesk_support' ? 'helpdesk' : ($dbRole->subsystem_code ?: 'global'),
                            'scopeLabel' => 'Soporte y Helpdesk',
                            'scopeIcon' => '🎧',
                            'scopeColorHex' => '#4f46e5',
                            'description' => $dbRole->description ?: 'Rol institucional configurado en el sistema.',
                            'grantedPermissions' => $count,
                            'totalPermissions' => 19,
                            'percentage' => (int) round(($count / 19) * 100),
                            'isSystem' => (bool) $dbRole->is_system,
                            'type' => $dbRole->is_system ? 'Sistema' : 'Personalizado',
                            'status' => 'Activo',
                        ];
                    }
                }
            }
        } catch (\Throwable) {
        }

        // Filter by scope
        $filtered = array_filter($all, function (array $role) use ($scopeFilter): bool {
            if ($scopeFilter === 'TODOS') {
                return true;
            }

            return $role['scopeKey'] === $scopeFilter;
        });

        // Filter by search term
        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $filtered = array_filter($filtered, function (array $role) use ($trimmedSearch): bool {
                return str_contains(mb_strtolower($role['name']), $trimmedSearch)
                    || str_contains(mb_strtolower($role['code']), $trimmedSearch)
                    || str_contains(mb_strtolower($role['description']), $trimmedSearch)
                    || str_contains(mb_strtolower($role['scopeLabel']), $trimmedSearch);
            });
        }

        $total = count($filtered);
        $perPage = max(1, $perPage);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));

        $offset = ($page - 1) * $perPage;
        $paginated = array_slice(array_values($filtered), $offset, $perPage);

        $from = $total > 0 ? $offset + 1 : 0;
        $to = min($offset + $perPage, $total);

        return [
            'items' => $paginated,
            'total' => $total,
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
            'from' => $from,
            'to' => $to,
            'counts' => [
                'total' => count($all),
                'system' => count(array_filter($all, fn ($r) => ! empty($r['isSystem']))),
                'custom' => count(array_filter($all, fn ($r) => empty($r['isSystem']))),
                'transportes' => count(array_filter($all, fn ($r) => ($r['scopeKey'] ?? '') === 'transportes')),
                'itam' => count(array_filter($all, fn ($r) => ($r['scopeKey'] ?? '') === 'itam')),
                'helpdesk' => count(array_filter($all, fn ($r) => ($r['scopeKey'] ?? '') === 'helpdesk')),
                'global' => count(array_filter($all, fn ($r) => ($r['scopeKey'] ?? '') === 'global')),
            ],
        ];
    }

    /**
     * Find a role by id.
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        foreach (self::ROLES_DATA as $role) {
            if ($role['id'] === $id) {
                return $role;
            }
        }

        return null;
    }
}
