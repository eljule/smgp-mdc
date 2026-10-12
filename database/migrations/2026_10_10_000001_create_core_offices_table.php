<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_offices', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('acronym', 30)->index();
            $table->string('name', 255);
            $table->string('parent_name', 255)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('core_offices')->nullOnDelete();
            $table->string('sede', 120)->default('PALACIO MUNICIPAL (SEDE PRINCIPAL)')->index();
            $table->integer('level')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_offices');
    }
};
