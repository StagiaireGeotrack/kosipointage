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
        $heuresOuvrables = 0;
        
        // Cloner les dates pour ne pas les modifier
        $debut = $dateDebut->copy();
        $fin = $dateFin->copy();
        
        // Calculer la différence totale
        $diffJours = $debut->diffInDays($fin);
        $diffHeures = $debut->diffInHours($fin);
        
        // Si c'est le même jour
        if ($debut->isSameDay($fin)) {
            if (!$debut->isWeekend() && !$this->estJourNonTravaille($debut, $joursNonTravailles)) {
                $heuresOuvrables = $debut->diffInHours($fin);
            }
        } else {
            // Parcourir chaque jour de la période
            $currentDate = $debut->copy()->startOfDay();
            
            while ($currentDate->lte($fin->copy()->endOfDay())) {
                // Vérifier si c'est un jour ouvrable
                if (!$currentDate->isWeekend() && !$this->estJourNonTravaille($currentDate, $joursNonTravailles)) {
                    
                    if ($currentDate->isSameDay($debut)) {
                        // Premier jour : compter les heures jusqu'à minuit
                        $heuresOuvrables += $debut->diffInHours($debut->copy()->endOfDay());
                    } elseif ($currentDate->isSameDay($fin)) {
                        // Dernier jour : compter les heures depuis minuit
                        $heuresOuvrables += $fin->copy()->startOfDay()->diffInHours($fin);
                    } else {
                        // Jour complet : 24 heures
                        $joursOuvrables++;
                    }
                }
                
                $currentDate->addDay();
            }
        }
        
        // Convertir les heures en jours si >= 24h
        $joursSupplementaires = floor($heuresOuvrables / 24);
        $joursOuvrables += $joursSupplementaires;
        $heuresRestantes = $heuresOuvrables % 24;
        
        return [
            'jours' => $joursOuvrables,
            'heures' => round($heuresRestantes, 1),
            'total_heures' => round(($joursOuvrables * 24) + $heuresRestantes, 1),
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
        
        return $jours->pluck('Date')->map(fn($date) => $date->format('Y-m-d'))->toArray();
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