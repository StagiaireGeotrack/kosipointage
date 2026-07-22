<?php
// app/Exports/ReportMultiSheetExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;

class ReportMultiSheetExport implements WithMultipleSheets
{
    public function __construct(
        private Collection $rapport,
        private Collection $conges,
        private Collection $feries,
        private Collection $absences,
    ) {}

    public function sheets(): array
    {
        return [
            new GenericExport($this->rapport,  'Rapport'),
            new GenericExport($this->conges,   'Conges'),
            new GenericExport($this->feries,   'Jours Feries'),
            new GenericExport($this->absences, 'Absences'),
        ];
    }
}
