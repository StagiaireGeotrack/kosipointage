<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransitionCongesData extends Command
{
    protected $signature = 'conges:transition';
    protected $description = 'Transforme leave_types en catalogue global et alimente leave_policies';

    public function handle(): void
    {
        $this->info('=== DÉBUT TRANSITION ===');

        // 1. Récupère les types actuels liés à un siège
        $existingTypes = DB::table('leave_types')->whereNotNull('company_id')->get();

        if ($existingTypes->isEmpty()) {
            $this->warn('Aucun type avec company_id trouvé. Déjà en catalogue global ?');
            return;
        }

        $mapping = []; // ancien ID => nouveau ID global

        foreach ($existingTypes as $type) {
            
            // 2. Cherche ou crée le type global (sans company_id)
            $global = DB::table('leave_types')
                ->whereNull('company_id')
                ->where('code', $type->code)
                ->first();

            if ($global) {
                $globalId = $global->id;
                $this->line("Type global existant : {$type->code} => ID {$globalId}");
            } else {
                $globalId = DB::table('leave_types')->insertGetId([
                    'name' => $type->name,
                    'code' => $type->code,
                    'color' => $type->color ?? null,
                    'unit' => $type->unit ?? 'days',
                    'is_active' => $type->is_active ?? true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("✅ Type global créé : {$type->code} => ID {$globalId}");
            }

            $mapping[$type->id] = $globalId;

            // 3. Met à jour leave_policies (rules JSON + nouveau leave_type_id)
            $affected = DB::table('leave_policies')
                ->where('company_id', $type->company_id)
                ->where('leave_type_id', $type->id)
                ->update([
                    'leave_type_id' => $globalId,
                    'rules' => json_encode([
                        'calculation_method' => 'working_days',
                        'reference_schedule' => 'company',
                        'holiday_behavior' => 'exclude',
                        'notice_period_days' => 0,
                        'deducts_balance' => (bool) ($type->deducts_balance ?? true),
                        'requires_attachment' => $type->requires_attachment ?? 'never',
                        'attachment_threshold' => (float) ($type->attachment_threshold ?? 0),
                        'approval_required' => (bool) ($type->approval_required ?? true),
                        'allow_negative_balance' => (bool) ($type->allow_negative_balance ?? false),
                        'negative_limit' => (float) ($type->negative_limit ?? 0),
                        'visibility_level' => $type->visibility_level ?? 'all',
                        'min_notice_days' => 15,
                        'exclude_weekends' => true,
                        'exclude_holidays' => true,
                        'allow_half_day' => true,
                        'min_duration_days' => 0.5,
                        'max_consecutive_days' => 24,
                        'requires_approval_from' => 'manager_then_rh',
                        'max_per_year' => 25,
                        'max_carryover_days' => 5,
                    ]),
                    'is_active' => $type->is_active ?? true,
                    'updated_at' => now(),
                ]);

            $this->line("→ Policies mises à jour pour siège {$type->company_id}, type {$type->code} : {$affected} ligne(s)");
        }

        // 4. Met à jour TOUTES les tables qui ont leave_type_id
        $tables = DB::select("SHOW TABLES");
        $dbName = DB::getDatabaseName();
        $updatedTables = [];

        foreach ($tables as $tableObj) {
            $tableName = array_values((array)$tableObj)[0];
            $hasColumn = DB::select("SHOW COLUMNS FROM `{$tableName}` WHERE Field = 'leave_type_id'");
            
            if (!empty($hasColumn) && $tableName !== 'leave_types') {
                foreach ($mapping as $oldId => $newId) {
                    $count = DB::table($tableName)->where('leave_type_id', $oldId)->update(['leave_type_id' => $newId]);
                    if ($count > 0) {
                        $updatedTables[] = "{$tableName} ({$count})";
                    }
                }
            }
        }

        if (!empty($updatedTables)) {
            $this->info('✅ Tables mises à jour : ' . implode(', ', $updatedTables));
        }

        // 5. Supprime les anciens types spécifiques au siège
        DB::table('leave_types')->whereNotNull('company_id')->delete();
        $this->info('✅ Anciens types spécifiques siège supprimés.');

        $this->info('=== TRANSITION TERMINÉE ===');
        $this->warn('Vérifie en base que leave_types n\'a plus de company_id.');
    }
}