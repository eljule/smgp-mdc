<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SedeSeeder extends Seeder
{
    public function run(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';
        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        DB::table('core_sedes')->delete();

        $sedes = [
            [
                'id' => 2,
                'code' => 'COSC',
                'name' => 'CENTRO DE OPERACIONES DE SEGURIDAD CIUDADANA',
                'address' => 'Av. Independencia',
                'color' => 'blue',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 20:02:11',
            ],
            [
                'id' => 11,
                'code' => 'ALMACEN',
                'name' => 'ALMACEN',
                'address' => null,
                'color' => 'rose',
                'is_active' => true,
                'created_at' => '2026-10-15 20:13:18',
                'updated_at' => '2026-10-15 20:13:18',
            ],
            [
                'id' => 12,
                'code' => 'MERCADO',
                'name' => 'MERCADO',
                'address' => 'Av. Moquegua, Piura y Ayacucho',
                'color' => 'indigo',
                'is_active' => true,
                'created_at' => '2026-10-15 20:15:40',
                'updated_at' => '2026-10-15 20:18:53',
            ],
            [
                'id' => 1,
                'code' => 'PRINCIPAL',
                'name' => 'PALACIO MUNICIPAL (SEDE PRINCIPAL)',
                'address' => 'Calle. Ayacucho',
                'color' => 'emerald',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 20:37:33',
            ],
            [
                'id' => 10,
                'code' => 'GDH',
                'name' => 'DESARROLLO HUMANO (GDH)',
                'address' => 'Calle. Los Rosales',
                'color' => 'purple',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 20:38:16',
            ],
            [
                'id' => 3,
                'code' => 'MAESTRANZA',
                'name' => 'SERVICIOS PÚBLICOS (MAESTRANZA)',
                'address' => 'Calle. Villa Real',
                'color' => 'cyan',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 20:40:27',
            ],
            [
                'id' => 6,
                'code' => 'BIBLIOTECA',
                'name' => 'BIBLIOTECA MUNICIPAL',
                'address' => 'Av. Junin',
                'color' => 'orange',
                'is_active' => true,
                'created_at' => '2026-10-15 02:12:58',
                'updated_at' => '2026-10-15 20:44:29',
            ],
            [
                'id' => 13,
                'code' => 'RENTAS',
                'name' => 'ADMINISTRACION TRIBUTARIA (RENTAS)',
                'address' => 'Calle. Los Almendros',
                'color' => 'amber',
                'is_active' => true,
                'created_at' => '2026-10-15 20:44:17',
                'updated_at' => '2026-10-15 20:45:59',
            ],
            [
                'id' => 14,
                'code' => 'TERMINAL',
                'name' => 'TERMINAL TERRESTRE',
                'address' => 'Av. Guardia Civil',
                'color' => 'emerald',
                'is_active' => true,
                'created_at' => '2026-10-15 20:52:53',
                'updated_at' => '2026-10-15 20:52:53',
            ],
        ];
        foreach (array_chunk($sedes, 50) as $chunk) {
            DB::table('core_sedes')->insert($chunk);
        }

        if ($isPgsql) {
            DB::statement("SELECT setval(pg_get_serial_sequence('core_sedes', 'id'), coalesce(max(id), 0) + 1, false) FROM core_sedes;");
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }
}
