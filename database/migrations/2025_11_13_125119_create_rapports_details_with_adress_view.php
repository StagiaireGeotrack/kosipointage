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
        DB::statement("CREATE VIEW `rapports_details_with_adress` AS select date_format(cast(`r`.`date_pointage` as date),'%d-%m-%Y') AS `date_pointage`,`r`.`date_pointage` AS `date_reel`,`r`.`SiegeID` AS `SiegeID`,`es`.`Nom` AS `siege_nom`,`r`.`employee_id` AS `employee_id`,`emp`.`Nom` AS `employee_nom`,`r`.`heure_entree` AS `heure_entree`,(select `e`.`Nom_Lieu_Ville` from (`kosi_pointage`.`pointages` `p` join `kosi_pointage`.`entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'entry' and cast(`p`.`timestamp_` as time) <= '12:00:00' order by `p`.`timestamp_` limit 1) AS `adresse_entree_matin`,`r`.`pause_dejeuner` AS `pause_dejeuner`,(select `e`.`Nom_Lieu_Ville` from (`kosi_pointage`.`pointages` `p` join `kosi_pointage`.`entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'exit' and cast(`p`.`timestamp_` as time) <= '14:00:00' order by `p`.`timestamp_` limit 1) AS `adresse_sortie_matin`,(select `e`.`Nom_Lieu_Ville` from (`kosi_pointage`.`pointages` `p` join `kosi_pointage`.`entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'entry' and cast(`p`.`timestamp_` as time) >= '12:00:00' order by `p`.`timestamp_` limit 1) AS `adresse_entree_apres_midi`,`r`.`heure_sortie` AS `heure_sortie`,(select `e`.`Nom_Lieu_Ville` from (`kosi_pointage`.`pointages` `p` join `kosi_pointage`.`entreprises` `e` on(`p`.`company_id` = `e`.`ID`)) where `p`.`employee_id` = `r`.`employee_id` and cast(`p`.`timestamp_` as date) = `r`.`date_pointage` and `p`.`type_` = 'exit' and cast(`p`.`timestamp_` as time) >= '14:00:00' order by `p`.`timestamp_` desc limit 1) AS `adresse_sortie_apres_midi`,`r`.`total_heure_journee` AS `total_heure_journee` from ((`kosi_pointage`.`rapports` `r` join `kosi_pointage`.`entreprises_sieges` `es` on(`r`.`SiegeID` = `es`.`ID`)) join `kosi_pointage`.`employes` `emp` on(`r`.`employee_id` = `emp`.`ID`)) order by `r`.`date_pointage`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `rapports_details_with_adress`");
    }
};
