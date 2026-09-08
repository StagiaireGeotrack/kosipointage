<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horaires_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('poste_id');  // job_titles.id = int
            $table->enum('jour_semaine', ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->time('pause_debut')->nullable();
            $table->time('pause_fin')->nullable();
            $table->time('deuxieme_debut')->nullable();
            $table->time('deuxieme_fin')->nullable();
            $table->boolean('par_defaut')->default(true);
            $table->unsignedInteger('cree_par');  // ✅ CORRIGÉ : users.id = bigint
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('poste_id')->references('id')->on('job_titles')->onDelete('cascade');
            $table->foreign('cree_par')->references('ID')->on('administration')->onDelete('cascade');
            $table->unique(['poste_id', 'jour_semaine']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('horaires_types');
    }
};
