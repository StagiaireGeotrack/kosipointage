<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            
            // Correction : unsignedBigInteger pour matcher la clé primaire ID de "administration"
            $table->unsignedBigInteger('admin_id');
            $table->enum('role', ['superadmin', 'company_admin', 'rh', 'manager', 'direction']);
            $table->unsignedBigInteger('company_id')->nullable(); // null = global (superadmin)
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Contrainte de clé étrangère
            $table->foreign('admin_id')->references('ID')->on('administration')->onDelete('cascade');

            // Index
            $table->index(['admin_id', 'is_active']);
            $table->index(['company_id', 'role', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
    }
};