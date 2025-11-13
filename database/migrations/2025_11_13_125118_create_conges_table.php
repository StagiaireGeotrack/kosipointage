<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id')->index('fk_conges_employee');
            $table->unsignedInteger('SiegeID')->nullable()->index('fk_sieges_conges');
            $table->dateTime('date_debut', 6);
            $table->dateTime('date_fin', 6);
            $table->string('type_conge', 25);
            $table->text('commentaire')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};
