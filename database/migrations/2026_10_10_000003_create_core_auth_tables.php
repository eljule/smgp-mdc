<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_roles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('subsystem_code', 100)->default('GLOBAL')->index();
            $table->boolean('is_system')->default(false);
            $table->jsonb('permission_codes')->default('[]');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('core_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('subsystem_code', 100)->index();
            $table->string('category', 100)->default('GENERAL');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('core_users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('password', 255);
            $table->string('full_name', 255);
            $table->string('email', 255)->nullable();
            $table->string('role', 100)->default('Operador');
            $table->jsonb('allowed_modules')->default('[]');
            $table->boolean('is_active')->default(true);
            $table->foreignId('person_id')->nullable()->constrained('core_people')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_users');
        Schema::dropIfExists('core_permissions');
        Schema::dropIfExists('core_roles');
    }
};
