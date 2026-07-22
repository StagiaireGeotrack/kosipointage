<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9px; color: #333; line-height: 1.4; padding: 20px; }

        /* En-tête employé */
        .doc-header { text-align: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 3px solid #2196F3; }
        .doc-header h1 { font-size: 18px; color: #2196F3; font-weight: bold; text-transform: uppercase; margin-bottom: 4px; }
        .doc-header .subtitle { font-size: 10px; color: #666; font-style: italic; }

        /* Info export */
        .export-info { background-color: #f5f5f5; padding: 8px 14px; margin-bottom: 16px; border-left: 4px solid #2196F3; }
        .export-info .info-row { display: inline-block; margin-right: 25px; font-size: 9px; }
        .info-label { font-weight: bold; color: #2196F3; }

        /* Bandeau de section */
        .section-banner { padding: 8px 14px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; font-size: 11px; color: white; }
        .section-rapport  { background-color: #2196F3; }
        .section-conge    { background-color: #4CAF50; }
        .section-ferie    { background-color: #FF9800; }
        .section-absence  { background-color: #f44336; }

        /* Compteur de section */
        .section-count { font-size: 9px; font-weight: normal; float: right; padding-top: 1px; }

        /* Tableau */
        table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 8px; }
        table thead th { color: white; padding: 8px 6px; text-align: left; font-weight: bold; font-size: 8px; border: 1px solid rgba(0,0,0,0.2); text-transform: uppercase; }
        .th-rapport  { background-color: #2196F3; }
        .th-conge    { background-color: #4CAF50; }
        .th-ferie    { background-color: #FF9800; }
        .th-absence  { background-color: #f44336; }
        table tbody td { padding: 6px; border: 1px solid #ddd; font-size: 8px; vertical-align: middle; }
        table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        table tbody tr:nth-child(odd)  { background-color: #ffffff; }

        /* Aucune donnée */
        .no-data { text-align: center; padding: 20px; font-size: 10px; color: #999; font-style: italic; border: 1px dashed #ddd; border-radius: 4px; margin-top: 6px; }

        /* Saut de page */
        .page-break { page-break-before: always; padding-top: 10px; }

        /* Pied de page */
        .footer { margin-top: 25px; padding-top: 12px; border-top: 2px solid #ddd; text-align: center; font-size: 8px; color: #666; }
        .footer .footer-bold { font-weight: bold; color: #2196F3; }

        @page { margin: 15mm; }
        table tr { page-break-inside: avoid; }
    </style>
</head>
<body>

    {{-- ===== EN-TÊTE DU DOCUMENT ===== --}}
    <div class="doc-header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Rapport complet de présence</div>
    </div>

    <div class="export-info">
        <div class="info-row"><span class="info-label">Date d'export :</span> {{ $date }}</div>
        <div class="info-row"><span class="info-label">Exporté par :</span> {{ $user }}</div>
        @if(!empty($filters['date_from']) && !empty($filters['date_to']))
        <div class="info-row"><span class="info-label">Période :</span> {{ $filters['date_from'] }} → {{ $filters['date_to'] }}</div>
        @endif
    </div>

    {{-- ===== SECTION 1 : RAPPORT DE PRÉSENCE ===== --}}
    <div class="section-banner section-rapport">
        📋 Rapport de présence
        <span class="section-count">{{ $rapport->count() }} enregistrement(s)</span>
    </div>

    @if($rapport->isNotEmpty())
    <table>
        <thead>
            <tr>
                @foreach(array_keys($rapport->first()) as $header)
                    <th class="th-rapport">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rapport as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell ?? '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="no-data">📭 Aucun enregistrement de présence sur cette période.</div>
    @endif

    {{-- ===== SECTION 2 : CONGÉS ===== --}}
    <div class="page-break">
        <div class="section-banner section-conge">
            🏖️ Congés
            <span class="section-count">{{ $conges->count() }} enregistrement(s)</span>
        </div>

        @if($conges->isNotEmpty())
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($conges->first()) as $header)
                        <th class="th-conge">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($conges as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="no-data">📭 Aucun congé enregistré sur cette période.</div>
        @endif
    </div>

    {{-- ===== SECTION 3 : JOURS FÉRIÉS / NON TRAVAILLÉS ===== --}}
    <div class="page-break">
        <div class="section-banner section-ferie">
            📅 Jours fériés / non travaillés
            <span class="section-count">{{ $feries->count() }} enregistrement(s)</span>
        </div>

        @if($feries->isNotEmpty())
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($feries->first()) as $header)
                        <th class="th-ferie">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($feries as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="no-data">📭 Aucun jour férié ou non travaillé sur cette période.</div>
        @endif
    </div>

    {{-- ===== SECTION 4 : ABSENCES ===== --}}
    <div class="page-break">
        <div class="section-banner section-absence">
            ⚠️ Absences
            <span class="section-count">{{ $absences->count() }} jour(s)</span>
        </div>

        @if($absences->isNotEmpty())
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($absences->first()) as $header)
                        <th class="th-absence">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($absences as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="no-data">✅ Aucune absence détectée sur cette période.</div>
        @endif
    </div>

    {{-- Pied de page --}}
    <div class="footer">
        <span class="footer-bold">{{ config('app.name', 'KOSI-TIME') }}</span> —
        Document généré le {{ $date }}
    </div>

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
