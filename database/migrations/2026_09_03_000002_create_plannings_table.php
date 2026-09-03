<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siege_id');  // Entreprises_sieges.ID = int
            $table->unsignedInteger('service_id')->nullable();  // departments.id = int
            $table->unsignedInteger('poste_id')->nullable();  // job_titles.id = int
            $table->date('date_debut_semaine');
            $table->date('date_fin_semaine');
            $table->string('nom', 255);
            $table->enum('statut', ['brouillon','genere','valide','publie','archive'])->default('brouillon');
            $table->unsignedInteger('cree_par');  // ✅ CORRIGÉ : users.id = bigint
            $table->unsignedInteger('valide_par')->nullable();  // ✅ CORRIGÉ : users.id = bigint
            $table->timestamp('valide_le')->nullable();
            $table->timestamp('publie_le')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('siege_id')->references('ID')->on('Entreprises_sieges')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('poste_id')->references('id')->on('job_titles')->onDelete('set null');
             $table->foreign('cree_par')->references('ID')->on('administration')->onDelete('cascade');
            $table->foreign('valide_par')->references('ID')->on('administration')->onDelete('set null');

            $table->index(['date_debut_semaine', 'date_fin_semaine']);
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plannings');
    }
};
