<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.s
     */
    public function up(): void
    {
        // La colonne SiegeID n'existe plus dans la table leave_types.
        // Elle a été supprimée par la migration
        // 2026_07_30_161021_clean_leave_types_columns.php.
        //
        // Cette migration ne doit donc effectuer aucune modification.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à restaurer.
    }
};
