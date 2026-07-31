<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            // 1. Supprimer d'abord la clé étrangère
            $table->dropForeign(['company_id']);
            
            // 2. Modifier la colonne pour la rendre nullable
            $table->unsignedBigInteger('company_id')->nullable()->change();
            
            // 3. (Optionnel) Recréer la clé étrangère en nullable
            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            // Annulation : remettre NOT NULL et recréer la FK
            $table->dropForeign(['company_id']);
            $table->unsignedBigInteger('company_id')->nullable(false)->change();
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }
};