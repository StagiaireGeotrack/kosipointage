<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use App\Models\RuleField;
use Illuminate\Database\Seeder;

class LeaveTypesFranceSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creation des 5 types de conges avec champs differents...');

        // ============================================================
        // 1. CONGE PAYE — 15 champs standards
        // ============================================================
        $cp = LeaveType::firstOrCreate(
            ['code' => 'CP'],
            ['name' => 'Conge Paye', 'color' => '#3B82F6', 'is_active' => true]
        );
        $cp->ruleFields()->delete();
        $cp->ruleFields()->createMany([
            ['field_key' => 'min_notice_days',        'field_type' => 'number',  'label' => 'Preavis minimum (jours)',      'default_value' => '15',  'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 1],
            ['field_key' => 'max_per_year',           'field_type' => 'number',  'label' => 'Maximum par an',               'default_value' => '25',  'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 2],
            ['field_key' => 'max_consecutive_days',   'field_type' => 'number',  'label' => 'Max consecutifs (jours)',      'default_value' => '24',  'validation' => ['min' => 1, 'max' => 365],                        'sort_order' => 3],
            ['field_key' => 'max_carryover_days',     'field_type' => 'number',  'label' => 'Report max (jours)',           'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365],                        'sort_order' => 4],
            ['field_key' => 'min_duration_days',      'field_type' => 'number',  'label' => 'Duree minimale (jours)',       'default_value' => '0.5', 'validation' => ['min' => 0.5, 'max' => 365, 'step' => 0.5],      'sort_order' => 5],
            ['field_key' => 'requires_approval_from', 'field_type' => 'select',  'label' => 'Approbation par',              'default_value' => 'manager', 'options' => $this->approvalOptions(),                      'sort_order' => 6],
            ['field_key' => 'allow_half_day',         'field_type' => 'boolean', 'label' => 'Demi-journee autorisee',       'default_value' => '1',                                                                                  'sort_order' => 7],
            ['field_key' => 'exclude_weekends',       'field_type' => 'boolean', 'label' => 'Exclure week-ends',            'default_value' => '1',                                                                                  'sort_order' => 8],
            ['field_key' => 'exclude_holidays',       'field_type' => 'boolean', 'label' => 'Exclure jours feries',         'default_value' => '1',                                                                                  'sort_order' => 9],
            ['field_key' => 'deducts_balance',        'field_type' => 'boolean', 'label' => 'Decompte solde',               'default_value' => '1',                                                                                  'sort_order' => 10],
            ['field_key' => 'approval_required',      'field_type' => 'boolean', 'label' => 'Approbation requise',          'default_value' => '1',                                                                                  'sort_order' => 11],
            ['field_key' => 'requires_attachment',    'field_type' => 'select',  'label' => 'Piece justificative',          'default_value' => 'never', 'options' => $this->attachmentOptions(),                    'sort_order' => 12],
            ['field_key' => 'attachment_threshold',   'field_type' => 'number',  'label' => 'Seuil piece (jours)',          'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365, 'step' => 0.5],         'sort_order' => 13],
            ['field_key' => 'allow_negative_balance', 'field_type' => 'boolean', 'label' => 'Solde negatif autorise',       'default_value' => '0',                                                                                  'sort_order' => 14],
            ['field_key' => 'negative_limit',         'field_type' => 'number',  'label' => 'Limite negative',              'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365],                        'sort_order' => 15],
        ]);
        $this->command->info('CP : 15 champs');

        // ============================================================
        // 2. ARRET MALADIE — 7 champs (pas de max_per_year, pas de report)
        // ============================================================
        $maladie = LeaveType::firstOrCreate(
            ['code' => 'MALADIE'],
            ['name' => 'Arret Maladie', 'color' => '#EF4444', 'is_active' => true]
        );
        $maladie->ruleFields()->delete();
        $maladie->ruleFields()->createMany([
            ['field_key' => 'min_notice_days',        'field_type' => 'number',  'label' => 'Preavis minimum (jours)',      'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 1],
            ['field_key' => 'min_duration_days',      'field_type' => 'number',  'label' => 'Duree minimale (jours)',       'default_value' => '1',   'validation' => ['min' => 1, 'max' => 365],                        'sort_order' => 2],
            ['field_key' => 'requires_approval_from', 'field_type' => 'select',  'label' => 'Approbation par',              'default_value' => 'rh',      'options' => $this->approvalOptions(),                      'sort_order' => 3],
            ['field_key' => 'allow_half_day',         'field_type' => 'boolean', 'label' => 'Demi-journee autorisee',       'default_value' => '0',                                                                                  'sort_order' => 4],
            ['field_key' => 'deducts_balance',        'field_type' => 'boolean', 'label' => 'Decompte solde',               'default_value' => '0',                                                                                  'sort_order' => 5],
            ['field_key' => 'approval_required',      'field_type' => 'boolean', 'label' => 'Approbation requise',          'default_value' => '1',                                                                                  'sort_order' => 6],
            ['field_key' => 'requires_attachment',    'field_type' => 'select',  'label' => 'Piece justificative',          'default_value' => 'always',  'options' => $this->attachmentOptions(),                    'sort_order' => 7],
            ['field_key' => 'ijss_rate',              'field_type' => 'number',  'label' => 'Taux IJSS (%)',                'default_value' => '50',  'validation' => ['min' => 0, 'max' => 100, 'step' => 'any'],       'sort_order' => 8],
        ]);
        $this->command->info('MALADIE : 8 champs (pas de max/an, pas de report)');

        // ============================================================
        // 3. MATERNITE — champs specifiques (pre/post natal)
        // ============================================================
        $mat = LeaveType::firstOrCreate(
            ['code' => 'MATERNITE'],
            ['name' => 'Conge Maternite', 'color' => '#EC4899', 'is_active' => true]
        );
        $mat->ruleFields()->delete();
        $mat->ruleFields()->createMany([
            ['field_key' => 'min_notice_days',        'field_type' => 'number',  'label' => 'Preavis minimum (jours)',      'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 1],
            ['field_key' => 'legal_duration_days',    'field_type' => 'number',  'label' => 'Duree legale (jours)',         'default_value' => '112', 'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 2],
            ['field_key' => 'prenatal_leave_days',    'field_type' => 'number',  'label' => 'Conge prenatal (jours)',       'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365],                        'sort_order' => 3],
            ['field_key' => 'postnatal_leave_days',   'field_type' => 'number',  'label' => 'Conge postnatal (jours)',      'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365],                        'sort_order' => 4],
            ['field_key' => 'requires_approval_from', 'field_type' => 'select',  'label' => 'Approbation par',              'default_value' => 'rh',      'options' => $this->approvalOptions(),                      'sort_order' => 5],
            ['field_key' => 'allow_half_day',         'field_type' => 'boolean', 'label' => 'Demi-journee autorisee',       'default_value' => '0',                                                                                  'sort_order' => 6],
            ['field_key' => 'deducts_balance',        'field_type' => 'boolean', 'label' => 'Decompte solde',               'default_value' => '0',                                                                                  'sort_order' => 7],
            ['field_key' => 'approval_required',      'field_type' => 'boolean', 'label' => 'Approbation requise',          'default_value' => '0',                                                                                  'sort_order' => 8],
            ['field_key' => 'requires_attachment',    'field_type' => 'select',  'label' => 'Piece justificative',          'default_value' => 'always',  'options' => $this->attachmentOptions(),                    'sort_order' => 9],
        ]);
        $this->command->info('MATERNITE : 9 champs (pre/post natal)');

        // ============================================================
        // 4. SANS SOLDE — champs specifiques (direction, protection emploi)
        // ============================================================
        $ss = LeaveType::firstOrCreate(
            ['code' => 'SS'],
            ['name' => 'Conge Sans Solde', 'color' => '#6B7280', 'is_active' => true]
        );
        $ss->ruleFields()->delete();
        $ss->ruleFields()->createMany([
            ['field_key' => 'min_notice_days',        'field_type' => 'number',  'label' => 'Preavis minimum (jours)',      'default_value' => '30',  'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 1],
            ['field_key' => 'max_per_year',           'field_type' => 'number',  'label' => 'Max par an (jours)',           'default_value' => '',    'validation' => ['min' => 1, 'max' => 365],                        'sort_order' => 2],
            ['field_key' => 'max_consecutive_days',   'field_type' => 'number',  'label' => 'Max consecutifs (jours)',      'default_value' => '',    'validation' => ['min' => 1, 'max' => 365],                        'sort_order' => 3],
            ['field_key' => 'min_duration_days',      'field_type' => 'number',  'label' => 'Duree minimale (jours)',       'default_value' => '0.5', 'validation' => ['min' => 0.5, 'max' => 365, 'step' => 0.5],      'sort_order' => 4],
            ['field_key' => 'requires_approval_from', 'field_type' => 'select',  'label' => 'Approbation par',              'default_value' => 'direction', 'options' => $this->approvalOptions(),                  'sort_order' => 5],
            ['field_key' => 'allow_half_day',         'field_type' => 'boolean', 'label' => 'Demi-journee autorisee',       'default_value' => '1',                                                                                  'sort_order' => 6],
            ['field_key' => 'deducts_balance',        'field_type' => 'boolean', 'label' => 'Decompte solde',               'default_value' => '0',                                                                                  'sort_order' => 7],
            ['field_key' => 'approval_required',      'field_type' => 'boolean', 'label' => 'Approbation requise',          'default_value' => '1',                                                                                  'sort_order' => 8],
            ['field_key' => 'requires_attachment',    'field_type' => 'select',  'label' => 'Piece justificative',          'default_value' => 'never',   'options' => $this->attachmentOptions(),                    'sort_order' => 9],
            ['field_key' => 'job_protection',         'field_type' => 'boolean', 'label' => 'Protection de l\'emploi',      'default_value' => '1',                                                                                  'sort_order' => 10],
        ]);
        $this->command->info('SANS SOLDE : 10 champs (direction obligatoire)');

        // ============================================================
        // 5. ENFANT MALADE — champs specifiques (age enfant, max par enfant)
        // ============================================================
        $enf = LeaveType::firstOrCreate(
            ['code' => 'ENFANT_MALADE'],
            ['name' => 'Conge Enfant Malade', 'color' => '#E11D48', 'is_active' => true]
        );
        $enf->ruleFields()->delete();
        $enf->ruleFields()->createMany([
            ['field_key' => 'min_notice_days',        'field_type' => 'number',  'label' => 'Preavis minimum (jours)',      'default_value' => '0',   'validation' => ['min' => 0, 'max' => 365, 'required' => true],  'sort_order' => 1],
            ['field_key' => 'max_per_year',           'field_type' => 'number',  'label' => 'Max par an (jours)',           'default_value' => '5',   'validation' => ['min' => 0, 'max' => 30, 'required' => true],   'sort_order' => 2],
            ['field_key' => 'max_per_child',          'field_type' => 'number',  'label' => 'Max par enfant (jours)',       'default_value' => '5',   'validation' => ['min' => 0, 'max' => 30],                         'sort_order' => 3],
            ['field_key' => 'child_age_limit',        'field_type' => 'number',  'label' => 'Limite d\'age enfant (ans)',    'default_value' => '16',  'validation' => ['min' => 0, 'max' => 25],                         'sort_order' => 4],
            ['field_key' => 'min_duration_days',      'field_type' => 'number',  'label' => 'Duree minimale (jours)',       'default_value' => '1',   'validation' => ['min' => 1, 'max' => 30],                         'sort_order' => 5],
            ['field_key' => 'requires_approval_from', 'field_type' => 'select',  'label' => 'Approbation par',              'default_value' => 'rh',      'options' => $this->approvalOptions(),                      'sort_order' => 6],
            ['field_key' => 'allow_half_day',         'field_type' => 'boolean', 'label' => 'Demi-journee autorisee',       'default_value' => '0',                                                                                  'sort_order' => 7],
            ['field_key' => 'deducts_balance',        'field_type' => 'boolean', 'label' => 'Decompte solde',               'default_value' => '0',                                                                                  'sort_order' => 8],
            ['field_key' => 'approval_required',      'field_type' => 'boolean', 'label' => 'Approbation requise',          'default_value' => '1',                                                                                  'sort_order' => 9],
            ['field_key' => 'requires_attachment',    'field_type' => 'select',  'label' => 'Piece justificative',          'default_value' => 'always',  'options' => $this->attachmentOptions(),                    'sort_order' => 10],
            ['field_key' => 'medical_certificate',    'field_type' => 'boolean', 'label' => 'Certificat medical requis',    'default_value' => '1',                                                                                  'sort_order' => 11],
        ]);
        $this->command->info('ENFANT MALADE : 11 champs (age enfant, max par enfant)');

        // ============================================================
        $this->command->newLine();
        $this->command->info('✅ Termine !');
        $this->command->info('   CP           : 15 champs (complet)');
        $this->command->info('   MALADIE      : 8 champs (pas de max/an, pas de report)');
        $this->command->info('   MATERNITE    : 9 champs (pre/post natal)');
        $this->command->info('   SANS SOLDE   : 10 champs (direction obligatoire)');
        $this->command->info('   ENFANT MALADE: 11 champs (age enfant, max par enfant)');
    }

    private function approvalOptions(): array
    {
        return [
            ['value' => 'manager', 'label' => 'Manager'],
            ['value' => 'rh', 'label' => 'RH'],
            ['value' => 'direction', 'label' => 'Direction'],
            ['value' => 'manager_then_rh', 'label' => 'Manager puis RH'],
        ];
    }

    private function attachmentOptions(): array
    {
        return [
            ['value' => 'never', 'label' => 'Jamais'],
            ['value' => 'always', 'label' => 'Toujours'],
            ['value' => 'from_duration', 'label' => 'A partir d\'une duree'],
        ];
    }
}