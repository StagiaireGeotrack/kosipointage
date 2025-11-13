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
        Schema::create('employes', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('Nom');
            $table->string('BadgeID', 25);
            $table->boolean('HasBiometricSetup')->default(false);
            $table->boolean('HasFaceSetup')->default(false);
            $table->longText('FaceEncodingPath')->nullable();
            $table->text('Pin')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->boolean('Actived')->default(false);
            $table->unsignedInteger('SiegeID')->index('employes_siegeid_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
