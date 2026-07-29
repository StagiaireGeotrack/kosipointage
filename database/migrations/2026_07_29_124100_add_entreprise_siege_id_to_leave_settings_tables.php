<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On vérifie avant d'ajouter la colonne sur leave_types
        if (Schema::hasTable('leave_types') && !Schema::hasColumn('leave_types', 'entreprise_siege_id')) {
            Schema::table('leave_types', function (Blueprint $table) {
                $table->foreignId('entreprise_siege_id')->nullable()->after('id')->constrained('entreprise_sieges')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('leave_types', 'entreprise_siege_id')) {
            Schema::table('leave_types', function (Blueprint $table) {
                $table->dropForeign(['entreprise_siege_id']);
                $table->dropColumn('entreprise_siege_id');
            });
        }
    }
};