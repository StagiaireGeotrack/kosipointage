<?php

namespace Database\Seeders;

use App\Models\CalculationRule;
use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class CalculationRulesSeeder extends Seeder
{
    public function run(): void
    {
        // ─── CP ───
        $cp = LeaveType::where('code', 'CP')->first();
        if ($cp) {
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $cp->id, 'output_variable' => 'entitlement'],
                [
                    'name' => 'Droits acquis mensuels',
                    'formula' => 'floor((max_per_year / mois_annee) * mois_travailles)',
                    'sort_order' => 1,
                ]
            );
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $cp->id, 'output_variable' => 'seniority_bonus'],
                [
                    'name' => 'Bonus anciennete',
                    'formula' => 'if(anciennete_annees >= 5, if(anciennete_annees >= 10, 5, 2), 0)',
                    'sort_order' => 2,
                ]
            );
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $cp->id, 'output_variable' => 'total_entitlement'],
                [
                    'name' => 'Total droits',
                    'formula' => 'entitlement + seniority_bonus',
                    'sort_order' => 3,
                ]
            );
            $this->command->info('CP : 3 formules creees.');
        }

        // ─── MALADIE ───
        $maladie = LeaveType::where('code', 'MALADIE')->first();
        if ($maladie) {
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $maladie->id, 'output_variable' => 'entitlement'],
                [
                    'name' => 'Jours maladie',
                    'formula' => '9999',
                    'sort_order' => 1,
                ]
            );
            $this->command->info('MALADIE : 1 formule creee.');
        }

        // ─── MATERNITE ───
        $mat = LeaveType::where('code', 'MATERNITE')->first();
        if ($mat) {
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $mat->id, 'output_variable' => 'entitlement'],
                [
                    'name' => 'Duree maternite',
                    'formula' => 'legal_duration_days',
                    'sort_order' => 1,
                ]
            );
            $this->command->info('MATERNITE : 1 formule creee.');
        }

        // ─── SANS SOLDE ───
        $ss = LeaveType::where('code', 'SS')->first();
        if ($ss) {
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $ss->id, 'output_variable' => 'entitlement'],
                [
                    'name' => 'Sans solde',
                    'formula' => '0',
                    'sort_order' => 1,
                ]
            );
            $this->command->info('SANS SOLDE : 1 formule creee.');
        }

        // ─── ENFANT MALADE ───
        $enf = LeaveType::where('code', 'ENFANT_MALADE')->first();
        if ($enf) {
            CalculationRule::firstOrCreate(
                ['leave_type_id' => $enf->id, 'output_variable' => 'entitlement'],
                [
                    'name' => 'Max enfant malade',
                    'formula' => 'max_per_year',
                    'sort_order' => 1,
                ]
            );
            $this->command->info('ENFANT MALADE : 1 formule creee.');
        }

        $this->command->info('✅ Formules de calcul terminees !');
    }
}