<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE VIEW `rapports_details` AS select date_format(cast(`rapports`.`date_pointage` as date),'%d-%m-%Y') AS `date_pointage`,`rapports`.`date_pointage` AS `date_reel`,`rapports`.`SiegeID` AS `SiegeID`,`kosi_pointage`.`entreprises_sieges`.`Nom` AS `siege_nom`,`rapports`.`employee_id` AS `employee_id`,`kosi_pointage`.`employes`.`Nom` AS `employee_nom`,`rapports`.`heure_entree` AS `heure_entree`,`rapports`.`pause_dejeuner` AS `pause_dejeuner`,`rapports`.`heure_sortie` AS `heure_sortie`,`rapports`.`total_heure_journee` AS `total_heure_journee` from ((`kosi_pointage`.`rapports` join `kosi_pointage`.`entreprises_sieges` on(`rapports`.`SiegeID` = `kosi_pointage`.`entreprises_sieges`.`ID`)) join `kosi_pointage`.`employes` on(`rapports`.`employee_id` = `kosi_pointage`.`employes`.`ID`)) order by `rapports`.`date_pointage`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `rapports_details`");
    }
};
