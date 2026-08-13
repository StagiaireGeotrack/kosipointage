<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Types de congés (globaux site_id=null, ou privés site_id=XX)
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('name');
            $table->string('code', 20);
            $table->enum('unit', ['days', 'half_days', 'hours'])->default('days');
            $table->boolean('deducts_balance')->default(true);
            $table->enum('requires_attachment', ['never', 'always', 'after_duration'])->default('never');
            $table->integer('requires_attachment_after')->nullable();
            $table->boolean('allow_negative_balance')->default(false);
            $table->integer('max_negative_limit')->nullable();
            $table->string('color', 7)->default('#10B981');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['code', 'site_id']);
            $table->index(['site_id', 'is_active']);
        });

        // Activation des types globaux par siège
        Schema::create('leave_type_site_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['site_id', 'leave_type_id']);
        });

        // Règles de décompte
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->enum('counting_method', ['working_days', 'calendar_days', 'hours'])->default('working_days');
            $table->enum('weekend_days', ['saturday_sunday', 'friday_saturday', 'sunday_only'])->default('saturday_sunday');
            $table->boolean('exclude_holidays')->default(true);
            $table->timestamps();
        });

        // Liaison type ↔ politique (une politique peut servir plusieurs types)
        Schema::create('leave_type_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->foreignId('leave_policy_id')->constrained('leave_policies')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['leave_type_id', 'leave_policy_id']);
        });

        // Périodes de référence
        Schema::create('leave_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('allow_rollover')->default(false);
            $table->integer('max_rollover_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['site_id', 'start_date', 'end_date']);
        });

        // Jours fériés / fermetures
        Schema::create('company_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->date('date');
            $table->string('name');
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();

            $table->index(['site_id', 'date']);
        });

        // Circuits de validation
        Schema::create('leave_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->nullable()->constrained('leave_types')->nullOnDelete();
            $table->integer('step_order')->default(1);
            $table->enum('approver_type', ['manager', 'department_head', 'hr', 'specific_user'])->default('manager');
            $table->foreignId('specific_user_id')->nullable()->constrained('employes')->nullOnDelete();
            $table->integer('min_days_trigger')->nullable();
            $table->integer('max_days_trigger')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['site_id', 'leave_type_id', 'step_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_workflows');
        Schema::dropIfExists('company_holidays');
        Schema::dropIfExists('leave_periods');
        Schema::dropIfExists('leave_type_policies');
        Schema::dropIfExists('leave_policies');
        Schema::dropIfExists('leave_type_site_activations');
        Schema::dropIfExists('leave_types');
    }
};