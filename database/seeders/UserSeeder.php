<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        DB::table('core_users')->delete();

        // Ensure core_people is seeded if run in isolation
        if (DB::table('core_people')->count() === 0) {
            $this->call(PersonSeeder::class);
        }

        // Locate Jefe de la Oficina de Desarrollo Tecnológico
        $odtBoss = DB::table('core_people')
            ->where(function ($query) {
                $query->where('office', 'like', '%DESARROLLO TECNOL%')
                    ->orWhere('office', 'like', '%ODT%');
            })
            ->where(function ($query) {
                $query->where('position', 'like', '%JEFE%')
                    ->orWhere('position', 'like', '%DESARROLLO%');
            })
            ->first();

        if (! $odtBoss) {
            $odtBoss = DB::table('core_people')->where('document_number', '10783864')->first()
                ?? DB::table('core_people')->first();
        }

        $odtBossId = $odtBoss?->id;
        $odtBossName = $odtBoss
            ? trim(($odtBoss->first_name ?? '') . ' ' . ($odtBoss->paternal_surname ?? '') . ' ' . ($odtBoss->maternal_surname ?? ''))
            : 'JAIME HENRY SANDOVAL NIEVES';

        $users = [];

        // 1. Usuario root (Administrador Central)
        $users[] = [
            'id' => 1,
            'username' => 'root',
            'password' => '$2y$12$XBBRoZV5esbThWU.6ztx5.G9WnMpETb6gKfqYlPt2ZBsqZCJSBS8S',
            'full_name' => 'Administrador del Sistema SIGM',
            'email' => 'admin@castilla.gob.pe',
            'role' => 'Administrador Central',
            'allowed_modules' => json_encode(['admin', 'itam', 'transportes', 'helpdesk']),
            'is_active' => true,
            'person_id' => $odtBossId,
            'must_change_password' => false,
            'failed_login_attempts' => 0,
            'is_locked' => false,
            'remember_token' => 'Ruyk1C0grlpnfvD0AvEEmOsJ81poAhKYRYIAjCpkYZR9z6TSKvhu6copw1xZ',
            'created_at' => '2026-10-15 02:12:58',
            'updated_at' => '2026-10-15 02:12:58',
        ];

        // 2. Cuatro usuarios técnicos vinculados al Servidor Municipal de la ODT con rol TÉCNICO DE SOPORTE E ITAM
        for ($i = 1; $i <= 4; $i++) {
            $users[] = [
                'id' => $i + 1,
                'username' => "soporte{$i}",
                'password' => '$2y$12$XBBRoZV5esbThWU.6ztx5.G9WnMpETb6gKfqYlPt2ZBsqZCJSBS8S',
                'full_name' => $odtBossName,
                'email' => "soporte{$i}@castilla.gob.pe",
                'role' => 'Técnico de Soporte e ITAM',
                'allowed_modules' => json_encode(['admin', 'itam', 'helpdesk']),
                'is_active' => true,
                'person_id' => $odtBossId,
                'must_change_password' => false,
                'failed_login_attempts' => 0,
                'is_locked' => false,
                'remember_token' => null,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ];
        }

        // 3. Un usuario por cada persona de la base de datos con el rol de Personal General / Reportante
        $people = DB::table('core_people')->orderBy('id')->get();
        $nextId = 6;

        foreach ($people as $person) {
            $username = trim((string) $person->document_number);
            if ($username === '') {
                $username = 'user_' . $person->id;
            }

            $fullName = trim(($person->first_name ?? '') . ' ' . ($person->paternal_surname ?? '') . ' ' . ($person->maternal_surname ?? ''));
            if ($fullName === '') {
                $fullName = 'Servidor Municipal ' . $person->id;
            }

            $email = ! empty($person->email)
                ? $person->email
                : strtolower($username) . '@castilla.gob.pe';

            $users[] = [
                'id' => $nextId++,
                'username' => $username,
                'password' => '$2y$12$XBBRoZV5esbThWU.6ztx5.G9WnMpETb6gKfqYlPt2ZBsqZCJSBS8S',
                'full_name' => $fullName,
                'email' => $email,
                'role' => 'Personal General / Reportante',
                'allowed_modules' => json_encode(['helpdesk']),
                'is_active' => true,
                'person_id' => $person->id,
                'must_change_password' => false,
                'failed_login_attempts' => 0,
                'is_locked' => false,
                'remember_token' => null,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ];
        }

        $existingPersonIds = DB::table('core_people')->pluck('id')->all();
        foreach ($users as &$user) {
            if ($user['person_id'] !== null && ! in_array($user['person_id'], $existingPersonIds, true)) {
                $user['person_id'] = null;
            }
        }
        unset($user);

        foreach (array_chunk($users, 50) as $chunk) {
            DB::table('core_users')->insert($chunk);
        }

        if ($isPgsql) {
            DB::statement("SELECT setval(pg_get_serial_sequence('core_users', 'id'), coalesce(max(id), 0) + 1, false) FROM core_users;");
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }
}
