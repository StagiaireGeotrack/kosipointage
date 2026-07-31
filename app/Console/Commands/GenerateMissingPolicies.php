<?php

namespace App\Console\Commands;

use App\Models\LeavePolicy;
use App\Models\LeaveType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMissingPolicies extends Command
{
    protected $signature = 'policies:generate-missing';
    protected $description = 'Génère les policies manquantes pour tous les sièges existants';

    public function handle(): void
    {
        $sieges = DB::table('entreprises_sieges')->get();
       $types = LeaveType::where('is_active', true)->get();


        if ($types->isEmpty()) {
            $this->error('Aucun type global trouvé. Lance d\'abord conges:transition');
            return;
        }

        foreach ($sieges as $siege) {
            foreach ($types as $type) {
                
                // ⛔ EXCLUSIONS : modifie ici selon tes besoins
                // Exemple : siège 2 n'a pas de Maternité
                // if ($type->code === 'MAT' && $siege->id === 2) {
                //     continue;
                // }

                LeavePolicy::firstOrCreate(
                    [
                        'company_id' => $siege->ID,
                        'leave_type_id' => $type->id,
                    ],
                    [
                        'rules' => [
                            'min_notice_days' => 15,
                            'allow_half_day' => true,
                            'exclude_holidays' => true,
                            'exclude_weekends' => true,
                            'min_duration_days' => 0.5,
                            'max_consecutive_days' => 24,
                            'max_per_year' => 25,
                            'requires_approval_from' => 'manager_then_rh',
                            'max_carryover_days' => 5,
                            'deducts_balance' => in_array($type->code, ['CP', 'RTT']),
                            'approval_required' => true,
                            'requires_attachment' => 'never',
                            'attachment_threshold' => 0,
                            'allow_negative_balance' => false,
                            'negative_limit' => 0,
                        ],
                        'is_active' => true,
                    ]
                );
            }
            $this->info("Policies générées pour le siège {$siege->ID}");
        }
    }
}