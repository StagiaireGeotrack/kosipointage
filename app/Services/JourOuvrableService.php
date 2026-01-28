<?php
// app/Services/JourOuvrableService.php

namespace App\Services;

use App\Repositories\JourNonTravailleRepository;
use Carbon\Carbon;

class JourOuvrableService
{
    protected $jourNonTravailleRepository;
    
    public function __construct(JourNonTravailleRepository $jourNonTravailleRepository)
    {
        $this->jourNonTravailleRepository = $jourNonTravailleRepository;
    }
    
    /**
     * Calculer le nombre de jours ouvrables entre deux dates
     * (exclut weekends, jours fériés et autres jours non travaillés)
     */
    public function calculerJoursOuvrables(Carbon $dateDebut, Carbon $dateFin, ?int $siegeId = null): array
    {
        // Récupérer tous les jours non travaillés pour la période
        $joursNonTravailles = $this->getJoursNonTravaillesArray($dateDebut, $dateFin, $siegeId);
        
        $joursOuvrables = 0;
        
        // Cloner les dates pour ne pas les modifier
        $debut = $dateDebut->copy();
        $fin = $dateFin->copy();
        
        // Calculer la différence totale
        $diffJours = $debut->diffInDays($fin);
        $diffHeures = $debut->diffInHours($fin);
        
        // ✅ CAS 1 : Si c'est le même jour
        if ($debut->isSameDay($fin)) {
            if (!$debut->isWeekend() && !$this->estJourNonTravaille($debut, $joursNonTravailles)) {
                $heures = $debut->diffInHours($fin);
                
                if ($heures > 4) {
                    $joursOuvrables = 1;
                } else {
                    $joursOuvrables = 0.5;
                }
            }
        } 
        // ✅ CAS 2 : Plusieurs jours
        else {
            $currentDate = $debut->copy()->startOfDay();
            $premierJourHeures = 0;
            $dernierJourHeures = 0;
            
            while ($currentDate->lte($fin->copy()->endOfDay())) {
                // Vérifier si c'est un jour ouvrable
                if (!$currentDate->isWeekend() && !$this->estJourNonTravaille($currentDate, $joursNonTravailles)) {
                    
                    if ($currentDate->isSameDay($debut)) {
                        // Premier jour : compter les heures travaillées
                        $premierJourHeures = $debut->diffInHours($debut->copy()->endOfDay());
                    } 
                    elseif ($currentDate->isSameDay($fin)) {
                        // Dernier jour : compter les heures travaillées
                        $dernierJourHeures = $fin->copy()->startOfDay()->diffInHours($fin);
                    } 
                    else {
                        // Jour complet ouvrable
                        $joursOuvrables += 1;
                    }
                }
                
                $currentDate->addDay();
            }
            
            // ✅ Convertir le premier jour en demi-journée ou jour complet
            if ($premierJourHeures > 0) {
                if ($premierJourHeures > 4) {
                    $joursOuvrables += 1;
                } else {
                    $joursOuvrables += 0.5;
                }
            }
            
            // ✅ Convertir le dernier jour en demi-journée ou jour complet
            if ($dernierJourHeures > 0) {
                if ($dernierJourHeures > 4) {
                    $joursOuvrables += 1;
                } else {
                    $joursOuvrables += 0.5;
                }
            }
        }

        return [
            'jours' => $joursOuvrables,
            'heures' => 0,
            'total_heures' => round($joursOuvrables * 8, 1),
            'jours_calendrier' => $diffJours,
            'heures_calendrier' => $diffHeures,
        ];
    }
    
    /**
     * Récupérer les jours non travaillés sous forme de tableau de dates
     */
    private function getJoursNonTravaillesArray(Carbon $dateDebut, Carbon $dateFin, ?int $siegeId = null): array
    {
        $jours = $this->jourNonTravailleRepository->getJoursNonTravaillesPourPeriode(
            $dateDebut->format('Y-m-d'),
            $dateFin->format('Y-m-d'),
            $siegeId
        );
        
        return $jours->pluck('Date')->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))->toArray();
    }
    
    /**
     * Vérifier si une date est un jour non travaillé
     */
    private function estJourNonTravaille(Carbon $date, array $joursNonTravailles): bool
    {
        return in_array($date->format('Y-m-d'), $joursNonTravailles);
    }
    
    /**
     * Obtenir tous les jours fériés d'une année pour un siège
     */
    public function getJoursFeriesAnnee(int $annee, ?int $siegeId = null)
    {
        $debut = Carbon::create($annee, 1, 1)->format('Y-m-d');
        $fin = Carbon::create($annee, 12, 31)->format('Y-m-d');
        
        return $this->jourNonTravailleRepository->getJoursNonTravaillesPourPeriode($debut, $fin, $siegeId);
    }
}