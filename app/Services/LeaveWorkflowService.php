<?php
namespace App\Services;

use App\Models\LeaveWorkflow;
use App\Models\SiteLeaveWorkflowSetting;
use App\Models\Employe;
use App\Models\LeaveRequest;

class LeaveWorkflowService
{
    /**
     * Récupère le workflow applicable pour un employé
     * Priorité : site spécifique → global → défaut
     */
    public function getWorkflowForEmployee(int $employeeId): ?LeaveWorkflow
    {
        $employee = Employe::find($employeeId);
        $siteId = $employee->SiegeID;

        // 1. Workflow spécifique au site (via site_leave_workflow_settings)
        $siteSetting = SiteLeaveWorkflowSetting::where('site_id', $siteId)
                        ->where('is_active', true)
                        ->with('workflow')
                        ->first();

        if ($siteSetting && $siteSetting->workflow) {
            return $siteSetting->workflow;
        }

        // 2. Workflow global par défaut (is_default = 1, site_id = NULL)
        $default = LeaveWorkflow::where('is_default', true)
                    ->where('is_active', true)
                    ->first();

        if ($default) {
            return $default;
        }

        // 3. Fallback : créer un workflow minimal en base ?
        // Ou retourner null et lever une exception.
        throw new \Exception('Aucun workflow de validation configuré pour cet employé.');
    }

    /**
     * Récupère les responsables (IDs de la table administration) pour une étape donnée
     * selon le rôle défini dans l'étape.
     */
    public function getResponsiblesForStep(Employe $employee, array $step): array
    {
        $role = $step['role'] ?? 'manager';
        $adminIds = [];

        switch ($role) {
            case 'manager':
                // Le manager direct de l'employé
                $managerId = $employee->manager_id;
                if ($managerId) {
                    // Le manager est un employé, mais son compte administration peut être lié
                    // via le champ user_id ou en cherchant dans administration avec l'employé.
                    $manager = Employe::find($managerId);
                    if ($manager && $manager->user_id) {
                        $adminIds[] = $manager->user_id; // administration.ID
                    } else {
                        // Fallback : chercher un admin avec le même SiegeID et IsManager = 1
                        $admin = Administration::where('SiegeID', $employee->SiegeID)
                                    ->where('IsManager', 1)
                                    ->first();
                        if ($admin) $adminIds[] = $admin->ID;
                    }
                }
                break;

            case 'hr':
                // Responsable RH : on cherche un admin avec IsManager = 1 ou un rôle RH
                // Vous pouvez ajouter un champ IsHR dans administration.
                $admins = Administration::where('SiegeID', $employee->SiegeID)
                            ->where('IsManager', 1) // ou 'role' = 'hr'
                            ->get();
                foreach ($admins as $admin) {
                    $adminIds[] = $admin->ID;
                }
                break;

            case 'director':
                // Par exemple, le manager du manager (2e niveau)
                $manager = Employe::find($employee->manager_id);
                if ($manager && $manager->manager_id) {
                    $director = Employe::find($manager->manager_id);
                    if ($director && $director->user_id) {
                        $adminIds[] = $director->user_id;
                    }
                }
                break;

            default:
                // Rôle non reconnu : on peut lever une exception
                break;
        }

        return array_unique($adminIds);
    }

    /**
     * Envoie les notifications aux responsables de l'étape
     */
    public function notifyStep(LeaveRequest $request, array $step, array $adminIds)
    {
        // Utilisez votre service de notification existant
        foreach ($adminIds as $adminId) {
            // Créer une notification dans la table notifications
            // avec le message adapté.
        }
    }
}