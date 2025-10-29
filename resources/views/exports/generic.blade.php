<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        /* Reset et Configuration de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #333;
            line-height: 1.4;
            padding: 20px;
        }
        
        /* En-tête du document */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2196F3;
        }
        
        .header h1 {
            font-size: 20px;
            color: #2196F3;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .header .subtitle {
            font-size: 11px;
            color: #666;
            font-style: italic;
        }
        
        /* Informations d'export */
        .export-info {
            background-color: #f5f5f5;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-left: 4px solid #2196F3;
            border-radius: 3px;
        }
        
        .export-info .info-row {
            display: inline-block;
            margin-right: 30px;
            font-size: 9px;
        }
        
        .export-info .info-label {
            font-weight: bold;
            color: #2196F3;
        }
        
        .export-info .info-value {
            color: #333;
        }
        
        /* Statistiques (si présentes) */
        .statistics {
            background-color: #e3f2fd;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .statistics .stat-item {
            display: inline-block;
            margin: 0 15px;
            font-size: 10px;
        }
        
        .statistics .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #2196F3;
            display: block;
        }
        
        .statistics .stat-label {
            color: #666;
            font-size: 8px;
            text-transform: uppercase;
        }
        
        /* Tableau principal */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }

        table thead {
            background-color: #2196F3;
        }

        table thead th {
            color: white;
            background-color: #2196F3;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #1976D2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tbody td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 8px;
            vertical-align: middle;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        /* Badges pour statuts */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-success {
            background-color: #4CAF50;
            color: white;
        }
        
        .badge-danger {
            background-color: #f44336;
            color: white;
        }
        
        .badge-warning {
            background-color: #FF9800;
            color: white;
        }
        
        .badge-info {
            background-color: #2196F3;
            color: white;
        }
        
        /* Pied de page */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
        }
        
        .footer .footer-content {
            font-size: 8px;
            color: #666;
            line-height: 1.6;
        }
        
        .footer .footer-bold {
            font-weight: bold;
            color: #2196F3;
        }
        
        .footer .page-number {
            font-size: 7px;
            color: #999;
            margin-top: 5px;
        }
        
        /* Pagination pour PDF */
        @page {
            margin: 15mm;
        }
        
        /* Empêcher les coupures de lignes */
        table tr {
            page-break-inside: avoid;
        }
        
        /* Message si pas de données */
        .no-data {
            text-align: center;
            padding: 40px;
            font-size: 12px;
            color: #999;
            font-style: italic;
        }
        
        /* Responsive pour colonnes nombreuses */
        @media print {
            table {
                font-size: 7px;
            }
            
            table thead th {
                font-size: 8px;
                padding: 6px 4px;
            }
            
            table tbody td {
                font-size: 7px;
                padding: 5px 4px;
            }
        }
    </style>
</head>
<body>
    {{-- En-tête du document --}}
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Export des données</div>
    </div>
    
    {{-- Informations d'export --}}
    <div class="export-info">
        <div class="info-row">
            <span class="info-label">📅 Date d'export :</span>
            <span class="info-value">{{ $date }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">👤 Exporté par :</span>
            <span class="info-value">{{ $user }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">📊 Total :</span>
            <span class="info-value">{{ $data->count() }} enregistrement(s)</span>
        </div>
    </div>
    
    {{-- Statistiques (optionnel) --}}
    @if(isset($statistics))
    <div class="statistics">
        @foreach($statistics as $stat)
        <div class="stat-item">
            <span class="stat-number">{{ $stat['value'] }}</span>
            <span class="stat-label">{{ $stat['label'] }}</span>
        </div>
        @endforeach
    </div>
    @endif
    
    {{-- Tableau de données --}}
    @if($data->isNotEmpty())
    <table>
        <thead>
            <tr>
                @foreach(array_keys($data->first()) as $header)
                    <th>{{ ucfirst(str_replace(['_', 'ID'], [' ', ''], $header)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @foreach($row as $key => $cell)
                        <td>
                            {{-- Gérer les différents types de données --}}
                            @if(is_bool($cell))
                                @if($cell)
                                    <span class="badge badge-success">Oui</span>
                                @else
                                    <span class="badge badge-danger">Non</span>
                                @endif
                            @elseif(is_numeric($cell) && strlen($cell) > 10)
                                {{-- Formater les grands nombres --}}
                                {{ number_format($cell, 0, ',', ' ') }}
                            @elseif(preg_match('/^\d{4}-\d{2}-\d{2}/', $cell))
                                {{-- Formater les dates --}}
                                {{ \Carbon\Carbon::parse($cell)->format('d/m/Y H:i') }}
                            @else
                                {{ $cell ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>📭 Aucune donnée disponible pour l'export</p>
    </div>
    @endif
    
    {{-- Pied de page --}}
    <div class="footer">
        <div class="footer-content">
            <p>
                <span class="footer-bold">{{ config('app.name', 'Application') }}</span><br>
                Document généré automatiquement le {{ $date }}<br>
                Ce document contient <span class="footer-bold">{{ $data->count() }}</span> enregistrement(s)
            </p>
        </div>
        <div class="page-number">
            Page <span class="pageNumber"></span>
        </div>
    </div>
    
    {{-- Script pour la numérotation des pages (si supporté par DomPDF) --}}
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 30;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>