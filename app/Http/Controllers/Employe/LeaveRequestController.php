<?php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveBalance;
use App\Models\Employe;
use App\Models\LeaveRequestAttachment;
use App\Services\LeaveRequestService;
use App\Services\LeaveBalanceService;
use App\Services\LeaveDurationCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LeaveRequestController extends Controller
{
    protected $leaveRequestService;
    protected $balanceService;
    protected $durationCalculator;

    public function __construct(
        LeaveRequestService $leaveRequestService,
        LeaveBalanceService $balanceService,
        LeaveDurationCalculator $durationCalculator
    ) {
        $this->leaveRequestService = $leaveRequestService;
        $this->balanceService = $balanceService;
        $this->durationCalculator = $durationCalculator;
        
        // ✅ Ajouter le middleware auth
        $this->middleware('auth');
    }

    /**
     * Récupérer l'employé connecté
     */
    private function getEmployee()
    {
        // ✅ Vérifier d'abord l'authentification
        if (!Auth::check()) {
            Log::warning('getEmployee: Utilisateur non connecté');
            return null;
        }

        if (session()->has('employee_id')) {
            $employee = Employe::find(session('employee_id'));
            if ($employee) {
                return $employee;
            }
        }
        
        $user = Auth::user();
        
        if (!$user) {
            Log::error('getEmployee: Aucun utilisateur connecté');
            return null;
        }
        
        Log::info('getEmployee: User trouvé', [
            'user_id' => $user->ID ?? $user->id ?? 'unknown',
            'user_type' => get_class($user)
        ]);
        
        if ($user instanceof Employe) {
            Log::info('getEmployee: User est un Employe');
            session(['employee_id' => $user->ID]);
            return $user;
        }
        
        if (isset($user->employee) && $user->employee) {
            session(['employee_id' => $user->employee->ID]);
            return $user->employee;
        }
        
        if (isset($user->employee_id) && $user->employee_id) {
            $employee = Employe::find($user->employee_id);
            if ($employee) {
                session(['employee_id' => $employee->ID]);
                return $employee;
            }
        }
        
        $userId = $user->ID ?? $user->id ?? null;
        if ($userId) {
            $employee = Employe::where('user_id', $userId)->first();
            if ($employee) {
                session(['employee_id' => $employee->ID]);
                return $employee;
            }
        }
        
        $employee = Employe::where('Actived', 1)->first();
        if ($employee) {
            session(['employee_id' => $employee->ID]);
            return $employee;
        }
        
        Log::error('getEmployee: Aucun employé trouvé');
        return null;
    }

    /**
     * ✅ Calcul AJAX de la durée (GET)
     * Route: /employe/leave/calculate-duration
     */
    public function calculateDuration(Request $request)
    {
        try {
            // ✅ Vérifier l'authentification
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié. Veuillez vous connecter.'
                ], 401);
            }

            $employee = $this->getEmployee();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employé non trouvé'
                ], 401);
            }

            $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'period_id' => 'nullable|exists:leave_periods,id'
            ]);

            Log::info('Calcul durée', [
                'employee_id' => $employee->ID,
                'leave_type_id' => $request->leave_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ]);

            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $request->leave_type_id,
                $request->start_date,
                $request->end_date,
                $request->period_id
            );

            // ✅ Récupérer le type de congé pour les pièces justificatives
            $leaveType = LeaveType::find($request->leave_type_id);
            $attachmentRequired = false;
            $attachmentMessage = null;
            $attachmentRule = 'never';
            $threshold = null;

            if ($leaveType) {
                $attachmentRule = $leaveType->requires_attachment;
                
                if ($attachmentRule === 'always') {
                    $attachmentRequired = true;
                    $attachmentMessage = 'Pièce justificative obligatoire pour ce type de congé.';
                } elseif ($attachmentRule === 'after_duration') {
                    $threshold = $leaveType->requires_attachment_after ?? 3;
                    if ($duration > $threshold) {
                        $attachmentRequired = true;
                        $attachmentMessage = 'Pièce justificative obligatoire pour les congés de plus de ' . $threshold . ' jours.';
                    }
                }
            }

            return response()->json([
                'success' => true,
                'duration' => $duration,
                'duration_formatted' => number_format($duration, 1) . ' jour' . ($duration > 1 ? 's' : ''),
                'attachments' => [
                    'required' => $attachmentRequired,
                    'message' => $attachmentMessage,
                    'rule' => $attachmentRule,
                    'threshold' => $threshold,
                ],
                'details' => [
                    'employee' => $employee->FirstName . ' ' . $employee->LastName,
                    'method' => 'jours ouvrés (selon politique)',
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur calcul durée: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Calcul de durée pour un brouillon existant (GET)
     * Route: /employe/leave-requests/{id}/calculate-duration
     */
    public function calculateDurationForDraft(Request $request, $id)
    {
        try {
            // ✅ Vérifier l'authentification
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié. Veuillez vous connecter.'
                ], 401);
            }

            $employee = $this->getEmployee();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employé non trouvé'
                ], 401);
            }

            $leaveRequest = LeaveRequest::with('leaveType')
                ->where('employee_id', $employee->ID)
                ->findOrFail($id);

            if ($leaveRequest->status !== 'draft') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette demande n\'est pas un brouillon.'
                ], 400);
            }

            // ✅ Valider les paramètres
            $validator = validator($request->all(), [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $startDate = $request->start_date ?? $leaveRequest->start_date;
            $endDate = $request->end_date ?? $leaveRequest->end_date;
            
            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les dates de début et de fin sont requises.'
                ], 422);
            }

            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $leaveRequest->leave_type_id,
                $startDate,
                $endDate,
                $leaveRequest->period_id
            );

            $leaveType = $leaveRequest->leaveType;
            $attachmentRequired = false;
            $attachmentMessage = null;
            $attachmentRule = 'never';
            $threshold = null;
            $hasAttachments = LeaveRequestAttachment::where('leave_request_id', $id)->exists();
            $attachmentsCount = LeaveRequestAttachment::where('leave_request_id', $id)->count();

            if ($leaveType) {
                $attachmentRule = $leaveType->requires_attachment;
                
                if ($attachmentRule === 'always') {
                    $attachmentRequired = true;
                    $attachmentMessage = $hasAttachments ? '✅ Pièces fournies' : '❌ Pièce justificative obligatoire';
                } elseif ($attachmentRule === 'after_duration') {
                    $threshold = $leaveType->requires_attachment_after ?? 3;
                    if ($duration > $threshold) {
                        $attachmentRequired = true;
                        $attachmentMessage = $hasAttachments ? '✅ Pièces fournies' : '❌ Pièce justificative requise (' . $threshold . ' jours)';
                    }
                }
            }

            return response()->json([
                'success' => true,
                'duration' => $duration,
                'duration_formatted' => number_format($duration, 1) . ' jour' . ($duration > 1 ? 's' : ''),
                'previous_duration' => $leaveRequest->duration,
                'attachments' => [
                    'required' => $attachmentRequired,
                    'message' => $attachmentMessage,
                    'rule' => $attachmentRule,
                    'threshold' => $threshold,
                    'has_attachments' => $hasAttachments,
                    'count' => $attachmentsCount,
                ],
                'request' => [
                    'id' => $id,
                    'status' => $leaveRequest->status,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Demande non trouvée.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur calcul durée pour brouillon: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Récupérer le statut des pièces justificatives pour une demande
     * Route: /employe/leave-requests/{id}/attachments/status
     */
    public function getAttachmentsStatus($id)
    {
        try {
            // ✅ Vérifier l'authentification
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non authentifié. Veuillez vous connecter.'
                ], 401);
            }

            $employee = $this->getEmployee();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun employé associé à ce compte.'
                ], 401);
            }
            
            $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)
                ->with('leaveType')
                ->findOrFail($id);
            
            $status = $this->leaveRequestService->getAttachmentsStatus($id);
            
            return response()->json([
                'success' => true,
                'data' => $status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur getAttachmentsStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
        
        $stats = [
            'total_requests' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'rejected')->count(),
        ];
        
        $monthlyStats = $requests->groupBy(function($request) {
            return $request->created_at->month;
        })->map->count()->toArray();
        
        $monthlyStatsArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyStatsArray[] = $monthlyStats[$i] ?? 0;
        }
        
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
     * ✅ Créer une demande de congé (brouillon)
     * NE VALIDE PAS LES PIÈCES ICI
     */
    public function store(Request $request)
    {
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return redirect()->route('employe.login')
                    ->with('error', 'Aucun employé associé à ce compte.');
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

            // ✅ NE PAS valider les pièces ici - c'est un brouillon

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
                try {
                    $this->leaveRequestService->submitRequest($leaveRequest->id);
                    return redirect()->route('employe.leave-requests.index')
                        ->with('success', 'Demande soumise avec succès.');
                } catch (\Exception $e) {
                    return redirect()->route('employe.leave-requests.edit', $leaveRequest->id)
                        ->with('error', $e->getMessage());
                }
            }

            return redirect()->route('employe.leave-requests.show', $leaveRequest->id)
                ->with('success', 'Demande créée avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
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
            ->with(['leaveType', 'period', 'approver', 'attachments'])
            ->findOrFail($id);

        // ✅ Vérifier le statut des pièces
        $attachmentStatus = $this->leaveRequestService->getAttachmentsStatus($id);

        return view('employes.leave_requests.show', compact('request', 'attachmentStatus'));
    }

    /**
     * Formulaire d'édition d'une demande (brouillon)
     */
    public function edit($id)
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        $request = LeaveRequest::where('employee_id', $employee->ID)
            ->where('status', 'draft')
            ->with(['leaveType', 'attachments'])
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
        
        // ✅ Vérifier le statut des pièces
        $attachmentStatus = $this->leaveRequestService->getAttachmentsStatus($id);
        
        return view('employes.leave_requests.edit', compact('request', 'leaveTypes', 'periods', 'attachmentStatus'));
    }

    /**
     * Mettre à jour une demande (brouillon)
     * NE VALIDE PAS LES PIÈCES ICI
     */
    public function update(Request $request, $id)
    {
        try {
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
            
            // ✅ NE PAS valider les pièces ici - c'est un brouillon
            
            $leaveRequest->update([
                'leave_type_id' => $request->leave_type_id,
                'period_id' => $request->period_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'comment' => $request->comment,
            ]);
            
            // Recalculer la durée
            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $request->leave_type_id,
                $request->start_date,
                $request->end_date,
                $request->period_id
            );
            $leaveRequest->update(['duration' => $duration]);
            
            if ($request->has('submit')) {
                try {
                    $this->leaveRequestService->submitRequest($id);
                    return redirect()->route('employe.leave-requests.index')
                        ->with('success', 'Demande soumise avec succès.');
                } catch (\Exception $e) {
                    return redirect()->route('employe.leave-requests.edit', $id)
                        ->with('error', $e->getMessage());
                }
            }
            
            return redirect()->route('employe.leave-requests.show', $id)
                ->with('success', 'Demande mise à jour avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
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

        // Supprimer les fichiers joints
        $attachments = LeaveRequestAttachment::where('leave_request_id', $id)->get();
        foreach ($attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $request->delete();

        return redirect()->route('employe.leave-requests.index')
            ->with('success', 'Demande supprimée avec succès.');
    }

    /**
     * Ajouter une pièce jointe
     */
    public function uploadAttachment(Request $request, $id)
    {
        try {
            Log::info('=== UPLOAD ATTACHMENT START ===', [
                'id' => $id,
                'method' => $request->method(),
                'has_file' => $request->hasFile('attachment')
            ]);
            
            $employee = $this->getEmployee();
            
            if (!$employee) {
                Log::error('Aucun employé trouvé');
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun employé associé à ce compte.'
                ], 401);
            }
            
            Log::info('Employé trouvé', ['employee_id' => $employee->ID]);
            
            $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)
                ->whereIn('status', ['draft', 'pending'])
                ->find($id);
            
            if (!$leaveRequest) {
                Log::error('Demande non trouvée', ['id' => $id, 'employee_id' => $employee->ID]);
                return response()->json([
                    'success' => false,
                    'message' => 'Demande non trouvée ou non modifiable'
                ], 404);
            }
            
            Log::info('Demande trouvée', ['request_id' => $leaveRequest->id]);
            
            if (!$request->hasFile('attachment')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun fichier reçu'
                ], 400);
            }
            
            $file = $request->file('attachment');
            
            $request->validate([
                'attachment' => 'required|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,txt',
            ]);
            
            Log::info('Fichier validé', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ]);
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('leave_attachments/' . $id, $fileName, 'public');
            
            Log::info('Fichier stocké', ['path' => $filePath]);
            
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
            
            Log::info('Attachment créé', ['attachment_id' => $attachment->id]);
            
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
            Log::error('Erreur validation', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur upload attachment', [
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
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun employé associé à ce compte.'
                ], 401);
            }
            
            $attachment = LeaveRequestAttachment::with('leaveRequest')
                ->whereHas('leaveRequest', function ($query) use ($employee) {
                    $query->where('employee_id', $employee->ID);
                })
                ->findOrFail($id);
            
            Storage::disk('public')->delete($attachment->file_path);
            
            $attachment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pièce jointe supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
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
    public function getCalendarEvents(Request $request)
    {
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return response()->json([]);
            }
            
            $requests = LeaveRequest::where('employee_id', $employee->ID)
                ->whereIn('status', ['approved', 'pending', 'rejected', 'draft'])
                ->with('leaveType')
                ->get();
            
            $events = $requests->map(function($request) {
                if (!$request->leaveType) {
                    return [
                        'id' => $request->id,
                        'title' => 'Type inconnu (' . number_format($request->duration, 1) . 'j)',
                        'start' => $request->start_date->format('Y-m-d'),
                        'end' => $request->end_date->format('Y-m-d'),
                        'backgroundColor' => '#6b7280',
                        'borderColor' => '#6b7280',
                        'extendedProps' => [
                            'status' => $request->status,
                            'duration' => $request->duration,
                            'url' => route('employe.leave-requests.show', $request->id)
                        ]
                    ];
                }
                
                $statusColors = [
                    'pending' => '#f59e0b',
                    'approved' => '#22c55e',
                    'rejected' => '#ef4444',
                    'draft' => '#6b7280'
                ];
                
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
            Log::error('Erreur calendrier: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}