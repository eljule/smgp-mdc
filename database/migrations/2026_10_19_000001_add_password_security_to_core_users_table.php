<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('core_users', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false)->after('password');
            $table->timestamp('password_changed_at')->nullable()->after('must_change_password');
            $table->unsignedInteger('failed_login_attempts')->default(0)->after('password_changed_at');
            $table->boolean('is_locked')->default(false)->after('failed_login_attempts');
            $table->timestamp('locked_at')->nullable()->after('is_locked');
        });
    }

    public function down(): void
    {
        Schema::table('core_users', function (Blueprint $table) {
            $table->dropColumn([
                'must_change_password',
                'password_changed_at',
                'failed_login_attempts',
                'is_locked',
                'locked_at',
            ]);
        });
    }
};
