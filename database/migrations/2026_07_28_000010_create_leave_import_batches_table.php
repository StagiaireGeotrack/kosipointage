<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_import_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('batch_number', 50)->unique();
            $table->enum('type', ['opening_balances', 'historical_leaves']);
            $table->enum('status', ['simulated', 'imported', 'cancelled'])->default('simulated');
            $table->string('file_name', 255);
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('success_rows')->default(0);
            $table->unsignedInteger('error_rows')->default(0);
            $table->json('errors_json')->nullable();
            $table->unsignedInteger('imported_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');
            $table->foreign('imported_by')->references('ID')->on('administration')->onDelete('set null');

            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_import_batches');
    }
};
