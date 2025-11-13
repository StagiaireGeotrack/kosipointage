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
        DB::statement("CREATE VIEW `rapports` AS select `kosi_pointage`.`pointages`.`SiegeID` AS `SiegeID`,`kosi_pointage`.`pointages`.`employee_id` AS `employee_id`,cast(`kosi_pointage`.`pointages`.`timestamp_` as date) AS `date_pointage`,date_format(min(case when `kosi_pointage`.`pointages`.`type_` = 'entry' then `kosi_pointage`.`pointages`.`timestamp_` end),'%H:%i:%s') AS `heure_entree`,concat(date_format(max(case when `kosi_pointage`.`pointages`.`type_` = 'exit' and cast(`kosi_pointage`.`pointages`.`timestamp_` as time) < '14:00:00' then `kosi_pointage`.`pointages`.`timestamp_` end),'%H:%i:%s'),' - ',date_format(min(case when `kosi_pointage`.`pointages`.`type_` = 'entry' and cast(`kosi_pointage`.`pointages`.`timestamp_` as time) > '12:00:00' then `kosi_pointage`.`pointages`.`timestamp_` end),'%H:%i:%s')) AS `pause_dejeuner`,date_format(max(case when `kosi_pointage`.`pointages`.`type_` = 'exit' then `kosi_pointage`.`pointages`.`timestamp_` end),'%H:%i:%s') AS `heure_sortie`,sec_to_time(coalesce(sum(case when `kosi_pointage`.`pointages`.`type_` = 'exit' then time_to_sec(cast(`kosi_pointage`.`pointages`.`timestamp_` as time)) else 0 end) - sum(case when `kosi_pointage`.`pointages`.`type_` = 'entry' then time_to_sec(cast(`kosi_pointage`.`pointages`.`timestamp_` as time)) else 0 end),0)) AS `total_heure_journee` from `kosi_pointage`.`pointages` group by `kosi_pointage`.`pointages`.`SiegeID`,`kosi_pointage`.`pointages`.`employee_id`,cast(`kosi_pointage`.`pointages`.`timestamp_` as date)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `rapports`");
    }
};
