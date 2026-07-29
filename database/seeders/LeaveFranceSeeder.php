<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveFranceSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Récupérer les sièges existants ───────────────────────────────
        $sieges = DB::table('entreprises_sieges')->get();

        if ($sieges->isEmpty()) {
            $this->command->error("❌ Aucun siège trouvé dans 'entreprises_sieges'. Créez un siège d'abord.");
            return;
        }

        // ─── 2. Préparer les choix ───────────────────────────────────────────
        $choices = [];
        foreach ($sieges as $siege) {
            $name = $siege->Nom ?? $siege->nom ?? $siege->name ?? $siege->nom_siege ?? 'Sans nom';
            $choices[$siege->ID] = "[ID:{$siege->ID}] {$name}";
        }

        // ─── 3. Demander à l'utilisateur de choisir ──────────────────────────
        $selectedLabel = $this->command->choice(
            'Pour quel siège voulez-vous injecter les règles de congés France ?',
            $choices,
            0 // défaut = premier
        );

        $targetCompanyId = array_search($selectedLabel, $choices);

        $this->command->newLine();
        $this->command->info("🇫🇷 Injection des règles France pour le siège ID = {$targetCompanyId}...");

        // ─── 4. Vérifier si déjà des données pour ce siège ─────────────────
        $existingTypes = DB::table('leave_types')->where('company_id', $targetCompanyId)->count();
        if ($existingTypes > 0) {
            if (!$this->command->confirm(
                "⚠️  Ce siège a déjà des types de congés. Voulez-vous les supprimer et réinitialiser ?",
                false
            )) {
                $this->command->info('❌ Seeder annulé. Aucune modification effectuée.');
                return;
            }

            // Nettoyage en cascade (ordre inverse des dépendances FK)
            DB::table('leave_balance_transactions')->where('company_id', $targetCompanyId)->delete();
            DB::table('company_holidays')->where('company_id', $targetCompanyId)->delete();
            DB::table('leave_periods')->where('company_id', $targetCompanyId)->delete();
            DB::table('leave_policy_assignments')->where('company_id', $targetCompanyId)->delete();
            DB::table('leave_policies')->where('company_id', $targetCompanyId)->delete();
            DB::table('leave_types')->where('company_id', $targetCompanyId)->delete();

            $this->command->info('🗑️  Anciennes données supprimées pour ce siège.');
        }

        $now = now();

        // ─── 5. TYPES DE CONGÉS ──────────────────────────────────────────────
        $types = [
            [
                'company_id' => $targetCompanyId, 'name' => 'Congé Payé', 'code' => 'CP',
                'color' => '#4CAF50', 'unit' => 'days', 'deducts_balance' => 1,
                'requires_attachment' => 'never', 'attachment_threshold' => 0,
                'approval_required' => 1, 'allow_negative_balance' => 0,
                'negative_limit' => 0, 'visibility_level' => 'all', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'company_id' => $targetCompanyId, 'name' => 'RTT', 'code' => 'RTT',
                'color' => '#2196F3', 'unit' => 'days', 'deducts_balance' => 1,
                'requires_attachment' => 'never', 'attachment_threshold' => 0,
                'approval_required' => 1, 'allow_negative_balance' => 0,
                'negative_limit' => 0, 'visibility_level' => 'all', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'company_id' => $targetCompanyId, 'name' => 'Maladie', 'code' => 'MALADIE',
                'color' => '#F44336', 'unit' => 'days', 'deducts_balance' => 0,
                'requires_attachment' => 'from_duration', 'attachment_threshold' => 3,
                'approval_required' => 1, 'allow_negative_balance' => 0,
                'negative_limit' => 0, 'visibility_level' => 'all', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'company_id' => $targetCompanyId, 'name' => 'Sans Solde', 'code' => 'SS',
                'color' => '#9E9E9E', 'unit' => 'days', 'deducts_balance' => 0,
                'requires_attachment' => 'never', 'attachment_threshold' => 0,
                'approval_required' => 1, 'allow_negative_balance' => 0,
                'negative_limit' => 0, 'visibility_level' => 'all', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'company_id' => $targetCompanyId, 'name' => 'Récupération', 'code' => 'RECUP',
                'color' => '#FF9800', 'unit' => 'days', 'deducts_balance' => 0,
                'requires_attachment' => 'never', 'attachment_threshold' => 0,
                'approval_required' => 1, 'allow_negative_balance' => 0,
                'negative_limit' => 0, 'visibility_level' => 'all', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'company_id' => $targetCompanyId, 'name' => 'Autre', 'code' => 'AUTRE',
                'color' => '#673AB7', 'unit' => 'days', 'deducts_balance' => 0,
                'requires_attachment' => 'always', 'attachment_threshold' => 0,
                'approval_required' => 1, 'allow_negative_balance' => 0,
                'negative_limit' => 0, 'visibility_level' => 'all', 'is_active' => 1,
                'created_at' => $now, 'updated_at' => $now
            ],
        ];
        DB::table('leave_types')->insert($types);

        // Récupérer les IDs créés
        $cpId = DB::table('leave_types')
            ->where('company_id', $targetCompanyId)
            ->where('code', 'CP')
            ->value('id');

        $rttId = DB::table('leave_types')
            ->where('company_id', $targetCompanyId)
            ->where('code', 'RTT')
            ->value('id');

        // ─── 6. POLITIQUES ─────────────────────────────────────────────────
        $policies = [
            [
                'company_id' => $targetCompanyId, 'leave_type_id' => $cpId,
                'calculation_method' => 'working_days', 'reference_schedule' => 'company',
                'holiday_behavior' => 'exclude', 'half_day_morning_start' => '09:00:00',
                'half_day_afternoon_start' => '14:00:00', 'rounding_method' => 'half',
                'max_simultaneous_absents' => null, 'min_remaining_staff' => null,
                'alert_type' => 'informative', 'notice_period_days' => 15,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'company_id' => $targetCompanyId, 'leave_type_id' => $rttId,
                'calculation_method' => 'working_days', 'reference_schedule' => 'company',
                'holiday_behavior' => 'exclude', 'half_day_morning_start' => '09:00:00',
                'half_day_afternoon_start' => '14:00:00', 'rounding_method' => 'half',
                'max_simultaneous_absents' => null, 'min_remaining_staff' => null,
                'alert_type' => 'informative', 'notice_period_days' => 15,
                'created_at' => $now, 'updated_at' => $now
            ],
        ];
        DB::table('leave_policies')->insert($policies);

        $cpPolicyId = DB::table('leave_policies')
            ->where('company_id', $targetCompanyId)
            ->where('leave_type_id', $cpId)
            ->value('id');

        $rttPolicyId = DB::table('leave_policies')
            ->where('company_id', $targetCompanyId)
            ->where('leave_type_id', $rttId)
            ->value('id');

        // ─── 7. ASSIGNATIONS (toute l'entreprise par défaut) ───────────────
        DB::table('leave_policy_assignments')->insert([
            [
                'policy_id' => $cpPolicyId, 'target_type' => 'company',
                'target_id' => $targetCompanyId, 'company_id' => $targetCompanyId,
                'created_at' => $now, 'updated_at' => $now
            ],
            [
                'policy_id' => $rttPolicyId, 'target_type' => 'company',
                'target_id' => $targetCompanyId, 'company_id' => $targetCompanyId,
                'created_at' => $now, 'updated_at' => $now
            ],
        ]);

        // ─── 8. PÉRIODE DE RÉFÉRENCE ───────────────────────────────────────
        DB::table('leave_periods')->insert([
            'company_id' => $targetCompanyId,
            'name' => 'Période 2026-2027',
            'start_date' => '2026-06-01',
            'end_date' => '2027-05-31',
            'booking_deadline' => '2027-05-15',
            'carryover_allowed' => 1,
            'carryover_limit' => 5,
            'carryover_expiration_date' => '2027-12-31',
            'status' => 'open',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ─── 9. JOURS FÉRIÉS FRANCE 2026 ───────────────────────────────────
        $holidays = [
            ['company_id' => $targetCompanyId, 'date' => '2026-01-01', 'name' => "Jour de l'An", 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-04-06', 'name' => 'Lundi de Pâques', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-05-01', 'name' => 'Fête du Travail', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-05-08', 'name' => 'Victoire 1945', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-05-14', 'name' => 'Ascension', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-05-25', 'name' => 'Lundi de Pentecôte', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-07-14', 'name' => 'Fête Nationale', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-08-15', 'name' => 'Assomption', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-11-01', 'name' => 'Toussaint', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-11-11', 'name' => 'Armistice 1918', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $targetCompanyId, 'date' => '2026-12-25', 'name' => 'Noël', 'type' => 'public_holiday', 'is_recurrent' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('company_holidays')->insert($holidays);

        // ─── 10. RÉCAP ─────────────────────────────────────────────────────
        $this->command->newLine();
        $this->command->info("✅ Seeder France terminé avec succès !");
        $this->command->info("   Siège cible : ID = {$targetCompanyId}");
        $this->command->info("   • 6 types de congés créés");
        $this->command->info("   • 2 politiques créées (CP + RTT)");
        $this->command->info("   • 1 période 2026-2027 créée");
        $this->command->info("   • 11 jours fériés créés");
        $this->command->newLine();
        $this->command->info("➡️  Prochaine étape : Étape 1.5 — Interfaces Admin CRUD");
    }
}