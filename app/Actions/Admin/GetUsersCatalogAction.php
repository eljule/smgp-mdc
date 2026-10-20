<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\User;

final class GetUsersCatalogAction
{
    /**
     * Preset role templates defined in SIGM.
     *
     * @var array<int, array<string, mixed>>
     */
    public const ROLE_PRESETS = [
        [
            'key' => 'admin',
            'title' => 'Administrador Central',
            'dotColor' => 'emerald',
            'description' => 'Acceso total y configuración de todos los subsistemas del SIGM.',
            'modulesCount' => 4,
            'subsystems' => ['admin', 'itam', 'transportes', 'helpdesk'],
        ],
        [
            'key' => 'itam',
            'title' => 'Técnico de Soporte e ITAM',
            'dotColor' => 'blue',
            'description' => 'Gestión de activos tecnológicos, marcas, modelos y atención operativa de incidencias informáticas.',
            'modulesCount' => 2,
            'subsystems' => ['itam', 'helpdesk'],
        ],
        [
            'key' => 'transportes',
            'title' => 'Operador de Transportes',
            'dotColor' => 'amber',
            'description' => 'Empadronamiento de vehículos menores, emisión de licencias y fiscalización.',
            'modulesCount' => 2,
            'subsystems' => ['transportes', 'helpdesk'],
        ],
        [
            'key' => 'general',
            'title' => 'Personal General / Reportante',
            'dotColor' => 'purple',
            'description' => 'Acceso al portal de solicitudes y emisión de tickets de soporte técnico institucional.',
            'modulesCount' => 1,
            'subsystems' => ['helpdesk'],
        ],
    ];

    /**
     * Subsystem catalog definition for labels, badges and icons.
     *
     * @var array<string, array<string, string>>
     */
    public const SUBSYSTEMS_META = [
        'all' => [
            'label' => 'Todos los subsistemas (Acceso Total)',
            'shortLabel' => 'Todos los subsistemas (Acceso Total)',
            'icon' => '🌱',
            'bgClass' => 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800',
        ],
        'admin' => [
            'label' => 'Dashboard Central y Configuración',
            'shortLabel' => 'Dashboard Central y Co...',
            'icon' => '📊',
            'bgClass' => 'bg-emerald-700 text-white',
        ],
        'itam' => [
            'label' => 'Inventario y Gestión TI',
            'shortLabel' => 'Inventario y Gestión TI',
            'icon' => '💻',
            'bgClass' => 'bg-blue-600 text-white',
        ],
        'helpdesk' => [
            'label' => 'Soporte Técnico y Helpdesk',
            'shortLabel' => 'Soporte Técnico y Help...',
            'icon' => '🎧',
            'bgClass' => 'bg-purple-600 text-white',
        ],
        'transportes' => [
            'label' => 'Gestión de Transportes',
            'shortLabel' => 'Gestión de Transportes',
            'icon' => '🚗',
            'bgClass' => 'bg-amber-600 text-white',
        ],
    ];

