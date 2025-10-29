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
            $title . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
    
    public function exportToPdf(Collection $data, string $title, string $viewPath, array $extraData = [])
    {
        // Préparation des données pour le PDF
        $exportData = $this->prepareExportData($data);
        
        // Création des données pour la vue
        $viewData = array_merge([
            'data' => $exportData,
            'title' => $title,
            'date' => Carbon::now()->format('d/m/Y H:i'),
            'user' => Auth::user()->Identifiant_email,
        ], $extraData);
        
        // Génération du PDF
        $pdf = Pdf::loadView($viewPath, $viewData);
        
        // Configurer les options PDF si nécessaire
        $pdf->setPaper('a4', 'landscape');
        
        // Téléchargement du PDF
        return $pdf->download($title . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf');
    }
    
    protected function prepareExportData(Collection $data)
    {
        // Traiter les données pour l'export
        return $data->map(function ($item) {
            // Convertir en array selon le type
            if ($item instanceof \stdClass) {
                $array = (array)$item;
            } elseif (is_array($item)) {
                $array = $item;  // ✅ Déjà un array
            } else {
                $array = $item->toArray();  // Model Eloquent
            }
            
            // Transformer les booléens en Oui/Non
            foreach ($array as $key => $value) {
                if (is_bool($value)) {
                    $array[$key] = $value ? 'Oui' :'Non';
                }
            }
            
            return $array;
        });
    }
}