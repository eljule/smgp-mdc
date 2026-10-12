<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helpdesk_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 30)->unique();
            $table->foreignId('applicant_id')->nullable()->constrained('core_people')->nullOnDelete();
            $table->string('applicant_name', 150);
            $table->string('applicant_phone', 50)->nullable();
            $table->string('applicant_email', 120)->nullable();
            $table->foreignId('office_id')->nullable()->constrained('core_offices')->nullOnDelete();
            $table->string('office_name', 150);
            $table->foreignId('asset_id')->nullable()->constrained('itam_assets')->nullOnDelete();
            $table->string('asset_computer_code', 50)->nullable();
            $table->string('asset_category', 80)->nullable();
            $table->string('category', 50)->default('HARDWARE');
            $table->string('priority', 30)->default('MEDIA');
            $table->string('subject', 250);
            $table->text('description');
            $table->text('evidence_photo_url')->nullable();
            $table->string('status', 30)->default('ABIERTO');
            $table->foreignId('assigned_technician_id')->nullable()->constrained('core_users')->nullOnDelete();
            $table->string('assigned_technician_name', 150)->nullable();
            $table->string('assigned_technician_phone', 50)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->boolean('user_conformity')->nullable();
            $table->integer('user_rating')->nullable();
            $table->text('user_feedback')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->string('locked_by', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('helpdesk_ticket_technical_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('helpdesk_tickets')->cascadeOnDelete();
            $table->string('confirmed_category', 50)->nullable();
            $table->text('real_diagnosis')->nullable();
            $table->text('solution_applied')->nullable();
            $table->text('pause_reason')->nullable();
            $table->boolean('is_definitive_decommission')->default(false);
            $table->string('decommission_act_number', 50)->nullable();
            $table->text('decommission_reason')->nullable();
            $table->string('decommission_destination', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('helpdesk_ticket_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('helpdesk_tickets')->cascadeOnDelete();
            $table->foreignId('supply_id')->constrained('itam_supplies')->cascadeOnDelete();
            $table->string('supply_code', 50);
            $table->string('supply_name', 150);
            $table->integer('quantity')->default(1);
            $table->string('unit', 30)->default('UND');
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('helpdesk_ticket_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('helpdesk_tickets')->cascadeOnDelete();
            $table->foreignId('temporary_asset_id')->constrained('itam_assets')->restrictOnDelete();
            $table->string('temporary_asset_code', 50);
            $table->string('temporary_asset_name', 150);
            $table->foreignId('damaged_asset_id')->nullable()->constrained('itam_assets')->nullOnDelete();
            $table->string('damaged_asset_code', 50)->nullable();
            $table->string('status', 30)->default('PRESTADO');
            $table->string('loan_date', 30)->nullable();
            $table->string('return_date', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('helpdesk_ticket_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('helpdesk_tickets')->cascadeOnDelete();
            $table->string('previous_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->string('action', 60);
            $table->foreignId('performed_by_user_id')->nullable()->constrained('core_users')->nullOnDelete();
            $table->string('performed_by_user_name', 150);
            $table->text('notes')->nullable();
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();
        });

        Schema::create('helpdesk_knowledge_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 250);
            $table->string('category', 50)->default('HARDWARE');
            $table->text('summary');
            $table->text('symptoms')->nullable();
            $table->text('solution_steps');
            $table->jsonb('tags')->default('[]');
            $table->foreignId('source_ticket_id')->nullable()->constrained('helpdesk_tickets')->nullOnDelete();
            $table->string('source_ticket_number', 30)->nullable();
            $table->foreignId('author_technician_id')->nullable()->constrained('core_users')->nullOnDelete();
            $table->string('author_technician_name', 150);
            $table->integer('views_count')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('helpdesk_knowledge_articles');
        Schema::dropIfExists('helpdesk_ticket_audit_logs');
        Schema::dropIfExists('helpdesk_ticket_loans');
        Schema::dropIfExists('helpdesk_ticket_supplies');
        Schema::dropIfExists('helpdesk_ticket_technical_details');
        Schema::dropIfExists('helpdesk_tickets');
    }
};
