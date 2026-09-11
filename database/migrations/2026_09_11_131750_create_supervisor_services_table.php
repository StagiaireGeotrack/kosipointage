<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisor_services', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();

            // ⚠️ unsignedInteger OBLIGATOIRE (car administration.ID = int(10) unsigned)
            $table->unsignedInteger('admin_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('created_by')->nullable();

            $table->timestamp('created_at')->nullable();

            // Index + contraintes
            $table->unique(['admin_id', 'service_id'], 'uniq_supervisor_service');
            $table->index('admin_id');
            $table->index('service_id');

            // Foreign keys (types compatibles)
            $table->foreign('admin_id')
                  ->references('ID')
                  ->on('administration')
                  ->onDelete('cascade');

            $table->foreign('service_id')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('cascade');

            $table->foreign('created_by')
                  ->references('ID')
                  ->on('administration')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisor_services');
    }
};
