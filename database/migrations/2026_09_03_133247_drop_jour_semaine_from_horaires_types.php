<?php
// database/migrations/2026_09_03_000008_drop_jour_semaine_from_horaires_types.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('horaires_types', function (Blueprint $table) {
            // Supprimer la colonne jour_semaine
            $table->dropColumn('jour_semaine');
        });
    }

    public function down()
    {
        Schema::table('horaires_types', function (Blueprint $table) {
            $table->enum('jour_semaine', [
                'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'
            ])->after('poste_id');
        });
    }
};