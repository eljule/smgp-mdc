<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Carga inicial oficial para puesta en marcha en Producción.
     * Incluye exclusivamente datos originales:
     * 1. Módulos del Sistema SIGM (core_modules)
     * 2. Sedes Municipales Oficiales (core_sedes)
     * 3. Estructura Orgánica y Oficinas (core_offices)
     * 4. Padrón Oficial de Personal Municipal (core_people)
     * 5. Roles y Matriz de Permisos Institucionales (core_roles, core_permissions, etc.)
     * 6. Cuentas de Usuario Oficiales y Asignación de Roles (core_users)
     * 7. Catálogos Maestros de ITAM: Categorías Oficiales con Esquemas Dinámicos de Especificaciones, Marcas y Modelos
     */
    public function run(): void
    {
        $this->call([
            CoreModuleSeeder::class,
            SedeSeeder::class,
            OfficeSeeder::class,
            PersonSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            ItamSeeder::class,
        ]);
    }
}
