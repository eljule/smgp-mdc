<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('core_modules', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 100);
            $table->text('description');
            $table->string('icon_url', 255);
            $table->string('route', 255);
            $table->string('accent_color', 50);
            $table->string('status', 30)->default('active');
            $table->string('status_message', 255)->nullable();
            $table->string('wait_time', 100)->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_auth')->default(true);
            $table->json('secondary_action')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'order'], 'core_modules_active_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_modules');
    }
};
