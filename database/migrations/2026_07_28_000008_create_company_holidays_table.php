<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_holidays', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->date('date');
            $table->string('name', 255);
            $table->enum('type', ['public_holiday', 'company_closure'])->default('public_holiday');
            $table->boolean('is_recurrent')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('company_id')->references('ID')->on('entreprises_sieges')->onDelete('cascade');

            $table->index(['company_id', 'date']);
            $table->index(['company_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_holidays');
    }
};
