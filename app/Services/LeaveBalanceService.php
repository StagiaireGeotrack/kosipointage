<?php
// app/Services/LeaveBalanceService.php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\Employe;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LeaveBalanceService
{
    /**
     * Créer ou mettre à jour le solde d'un employé
     */
    // app/Services/LeaveBalanceService.php

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

    // ✅ Les crédits qui AUGMENTENT le droit (opening, carryover, adjustments positifs)
    $totalEntitled = $transactions->filter(function($t) {
            return in_array($t->type, ['opening', 'carryover'])
                || ($t->type === 'adjustment' && $t->amount > 0);
        })
        ->sum('amount');

    // ✅ Les crédits qui ANNULENT des débits (credit)
    $totalCreditsFromCancellation = $transactions->filter(function($t) {
            return $t->type === 'credit' && $t->amount > 0;
        })
        ->sum('amount');

    // ✅ Les débits (prises de congé)
    $totalDebits = $transactions->filter(function($t) {
            return $t->type === 'debit' && $t->amount < 0;
        })
        ->sum('amount'); // somme négative

    // ✅ Les débits en attente
    $totalPending = $transactions->filter(function($t) {
            return $t->type === 'pending_debit';
        })
        ->sum('amount');

    // ✅ Calcul du solde restant
    // total_entitled = droits initiaux (opening + carryover + adjustments positifs)
    // total_taken = total des débits (absolus) - crédits d'annulation
    $totalTaken = abs($totalDebits) - $totalCreditsFromCancellation;
    
    // ✅ S'assurer que total_taken n'est pas négatif
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
     * ✅ Créer une transaction de solde - AVEC VÉRIFICATION DE DOUBLON
     */
    // app/Services/LeaveBalanceService.php

/**
 * ✅ Créer une transaction de solde - AVEC VÉRIFICATION DE DOUBLON AMÉLIORÉE
 */
public function createTransaction($employeeId, $leaveTypeId, $periodId, $amount, $type, $description = null, $referenceId = null, $referenceType = null, $metadata = null)
{
    // ✅ VÉRIFICATION DE DOUBLON PLUS ROBUSTE
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
                'new_amount' => $amount
            ]);
            
            // ✅ Mettre à jour le solde
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

    // ✅ Mise à jour du solde
    $this->updateBalance($employeeId, $leaveTypeId, $periodId);

    return $transaction;
}

    /**
     * Initialiser le solde d'un employé (solde initial)
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
     * ✅ Débiter le solde d'un employé - AVEC VÉRIFICATION
     */
    public function debitBalance($employeeId, $leaveTypeId, $periodId, $amount, $referenceId = null, $description = null)
    {
        // ✅ Vérifier si un débit existe déjà pour cette référence
        if ($referenceId) {
            $existing = LeaveBalanceTransaction::where('reference_id', $referenceId)
                ->where('reference_type', 'leave_request')
                ->where('type', 'debit')
                ->first();

            if ($existing) {
                Log::warning('Débit déjà existant pour cette demande', [
                    'reference_id' => $referenceId,
                    'existing_id' => $existing->id
                ]);
                return $existing;
            }
        }

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
     * ✅ Créditer le solde d'un employé - AVEC VÉRIFICATION
     */
   // app/Services/LeaveBalanceService.php

/**
 * ✅ Créditer le solde d'un employé - AVEC VÉRIFICATION ROBUSTE
 */
public function creditBalance($employeeId, $leaveTypeId, $periodId, $amount, $referenceId = null, $description = null)
{
    // ✅ Vérifier si un crédit existe déjà pour cette référence
    if ($referenceId) {
        $existing = LeaveBalanceTransaction::where('reference_id', $referenceId)
            ->where('reference_type', 'leave_request')
            ->where('type', 'credit')
            ->where('amount', '>', 0) // ✅ s'assurer que c'est un crédit positif
            ->first();

        if ($existing) {
            Log::info('Crédit déjà existant pour cette référence', [
                'reference_id' => $referenceId,
                'reference_type' => 'leave_request',
                'transaction_id' => $existing->id,
                'amount' => $existing->amount
            ]);
            
            // ✅ Mettre à jour le solde quand même
            $this->updateBalance($employeeId, $leaveTypeId, $periodId);
            
            return $existing;
        }
    }

    return $this->createTransaction(
        $employeeId,
        $leaveTypeId,
        $periodId,
        $amount, // ✅ amount positif pour un crédit
        'credit',
        $description ?? 'Crédit suite à annulation de congé',
        $referenceId,
        'leave_request',
        ['type' => 'credit', 'cancelled_request' => true]
    );
}

    /**
     * Ajuster manuellement le solde d'un employé
     */
    public function adjustBalance($employeeId, $leaveTypeId, $periodId, $amount, $description, $metadata = null)
    {
        if (empty($description)) {
            throw new \Exception('Un motif est obligatoire pour un ajustement manuel.');
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
     * Obtenir le solde d'un employé pour un type de congé
     */
    public function getBalance($employeeId, $leaveTypeId, $periodId)
    {
        return LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_id', $periodId)
            ->first();
    }

    /**
     * Obtenir le solde disponible d'un employé pour un type de congé
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
     * Vérifier si un employé a assez de solde pour un type de congé
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
     * Obtenir l'historique des transactions d'un employé
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