    /**
     * Active user accounts registered in SIGM.
     *
     * @var array<int, array<string, mixed>>
     */
    private const USERS_DATA = [
        [
            'id' => 1,
            'username' => 'root',
            'avatar' => 'RO',
            'fullName' => 'ADMINISTRADOR DEL SISTEMA SIGM',
            'email' => 'admin@castilla.gob.pe',
            'primaryRole' => 'Administrador Central',
            'subsystems' => ['all'],
            'status' => 'Activo',
            'personId' => null,
        ],
        [
            'id' => 2,
            'username' => '10783864',
            'avatar' => '10',
            'fullName' => 'JAIME HENRY SANDOVAL NIEVES',
            'email' => 'jsandoval@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 35,
        ],
        [
            'id' => 3,
            'username' => 'soporte1',
            'avatar' => 'SO',
            'fullName' => 'JAIME HENRY SANDOVAL NIEVES',
            'email' => 'jsandoval@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 35,
        ],
        [
            'id' => 4,
            'username' => 'soporte2',
            'avatar' => 'SO',
            'fullName' => 'JAIME HENRY SANDOVAL NIEVES',
            'email' => 'jsandoval@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 35,
        ],
        [
            'id' => 5,
            'username' => 'soporte3',
            'avatar' => 'SO',
            'fullName' => 'JAIME HENRY SANDOVAL NIEVES',
            'email' => 'jsandoval@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 35,
        ],
        [
            'id' => 6,
            'username' => 'soporte4',
            'avatar' => 'SO',
            'fullName' => 'JAIME HENRY SANDOVAL NIEVES',
            'email' => 'jsandoval@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 35,
        ],
        [
            'id' => 7,
            'username' => '02821801',
            'avatar' => '02',
            'fullName' => 'CARLOS EDUARDO ROJAS MATIAS',
            'email' => 'crojas@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 1,
        ],
        [
            'id' => 8,
            'username' => '02852121',
            'avatar' => '02',
            'fullName' => 'EMMA FLOR SAAVEDRA PACHERREZ',
            'email' => 'esaavedra@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 2,
        ],
        [
            'id' => 9,
            'username' => '43268255',
            'avatar' => '43',
            'fullName' => 'ROBERTO PABLO BARRERA ZUÑIGA',
            'email' => 'rbarrera@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 3,
        ],
        [
            'id' => 10,
            'username' => '43465876',
            'avatar' => '43',
            'fullName' => 'RICARDO FELIX MARTINEZ CÓNDOR',
            'email' => 'rmartinez@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 4,
        ],
        [
            'id' => 11,
            'username' => '02830165',
            'avatar' => '02',
            'fullName' => 'GUSTAVO ADOLFO PAREDES SHIMOKAWA',
            'email' => 'gparedes@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 5,
        ],
        [
            'id' => 12,
            'username' => '16688162',
            'avatar' => '16',
            'fullName' => 'JOSE FERNANDO RAMIREZ MORENO',
            'email' => 'jramirez@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 6,
        ],
        [
            'id' => 13,
            'username' => '02657504',
            'avatar' => '02',
            'fullName' => 'WILLIAM OSWALDO ROJAS MERINO',
            'email' => 'wrojas@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 7,
        ],
        [
            'id' => 14,
            'username' => '03386696',
            'avatar' => '03',
            'fullName' => 'JUAN RUPERTO VILELA ESPINOZA',
            'email' => 'jvilela@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 8,
        ],
        [
            'id' => 15,
            'username' => '02783056',
            'avatar' => '02',
            'fullName' => 'LUIS RICARDO LLACSAHUANGA TIMOTEO',
            'email' => 'lllacsahuanga@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 9,
        ],
        [
            'id' => 16,
            'username' => '40774696',
            'avatar' => '40',
            'fullName' => 'DORIS LOPEZ GARCIA',
            'email' => 'dlopez@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 10,
        ],
        [
            'id' => 17,
            'username' => '45821903',
            'avatar' => '45',
            'fullName' => 'JUAN ALBERTO PÉREZ MORALES',
            'email' => 'jperez@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 12,
        ],
        [
            'id' => 18,
            'username' => '40192837',
            'avatar' => '40',
            'fullName' => 'MARÍA ELENA GÓMEZ CASTILLO',
            'email' => 'mgomez@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 13,
        ],
        [
            'id' => 19,
            'username' => '42747895',
            'avatar' => '42',
            'fullName' => 'CINTHYA KATTY FERRO COLUMBUS',
            'email' => 'cferro@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 14,
        ],
        [
            'id' => 20,
            'username' => '47120938',
            'avatar' => '47',
            'fullName' => 'WALTER SÁNCHEZ NAVARRO',
            'email' => 'wsanchez@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 15,
        ],
        [
            'id' => 21,
            'username' => '08192844',
            'avatar' => '08',
            'fullName' => 'CARLOS EDUARDO MENDOZA RUIZ',
            'email' => 'cmendoza@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 16,
        ],
        [
            'id' => 22,
            'username' => '02871142',
            'avatar' => '02',
            'fullName' => 'ANA MARÍA RIVAS ZAPATA',
            'email' => 'arivas@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 17,
        ],
        [
            'id' => 23,
            'username' => '03891244',
            'avatar' => '03',
            'fullName' => 'SEGUNDO MANUEL CORDOVA MORALES',
            'email' => 'scordova@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 18,
        ],
        [
            'id' => 24,
            'username' => '41892301',
            'avatar' => '41',
            'fullName' => 'PATRICIA DEL PILAR MOROCHO RUIZ',
            'email' => 'pmorocho@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 19,
        ],
        [
            'id' => 25,
            'username' => '02914829',
            'avatar' => '02',
            'fullName' => 'MIGUEL ANGEL FARFÁN JIMÉNEZ',
            'email' => 'mfarfan@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 20,
        ],
        [
            'id' => 26,
            'username' => '44910283',
            'avatar' => '44',
            'fullName' => 'GLADYS BEATRIZ CARREÑO VIERA',
            'email' => 'gcarreno@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 21,
        ],
        [
            'id' => 27,
            'username' => '03819234',
            'avatar' => '03',
            'fullName' => 'VICTOR RAUL CHUNGA NUNURA',
            'email' => 'vchunga@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 22,
        ],
        [
            'id' => 28,
            'username' => '42190847',
            'avatar' => '42',
            'fullName' => 'ROSA ELENA CASTILLO PEÑA',
            'email' => 'rcastillo@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 23,
        ],
        [
            'id' => 29,
            'username' => '02948172',
            'avatar' => '02',
            'fullName' => 'FELIX ARTURO SOSA MONTERO',
            'email' => 'fsosa@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 24,
        ],
        [
            'id' => 30,
            'username' => '45102938',
            'avatar' => '45',
            'fullName' => 'JOSÉ LUIS TAVARA BENITES',
            'email' => 'jtavara@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 25,
        ],
        [
            'id' => 31,
            'username' => '02849103',
            'avatar' => '02',
            'fullName' => 'KARINA LISSET VALVERDE TIMANA',
            'email' => 'kvalverde@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 26,
        ],
        [
            'id' => 32,
            'username' => '43920194',
            'avatar' => '43',
            'fullName' => 'EDGAR OMAR SILVA MECA',
            'email' => 'esilva@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 27,
        ],
        [
            'id' => 33,
            'username' => '03810294',
            'avatar' => '03',
            'fullName' => 'LILIANA PAOLA CORREA GUERRERO',
            'email' => 'lcorrea@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 28,
        ],
        [
            'id' => 34,
            'username' => '46102938',
            'avatar' => '46',
            'fullName' => 'HECTOR DANIEL ZAVALA CORONADO',
            'email' => 'hzavala@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 29,
        ],
        [
            'id' => 35,
            'username' => '02910394',
            'avatar' => '02',
            'fullName' => 'CLAUDIA MARIBEL CHERO LITANO',
            'email' => 'cchero@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 30,
        ],
        [
            'id' => 36,
            'username' => '44102938',
            'avatar' => '44',
            'fullName' => 'CESAR AUGUSTO PURIZACA FLORES',
            'email' => 'cpurizaca@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 31,
        ],
        [
            'id' => 37,
            'username' => '03810283',
            'avatar' => '03',
            'fullName' => 'MARCOS ANTONIO ALAMA SILVA',
            'email' => 'malama@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 32,
        ],
        [
            'id' => 38,
            'username' => '45102984',
            'avatar' => '45',
            'fullName' => 'VANESSA DEL CARMEN DIOSES IPANAQUE',
            'email' => 'vdioses@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 33,
        ],
        [
            'id' => 39,
            'username' => '02910485',
            'avatar' => '02',
            'fullName' => 'JORGE ARMANDO TINEO CORNEJO',
            'email' => 'jtineo@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 34,
        ],
        [
            'id' => 40,
            'username' => '43102948',
            'avatar' => '43',
            'fullName' => 'MONICA ROXANA TALLEDO RIVERA',
            'email' => 'mtalledo@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 35,
        ],
        [
            'id' => 41,
            'username' => '03910284',
            'avatar' => '03',
            'fullName' => 'RAFAEL ESTEBAN VIERA ATO',
            'email' => 'rviera@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 36,
        ],
        [
            'id' => 42,
            'username' => '46102948',
            'avatar' => '46',
            'fullName' => 'CARMEN ROSA VILCHEZ SAAVEDRA',
            'email' => 'cvilchez@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 37,
        ],
        [
            'id' => 43,
            'username' => '02810394',
            'avatar' => '02',
            'fullName' => 'MANUEL ALBERTO OROZCO GUZMAN',
            'email' => 'morozco@castilla.gob.pe',
            'primaryRole' => 'OPERADOR DE TRANSPORTES',
            'subsystems' => ['transportes', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 38,
        ],
        [
            'id' => 44,
            'username' => '44102985',
            'avatar' => '44',
            'fullName' => 'TERESA DE JESUS MEDINA ZAPATA',
            'email' => 'tmedina@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 39,
        ],
        [
            'id' => 45,
            'username' => '03810495',
            'avatar' => '03',
            'fullName' => 'ALBERTO ENRIQUE NOLE CORREA',
            'email' => 'anole@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 40,
        ],
        [
            'id' => 46,
            'username' => '45102958',
            'avatar' => '45',
            'fullName' => 'SILVIA MARLENE YARLEQUE MORALES',
            'email' => 'syarleque@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 41,
        ],
        [
            'id' => 47,
            'username' => '02910594',
            'avatar' => '02',
            'fullName' => 'OSCAR ROLANDO VALLADARES RIVAS',
            'email' => 'ovalladares@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 42,
        ],
        [
            'id' => 48,
            'username' => '43102958',
            'avatar' => '43',
            'fullName' => 'LUCIA ESPERANZA SOSA CHUNGA',
            'email' => 'lsosa@castilla.gob.pe',
            'primaryRole' => 'PERSONAL GENERAL / REPORTANTE',
            'subsystems' => ['helpdesk'],
            'status' => 'Activo',
            'personId' => 43,
        ],
        [
            'id' => 49,
            'username' => '03910394',
            'avatar' => '03',
            'fullName' => 'FERNANDO JAVIER CHAVEZ NIZAMA',
            'email' => 'fchavez@castilla.gob.pe',
            'primaryRole' => 'TÉCNICO DE SOPORTE E ITAM',
            'subsystems' => ['itam', 'helpdesk'],
            'status' => 'Activo',
            'personId' => 44,
        ],
    ];

    /**
     * Remove accents and normalize string for intelligent case and accent insensitive search.
     */
    public static function normalizeString(string $text): string
    {
        $accents = ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'];
        $clean = ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'u', 'n'];

        return mb_strtolower(str_replace($accents, $clean, trim($text)));
    }

