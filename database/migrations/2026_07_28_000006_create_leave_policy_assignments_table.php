<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_policy_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_id');
            $table->enum('target_type', ['company', 'department', 'employee']);
            $table->unsignedInteger('target_id'); // ID du siège, département ou employé
            $table->unsignedInteger('company_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('policy_id')->references('id')->on('leave_policies')->onDelete('cascade');
            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');

            $table->index(['company_id', 'target_type', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_policy_assignments');
    }
};
