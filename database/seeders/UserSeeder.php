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

        $users = [
            [
                'id' => 2,
                'username' => 'jperez',
                'password' => '$2y$12$vVDbjOhVl9cLQIdtGF7amOs/eEZxUGMUB62TDk8BKc9iFH98zGwy2',
                'full_name' => 'Juan Alberto Pérez Morales',
                'email' => 'jperez@sigm.gob.pe',
                'role' => 'Técnico de Soporte e ITAM',
                'allowed_modules' => '["admin", "itam", "helpdesk"]',
                'is_active' => true,
                'person_id' => 39,
                'remember_token' => null,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 3,
                'username' => 'mgomez',
                'password' => '$2y$12$AEHRBTfDlRVALVqk0SaxDukpqOAY8dR6XlJvEXvcP9JL59IBRFx7i',
                'full_name' => 'María Elena Gómez Castillo',
                'email' => 'mgomez@sigm.gob.pe',
                'role' => 'Operador de Transportes',
                'allowed_modules' => '["transportes", "helpdesk"]',
                'is_active' => true,
                'person_id' => 5,
                'remember_token' => null,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
            [
                'id' => 1,
                'username' => 'root',
                'password' => '$2y$12$XBBRoZV5esbThWU.6ztx5.G9WnMpETb6gKfqYlPt2ZBsqZCJSBS8S',
                'full_name' => 'Administrador del Sistema SIGM',
                'email' => 'admin@castilla.gob.pe',
                'role' => 'Administrador Central',
                'allowed_modules' => '["admin", "itam", "transportes", "helpdesk"]',
                'is_active' => true,
                'person_id' => 35,
                'remember_token' => 'Ruyk1C0grlpnfvD0AvEEmOsJ81poAhKYRYIAjCpkYZR9z6TSKvhu6copw1xZ',
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 02:12:58',
            ],
        ];

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
