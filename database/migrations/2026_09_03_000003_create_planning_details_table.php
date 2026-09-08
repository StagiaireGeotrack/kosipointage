<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planning_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_id');  // plannings.id = bigint
            $table->unsignedInteger('employe_id');  // Employes.ID = int
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->time('pause_debut')->nullable();
            $table->time('pause_fin')->nullable();
            $table->time('deuxieme_debut')->nullable();
            $table->time('deuxieme_fin')->nullable();
            $table->text('commentaire')->nullable();
            $table->enum('statut', ['planifie','confirme','effectue','annule','absent'])->default('planifie');
            $table->timestamps();

            $table->foreign('planning_id')->references('id')->on('plannings')->onDelete('cascade');
            $table->foreign('employe_id')->references('ID')->on('Employes')->onDelete('cascade');

            $table->unique(['planning_id', 'employe_id', 'date']);
            $table->index(['employe_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_details');
    }
};
