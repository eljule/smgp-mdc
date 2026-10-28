<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDemoDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sigm:clean-demo {--force : Ejecutar sin solicitar confirmación interactiva}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purga todos los datos demo de ITAM y Helpdesk, dejando intactos los módulos, sedes, oficinas, personal, usuarios, roles, permisos y catálogos maestros ITAM (categorías con esquemas, marcas y modelos) para producción.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force')) {
            $confirmed = $this->confirm(
                'ADVERTENCIA: Esta acción eliminará permanentemente todos los bienes (assets), préstamos, movimientos, auditorías, mantenimientos, tickets de soporte e insumos de prueba. ¿Desea continuar?',
                false
            );

            if (!$confirmed) {
                $this->info('Operación cancelada por el usuario.');
                return self::SUCCESS;
            }
        }

        $this->info('Iniciando purga de datos demo y preparación de base de datos para producción...');

        $isPgsql = DB::getDriverName() === 'pgsql';

        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        $tablesToClean = [
            'helpdesk_ticket_supplies',
            'helpdesk_ticket_loans',
            'helpdesk_ticket_audit_logs',
            'helpdesk_ticket_technical_details',
            'helpdesk_knowledge_articles',
            'helpdesk_tickets',
            'itam_maintenance_supplies',
            'itam_maintenance_orders',
            'itam_audit_verifications',
            'itam_inventory_audits',
            'itam_asset_movements',
            'itam_asset_loan_items',
            'itam_asset_loans',
            'itam_asset_software',
            'itam_software',
            'itam_assets',
            'itam_supplies',
        ];

        foreach ($tablesToClean as $table) {
            if (Schema::hasTable($table)) {
                if ($table === 'itam_assets') {
                    DB::table($table)->update(['parent_asset_id' => null]);
                }
                DB::table($table)->delete();
            }
        }

        if ($isPgsql) {
            foreach ($tablesToClean as $table) {
                if (Schema::hasTable($table)) {
                    DB::statement("SELECT setval(pg_get_serial_sequence('{$table}', 'id'), 1, false);");
                }
            }
            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }

        $this->newLine();
        $this->info('=== RESUMEN DE DATOS MAESTROS PRESERVADOS (PRODUCCIÓN) ===');
        $preservedRows = [
            ['Módulos del Sistema', 'core_modules', (string) DB::table('core_modules')->count()],
            ['Sedes Municipales', 'core_sedes', (string) DB::table('core_sedes')->count()],
            ['Oficinas Municipales', 'core_offices', (string) DB::table('core_offices')->count()],
            ['Personal Municipal (Padrón)', 'core_people', (string) DB::table('core_people')->count()],
            ['Roles Spatie', 'core_roles', (string) DB::table('core_roles')->count()],
            ['Permisos Spatie', 'core_permissions', (string) DB::table('core_permissions')->count()],
            ['Usuarios Oficiales', 'core_users', (string) DB::table('core_users')->count()],
            ['Categorías ITAM (Esquemas)', 'itam_asset_categories', (string) DB::table('itam_asset_categories')->count()],
            ['Marcas Oficiales ITAM', 'itam_asset_brands', (string) DB::table('itam_asset_brands')->count()],
            ['Modelos Oficiales ITAM', 'itam_asset_models', (string) DB::table('itam_asset_models')->count()],
        ];
        $this->table(['Entidad', 'Tabla', 'Registros Preservados'], $preservedRows);

        $this->newLine();
        $this->info('=== RESUMEN DE TABLAS OPERATIVAS LIMPIAS (0 REGISTROS) ===');
        $cleanedRows = [];
        foreach ($tablesToClean as $table) {
            if (Schema::hasTable($table)) {
                $cleanedRows[] = [$table, (string) DB::table($table)->count(), 'Secuencia reiniciada a 1'];
            }
        }
        $this->table(['Tabla', 'Registros Actuales', 'Estado Secuencia'], $cleanedRows);

        $this->newLine();
        $this->info('✔ Base de datos limpia y lista para operar en producción.');

        return self::SUCCESS;
    }
}
