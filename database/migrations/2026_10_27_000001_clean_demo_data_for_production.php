<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Limpia los datos de prueba/demo en ITAM y Helpdesk,
     * dejando intactos los datos administrativos originales y los catálogos maestros de ITAM
     * (categorías con esquemas dinámicos, marcas y modelos) listos para la operación en producción.
     */
    public function up(): void
    {
        $isPgsql = DB::getDriverName() === 'pgsql';

        if ($isPgsql) {
            DB::statement('SET session_replication_role = replica;');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        // 1. Tablas operativas y dependientes de Helpdesk
        if (Schema::hasTable('helpdesk_ticket_supplies')) {
            DB::table('helpdesk_ticket_supplies')->delete();
        }
        if (Schema::hasTable('helpdesk_ticket_loans')) {
            DB::table('helpdesk_ticket_loans')->delete();
        }
        if (Schema::hasTable('helpdesk_ticket_audit_logs')) {
            DB::table('helpdesk_ticket_audit_logs')->delete();
        }
        if (Schema::hasTable('helpdesk_ticket_technical_details')) {
            DB::table('helpdesk_ticket_technical_details')->delete();
        }
        if (Schema::hasTable('helpdesk_knowledge_articles')) {
            DB::table('helpdesk_knowledge_articles')->delete();
        }
        if (Schema::hasTable('helpdesk_tickets')) {
            DB::table('helpdesk_tickets')->delete();
        }

        // 2. Tablas operativas y dependientes de ITAM
        if (Schema::hasTable('itam_maintenance_supplies')) {
            DB::table('itam_maintenance_supplies')->delete();
        }
        if (Schema::hasTable('itam_maintenance_orders')) {
            DB::table('itam_maintenance_orders')->delete();
        }
        if (Schema::hasTable('itam_audit_verifications')) {
            DB::table('itam_audit_verifications')->delete();
        }
        if (Schema::hasTable('itam_inventory_audits')) {
            DB::table('itam_inventory_audits')->delete();
        }
        if (Schema::hasTable('itam_asset_movements')) {
            DB::table('itam_asset_movements')->delete();
        }
        if (Schema::hasTable('itam_asset_loan_items')) {
            DB::table('itam_asset_loan_items')->delete();
        }
        if (Schema::hasTable('itam_asset_loans')) {
            DB::table('itam_asset_loans')->delete();
        }
        if (Schema::hasTable('itam_asset_software')) {
            DB::table('itam_asset_software')->delete();
        }
        if (Schema::hasTable('itam_software')) {
            DB::table('itam_software')->delete();
        }
        if (Schema::hasTable('itam_assets')) {
            DB::table('itam_assets')->update(['parent_asset_id' => null]);
            DB::table('itam_assets')->delete();
        }
        if (Schema::hasTable('itam_supplies')) {
            DB::table('itam_supplies')->delete();
        }

        // 3. Reinicio de secuencias en PostgreSQL
        if ($isPgsql) {
            $tablesWithSeq = [
                'helpdesk_tickets',
                'helpdesk_ticket_technical_details',
                'helpdesk_ticket_audit_logs',
                'helpdesk_ticket_loans',
                'helpdesk_ticket_supplies',
                'helpdesk_knowledge_articles',
                'itam_assets',
                'itam_asset_loans',
                'itam_asset_loan_items',
                'itam_asset_movements',
                'itam_software',
                'itam_inventory_audits',
                'itam_audit_verifications',
                'itam_maintenance_orders',
                'itam_maintenance_supplies',
                'itam_supplies',
            ];

            foreach ($tablesWithSeq as $tableName) {
                if (Schema::hasTable($tableName)) {
                    DB::statement("SELECT setval(pg_get_serial_sequence('{$tableName}', 'id'), 1, false);");
                }
            }

            DB::statement('SET session_replication_role = DEFAULT;');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpieza de datos demo para pase a producción.
    }
};
