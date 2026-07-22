<?php
// app/Services/ExportService.php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GenericExport;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ExportService
{
    public function exportToExcel(Collection $data, string $title, array $columns = [])
    {
        // Ajout des informations d'export
        $exportData = $this->prepareExportData($data);
        
        return Excel::download(
            new GenericExport($exportData, $title, $columns),
            $title . '_' . Carbon::now()->isoFormat('dddd D MMMM YYYY - HH:mm:ss') . '.xlsx'
        );
    }
    
    public function exportToPdf(Collection $data, string $title, string $viewPath, array $extraData = [])
    {
        // Preparation des donnees pour le PDF
        $exportData = $this->prepareExportData($data);
        
        // Creation des donnees pour la vue
        $viewData = array_merge([
            'data'  => $exportData,
            'title' => $title,
            'date'  => Carbon::now()->isoFormat('dddd D MMMM YYYY - HH:mm:ss'),
            'user'  => Auth::user()->Identifiant_email,
        ], $extraData);
        
        // Generation du PDF
        $pdf = Pdf::loadView($viewPath, $viewData);
        
        // Configurer les options PDF
        $pdf->setPaper('a4', 'landscape');
        
        // Telechargement du PDF
        return $pdf->download($title . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Exporte un fichier Excel par employe dans un ZIP.
     * $dataByEmployee : ['Nom Employe' => array de lignes]
     */
    public function exportToExcelZip(array $dataByEmployee, string $title): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $zipPath = sys_get_temp_dir() . '/export_' . time() . rand(100, 999) . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($dataByEmployee as $employeeName => $rows) {
            $collection = $this->prepareExportData(collect($rows));
            $content    = Excel::raw(
                new GenericExport($collection, (string) $employeeName),
                \Maatwebsite\Excel\Excel::XLSX
            );
            $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', (string) $employeeName);
            $zip->addFromString($safe . '.xlsx', $content);
        }

        $zip->close();

        return response()->download(
            $zipPath,
            $title . '_' . Carbon::now()->format('Y-m-d') . '.zip'
        )->deleteFileAfterSend(true);
    }

    /**
     * Exporte un fichier PDF par employe dans un ZIP.
     * $dataByEmployee : ['Nom Employe' => array de lignes]
     */
    public function exportToPdfZip(array $dataByEmployee, string $title, string $viewPath, array $extraData = []): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $zipPath = sys_get_temp_dir() . '/export_' . time() . rand(100, 999) . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($dataByEmployee as $employeeName => $rows) {
            $exportData = $this->prepareExportData(collect($rows));

            $viewData = array_merge([
                'data'  => $exportData,
                'title' => $title . ' - ' . $employeeName,
                'date'  => Carbon::now()->isoFormat('dddd D MMMM YYYY - HH:mm:ss'),
                'user'  => Auth::user()->Identifiant_email,
            ], $extraData);

            $pdf  = Pdf::loadView($viewPath, $viewData)->setPaper('a4', 'landscape');
            $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', (string) $employeeName);
            $zip->addFromString($safe . '.pdf', $pdf->output());
        }

        $zip->close();

        return response()->download(
            $zipPath,
            $title . '_' . Carbon::now()->format('Y-m-d') . '.zip'
        )->deleteFileAfterSend(true);
    }

    protected function prepareExportData(Collection $data)
    {
        // Traiter les donnees pour l'export
        return $data->map(function ($item) {
            // Convertir en array selon le type
            if ($item instanceof \stdClass) {
                $array = (array)$item;
            } elseif (is_array($item)) {
                $array = $item;
            } else {
                $array = $item->toArray();
            }
            
            // Transformer les booleens en Oui/Non
            foreach ($array as $key => $value) {
                if (is_bool($value)) {
                    $array[$key] = $value ? 'Oui' : 'Non';
                }
            }
            
            return $array;
        });
    }
}