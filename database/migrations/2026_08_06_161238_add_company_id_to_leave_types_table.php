<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            // company_id : null = global, sinon = ID du siège
            if (!Schema::hasColumn('leave_types', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('id');
            }

            // created_by : pour savoir qui a créé le type
            if (!Schema::hasColumn('leave_types', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('company_id');
            }

            // Supprime l'ancienne contrainte unique sur 'code' si elle existe
            try {
                $table->dropUnique(['code']);
            } catch (\Throwable $e) {
                // ignore si elle n'existe pas
            }
        });
    }

    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            if (Schema::hasColumn('leave_types', 'company_id')) {
                $table->dropColumn('company_id');
            }
            if (Schema::hasColumn('leave_types', 'created_by')) {
                $table->dropColumn('created_by');
            }
        });
    }
};