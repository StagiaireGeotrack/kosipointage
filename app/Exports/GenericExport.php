<?php
// app/Exports/GenericExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class GenericExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $title;
    protected $columns;

    public function __construct(Collection $data, string $title, array $columns = [])
    {
        $this->data = $data;
        $this->title = $title;
        $this->columns = $columns;
    }

    public function collection()
    {
        // Si des colonnes spécifiques sont définies, filtrer les données
        if (!empty($this->columns)) {
            return $this->data->map(function ($item) {
                return collect($item)->only($this->columns)->toArray();
            });
        }
        return $this->data;
    }

    public function headings(): array
    {
        // Si des colonnes sont définies, utiliser ces noms comme en-têtes
        if (!empty($this->columns)) {
            return $this->columns;
        }
        
        // Sinon, utiliser les clés du premier élément comme en-têtes
        if ($this->data->isNotEmpty()) {
            return array_keys($this->data->first() instanceof \stdClass 
                ? (array)$this->data->first() 
                : $this->data->first()->toArray());
        }
        
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        // Ajouter un titre
        $sheet->mergeCells('A1:' . $this->getLastColumn($sheet) . '1');
        $sheet->setCellValue('A1', $this->title);
        
        // Styles pour le titre
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Styles pour les en-têtes
        $sheet->getStyle('A2:' . $this->getLastColumn($sheet) . '2')->getFont()->setBold(true);
        $sheet->getStyle('A2:' . $this->getLastColumn($sheet) . '2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:' . $this->getLastColumn($sheet) . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A2:' . $this->getLastColumn($sheet) . '2')->getFill()->getStartColor()->setARGB('FFD3D3D3');
        
        // Bordures pour le tableau
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A2:' . $this->getLastColumn($sheet) . ($this->data->count() + 2))->applyFromArray($styleArray);
        
        return [];
    }

    private function getLastColumn(Worksheet $sheet)
    {
        return $sheet->getHighestColumn();
    }
}