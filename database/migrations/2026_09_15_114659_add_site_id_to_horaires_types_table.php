<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('horaires_types', function (Blueprint $table) {
            // Référence vers Entreprises.ID (int unsigned)
            $table->unsignedInteger('site_id')->nullable()->after('poste_id');
            $table->foreign('site_id')
                  ->references('ID')
                  ->on('Entreprises')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('horaires_types', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
