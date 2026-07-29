<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_meta', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id')->unique(); // sac à métadonnées 1:1
            $table->string('department_name', 255)->nullable();
            $table->string('job_title', 255)->nullable();
            $table->unsignedTinyInteger('hierarchy_level')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('employment_status', ['active', 'suspended', 'left'])->default('active');
            $table->unsignedInteger('company_id'); // = SiegeID
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('employee_id')->references('ID')->on('employes')->onDelete('cascade');
            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');

            $table->index(['company_id', 'employment_status']);
            $table->index(['department_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_meta');
    }
};
