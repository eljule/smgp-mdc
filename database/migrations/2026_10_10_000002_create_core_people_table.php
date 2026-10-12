<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_people', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->index(); // 'PERSONAL' | 'ADMINISTRADO'
            $table->string('document_type', 20)->default('DNI');
            $table->string('document_number', 50)->unique();
            $table->string('first_name', 100);
            $table->string('paternal_surname', 100);
            $table->string('maternal_surname', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 255)->nullable();

            // Personal Municipal
            $table->string('position', 150)->nullable();
            $table->string('office', 150)->nullable();
            $table->string('labor_condition', 100)->nullable();
            $table->string('entry_date', 30)->nullable();

            // Administrado
            $table->string('business_name', 255)->nullable();
            $table->boolean('allows_online_access')->default(false);

            // Situación Laboral
            $table->string('labor_status', 30)->default('ACTIVO'); // 'ACTIVO' | 'CESADO'
            $table->string('departure_date', 30)->nullable();
            $table->string('cessation_reason', 255)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_people');
    }
};
