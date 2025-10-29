<?php
// app/Exports/GenericExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class GenericExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
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
            $firstItem = $this->data->first();
            
            // Convertir en array de manière sécurisée
            if (is_array($firstItem)) {
                $array = $firstItem;
            } elseif ($firstItem instanceof \stdClass) {
                $array = (array)$firstItem;
            } elseif (is_object($firstItem) && method_exists($firstItem, 'toArray')) {
                $array = $firstItem->toArray();
            } else {
                $array = (array)$firstItem;
            }
            
            return array_keys($array);
        }
        
        return [];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        // Style pour les en-têtes (ligne 1)
        $lastColumn = $sheet->getHighestColumn();
        
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFD3D3D3',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Bordures pour tout le tableau de données
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
        
        return [];
    }
}