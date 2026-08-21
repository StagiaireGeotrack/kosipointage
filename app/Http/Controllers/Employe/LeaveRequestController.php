<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveBalance;
use App\Models\Employe;
use App\Services\LeaveRequestService;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
   use App\Models\LeaveRequestAttachment;
use Illuminate\Support\Facades\Storage;
class LeaveRequestController extends Controller
{
    protected $leaveRequestService;
    protected $balanceService;

    public function __construct(LeaveRequestService $leaveRequestService, LeaveBalanceService $balanceService)
    {
        $this->leaveRequestService = $leaveRequestService;
        $this->balanceService = $balanceService;
    }

    /**
     * Récupérer l'employé connecté
     */
   /**
 * Récupérer l'employé connecté - Version corrigée
 */
/**
 * Récupérer l'employé connecté - Version améliorée
 */
private function getEmployee()
{
    // Vérifier si l'employé est déjà dans la session
    if (session()->has('employee_id')) {
        $employee = Employe::find(session('employee_id'));
        if ($employee) {
            return $employee;
        }
    }
    
    $user = Auth::user();
    
    if (!$user) {
        \Log::error('getEmployee: Aucun utilisateur connecté');
        return null;
    }
    
    \Log::info('getEmployee: User trouvé', [
        'user_id' => $user->ID ?? $user->id ?? 'unknown',
        'user_type' => get_class($user)
    ]);
    
    // Si l'utilisateur est un employé directement
    if ($user instanceof Employe) {
        \Log::info('getEmployee: User est un Employe');
        // Stocker dans la session pour les appels suivants
        session(['employee_id' => $user->ID]);
        return $user;
    }
    
    // Si l'utilisateur a une relation employee
    if (isset($user->employee) && $user->employee) {
        session(['employee_id' => $user->employee->ID]);
        return $user->employee;
    }
    
    // Si l'utilisateur a un employee_id
    if (isset($user->employee_id) && $user->employee_id) {
        $employee = Employe::find($user->employee_id);
        if ($employee) {
            session(['employee_id' => $employee->ID]);
            return $employee;
        }
    }
    
    // Récupérer l'employé par user_id
    $userId = $user->ID ?? $user->id ?? null;
    if ($userId) {
        $employee = Employe::where('user_id', $userId)->first();
        if ($employee) {
            session(['employee_id' => $employee->ID]);
            return $employee;
        }
    }
    
    // Fallback : récupérer le premier employé actif
    $employee = Employe::where('Actived', 1)->first();
    if ($employee) {
        session(['employee_id' => $employee->ID]);
        return $employee;
    }
    
    \Log::error('getEmployee: Aucun employé trouvé');
    return null;
}

    /**
     * Tableau de bord de l'employé
     */
public function dashboard()
{
    $employee = $this->getEmployee();
    
    if (!$employee) {
        return redirect()->route('employe.login')
            ->with('error', 'Aucun employé associé à ce compte.');
    }
    
    $balances = LeaveBalance::with(['leaveType', 'period'])
        ->where('employee_id', $employee->ID)
        ->get();

    $requests = $this->leaveRequestService->getEmployeeRequests($employee->ID);
    
    // Statistiques
    $stats = [
        'total_requests' => $requests->count(),
        'pending' => $requests->where('status', 'pending')->count(),
        'approved' => $requests->where('status', 'approved')->count(),
        'rejected' => $requests->where('status', 'rejected')->count(),
    ];
    
    // Statistiques mensuelles (12 mois)
    $monthlyStats = $requests->groupBy(function($request) {
        return $request->created_at->month;
    })->map->count()->toArray();
    
    // Remplir les mois manquants avec 0
    $monthlyStatsArray = [];
    for ($i = 1; $i <= 12; $i++) {
        $monthlyStatsArray[] = $monthlyStats[$i] ?? 0;
    }
    
    // Statistiques par type
    $typeStats = $requests->groupBy('leave_type_id')
        ->map(function($group) {
            return $group->count();
        });
    
    $typeLabels = [];
    $typeData = [];
    
    if ($typeStats->isNotEmpty()) {
        $types = LeaveType::whereIn('id', $typeStats->keys())->get();
        foreach ($types as $type) {
            $typeLabels[] = $type->name;
            $typeData[] = $typeStats[$type->id] ?? 0;
        }
    }

    return view('employes.dashboard', compact(
        'balances', 
        'requests', 
        'stats', 
        'monthlyStatsArray', 
        'typeLabels', 
        'typeData'
    ));
}
    /**
     * Liste des demandes de congé
     */
    public function index()
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        $requests = $this->leaveRequestService->getEmployeeRequests($employee->ID);
        
