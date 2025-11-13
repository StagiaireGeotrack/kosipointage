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
        Schema::create('jours_non_travailles', function ($table) {
            $table->id('ID');
            $table->date('Date');
            $table->string('Nom', 255);
            $table->enum('Type', ['ferie', 'fermeture', 'autre'])->default('ferie');
            
            // ✅ CORRECTION : Utiliser le même type que la clé primaire de Entreprises_sieges
            // Si Entreprises_sieges utilise $table->id('ID'), alors c'est un unsignedBigInteger
            // Si c'est un $table->increments('ID'), alors c'est un unsignedInteger
            $table->unsignedInteger('SiegeID')->nullable(); // ← Changé de unsignedBigInteger à unsignedInteger
            
            $table->boolean('Recurrent')->default(false);
            $table->text('Description')->nullable();
            $table->boolean('Actived')->default(true);
            $table->timestamps();
            
            // ✅ Ajouter la clé étrangère
            $table->foreign('SiegeID')
                  ->references('ID')
                  ->on('Entreprises_sieges')
                  ->onDelete('cascade');
            
            $table->index(['Date', 'SiegeID']);
            $table->index('Actived');
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
