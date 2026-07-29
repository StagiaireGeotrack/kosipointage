<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->string('name', 255);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('booking_deadline')->nullable();
            $table->boolean('carryover_allowed')->default(false);
            $table->decimal('carryover_limit', 5, 2)->default(0);
            $table->date('carryover_expiration_date')->nullable();
            $table->enum('status', ['draft', 'open', 'closed', 'archived'])->default('draft');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');

            $table->index(['company_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_periods');
    }
};
