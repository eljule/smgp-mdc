<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * En producción y despliegues oficiales ejecuta únicamente los seeders con datos originales.
     * En entorno de pruebas automatizadas (SQLite :memory:) o con flag SEED_DEMO_DATA=true carga además la data de prueba.
     */
    public function run(): void
    {
        // 1. Datos Maestros Oficiales de Producción
        $this->call([
            CoreModuleSeeder::class,
            SedeSeeder::class,
            OfficeSeeder::class,
            PersonSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            ItamSeeder::class,
        ]);

        // 2. Datos de Demostración y Pruebas (solo para suite de pruebas en memoria SQLite o si se activa demo)
        if (config('database.default') === 'sqlite' || (bool) env('SEED_DEMO_DATA', false)) {
            $this->call([
                DemoItamSeeder::class,
                HelpdeskSeeder::class,
            ]);
        }
    }
}