    /**
     * Retrieve paginated users catalog with search and role filters.
     *
     * @return array<string, mixed>
     */
    public function handle(
        string $search = '',
        string $roleFilter = 'TODOS',
        int $page = 1,
        int $perPage = 10
    ): array {
        $peopleDniMap = [];
        foreach (GetPeopleCatalogAction::PEOPLE_DATA as $p) {
            $peopleDniMap[$p['id']] = $p['documentNumber'];
        }

        $all = [];
        foreach (self::USERS_DATA as $user) {
            $dni = null;
            if (is_numeric($user['username']) && strlen((string) $user['username']) === 8) {
                $dni = (string) $user['username'];
            } elseif (! empty($user['personId']) && isset($peopleDniMap[$user['personId']])) {
                $dni = $peopleDniMap[$user['personId']];
            }
            $user['dni'] = $dni;
            $all[] = $user;
        }

        // Merge any database users registered dynamically in core_users and sync lock status
        try {
            $existingUsernames = array_column($all, 'username');
            $dbUsers = User::with('person')->get();
            $dbUsersMap = $dbUsers->keyBy('username');

            foreach ($all as &$u) {
                if (isset($dbUsersMap[$u['username']])) {
                    $db = $dbUsersMap[$u['username']];
                    $u['id'] = $db->id;
                    $u['dbId'] = $db->id;
                    $u['isLocked'] = (bool) $db->is_locked;
                    $u['mustChangePassword'] = (bool) $db->must_change_password;
                    $u['failedAttempts'] = $db->failed_login_attempts ?? 0;
                    $u['status'] = $db->is_locked ? 'Bloqueado' : ($db->is_active ? 'Activo' : 'Inactivo');
                    if ($db->allowed_modules !== null) {
                        $u['subsystems'] = (array) $db->allowed_modules;
                    }
                    if (! empty($db->role)) {
                        $u['primaryRole'] = $db->role;
                    }
                    if ($u['username'] !== 'root') {
                        if ($db->person_id) {
                            $u['personId'] = $db->person_id;
                        }
                        if ($db->person?->document_number) {
                            $u['dni'] = $db->person->document_number;
                        } elseif (is_numeric($db->username) && strlen((string) $db->username) === 8) {
                            $u['dni'] = (string) $db->username;
                        }
                        if (! empty($db->person?->full_name)) {
                            $u['fullName'] = $db->person->full_name;
                        } elseif (! empty($db->full_name)) {
                            $u['fullName'] = $db->full_name;
                        }
                    }
                } else {
                    $u['isLocked'] = false;
                    $u['mustChangePassword'] = false;
                    $u['failedAttempts'] = 0;
                    $u['dbId'] = null;
                }
            }
            unset($u);

            foreach ($dbUsers as $dbUser) {
                if (! in_array($dbUser->username, $existingUsernames, true)) {
                    $all[] = [
                        'id' => $dbUser->id,
                        'username' => $dbUser->username,
                        'avatar' => $dbUser->initials,
                        'fullName' => $dbUser->display_title,
                        'email' => $dbUser->email,
                        'primaryRole' => $dbUser->role ?: 'Usuario Municipal',
                        'subsystems' => $dbUser->allowed_modules ?: ['helpdesk'],
                        'status' => $dbUser->is_locked ? 'Bloqueado' : ($dbUser->is_active ? 'Activo' : 'Inactivo'),
                        'personId' => $dbUser->person_id,
                        'dni' => $dbUser->person?->document_number ?: (is_numeric($dbUser->username) && strlen((string) $dbUser->username) === 8 ? $dbUser->username : null),
                        'isLocked' => (bool) $dbUser->is_locked,
                        'mustChangePassword' => (bool) $dbUser->must_change_password,
                        'failedAttempts' => $dbUser->failed_login_attempts ?? 0,
                        'dbId' => $dbUser->id,
                    ];
                }
            }
        } catch (\Throwable) {
            // Silently fallback if database is not available during initial boot
        }

        // Filter by role if set
        $filtered = array_filter($all, function (array $user) use ($roleFilter): bool {
            if ($roleFilter === 'TODOS') {
                return true;
            }

            return mb_strtoupper($user['primaryRole']) === mb_strtoupper($roleFilter);
        });

        // Filter by search term (Buscador Inteligente: DNI, Nombre, Usuario, Correo, Rol)
        $trimmedSearch = self::normalizeString($search);
        if ($trimmedSearch !== '') {
            $filtered = array_filter($filtered, function (array $user) use ($trimmedSearch): bool {
                $userDni = (string) ($user['dni'] ?? '');
                $username = (string) ($user['username'] ?? '');
                $fullName = (string) ($user['fullName'] ?? '');
                $email = (string) ($user['email'] ?? '');
                $role = (string) ($user['primaryRole'] ?? '');

                return str_contains(self::normalizeString($username), $trimmedSearch)
                    || str_contains(self::normalizeString($fullName), $trimmedSearch)
                    || str_contains(self::normalizeString($email), $trimmedSearch)
                    || str_contains(self::normalizeString($role), $trimmedSearch)
                    || ($userDni !== '' && str_contains(self::normalizeString($userDni), $trimmedSearch));
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
            'presets' => self::ROLE_PRESETS,
            'subsystemsMeta' => self::SUBSYSTEMS_META,
            'counts' => [
                'total' => count($all),
                'active' => count(array_filter($all, fn ($u) => ($u['status'] ?? 'Activo') === 'Activo')),
                'filtered' => $total,
            ],
        ];
    }

    /**
     * Find a user by id.
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $res = $this->handle(perPage: 9999);
        foreach ($res['items'] as $user) {
            if ($user['id'] === $id || ($user['dbId'] ?? null) === $id) {
                return $user;
            }
        }

        foreach (self::USERS_DATA as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Find a user by username.
     *
     * @return array<string, mixed>|null
     */
    public function findByUsername(string $username): ?array
    {
        $res = $this->handle(search: $username, perPage: 10);
        foreach ($res['items'] as $user) {
            if (mb_strtolower((string) $user['username']) === mb_strtolower(trim($username))) {
                return $user;
            }
        }

        return null;
    }
}
