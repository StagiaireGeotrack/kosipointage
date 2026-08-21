<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeNotificationController extends Controller
{
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $employee = Auth::guard('employe')->user();
        
        // Si pas d'employé, essayer avec Auth::user()
        if (!$employee) {
            $employee = Auth::user();
        }
        
        // Si c'est un employé, récupérer son ID
        if ($employee && isset($employee->ID)) {
            $userId = $employee->ID;
        } else {
            // Fallback: utiliser l'ID 5 (Emile)
            $userId = 5;
        }
        
        // Récupérer les notifications
        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $unreadCount = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
        
        return view('employes.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $employee = Auth::guard('employe')->user();
        
        if (!$employee) {
            $employee = Auth::user();
        }
        
        $userId = $employee->ID ?? 5;
        
        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => 1,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $employee = Auth::guard('employe')->user();
        
        if (!$employee) {
            $employee = Auth::user();
        }
        
        $userId = $employee->ID ?? 5;
        
        $count = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}