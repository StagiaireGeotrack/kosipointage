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
        Schema::table('pointages', function (Blueprint $table) {
            $table->foreign(['company_id'], 'fk_pointages_company')->references(['ID'])->on('entreprises')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['employee_id'])->references(['ID'])->on('employes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['SiegeID'])->references(['ID'])->on('entreprises_sieges')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pointages', function (Blueprint $table) {
            $table->dropForeign('fk_pointages_company');
            $table->dropForeign('pointages_employee_id_foreign');
            $table->dropForeign('pointages_siegeid_foreign');
        });
    }
};
