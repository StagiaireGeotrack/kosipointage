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
        Schema::create('seller_sieges', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->unsignedInteger('SellerID');
            $table->unsignedInteger('SiegeID')->index('seller_sieges_siegeid_foreign');
            $table->dateTime('CreatedAt', 6)->useCurrent();

            $table->unique(['SellerID', 'SiegeID'], 'unique_seller_siege');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_sieges');
    }
};
