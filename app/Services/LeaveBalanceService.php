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

        // Calculer le total des transactions
        $transactions = LeaveBalanceTransaction::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_id', $periodId)
            ->get();

        $totalCredits = $transactions->where('amount', '>', 0)->sum('amount');
        $totalDebits = abs($transactions->where('amount', '<', 0)->sum('amount'));

        $balance->total_entitled = $totalCredits;
        $balance->total_taken = $totalDebits;
        $balance->remaining = $totalCredits - $totalDebits;

        $balance->save();

        return $balance;
    }

    /**
     * Créer une transaction de solde
     */
    public function createTransaction($employeeId, $leaveTypeId, $periodId, $amount, $type, $description = null, $referenceId = null, $referenceType = null, $metadata = null)
    {
        $transaction = LeaveBalanceTransaction::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'period_id' => $periodId,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
            'metadata' => $metadata,
            'created_by' => Auth::user()?->ID ?? null,
            'created_at' => now(),
        ]);

        // Mettre à jour le solde
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
     * Débiter le solde d'un employé (validation de congé)
     */
    public function debitBalance($employeeId, $leaveTypeId, $periodId, $amount, $referenceId = null, $description = null)
    {
        // Vérifier que le solde est suffisant
        $balance = LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('period_id', $periodId)
            ->first();

        if ($balance && $balance->remaining < $amount) {
            // Vérifier si le solde négatif est autorisé
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
     * Créditer le solde d'un employé (annulation de congé)
     */
    public function creditBalance($employeeId, $leaveTypeId, $periodId, $amount, $referenceId = null, $description = null)
    {
        return $this->createTransaction(
            $employeeId,
            $leaveTypeId,
            $periodId,
            $amount,
            'credit',
            $description ?? 'Crédit suite à annulation de congé',
            $referenceId,
            'leave_request',
            ['type' => 'credit']
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

        return $balance->remaining - $balance->total_pending;
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
}