        return view('employes.leave_requests.index', compact('requests'));
    }

    /**
     * Formulaire de demande de congé
     */
    public function create()
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        $userSiteId = $employee->SiegeID;

        $leaveTypes = LeaveType::where('is_active', true)
            ->where(function ($q) use ($userSiteId) {
                $q->where('site_id', $userSiteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('name')
            ->get();

        $periods = LeavePeriod::where('is_active', true)
            ->where(function ($q) use ($userSiteId) {
                $q->where('site_id', $userSiteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('start_date', 'desc')
            ->get();

        return view('employes.leave_requests.create', compact('leaveTypes', 'periods'));
    }

    /**
     * Créer une demande de congé (brouillon)
     */
    public function store(Request $request)
{
    try {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun employé associé à ce compte.'
            ], 401);
        }

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'period_id' => 'required|exists:leave_periods,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
            'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,txt',
        ]);

        // Créer la demande
        $leaveRequest = $this->leaveRequestService->createRequest(
            $employee->ID,
            $request->leave_type_id,
            $request->period_id,
            $request->start_date,
            $request->end_date,
            $request->reason,
            $request->comment
        );

        // Gérer les fichiers joints
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('leave_attachments/' . $leaveRequest->id, $fileName, 'public');
                
                LeaveRequestAttachment::create([
                    'leave_request_id' => $leaveRequest->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => $employee->FirstName . ' ' . $employee->LastName
                ]);
            }
        }

        // Si le bouton "Soumettre" a été cliqué
        if ($request->has('submit')) {
            $this->leaveRequestService->submitRequest($leaveRequest->id);
            return response()->json([
                'success' => true,
                'message' => 'Demande soumise avec succès.',
                'redirect' => route('employe.leave-requests.index')
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Demande créée avec succès.',
            'redirect' => route('employe.leave-requests.show', $leaveRequest->id)
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Afficher une demande
     */
    public function show($id)
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        $request = LeaveRequest::where('employee_id', $employee->ID)
            ->with(['leaveType', 'period', 'approver'])
            ->findOrFail($id);

        return view('employes.leave_requests.show', compact('request'));
    }

    /**
     * Formulaire d'édition d'une demande (NOUVEAU)
     */
    public function edit($id)
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        // Récupérer la demande si elle est en brouillon
        $request = LeaveRequest::where('employee_id', $employee->ID)
            ->where('status', 'draft')
            ->findOrFail($id);
        
        $userSiteId = $employee->SiegeID;
        
        $leaveTypes = LeaveType::where('is_active', true)
            ->where(function ($q) use ($userSiteId) {
                $q->where('site_id', $userSiteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('name')
            ->get();
        
        $periods = LeavePeriod::where('is_active', true)
            ->where(function ($q) use ($userSiteId) {
                $q->where('site_id', $userSiteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('start_date', 'desc')
            ->get();
        
        return view('employes.leave_requests.edit', compact('request', 'leaveTypes', 'periods'));
    }

    /**
     * Mettre à jour une demande (NOUVEAU)
     */
    public function update(Request $request, $id)
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)
            ->where('status', 'draft')
            ->findOrFail($id);
        
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'period_id' => 'required|exists:leave_periods,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $leaveRequest->update([
            'leave_type_id' => $request->leave_type_id,
            'period_id' => $request->period_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'comment' => $request->comment,
        ]);
        
        // Si le bouton "Soumettre" a été cliqué
        if ($request->has('submit')) {
            $this->leaveRequestService->submitRequest($id);
            return redirect()->route('employe.leave-requests.index')
                ->with('success', 'Demande soumise avec succès.');
        }
        
        return redirect()->route('employe.leave-requests.show', $id)
            ->with('success', 'Demande mise à jour avec succès.');
    }

    /**
     * Soumettre une demande
     */
    public function submit($id)
    {
        try {
            $this->leaveRequestService->submitRequest($id);
            return redirect()->route('employe.leave-requests.index')
                ->with('success', 'Demande soumise avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Annuler une demande (brouillon)
     */
    public function destroy($id)
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        $request = LeaveRequest::where('employee_id', $employee->ID)
            ->where('status', 'draft')
            ->findOrFail($id);

        $request->delete();

        return redirect()->route('employe.leave-requests.index')
            ->with('success', 'Demande supprimée avec succès.');
    }





 

/**
 * Ajouter une pièce jointe
 */
/**
 * Ajouter une pièce jointe
 */
/**
 * Ajouter une pièce jointe - Version simplifiée
 */
/**
 * Ajouter une pièce jointe - Version de débogage
 */
/**
 * Ajouter une pièce jointe - Version corrigée
 */
public function uploadAttachment(Request $request, $id)
{
    try {
        \Log::info('=== UPLOAD ATTACHMENT START ===', [
            'id' => $id,
            'method' => $request->method(),
            'has_file' => $request->hasFile('attachment')
        ]);
        
        // Récupérer l'employé une seule fois
        $employee = $this->getEmployee();
        
        if (!$employee) {
            \Log::error('Aucun employé trouvé');
            return response()->json([
                'success' => false,
                'message' => 'Aucun employé associé à ce compte.'
            ], 401);
        }
        
        \Log::info('Employé trouvé', ['employee_id' => $employee->ID]);
        
        // Vérifier la demande
        $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)
            ->whereIn('status', ['draft', 'pending'])
            ->find($id);
        
        if (!$leaveRequest) {
            \Log::error('Demande non trouvée', ['id' => $id, 'employee_id' => $employee->ID]);
            return response()->json([
                'success' => false,
                'message' => 'Demande non trouvée ou non modifiable'
            ], 404);
        }
        
        \Log::info('Demande trouvée', ['request_id' => $leaveRequest->id]);
        
        // Vérifier le fichier
        if (!$request->hasFile('attachment')) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun fichier reçu'
            ], 400);
        }
        
        $file = $request->file('attachment');
        
        // Valider le fichier
        $request->validate([
            'attachment' => 'required|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,txt',
        ]);
        
        \Log::info('Fichier validé', [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize()
        ]);
        
        // Stocker le fichier
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('leave_attachments/' . $id, $fileName, 'public');
        
        \Log::info('Fichier stocké', ['path' => $filePath]);
        
        // Créer l'attachment
        $uploadedBy = $employee->FirstName . ' ' . $employee->LastName;
        if (empty(trim($uploadedBy))) {
            $uploadedBy = 'Employé #' . $employee->ID;
        }
        
        $attachment = LeaveRequestAttachment::create([
            'leave_request_id' => $leaveRequest->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => $uploadedBy,
        ]);
        
        \Log::info('Attachment créé', ['attachment_id' => $attachment->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pièce jointe ajoutée avec succès',
            'attachment' => [
                'id' => $attachment->id,
                'file_name' => $attachment->file_name,
                'file_size' => $attachment->file_size,
                'created_at' => $attachment->created_at
            ]
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Erreur validation', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Erreur upload attachment', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Supprimer une pièce jointe
 */
public function deleteAttachment($id)
{
    $employee = $this->getEmployee();
    
    if (!$employee) {
        return redirect()->route('employe.login')
            ->with('error', 'Aucun employé associé à ce compte.');
    }
    
    $attachment = LeaveRequestAttachment::with('leaveRequest')
        ->whereHas('leaveRequest', function ($query) use ($employee) {
            $query->where('employee_id', $employee->ID);
        })
        ->findOrFail($id);
    
    // Supprimer le fichier physique
    Storage::disk('public')->delete($attachment->file_path);
    
    $attachment->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Pièce jointe supprimée avec succès'
    ]);
}

/**
 * Télécharger une pièce jointe
 */
public function downloadAttachment($id)
{
    $employee = $this->getEmployee();
    
    if (!$employee) {
        return redirect()->route('employe.login')
            ->with('error', 'Aucun employé associé à ce compte.');
    }
    
    $attachment = LeaveRequestAttachment::with('leaveRequest')
        ->whereHas('leaveRequest', function ($query) use ($employee) {
            $query->where('employee_id', $employee->ID);
        })
        ->findOrFail($id);
    
    return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
}
    /**
     * Annuler un congé validé
     */
    public function cancelApproved($id)
    {
        try {
            $this->leaveRequestService->cancelApprovedRequest($id);
            return redirect()->route('employe.leave-requests.index')
                ->with('success', 'Congé annulé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    /**
 * Calendrier des congés
 */
public function calendar()
{
    $employee = $this->getEmployee();
    
    if (!$employee) {
        return redirect()->route('employe.login')
            ->with('error', 'Aucun employé associé à ce compte.');
    }
    
    return view('employes.leave_calendar.index');
}

/**
 * Récupérer les événements pour le calendrier
 */
/**
 * Récupérer les événements pour le calendrier
 */
public function getCalendarEvents(Request $request)
{
    try {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return response()->json([]);
        }
        
        // Récupérer toutes les demandes
        $requests = LeaveRequest::where('employee_id', $employee->ID)
            ->whereIn('status', ['approved', 'pending', 'rejected', 'draft'])
            ->with('leaveType')
            ->get();
        
        $events = $requests->map(function($request) {
            // Vérifier que leaveType existe
            if (!$request->leaveType) {
                // Si le type n'existe pas, utiliser des valeurs par défaut
                return [
                    'id' => $request->id,
                    'title' => 'Type inconnu (' . number_format($request->duration, 1) . 'j)',
                    'start' => $request->start_date->format('Y-m-d'),
                    'end' => $request->end_date->format('Y-m-d'),
                    'backgroundColor' => '#6b7280', // Gris
                    'borderColor' => '#6b7280',
                    'extendedProps' => [
                        'status' => $request->status,
                        'duration' => $request->duration,
                        'url' => route('employe.leave-requests.show', $request->id)
                    ]
                ];
            }
            
            // Couleurs selon le statut
            $statusColors = [
                'pending' => '#f59e0b',
                'approved' => '#22c55e',
                'rejected' => '#ef4444',
                'draft' => '#6b7280'
            ];
            
            // Titre avec le type et la durée
            $title = $request->leaveType->name . ' (' . number_format($request->duration, 1) . 'j)';
            
            return [
                'id' => $request->id,
                'title' => $title,
                'start' => $request->start_date->format('Y-m-d'),
                'end' => $request->end_date->format('Y-m-d'),
                'backgroundColor' => $statusColors[$request->status] ?? '#4f8a8b',
                'borderColor' => $statusColors[$request->status] ?? '#4f8a8b',
                'extendedProps' => [
                    'status' => $request->status,
                    'duration' => $request->duration,
                    'url' => route('employe.leave-requests.show', $request->id)
                ]
            ];
        });
        
        return response()->json($events);
        
    } catch (\Exception $e) {
        \Log::error('Erreur calendrier: ' . $e->getMessage());
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}