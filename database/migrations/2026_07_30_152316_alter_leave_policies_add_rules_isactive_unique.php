<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leave_policies', function (Blueprint $table) {
            
            // 1. Ajoute rules (JSON)
            if (!Schema::hasColumn('leave_policies', 'rules')) {
                $table->json('rules')->nullable()->after('leave_type_id');
            }

            // 2. Ajoute is_active
            if (!Schema::hasColumn('leave_policies', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('rules');
            }
        });

        // 3. Contrainte unique : on vérifie d'abord si elle existe
        $indexExists = DB::select("SHOW INDEX FROM leave_policies WHERE Key_name = 'leave_policies_company_id_leave_type_id_unique'");
        
        if (empty($indexExists)) {
            Schema::table('leave_policies', function (Blueprint $table) {
                $table->unique(['company_id', 'leave_type_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('leave_policies', function (Blueprint $table) {
            if (Schema::hasColumn('leave_policies', 'rules')) {
                $table->dropColumn('rules');
            }
            if (Schema::hasColumn('leave_policies', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};