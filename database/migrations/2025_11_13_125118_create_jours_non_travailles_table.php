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
        Schema::create('jours_non_travailles', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->date('Date');
            $table->string('Nom');
            $table->enum('Type', ['ferie', 'fermeture', 'autre'])->default('ferie');
            $table->unsignedInteger('SiegeID')->nullable()->index('jours_non_travailles_siegeid_foreign');
            $table->boolean('Recurrent')->default(false);
            $table->text('Description')->nullable();
            $table->boolean('Actived')->default(true)->index();
            $table->timestamps();

            $table->index(['Date', 'SiegeID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jours_non_travailles');
    }
};
