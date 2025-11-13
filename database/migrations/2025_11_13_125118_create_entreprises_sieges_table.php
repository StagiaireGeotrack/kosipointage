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
        Schema::create('entreprises_sieges', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('Nom');
            $table->text('Nom_Lieu_Ville')->nullable();
            $table->boolean('Actived')->default(false);
            $table->timestamp('CreatedAt')->useCurrent();
            $table->text('Pays')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprises_sieges');
    }
};
