<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajouter la colonne SiegeID (nullable pour ne pas bloquer les lignes existantes)
        // Supprimer la colonne si elle existe déjà (ex: tentative précédente avec mauvais type)
        if (Schema::hasColumn('conge_validations', 'SiegeID')) {
            Schema::table('conge_validations', function (Blueprint $table) {
                $table->dropColumn('SiegeID');
            });
        }

        Schema::table('conge_validations', function (Blueprint $table) {
            $table->unsignedInteger('SiegeID')
                  ->nullable()
                  ->after('telephone')
                  ->index()
                  ->comment('Référence vers Entreprises_sieges (géré au niveau applicatif)');
        });

        // 2. Mettre à jour les 5 lignes existantes avec des SiegeID cohérents
        //    IDs disponibles : 1=Geotrack | 2=Run-Telemat | 3=ISLAND FOOD | 4=PRO ELEC SARL
        $updates = [
            1 => 1,  // Jean-Marc Dupont   → Geotrack Solution SIEGE
            2 => 2,  // Marie Kouassi      → Run-Telemat SIEGE
            3 => 3,  // Franck Nguyen      → ISLAND FOOD
            4 => 1,  // Aïcha Traoré       → Geotrack Solution SIEGE
            5 => 4,  // Stéphane Bernard   → PRO ELEC SARL
        ];

        foreach ($updates as $id => $siegeId) {
            DB::table('conge_validations')
                ->where('id', $id)
                ->update(['SiegeID' => $siegeId]);
        }
    }

    public function down(): void
    {
        Schema::table('conge_validations', function (Blueprint $table) {
            $table->dropIndex(['SiegeID']);
            $table->dropColumn('SiegeID');
        });
    }
};
