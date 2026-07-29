<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('employee_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->unsignedBigInteger('leave_period_id')->nullable();
            $table->enum('transaction_type', [
                'opening_balance',
                'accrual',
                'carryover',
                'approved_leave',
                'leave_cancellation',
                'manual_adjustment_credit',
                'manual_adjustment_debit',
                'balance_expiration'
            ]);
            $table->decimal('quantity', 5, 2);
            $table->date('effective_date');
            $table->unsignedInteger('leave_request_id')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');
            $table->foreign('employee_id')->references('ID')->on('employes')->onDelete('cascade');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->foreign('leave_period_id')->references('id')->on('leave_periods')->onDelete('set null');
            $table->foreign('created_by')->references('ID')->on('administration')->onDelete('set null');

            // NOM COURT pour respecter la limite MySQL de 64 caractères
            $table->unique(['leave_request_id', 'transaction_type'], 'lbt_idempotence');

            $table->index(['company_id', 'employee_id', 'leave_type_id'], 'lbt_main');
            $table->index(['effective_date'], 'lbt_effective');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balance_transactions');
    }
};