<?php
// app/Exports/LeaveBalanceExport.php

namespace App\Exports;

use App\Models\LeaveBalance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeaveBalanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $balances;

    public function __construct($balances)
    {
        $this->balances = $balances;
    }

    public function collection()
    {
        return $this->balances;
    }

    public function headings(): array
    {
        return [
            'Employé',
            'Matricule',
            'Siège',
            'Type de congé',
            'Période',
            'Acquis',
            'Pris',
            'En attente',
            'Restant',
            'Disponible',
        ];
    }

    public function map($balance): array
    {
        return [
            $balance->employee->Nom ?? 'N/A',
            $balance->employee->num_mat ?? 'N/A',
            $balance->employee->siege->Nom ?? 'N/A',
            $balance->leaveType->name ?? 'N/A',
            $balance->period->name ?? 'N/A',
            number_format($balance->total_entitled, 2),
            number_format($balance->total_taken, 2),
            number_format($balance->total_pending, 2),
            number_format($balance->remaining, 2),
            number_format($balance->remaining - $balance->total_pending, 2),
        ];
    }
}