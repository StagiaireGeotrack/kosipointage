<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->enum('calculation_method', ['working_days', 'calendar_days', 'scheduled_hours'])->default('working_days');
            $table->enum('reference_schedule', ['individual', 'department', 'company'])->default('company');
            $table->enum('holiday_behavior', ['exclude', 'include'])->default('exclude');
            $table->time('half_day_morning_start')->nullable();
            $table->time('half_day_afternoon_start')->nullable();
            $table->enum('rounding_method', ['none', 'quarter', 'half'])->default('none');
            $table->unsignedSmallInteger('max_simultaneous_absents')->nullable();
            $table->unsignedSmallInteger('min_remaining_staff')->nullable();
            $table->enum('alert_type', ['blocking', 'informative'])->default('informative');
            $table->unsignedSmallInteger('notice_period_days')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');

            $table->index(['company_id', 'leave_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_policies');
    }
};
