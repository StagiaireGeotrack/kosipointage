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
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LeaveBalanceImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
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
        $this->batch->update(['total_records' => $rows->count()]);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            try {
                // Récupérer les valeurs en ignorant la casse des en-têtes
                $matricule = $row['matricule'] ?? $row['MATRICULE'] ?? null;
                $codeConge = $row['code_conge'] ?? $row['CODE_CONGE'] ?? $row['code'] ?? $row['CODE'] ?? null;
                $solde = $row['solde'] ?? $row['SOLDE'] ?? $row['montant'] ?? $row['MONTANT'] ?? null;
                $description = $row['description'] ?? $row['DESCRIPTION'] ?? null;

                // Vérifier les champs obligatoires
                if (empty($matricule)) {
                    $this->addError($rowNumber, 'matricule', 'Le matricule est obligatoire.');
                    continue;
                }

                if (empty($codeConge)) {
                    $this->addError($rowNumber, 'code_conge', 'Le code congé est obligatoire.');
                    continue;
                }

                if ($solde === null || $solde === '') {
                    $this->addError($rowNumber, 'solde', 'Le solde est obligatoire.');
                    continue;
                }

                // Vérifier l'employé
                $employee = Employe::where('num_mat', $matricule)
                    ->where('SiegeID', $this->siteId)
                    ->first();

                if (!$employee) {
                    $this->addError($rowNumber, 'matricule', 'Employé non trouvé dans ce siège.');
                    continue;
                }

                // Vérifier le type de congé
                $leaveType = LeaveType::where('code', $codeConge)
                    ->where(function ($q) {
                        $q->where('site_id', $this->siteId)
                          ->orWhereNull('site_id');
                    })
                    ->first();

                if (!$leaveType) {
                    $this->addError($rowNumber, 'code_conge', 'Type de congé non trouvé.');
                    continue;
                }

                // Vérifier si le solde existe déjà
                $existing = LeaveBalance::where('employee_id', $employee->ID)
                    ->where('leave_type_id', $leaveType->id)
                    ->where('period_id', $this->periodId)
                    ->first();

                if ($existing && !$this->simulate) {
                    $this->addError($rowNumber, 'solde', 'Un solde existe déjà pour cet employé.');
                    continue;
                }

                // Validation du montant
                $amount = floatval(str_replace(',', '.', $solde));
                if ($amount < 0) {
                    $this->addError($rowNumber, 'solde', 'Le solde ne peut pas être négatif.');
                    continue;
                }

                if ($this->simulate) {
                    $this->successCount++;
                    continue;
                }

                // Créer le solde initial
                $this->balanceService->initializeBalance(
                    $employee->ID,
                    $leaveType->id,
                    $this->periodId,
                    $amount,
                    $description ?? 'Solde initial importé'
                );

                $this->successCount++;

            } catch (\Exception $e) {
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
    }

    public function rules(): array
    {
        return [
            // Les règles sont gérées manuellement dans collection()
        ];
    }

    public function customValidationMessages()
    {
        return [];
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