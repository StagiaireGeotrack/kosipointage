<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter la colonne IsSeller
        Schema::table('administration', function (Blueprint $table) {
            $table->boolean('IsSeller')->default(0)->after('IsSuperAdmin');
        });

        // Créer la table seller_sieges
        Schema::create('seller_sieges', function (Blueprint $table) {
            $table->id('ID');
            $table->unsignedInteger('SellerID');
            $table->unsignedInteger('SiegeID');
            $table->dateTime('CreatedAt', 6)->useCurrent();

            $table->foreign('SellerID')
                  ->references('ID')
                  ->on('administration')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('SiegeID')
                  ->references('ID')
                  ->on('Entreprises_sieges')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->unique(['SellerID', 'SiegeID'], 'unique_seller_siege');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_sieges');
        
        Schema::table('administration', function (Blueprint $table) {
            $table->dropColumn('IsSeller');
        });
    }
};
