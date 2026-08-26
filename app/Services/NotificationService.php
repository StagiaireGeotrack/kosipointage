<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\Employe;
use App\Models\Administration;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notifier le manager d'une nouvelle demande
     */
    public function notifyManager(LeaveRequest $leaveRequest)
    {
        // 1. Trouver le manager
        $manager = $this->findManager($leaveRequest->employee);
        
        if (!$manager) {
            Log::warning('Aucun manager trouvé pour la demande #' . $leaveRequest->id);
            return false;
        }

        // 2. Récupérer l'ID de l'utilisateur manager
        $userId = $this->getUserId($manager);
        
        if (!$userId) {
            Log::warning('Aucun ID utilisateur pour le manager');
            return false;
        }

        // 3. Créer la notification
        $employeeName = $leaveRequest->employee->Nom ?? 'Employé';
        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');
        $duration = number_format($leaveRequest->duration, 1);

        $message = "{$employeeName} a soumis une demande de {$leaveTypeName} du {$startDate} au {$endDate} ({$duration} jours)";

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_pending',
            'title' => ' Nouvelle demande de congé',
            'message' => $message,
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info('Notification envoyée au manager', [
            'manager_id' => $userId,
            'request_id' => $leaveRequest->id
        ]);

        return true;
    }

    /**
     * Notifier l'employé que sa demande est approuvée
     */
    public function notifyEmployeeApproved(LeaveRequest $leaveRequest)
    {
        $employee = $leaveRequest->employee;
        
        $userId = $this->getUserId($employee);
        
        if (!$userId) {
            Log::warning('Aucun ID utilisateur pour l\'employé', ['employee_id' => $employee->ID]);
            return false;
        }

        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_approved',
            'title' => 'Demande de congé approuvée',
            'message' => "Votre demande de {$leaveTypeName} du {$startDate} au {$endDate} a été approuvée.",
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    /**
     * Notifier l'employé que sa demande est refusée
     */
    public function notifyEmployeeRejected(LeaveRequest $leaveRequest)
    {
        $employee = $leaveRequest->employee;
        
        $userId = $this->getUserId($employee);
        
        if (!$userId) {
            return false;
        }

        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');
        $reason = $leaveRequest->rejection_reason ?? 'Non spécifié';

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_rejected',
            'title' => ' Demande de congé refusée',
            'message' => "Votre demande de {$leaveTypeName} du {$startDate} au {$endDate} a été refusée. Motif: {$reason}",
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    /**
     * ✅ Notifier l'employé que son congé est annulé
     */
    public function notifyEmployeeCancelled(LeaveRequest $leaveRequest)
    {
        $employee = $leaveRequest->employee;
        
        $userId = $this->getUserId($employee);
        
        if (!$userId) {
            Log::warning('Aucun ID utilisateur pour l\'employé', ['employee_id' => $employee->ID]);
            return false;
        }

        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_cancelled',
            'title' => ' Congé annulé',
            'message' => "Votre congé de {$leaveTypeName} du {$startDate} au {$endDate} a été annulé. Vos jours ont été recrédités.",
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info('Notification d\'annulation envoyée', [
            'employee_id' => $employee->ID,
            'request_id' => $leaveRequest->id
        ]);

        // ✅ Notifier aussi le manager si nécessaire
        $this->notifyManagerCancelled($leaveRequest);

        return true;
    }

    /**
     * ✅ Notifier le manager qu'un congé a été annulé
     */
    public function notifyManagerCancelled(LeaveRequest $leaveRequest)
    {
        $manager = $this->findManager($leaveRequest->employee);
        
        if (!$manager) {
            return false;
        }

        $userId = $this->getUserId($manager);
        
        if (!$userId) {
            return false;
        }

        $employeeName = $leaveRequest->employee->Nom ?? 'Employé';
        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_cancelled_manager',
            'title' => ' Congé annulé par l\'employé',
            'message' => "{$employeeName} a annulé son congé de {$leaveTypeName} du {$startDate} au {$endDate}.",
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Log::info('Notification d\'annulation envoyée au manager', [
            'manager_id' => $userId,
            'request_id' => $leaveRequest->id
        ]);

        return true;
    }

    /**
     * Notifier l'employé qu'une demande est en attente de modification
     */
    public function notifyEmployeeModificationRequested(LeaveRequest $leaveRequest)
    {
        $employee = $leaveRequest->employee;
        
        $userId = $this->getUserId($employee);
        
        if (!$userId) {
            return false;
        }

        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $startDate = $leaveRequest->start_date->format('d/m/Y');
        $endDate = $leaveRequest->end_date->format('d/m/Y');

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_modification_requested',
            'title' => ' Modification demandée',
            'message' => "Une modification est demandée pour votre demande de {$leaveTypeName} du {$startDate} au {$endDate}.",
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    /**
     * Notifier le manager qu'une demande a été soumise (rappel)
     */
    public function remindManager(LeaveRequest $leaveRequest)
    {
        $manager = $this->findManager($leaveRequest->employee);
        
        if (!$manager) {
            return false;
        }

        $userId = $this->getUserId($manager);
        
        if (!$userId) {
            return false;
        }

        $employeeName = $leaveRequest->employee->Nom ?? 'Employé';
        $leaveTypeName = $leaveRequest->leaveType->name ?? 'Congé';
        $daysPending = now()->diffInDays($leaveRequest->created_at);

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'type' => 'leave_reminder',
            'title' => ' Rappel: Demande de congé en attente',
            'message' => "La demande de {$leaveTypeName} de {$employeeName} est en attente depuis {$daysPending} jours.",
            'leave_request_id' => $leaveRequest->id,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    /**
     * Trouver le manager d'un employé
     */
    private function findManager(Employe $employee)
    {
        // 1. Manager direct (manager_id)
        if ($employee->manager_id) {
            $manager = Employe::find($employee->manager_id);
            if ($manager) {
                return $manager;
            }
        }

        // 2. Manager du département
        if ($employee->department_id) {
            $department = Department::find($employee->department_id);
            if ($department && $department->manager_employee_id) {
                $manager = Employe::find($department->manager_employee_id);
                if ($manager) {
                    return $manager;
                }
            }
        }

        // 3. Manager du site (administration.IsManager = 1)
        if ($employee->SiegeID) {
            $admin = Administration::where('SiegeID', $employee->SiegeID)
                ->where('IsManager', 1)
                ->where('Actived', 1)
                ->first();
            if ($admin) {
                return $admin;
            }
        }

        // 4. Fallback: Super Admin
        $superAdmin = Administration::where('IsSuperAdmin', 1)
            ->where('Actived', 1)
            ->first();
        
        return $superAdmin;
    }

    /**
     * Récupérer l'ID de l'utilisateur (administration)
     */
    private function getUserId($user)
    {
        // Si c'est un administration
        if ($user instanceof Administration) {
            return $user->ID;
        }

        // Si c'est un employé
        if ($user instanceof Employe) {
            // Si l'employé a un compte admin
            if ($user->user_id) {
                return $user->user_id;
            }
            // Sinon, chercher un compte admin lié à cet employé
            $admin = Administration::where('SiegeID', $user->SiegeID)
                ->where('IsManager', 1)
                ->first();
            if ($admin) {
                return $admin->ID;
            }
        }

        return null;
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($notificationId, $userId)
    {
        return DB::table('notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
                'updated_at' => now()
            ]);
    }

    /**
     * Récupérer les notifications non lues d'un utilisateur
     */
    public function getUnreadNotifications($userId)
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer toutes les notifications d'un utilisateur
     */
    public function getNotifications($userId, $limit = 50)
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Compter les notifications non lues
     */
    public function countUnread($userId)
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
    }
}