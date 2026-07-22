<?php
// database/seeders/CongeValidationSeeder.php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\CongeValidation;
use Illuminate\Database\Seeder;

class CongeValidationSeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            [
                'SiegeID'          => 1, // Geotrack Solution SIEGE
                'matricule'        => 'EMP-001',
                'nom_prenom'       => 'Jean-Marc Dupont',
                'email'            => 'jm.dupont@example.com',
                'telephone'        => '+33 6 01 02 03 04',
                'date_heure_debut' => Carbon::create(2026, 6, 2, 8, 0),
                'date_heure_fin'   => Carbon::create(2026, 6, 6, 17, 0),
                'status'           => 'validated',
                'date_creation'    => Carbon::now()->subDays(10),
                'date_validation'  => Carbon::now()->subDays(8),
            ],
            [
                'SiegeID'          => 2, // Run-Telemat SIEGE
                'matricule'        => 'EMP-002',
                'nom_prenom'       => 'Marie Kouassi',
                'email'            => 'm.kouassi@example.com',
                'telephone'        => '+225 07 12 34 56',
                'date_heure_debut' => Carbon::create(2026, 6, 9, 8, 0),
                'date_heure_fin'   => Carbon::create(2026, 6, 13, 17, 0),
                'status'           => 'en_cours',
                'date_creation'    => Carbon::now()->subDays(3),
                'date_validation'  => null,
            ],
            [
                'SiegeID'          => 3, // ISLAND FOOD
                'matricule'        => 'EMP-003',
                'nom_prenom'       => 'Franck Nguyen',
                'email'            => 'f.nguyen@example.com',
                'telephone'        => null,
                'date_heure_debut' => Carbon::create(2026, 5, 19, 8, 0),
                'date_heure_fin'   => Carbon::create(2026, 5, 23, 17, 0),
                'status'           => 'not_validated',
                'date_creation'    => Carbon::now()->subDays(20),
                'date_validation'  => Carbon::now()->subDays(18),
            ],
            [
                'SiegeID'          => 1, // Geotrack Solution SIEGE
                'matricule'        => null,
                'nom_prenom'       => 'Aïcha Traoré',
                'email'            => 'a.traore@example.com',
                'telephone'        => '+221 77 98 76 54',
                'date_heure_debut' => Carbon::create(2026, 7, 14, 0, 0),
                'date_heure_fin'   => Carbon::create(2026, 7, 25, 23, 59),
                'status'           => 'en_cours',
                'date_creation'    => Carbon::now()->subDay(),
                'date_validation'  => null,
            ],
            [
                'SiegeID'          => 4, // PRO ELEC SARL
                'matricule'        => 'EMP-005',
                'nom_prenom'       => 'Stéphane Bernard',
                'email'            => 's.bernard@example.com',
                'telephone'        => '+33 6 55 44 33 22',
                'date_heure_debut' => Carbon::create(2026, 8, 3, 8, 0),
                'date_heure_fin'   => Carbon::create(2026, 8, 14, 17, 0),
                'status'           => 'validated',
                'date_creation'    => Carbon::now()->subDays(5),
                'date_validation'  => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($records as $record) {
            CongeValidation::create($record);
        }
    }
}
