<?php
// app/Imports/LeaveBalanceImport.php

namespace App\Imports;

use App\Models\Employe;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveImportBatch;
use App\Services\LeaveBalanceService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LeaveBalanceImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    protected $periodId;
    protected $siteId;
    protected $batch;
    protected $balanceService;
    protected $errors = [];
    protected $successCount = 0;
    protected $failureCount = 0;
    protected $simulate = true;

    public function __construct($periodId, $siteId, $simulate = true)
    {
        $this->periodId = $periodId;
        $this->siteId = $siteId;
        $this->simulate = $simulate;
        $this->balanceService = new LeaveBalanceService();

        $this->batch = LeaveImportBatch::create([
            'site_id' => $siteId,
            'batch_number' => 'IMP-' . date('Ymd') . '-' . Str::random(6),
            'type' => 'opening_balance',
            'status' => 'processing',
            'total_records' => 0,
            'created_by' => Auth::user()?->ID ?? null,
        ]);
    }

    public function collection(Collection $rows)
    {
        // DEBUG : Voir ce qui est lu
        Log::info('=== DÉBUT IMPORT ===');
        Log::info('Nombre de lignes: ' . $rows->count());
        Log::info('Première ligne:', $rows->first()?->toArray() ?? []);
        Log::info('En-têtes: ' . implode(', ', array_keys($rows->first()?->toArray() ?? [])));

        $this->batch->update(['total_records' => $rows->count()]);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                // Récupérer les valeurs en ignorant la casse
                $rowArray = $row->toArray();
                $keys = array_keys($rowArray);
                
                // Trouver la bonne colonne pour le matricule
                $matriculeKey = $this->findKey($keys, ['matricule', 'MATRICULE', 'Matricule']);
                $codeKey = $this->findKey($keys, ['code_conge', 'CODE_CONGE', 'code', 'CODE']);
                $soldeKey = $this->findKey($keys, ['solde', 'SOLDE', 'montant', 'MONTANT']);
                $descKey = $this->findKey($keys, ['description', 'DESCRIPTION']);

                $matricule = trim($rowArray[$matriculeKey] ?? '');
                $codeConge = trim($rowArray[$codeKey] ?? '');
                $soldeRaw = $rowArray[$soldeKey] ?? 0;
                $solde = floatval(str_replace(',', '.', $soldeRaw));
                $description = trim($rowArray[$descKey] ?? '');

                Log::info("Ligne $rowNumber - Matricule: '$matricule', Code: '$codeConge', Solde: $solde");

                // === VALIDATION ===

                if (empty($matricule)) {
                    $this->addError($rowNumber, 'matricule', 'Le matricule est obligatoire.');
                    continue;
                }

                if (empty($codeConge)) {
                    $this->addError($rowNumber, 'code_conge', 'Le code congé est obligatoire.');
                    continue;
                }

                // Vérifier l'employé
                $employee = Employe::where('num_mat', $matricule)
                    ->where('SiegeID', $this->siteId)
                    ->first();

                if (!$employee) {
                    $this->addError($rowNumber, 'matricule', "Employé '$matricule' non trouvé dans ce siège.");
                    continue;
                }

                Log::info("Employé trouvé: {$employee->Nom} (ID: {$employee->ID})");

                // Vérifier le type de congé
                $leaveType = LeaveType::where('code', $codeConge)
                    ->where(function ($q) {
                        $q->where('site_id', $this->siteId)
                          ->orWhereNull('site_id');
                    })
                    ->first();

                if (!$leaveType) {
                    $this->addError($rowNumber, 'code_conge', "Type de congé '$codeConge' non trouvé.");
                    continue;
                }

                Log::info("Type de congé trouvé: {$leaveType->name}");

                if ($solde < 0) {
                    $this->addError($rowNumber, 'solde', 'Le solde ne peut pas être négatif.');
                    continue;
                }

                // Vérifier si le solde existe déjà
                $existing = LeaveBalance::where('employee_id', $employee->ID)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('period_id', $this->periodId)
                    ->first();

                if ($existing && !$this->simulate) {
                    $this->addError($rowNumber, 'solde', "Solde déjà existant pour {$employee->Nom}");
                    continue;
                }

                if ($this->simulate) {
                    $this->successCount++;
                    Log::info("SIMULATION - Ligne $rowNumber OK");
                } else {
                    $this->balanceService->initializeBalance(
                        $employee->ID,
                        $leaveType->id,
                        $this->periodId,
                        $solde,
                        $description ?: "Solde initial {$leaveType->name}"
                    );
                    $this->successCount++;
                    Log::info("IMPORT - Ligne $rowNumber OK");
                }

            } catch (\Exception $e) {
                Log::error("Erreur ligne $rowNumber: " . $e->getMessage());
                $this->addError($rowNumber, 'Général', $e->getMessage());
            }
        }

        $this->batch->update([
            'status' => 'completed',
            'successful_records' => $this->successCount,
            'failed_records' => $this->failureCount,
            'errors' => $this->errors,
            'completed_at' => now(),
        ]);

        Log::info("=== IMPORT TERMINÉ ===");
        Log::info("Succès: {$this->successCount}, Échecs: {$this->failureCount}");
    }

    private function findKey($keys, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (in_array($key, $keys)) {
                return $key;
            }
        }
        return null;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->addError($failure->row(), $failure->attribute(), implode(', ', $failure->errors()));
        }
    }

    protected function addError($row, $field, $message)
    {
        $this->errors[] = ['row' => $row, 'field' => $field, 'message' => $message];
        $this->failureCount++;
    }

    public function getBatch() { return $this->batch; }
    public function getErrors() { return $this->errors; }
    public function getSuccessCount() { return $this->successCount; }
    public function getFailureCount() { return $this->failureCount; }
}