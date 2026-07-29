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
            $table->unsignedInteger('admin_id');
            $table->enum('role', ['superadmin', 'company_admin', 'rh', 'manager', 'direction']);
            $table->unsignedInteger('company_id')->nullable(); // null = global (superadmin)
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('admin_id')->references('ID')->on('administration')->onDelete('cascade');

            $table->index(['admin_id', 'is_active']);
            $table->index(['company_id', 'role', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
    }
};
