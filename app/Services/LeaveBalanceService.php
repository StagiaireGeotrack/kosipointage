<?php
// app/Services/LeaveBalanceService.php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\Employe;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveBalanceService
{
    /**
     * Créer ou mettre à jour le solde d'un employé
     */
    public function updateBalance($employeeId, $leaveTypeId, $periodId)
    {
        $balance = LeaveBalance::firstOrNew([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'period_id' => $periodId,
        ]);

        $transactions = LeaveBalanceTransaction::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_id', $periodId)
            ->get();

        // ✅ Log des transactions
        \Log::info('updateBalance - Transactions', [
            'employee_id' => $employeeId,
            'transactions' => $transactions->map(fn($t) => [
                'type' => $t->type,
                'amount' => $t->amount,
                'description' => $t->description
            ])->toArray()
        ]);

        // ✅ 1. TOTAL DES DROITS (opening, carryover, adjustment positif)
        $totalEntitled = $transactions->filter(function($t) {
                return in_array($t->type, ['opening', 'carryover'])
                    || ($t->type === 'adjustment' && $t->amount > 0);
            })
            ->sum('amount');

        // ✅ 2. TOTAL DES DÉBITS (debit + adjustment négatif)
        $totalDebits = $transactions->filter(function($t) {
                return ($t->type === 'debit' && $t->amount < 0)
                    || ($t->type === 'adjustment' && $t->amount < 0);
            })
            ->sum('amount');

        // ✅ 3. TOTAL DES CRÉDITS D'ANNULATION (reversals)
        $totalReversals = $transactions->filter(function($t) {
                return $t->type === 'reversal' && $t->amount > 0;
            })
            ->sum('amount');

        // ✅ 4. TOTAL DES DÉBITS EN ATTENTE
        $totalPending = $transactions->filter(function($t) {
                return $t->type === 'pending_debit';
            })
            ->sum('amount');

        // ✅ 5. CALCUL FINAL
        $totalTaken = abs($totalDebits) - $totalReversals;
        
        if ($totalTaken < 0) {
            $totalTaken = 0;
        }

        $balance->total_entitled = $totalEntitled;
        $balance->total_taken = $totalTaken;
        $balance->total_pending = abs($totalPending);
        $balance->remaining = $totalEntitled - $totalTaken;

        $balance->save();

        return $balance;
    }

    /**
     * ✅ Créer une transaction de solde
     */
    public function createTransaction($employeeId, $leaveTypeId, $periodId, $amount, $type, $description = null, $referenceId = null, $referenceType = null, $metadata = null)
    {
        // ✅ VÉRIFICATION DE DOUBLON
        if ($referenceId && $referenceType) {
            $existing = LeaveBalanceTransaction::where('reference_id', $referenceId)
                ->where('reference_type', $referenceType)
                ->where('type', $type)
                ->first();

            if ($existing) {
                Log::warning('Transaction déjà existante', [
                    'reference_id' => $referenceId,
                    'reference_type' => $referenceType,
                    'type' => $type,
                    'existing_id' => $existing->id,
                    'existing_amount' => $existing->amount,
                ]);
                
                $this->updateBalance($employeeId, $leaveTypeId, $periodId);
                return $existing;
            }
        }

        // ✅ Création de la transaction
        $transaction = LeaveBalanceTransaction::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'period_id' => $periodId,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'created_by' => Auth::user()?->ID ?? null,
            'created_at' => now(),
        ]);

        $this->updateBalance($employeeId, $leaveTypeId, $periodId);

        return $transaction;
    }

    /**
     * Initialiser le solde d'un employé
     */
    public function initializeBalance($employeeId, $leaveTypeId, $periodId, $amount, $description = null)
    {
        return $this->createTransaction(
            $employeeId,
            $leaveTypeId,
            $periodId,
            $amount,
            'opening',
            $description ?? 'Solde initial',
            null,
            null,
            ['type' => 'initialization']
        );
    }

    /**
     * ✅ DÉBITER LE SOLDE (retrait de jours)
     */
    public function debitBalance($employeeId, $leaveTypeId, $periodId, $amount, $referenceId = null, $description = null)
    {
        if ($referenceId) {
            $existing = LeaveBalanceTransaction::where('reference_id', $referenceId)
                ->where('reference_type', 'leave_request')
                ->where('type', 'debit')
                ->first();

            if ($existing) {
                Log::info('Débit déjà existant, mise à jour du solde', [
                    'reference_id' => $referenceId,
                    'transaction_id' => $existing->id,
                    'amount' => $existing->amount
                ]);
                
                $this->updateBalance($employeeId, $leaveTypeId, $periodId);
                return $existing;
            }
        }

        // Vérifier le solde avant débit
        $balance = LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_id', $periodId)
            ->first();

        if ($balance && $balance->remaining < $amount) {
            $leaveType = LeaveType::find($leaveTypeId);
            if (!$leaveType || !$leaveType->allow_negative_balance) {
                throw new \Exception('Solde insuffisant pour ce type de congé.');
            }
        }

        return $this->createTransaction(
            $employeeId,
            $leaveTypeId,
            $periodId,
            -$amount,
            'debit',
            $description ?? 'Débit suite à validation de congé',
            $referenceId,
            'leave_request',
            ['type' => 'debit']
        );
    }

    /**
     * ✅ AJUSTER MANUELLEMENT LE SOLDE (ajout de jours)
     * Pour les retraits, utiliser debitBalance() à la place
     */
    public function adjustBalance($employeeId, $leaveTypeId, $periodId, $amount, $description, $metadata = null)
    {
        if (empty($description)) {
            throw new \Exception('Un motif est obligatoire pour un ajustement manuel.');
        }

        if ($amount <= 0) {
            throw new \Exception('Le montant doit être positif pour un ajustement. Utilisez debitBalance() pour les retraits.');
        }

        return $this->createTransaction(
            $employeeId,
            $leaveTypeId,
            $periodId,
            $amount,
            'adjustment',
            $description,
            null,
            null,
            array_merge(['type' => 'manual_adjustment'], $metadata ?? [])
        );
    }

    /**
     * ✅ ANNULER un débit (reversal)
     */
    public function reverseDebit($employeeId, $leaveTypeId, $periodId, $amount, $referenceId = null, $description = null)
    {
        if ($referenceId) {
            $existing = LeaveBalanceTransaction::where('reference_id', $referenceId)
                ->where('reference_type', 'leave_request')
                ->where('type', 'reversal')
                ->first();

            if ($existing) {
                Log::info('Reversal déjà existant', [
                    'reference_id' => $referenceId,
                    'transaction_id' => $existing->id,
                    'amount' => $existing->amount
                ]);
                
                $this->updateBalance($employeeId, $leaveTypeId, $periodId);
                return $existing;
            }
        }

        return $this->createTransaction(
            $employeeId,
            $leaveTypeId,
            $periodId,
            $amount,
            'reversal',
            $description ?? 'Annulation de congé',
            $referenceId,
            'leave_request',
            ['type' => 'reversal', 'cancelled_request' => true]
        );
    }

    /**
     * Reporter les jours non pris
     */
    public function carryoverBalance($employeeId, $leaveTypeId, $fromPeriodId, $toPeriodId, $amount, $description = null)
    {
        return $this->createTransaction(
            $employeeId,
            $leaveTypeId,
            $toPeriodId,
            $amount,
            'carryover',
            $description ?? 'Report des jours non pris',
            null,
            null,
            [
                'type' => 'carryover',
                'from_period_id' => $fromPeriodId,
                'to_period_id' => $toPeriodId,
            ]
        );
    }

    /**
     * Obtenir le solde d'un employé
     */
    public function getBalance($employeeId, $leaveTypeId, $periodId)
    {
        return LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_id', $periodId)
            ->first();
    }

    /**
     * Obtenir le solde disponible
     */
    public function getAvailableBalance($employeeId, $leaveTypeId, $periodId)
    {
        $balance = $this->getBalance($employeeId, $leaveTypeId, $periodId);
        
        if (!$balance) {
            return 0;
        }

        return $balance->remaining - ($balance->total_pending ?? 0);
    }

    /**
     * Vérifier si un employé a assez de solde
     */
    public function hasSufficientBalance($employeeId, $leaveTypeId, $periodId, $amount)
    {
        $available = $this->getAvailableBalance($employeeId, $leaveTypeId, $periodId);
        
        $leaveType = LeaveType::find($leaveTypeId);
        if ($leaveType && $leaveType->allow_negative_balance) {
            return true;
        }

        return $available >= $amount;
    }

    /**
     * Historique des transactions
     */
    public function getTransactionHistory($employeeId, $leaveTypeId = null, $periodId = null)
    {
        $query = LeaveBalanceTransaction::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc');

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        return $query->get();
    }
}