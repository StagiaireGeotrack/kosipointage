<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evenements_planning', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('siege_id');  // Entreprises_sieges.ID = int
            $table->unsignedInteger('service_id')->nullable();  // departments.id = int
            $table->unsignedInteger('poste_id')->nullable();  // job_titles.id = int
            $table->string('titre', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['formation','deplacement','reunion','conges_exceptionnel','autre']);
            $table->dateTime('debut');
            $table->dateTime('fin');
            $table->boolean('toute_la_journee')->default(false);
            $table->string('couleur', 50)->nullable();
            $table->unsignedInteger('cree_par');  // ✅ CORRIGÉ : users.id = bigint
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('siege_id')->references('ID')->on('Entreprises_sieges')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('poste_id')->references('id')->on('job_titles')->onDelete('set null');
            $table->foreign('cree_par')->references('ID')->on('administration')->onDelete('cascade');

            $table->index(['debut', 'fin']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('evenements_planning');
    }
};
