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
        Schema::create('entreprises', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('Nom');
            $table->longText('Logo')->nullable();
            $table->text('Nom_Lieu_Ville')->nullable();
            $table->decimal('Latitude', 18, 8);
            $table->decimal('Longitude', 18, 8);
            $table->decimal('RadiusInMeters', 18, 8)->default(10);
            $table->dateTime('CreatedAt')->useCurrent();
            $table->boolean('Actived')->default(false);
            $table->unsignedInteger('SiegeID')->index('entreprises_siegeid_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
