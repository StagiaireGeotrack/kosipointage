<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Désactive temporairement les contraintes FK
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        Schema::table('leave_types', function (Blueprint $table) {
            if (Schema::hasColumn('leave_types', 'company_id')) {
                $table->dropColumn('company_id');
            }
            
            $toDrop = [
                'entreprise_siege_id',
                'deducts_balance',
                'requires_attachment',
                'attachment_threshold',
                'approval_required',
                'allow_negative_balance',
                'negative_limit',
                'visibility_level',
            ];
            
            foreach ($toDrop as $col) {
                if (Schema::hasColumn('leave_types', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        
        // Réactive les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
    
    public function down(): void
    {
        // Restaure ton backup SQL si besoin
    }
};