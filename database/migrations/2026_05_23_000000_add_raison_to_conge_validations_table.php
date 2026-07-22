<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conge_validations', function (Blueprint $table) {
            $table->text('raison')
                  ->nullable()
                  ->after('status')
                  ->comment('Raison / motif du congé (optionnel)');
        });
    }

    public function down(): void
    {
        Schema::table('conge_validations', function (Blueprint $table) {
            $table->dropColumn('raison');
        });
    }
};
