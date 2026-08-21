<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Liste des notifications pour le manager
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $userId = $user->ID ?? $user->id ?? null;
        
        if (!$userId) {
            return redirect()->route('dashboard')->with('error', 'Utilisateur non trouvé');
        }
        
        $notifications = $this->notificationService->getNotifications($userId);
        $unreadCount = $this->notificationService->countUnread($userId);
        
        return view('manager.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = auth()->user();
        $userId = $user->ID ?? $user->id ?? null;
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 401);
        }
        
        $this->notificationService->markAsRead($id, $userId);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue'
        ]);
    }

    /**
     * Compter les notifications non lues
     */
    public function unreadCount()
    {
        $user = auth()->user();
        $userId = $user->ID ?? $user->id ?? null;
        
        if (!$userId) {
            return response()->json(['count' => 0]);
        }
        
        $count = $this->notificationService->countUnread($userId);
        
        return response()->json([
            'count' => $count
        ]);
    }
}