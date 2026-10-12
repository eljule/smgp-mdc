<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itam_asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('icon', 30)->default('💻');
            $table->string('color', 20)->default('#2563eb');
            $table->jsonb('custom_fields_schema')->default('[]');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('itam_asset_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('itam_asset_models', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->foreignId('brand_id')->constrained('itam_asset_brands')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('itam_asset_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('itam_assets', function (Blueprint $table) {
            $table->id();
            $table->string('computer_code', 60)->unique();
            $table->string('patrimonial_code', 60)->nullable()->index();
            $table->string('serial_number', 100)->nullable();
            $table->foreignId('category_id')->constrained('itam_asset_categories')->restrictOnDelete();
            $table->foreignId('brand_id')->constrained('itam_asset_brands')->restrictOnDelete();
            $table->foreignId('model_id')->constrained('itam_asset_models')->restrictOnDelete();
            $table->foreignId('parent_asset_id')->nullable()->constrained('itam_assets')->nullOnDelete();
            $table->string('color', 40)->nullable();
            $table->string('status', 30)->default('OPERATIVO'); // OPERATIVO, EN_MANTENIMIENTO, INOPERATIVO, PRESTADO, BAJA
            $table->string('physical_condition', 30)->default('BUENO'); // NUEVO, BUENO, REGULAR, MALO
            $table->string('office', 150)->nullable();
            $table->foreignId('assigned_person_id')->nullable()->constrained('core_people')->nullOnDelete();
            $table->string('acquisition_date', 20)->nullable();
            $table->string('supplier', 150)->nullable();
            $table->string('warranty_end_date', 20)->nullable();
            $table->boolean('is_loanable')->default(false);
            $table->jsonb('specifications')->default('{}');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('itam_asset_movements', function (Blueprint $table) {
            $table->id();
            $table->string('acta_number', 50)->unique();
            $table->foreignId('asset_id')->constrained('itam_assets')->cascadeOnDelete();
            $table->string('from_office', 150)->nullable();
            $table->string('to_office', 150);
            $table->foreignId('from_person_id')->nullable()->constrained('core_people')->nullOnDelete();
            $table->foreignId('to_person_id')->nullable()->constrained('core_people')->nullOnDelete();
            $table->string('movement_date', 20);
            $table->string('movement_type', 40)->default('TRANSFERENCIA');
            $table->text('reason');
            $table->string('technician_name', 150)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('include_children_in_transfer')->default(false);
            $table->timestamps();
        });

        Schema::create('itam_asset_loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code', 50)->unique();
            $table->string('loan_number', 50)->nullable();
            $table->string('requesting_office', 150);
            $table->foreignId('requesting_person_id')->nullable()->constrained('core_people')->nullOnDelete();
            $table->string('borrower_name', 150);
            $table->string('start_date', 30);
            $table->string('expected_return_date', 30);
            $table->string('actual_return_date', 30)->nullable();
            $table->text('event_reason');
            $table->string('status', 30)->default('RESERVADO'); // RESERVADO, ENTREGADO, DEVUELTO, VENCIDO
            $table->string('return_condition', 50)->nullable();
            $table->string('technician_name', 150)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('itam_asset_loan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('itam_asset_loans')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('itam_assets')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('itam_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->string('category', 50)->default('TONER_TINTA');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(2);
            $table->string('unit', 30)->default('UNIDAD');
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->string('location', 100)->nullable();
            $table->text('compatible_models')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('itam_maintenance_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('order_number', 50)->nullable();
            $table->foreignId('asset_id')->constrained('itam_assets')->cascadeOnDelete();
            $table->string('type', 30)->default('PREVENTIVO');
            $table->string('status', 30)->default('PROGRAMADO');
            $table->string('priority', 20)->default('MEDIA');
            $table->string('scheduled_date', 20);
            $table->string('completed_date', 20)->nullable();
            $table->string('technician_name', 150)->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('activities_performed')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('itam_maintenance_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_order_id')->constrained('itam_maintenance_orders')->cascadeOnDelete();
            $table->foreignId('supply_id')->constrained('itam_supplies')->restrictOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('itam_software', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('version', 50)->nullable();
            $table->string('developer', 100)->nullable();
            $table->string('license_type', 40)->default('OEM');
            $table->string('license_key', 255)->nullable();
            $table->integer('total_licenses')->default(1);
            $table->string('expiration_date', 20)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('itam_asset_software', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('itam_assets')->cascadeOnDelete();
            $table->foreignId('software_id')->constrained('itam_software')->cascadeOnDelete();
            $table->string('license_key_used', 255)->nullable();
            $table->timestamp('installed_at')->useCurrent();
            $table->timestamps();
            $table->unique(['asset_id', 'software_id']);
        });

        Schema::create('itam_inventory_audits', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index();
            $table->string('title', 150);
            $table->string('status', 30)->default('EN_PROCESO');
            $table->string('start_date', 20);
            $table->string('close_date', 20)->nullable();
            $table->jsonb('snapshot_data')->default('{}');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('itam_audit_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('itam_inventory_audits')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('itam_assets')->cascadeOnDelete();
            $table->string('expected_office', 150)->nullable();
            $table->string('found_office', 150)->nullable();
            $table->string('status', 40)->default('CONCILIADO');
            $table->string('verified_by', 150)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['audit_id', 'asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itam_audit_verifications');
        Schema::dropIfExists('itam_inventory_audits');
        Schema::dropIfExists('itam_asset_software');
        Schema::dropIfExists('itam_software');
        Schema::dropIfExists('itam_maintenance_supplies');
        Schema::dropIfExists('itam_maintenance_orders');
        Schema::dropIfExists('itam_supplies');
        Schema::dropIfExists('itam_asset_loan_items');
        Schema::dropIfExists('itam_asset_loans');
        Schema::dropIfExists('itam_asset_movements');
        Schema::dropIfExists('itam_assets');
        Schema::dropIfExists('itam_asset_models');
        Schema::dropIfExists('itam_asset_brands');
        Schema::dropIfExists('itam_asset_categories');
    }
};
