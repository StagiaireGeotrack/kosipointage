<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use App\Models\RuleField;
use Illuminate\Database\Seeder;

class RuleFieldsSeeder extends Seeder
{
    public function run(): void
    {
        $types = LeaveType::all();

        foreach ($types as $type) {
            // Évite les doublons
            if ($type->ruleFields()->exists()) continue;

            // Règles communes à TOUS les types
            $common = [
                [
                    'field_key'     => 'min_notice_days',
                    'field_type'    => 'number',
                    'label'         => 'Préavis minimum (jours)',
                    'default_value' => '15',
                    'validation'    => ['min' => 0, 'max' => 365, 'required' => true],
                    'sort_order'    => 1,
                ],
                [
                    'field_key'     => 'max_per_year',
                    'field_type'    => 'number',
                    'label'         => 'Maximum par an',
                    'default_value' => '25',
                    'validation'    => ['min' => 0, 'max' => 365, 'required' => true],
                    'sort_order'    => 2,
                ],
                [
                    'field_key'     => 'max_consecutive_days',
                    'field_type'    => 'number',
                    'label'         => 'Max consécutifs (jours)',
                    'default_value' => '24',
                    'validation'    => ['min' => 1, 'max' => 365],
                    'sort_order'    => 3,
                ],
                [
                    'field_key'     => 'max_carryover_days',
                    'field_type'    => 'number',
                    'label'         => 'Report max (jours)',
                    'default_value' => '0',
                    'validation'    => ['min' => 0, 'max' => 365],
                    'sort_order'    => 4,
                ],
                [
                    'field_key'     => 'min_duration_days',
                    'field_type'    => 'number',
                    'label'         => 'Durée minimale (jours)',
                    'default_value' => '0.5',
                    'validation'    => ['min' => 0.5, 'max' => 365, 'step' => 0.5],
                    'sort_order'    => 5,
                ],
                [
                    'field_key'     => 'requires_approval_from',
                    'field_type'    => 'select',
                    'label'         => 'Approbation par',
                    'default_value' => 'manager',
                    'options'       => [
                        ['value' => 'manager', 'label' => 'Manager'],
                        ['value' => 'rh', 'label' => 'RH'],
                        ['value' => 'direction', 'label' => 'Direction'],
                        ['value' => 'manager_then_rh', 'label' => 'Manager puis RH'],
                    ],
                    'sort_order'    => 6,
                ],
                [
                    'field_key'     => 'allow_half_day',
                    'field_type'    => 'boolean',
                    'label'         => 'Demi-journée autorisée',
                    'default_value' => '1',
                    'sort_order'    => 7,
                ],
                [
                    'field_key'     => 'exclude_weekends',
                    'field_type'    => 'boolean',
                    'label'         => 'Exclure week-ends',
                    'default_value' => '1',
                    'sort_order'    => 8,
                ],
                [
                    'field_key'     => 'exclude_holidays',
                    'field_type'    => 'boolean',
                    'label'         => 'Exclure jours fériés',
                    'default_value' => '1',
                    'sort_order'    => 9,
                ],
                [
                    'field_key'     => 'deducts_balance',
                    'field_type'    => 'boolean',
                    'label'         => 'Décompte solde',
                    'default_value' => '1',
                    'sort_order'    => 10,
                ],
                [
                    'field_key'     => 'approval_required',
                    'field_type'    => 'boolean',
                    'label'         => 'Approbation requise',
                    'default_value' => '1',
                    'sort_order'    => 11,
                ],
                [
                    'field_key'     => 'requires_attachment',
                    'field_type'    => 'select',
                    'label'         => 'Pièce justificative',
                    'default_value' => 'never',
                    'options'       => [
                        ['value' => 'never', 'label' => 'Jamais'],
                        ['value' => 'always', 'label' => 'Toujours'],
                        ['value' => 'from_duration', 'label' => 'À partir d\'une durée'],
                    ],
                    'sort_order'    => 12,
                ],
                [
                    'field_key'     => 'attachment_threshold',
                    'field_type'    => 'number',
                    'label'         => 'Seuil pièce (jours)',
                    'default_value' => '0',
                    'validation'    => ['min' => 0, 'max' => 365, 'step' => 0.5],
                    'sort_order'    => 13,
                ],
                [
                    'field_key'     => 'allow_negative_balance',
                    'field_type'    => 'boolean',
                    'label'         => 'Solde négatif autorisé',
                    'default_value' => '0',
                    'sort_order'    => 14,
                ],
                [
                    'field_key'     => 'negative_limit',
                    'field_type'    => 'number',
                    'label'         => 'Limite négative',
                    'default_value' => '0',
                    'validation'    => ['min' => 0, 'max' => 365],
                    'sort_order'    => 15,
                ],
            ];

            foreach ($common as $field) {
                $type->ruleFields()->create($field);
            }
        }
    }
}