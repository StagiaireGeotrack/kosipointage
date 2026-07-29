<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_managers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('manager_id');
            $table->unsignedInteger('company_id'); // = SiegeID
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('employee_id')->references('ID')->on('employes')->onDelete('cascade');
            $table->foreign('manager_id')->references('ID')->on('employes')->onDelete('cascade');
            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');

            $table->index(['company_id', 'is_active']);
            $table->index(['employee_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_managers');
    }
};
