<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conge_validations', function (Blueprint $table) {
            $table->text('raison_rejection')
                  ->nullable()
                  ->after('raison')
                  ->comment('Motif du refus (renseigné si status = not_validated)');
        });
    }

    public function down(): void
    {
        Schema::table('conge_validations', function (Blueprint $table) {
            $table->dropColumn('raison_rejection');
        });
    }
};
