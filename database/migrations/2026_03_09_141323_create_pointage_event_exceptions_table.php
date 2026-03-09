<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pointage_event_exceptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->enum('error_type', ['pointage_jour_ferie', 'pointage_weekend']);
            $table->unsignedInteger('SiegeID');
            $table->unsignedBigInteger('acknowledged_by');
            $table->dateTime('acknowledged_at');
            $table->text('note')->nullable();

            $table->unique(['employee_id', 'date', 'error_type'], 'unique_exception');
            $table->index('SiegeID');
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pointage_event_exceptions');
    }
};
