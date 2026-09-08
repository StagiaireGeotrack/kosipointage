<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comparaisons_planning', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_detail_id');  // planning_details.id = bigint
            $table->unsignedInteger('pointage_id')->nullable();  // Pointages.ID = int
            $table->time('heure_debut_prevue');
            $table->time('heure_fin_prevue');
            $table->time('heure_debut_reelle')->nullable();
            $table->time('heure_fin_reelle')->nullable();
            $table->integer('ecart_debut_minutes')->nullable();
            $table->integer('ecart_fin_minutes')->nullable();
            $table->integer('ecart_total_minutes')->nullable();
            $table->enum('statut', ['ponctuel','retard','avance','absent','pause_manquante','inconnu'])->default('inconnu');
            $table->date('date_comparaison');
            $table->timestamps();

            $table->foreign('planning_detail_id')->references('id')->on('planning_details')->onDelete('cascade');
            $table->foreign('pointage_id')->references('ID')->on('Pointages')->onDelete('set null');

            $table->unique(['planning_detail_id', 'date_comparaison']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comparaisons_planning');
    }
};
