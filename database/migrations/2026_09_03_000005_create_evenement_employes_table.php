<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evenement_employes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evenement_id');  // evenements_planning.id = bigint
            $table->unsignedInteger('employe_id');  // Employes.ID = int
            $table->timestamps();

            $table->foreign('evenement_id')->references('id')->on('evenements_planning')->onDelete('cascade');
            $table->foreign('employe_id')->references('ID')->on('Employes')->onDelete('cascade');

            $table->unique(['evenement_id', 'employe_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('evenement_employes');
    }
};
