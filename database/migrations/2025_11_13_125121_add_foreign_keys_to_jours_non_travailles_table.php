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
        Schema::table('jours_non_travailles', function (Blueprint $table) {
            $table->foreign(['SiegeID'])->references(['ID'])->on('entreprises_sieges')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jours_non_travailles', function (Blueprint $table) {
            $table->dropForeign('jours_non_travailles_siegeid_foreign');
        });
    }
};
