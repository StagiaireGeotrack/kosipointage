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
        DB::statement("CREATE VIEW `rapports_details_jour_nuit` AS select date_format(cast(`r`.`date_pointage` as date),'%d-%m-%Y') AS `date_pointage`,`r`.`date_pointage` AS `date_reel`,`r`.`SiegeID` AS `SiegeID`,`es`.`Nom` AS `siege_nom`,`r`.`employee_id` AS `employee_id`,`emp`.`Nom` AS `employee_nom`,`r`.`type_travail` AS `type_travail`,`r`.`heure_entree` AS `heure_entree`,`r`.`pause_dejeuner` AS `pause_dejeuner`,`r`.`heure_sortie` AS `heure_sortie`,`r`.`total_heure_journee` AS `total_heure_journee` from ((`kosi_pointage`.`rapports_jour_nuit` `r` join `kosi_pointage`.`entreprises_sieges` `es` on(`r`.`SiegeID` = `es`.`ID`)) join `kosi_pointage`.`employes` `emp` on(`r`.`employee_id` = `emp`.`ID`)) order by `r`.`date_pointage`,`r`.`heure_entree`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `rapports_details_jour_nuit`");
    }
};
