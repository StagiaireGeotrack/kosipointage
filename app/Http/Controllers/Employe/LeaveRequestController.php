<?php
// app/Http/Controllers/Employe/LeaveRequestController.php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveBalance;
use App\Models\Employe;
use App\Services\LeaveRequestService;
use App\Services\LeaveBalanceService;
use App\Services\LeaveDurationCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequestAttachment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\LeaveWorkflow;
use App\Models\LeaveValidator;
use App\Models\LeaveApproval;


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
    }

    private function getEmployee()
    {
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
        
        if ($user instanceof Employe) {
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
        
        \Log::error('getEmployee: Aucun employé trouvé');
        return null;
    }

    /**
     * ✅ VÉRIFICATION DES CHEVAUCHEMENTS
     */
    private function checkOverlappingLeaves($employeeId, $startDate, $endDate, $leaveTypeId = null, $excludeRequestId = null)
    {
        $query = LeaveRequest::where('employee_id', $employeeId)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($leaveTypeId) {
            $query->where('leave_type_id', $leaveTypeId);
        }

        if ($excludeRequestId) {
            $query->where('id', '!=', $excludeRequestId);
        }

        return $query->exists();
    }

    /**
     * ✅ Calcul AJAX de la durée - AVEC TOUTES LES VALIDATIONS
     */
    public function calculateDurationAjax(Request $request)
    {
        try {
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

            // ✅ Récupérer le type de congé avec ses règles
            $leaveType = LeaveType::find($request->leave_type_id);
            
            if (!$leaveType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type de congé non trouvé'
                ], 422);
            }

            // ✅ Calculer la durée
            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $request->leave_type_id,
                $request->start_date,
                $request->end_date,
                $request->period_id
            );

            // ✅ VÉRIFICATION 1 : Durée maximale par demande (leave_types.max_duration_per_request)
            $maxDuration = $leaveType->max_duration_per_request ?? null;
            if ($maxDuration && $duration > $maxDuration) {
                return response()->json([
                    'success' => false,
                    'message' => "La durée demandée ({$duration} jour(s)) dépasse la durée maximale autorisée de {$maxDuration} jour(s) pour ce type de congé.",
                    'error_type' => 'max_duration'
                ], 422);
            }

            // ✅ VÉRIFICATION 2 : Délai de prévenance (leave_types.min_notice_days)
            $minNoticeDays = $leaveType->min_notice_days ?? 0;
            if ($minNoticeDays > 0) {
                $startDate = Carbon::parse($request->start_date);
                $today = Carbon::today();
                $noticeRequired = $today->copy()->addDays($minNoticeDays);
                
                if ($startDate->lt($noticeRequired)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Vous devez faire votre demande au moins {$minNoticeDays} jours à l'avance. La date de début doit être après le " . $noticeRequired->format('d/m/Y') . ".",
                        'error_type' => 'notice'
                    ], 422);
                }
            }

            // ✅ VÉRIFICATION 3 : Chevauchement avec d'autres congés (leave_types.allow_overlap)
            if (!$leaveType->allow_overlap) {
                $hasOverlap = $this->checkOverlappingLeaves(
                    $employee->ID,
                    $request->start_date,
                    $request->end_date,
                    $request->leave_type_id,
                    null
                );

                if ($hasOverlap) {
                    return response()->json([
                        'success' => false,
                        'message' => "Vous avez déjà une demande de congé sur cette période. Les chevauchements ne sont pas autorisés pour ce type de congé.",
                        'error_type' => 'overlap'
                    ], 422);
                }
            }

            // ✅ VÉRIFICATION 4 : Période et Date limite de pose (leave_periods)
            if ($request->filled('period_id')) {
                $period = LeavePeriod::find($request->period_id);
                if ($period) {
                    $start = Carbon::parse($request->start_date);
                    $end = Carbon::parse($request->end_date);
                    $periodStart = Carbon::parse($period->start_date);
                    $periodEnd = Carbon::parse($period->end_date);
                    $today = Carbon::today();

                    // Dates dans la période
                    if ($start->lt($periodStart) || $end->gt($periodEnd)) {
                        return response()->json([
                            'success' => false,
                            'message' => "Les dates doivent être comprises entre {$periodStart->format('d/m/Y')} et {$periodEnd->format('d/m/Y')}.",
                        ], 422);
                    }

                    // Période ouverte
                    if ($period->status !== 'open' || !$period->is_active) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cette période n\'est pas ouverte pour les demandes.'
                        ], 422);
                    }

                    // ✅ DATE LIMITE DE POSE (leave_periods.submission_deadline)
                    if ($period->submission_deadline) {
                        $deadline = Carbon::parse($period->submission_deadline);
                        if ($today->gt($deadline)) {
                            return response()->json([
                                'success' => false,
                                'message' => "La date limite de pose était le {$deadline->format('d/m/Y')}. Vous ne pouvez plus faire de demande pour cette période."
                            ], 422);
                        }
                    }
                }
            }

            // VÉRIFICATION : Solde suffisant
            $balanceSufficient = true;
            $balanceMessage = null;

            if ($leaveType && $leaveType->deducts_balance) {
                $balance = LeaveBalance::where('employee_id', $employee->ID)
                    ->where('leave_type_id', $request->leave_type_id)
                    ->where('period_id', $request->period_id)
                    ->first();

                if ($balance) {
                    $availableBalance = $balance->remaining ?? 0;
                    if ($duration > $availableBalance && !$leaveType->allow_negative_balance) {
                        $balanceSufficient = false;
                        $balanceMessage = "Solde insuffisant : {$availableBalance} jour(s) disponible(s) pour {$duration} jour(s) demandé(s)";
                    }
                }
            }

            return response()->json([
                'success' => true,
                'duration' => $duration,
                'duration_formatted' => number_format($duration, 1) . ' jour' . ($duration > 1 ? 's' : ''),
                'balance_sufficient' => $balanceSufficient,
                'balance_message' => $balanceMessage,
                'details' => [
                    'employee' => $employee->FirstName . ' ' . $employee->LastName,
                    'method' => 'jours ouvrés (selon politique)',
                    'weekends_excluded' => true,
                    'holidays_excluded' => true,
                    'min_notice_days' => $leaveType->min_notice_days ?? 0,
                    'max_duration_per_request' => $leaveType->max_duration_per_request ?? 'Illimité',
                    'allow_overlap' => $leaveType->allow_overlap ? 'Oui' : 'Non',
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur calcul durée AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Récupérer les périodes par type de congé (API)
     */
    public function getPeriodsByType(Request $request, $leaveTypeId)
    {
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employé non trouvé'
                ], 401);
            }

            $userSiteId = $employee->SiegeID;

            $periods = LeavePeriod::where('is_active', true)
                ->where('leave_type_id', $leaveTypeId)
                ->where(function ($q) use ($userSiteId) {
                    $q->where('site_id', $userSiteId)
                      ->orWhereNull('site_id');
                })
                ->orderBy('start_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $periods->map(function ($period) {
                    return [
                        'id' => $period->id,
                        'name' => $period->name,
                        'start_date' => $period->start_date->format('Y-m-d'),
                        'end_date' => $period->end_date->format('Y-m-d'),
                        'submission_deadline' => $period->submission_deadline?->format('Y-m-d'),
                        'status' => $period->status,
                        'is_active' => $period->is_active,
                        'allow_rollover' => $period->allow_rollover,
                        'is_default' => $period->is_default,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur getPeriodsByType: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

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

        // Récupérer toutes les périodes groupées par type
        $allPeriods = LeavePeriod::where('is_active', true)
            ->where(function ($q) use ($userSiteId) {
                $q->where('site_id', $userSiteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('start_date', 'desc')
            ->get()
            ->groupBy('leave_type_id')
            ->map(function ($periods) {
                return $periods->map(function ($period) {
                    return [
                        'id' => $period->id,
                        'name' => $period->name,
                        'start_date' => $period->start_date->format('Y-m-d'),
                        'end_date' => $period->end_date->format('Y-m-d'),
                        'submission_deadline' => $period->submission_deadline?->format('Y-m-d'),
                        'status' => $period->status,
                        'is_active' => $period->is_active,
                        'allow_rollover' => $period->allow_rollover,
                        'max_rollover_days' => $period->max_rollover_days,
                        'rollover_expiry_date' => $period->rollover_expiry_date?->format('Y-m-d'),
                        'is_default' => $period->is_default,
                    ];
                });
            });

        return view('employes.leave_requests.create', compact('leaveTypes', 'allPeriods', 'employee'));
    }

    /**
     * ✅ Créer une demande de congé - AVEC TOUTES LES VALIDATIONS
     */
    public function store(Request $request)
    {
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return redirect()->route('employe.login')
                    ->with('error', 'Aucun employé associé à ce compte.');
            }

            $validated = $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'period_id' => 'required|exists:leave_periods,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:500',
                'comment' => 'nullable|string|max:500',
                'attachments.*' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,txt',
            ]);

            // ✅ Récupérer le type de congé
            $leaveType = LeaveType::find($validated['leave_type_id']);
            
            if (!$leaveType) {
                return back()->withErrors(['leave_type_id' => 'Type de congé non trouvé.'])
                    ->withInput();
            }

            // VÉRIFICATION : La période doit correspondre au type
            $period = LeavePeriod::findOrFail($validated['period_id']);
            if ($period->leave_type_id != $validated['leave_type_id']) {
                return back()->withErrors(['period_id' => 'La période ne correspond pas au type de congé sélectionné.'])
                    ->withInput();
            }

            // VÉRIFICATION : Les dates doivent être dans la période
            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);
            $periodStart = Carbon::parse($period->start_date);
            $periodEnd = Carbon::parse($period->end_date);
            $today = Carbon::today();

            if ($start->lt($periodStart) || $end->gt($periodEnd)) {
                return back()->withErrors([
                    'start_date' => "Les dates doivent être comprises entre {$periodStart->format('d/m/Y')} et {$periodEnd->format('d/m/Y')}.",
                ])->withInput();
            }

            // VÉRIFICATION : La période doit être ouverte
            if ($period->status !== 'open' || !$period->is_active) {
                return back()->withErrors(['period_id' => 'Cette période n\'est pas ouverte pour les demandes.'])
                    ->withInput();
            }

            // ✅ DATE LIMITE DE POSE (leave_periods.submission_deadline)
            if ($period->submission_deadline) {
                $deadline = Carbon::parse($period->submission_deadline);
                
                if ($today->gt($deadline)) {
                    return back()->withErrors([
                        'period_id' => "La date limite de pose était le {$deadline->format('d/m/Y')}. Vous ne pouvez plus faire de demande pour cette période."
                    ])->withInput();
                }
            }

            // ✅ Calculer la durée
            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $validated['leave_type_id'],
                $validated['start_date'],
                $validated['end_date'],
                $validated['period_id']
            );

            // ✅ VÉRIFICATION 1 : Durée maximale par demande (leave_types.max_duration_per_request)
            $maxDuration = $leaveType->max_duration_per_request ?? null;
            if ($maxDuration && $duration > $maxDuration) {
                return back()->withErrors([
                    'leave_type_id' => "La durée demandée ({$duration} jour(s)) dépasse la durée maximale autorisée de {$maxDuration} jour(s) pour ce type de congé."
                ])->withInput();
            }

            // ✅ VÉRIFICATION 2 : Délai de prévenance (leave_types.min_notice_days)
            $minNoticeDays = $leaveType->min_notice_days ?? 0;
            if ($minNoticeDays > 0) {
                $noticeRequired = $today->copy()->addDays($minNoticeDays);
                
                if ($start->lt($noticeRequired)) {
                    return back()->withErrors([
                        'start_date' => "Vous devez faire votre demande au moins {$minNoticeDays} jours à l'avance. La date de début doit être après le " . $noticeRequired->format('d/m/Y') . "."
                    ])->withInput();
                }
            }

            // ✅ VÉRIFICATION 3 : Chevauchement avec d'autres congés (leave_types.allow_overlap)
            if (!$leaveType->allow_overlap) {
                $hasOverlap = $this->checkOverlappingLeaves(
                    $employee->ID,
                    $validated['start_date'],
                    $validated['end_date'],
                    $validated['leave_type_id'],
                    null
                );

                if ($hasOverlap) {
                    return back()->withErrors([
                        'start_date' => "Vous avez déjà une demande de congé sur cette période. Les chevauchements ne sont pas autorisés pour ce type de congé."
                    ])->withInput();
                }
            }

            // VÉRIFICATION : Solde suffisant
            if ($leaveType && $leaveType->deducts_balance) {
                $balance = LeaveBalance::where('employee_id', $employee->ID)
                    ->where('leave_type_id', $validated['leave_type_id'])
                    ->where('period_id', $validated['period_id'])
                    ->first();

                if ($balance) {
                    $availableBalance = $balance->remaining ?? 0;
                    if ($duration > $availableBalance && !$leaveType->allow_negative_balance) {
                        return back()->withErrors([
                            'leave_type_id' => "Solde insuffisant : {$availableBalance} jour(s) disponible(s) pour {$duration} jour(s) demandé(s)."
                        ])->withInput();
                    }
                }
            }

            // Créer la demande
            $leaveRequest = $this->leaveRequestService->createRequest(
                $employee->ID,
                $validated['leave_type_id'],
                $validated['period_id'],
                $validated['start_date'],
                $validated['end_date'],
                $validated['reason'] ?? null,
                $validated['comment'] ?? null
            );

            // Mettre à jour la durée
            $leaveRequest->update(['duration' => $duration]);

            // Gérer les pièces jointes
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

         if ($request->has('submit')) {
    try {
        $this->applyWorkflow($leaveRequest);
        // Notifier le manager (optionnel)
        return redirect()->route('employe.leave-requests.index')
            ->with('success', 'Demande soumise avec succès.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }

}

            return redirect()->route('employe.leave-requests.show', $leaveRequest->id)
                ->with('success', 'Demande créée avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Erreur store leave request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

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

        $attachmentStatus = $this->leaveRequestService->getAttachmentsStatus($id);

        return view('employes.leave_requests.show', compact('request', 'attachmentStatus'));
    }

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
    
    // ✅ Récupérer toutes les périodes
    $periods = LeavePeriod::where('is_active', true)
        ->where(function ($q) use ($userSiteId) {
            $q->where('site_id', $userSiteId)
              ->orWhereNull('site_id');
        })
        ->orderBy('start_date', 'desc')
        ->get();
    
    // ✅ Grouper par type de congé
    $allPeriods = $periods->groupBy('leave_type_id')
        ->map(function ($periods) {
            return $periods->map(function ($period) {
                return [
                    'id' => $period->id,
                    'name' => $period->name,
                    'type_name' => $period->leaveType->name ?? 'Type inconnu',
                    'start_date' => $period->start_date->format('Y-m-d'),
                    'end_date' => $period->end_date->format('Y-m-d'),
                    'submission_deadline' => $period->submission_deadline?->format('Y-m-d'),
                    'status' => $period->status,
                    'is_active' => $period->is_active,
                    'allow_rollover' => $period->allow_rollover,
                    'is_default' => $period->is_default,
                ];
            });
        });
    
    $attachmentStatus = $this->leaveRequestService->getAttachmentsStatus($id);
    
    // ✅ Passer les deux variables
    return view('employes.leave_requests.edit', compact(
        'request', 
        'leaveTypes', 
        'periods',        // ← Ajouté pour compatibilité
        'allPeriods',     // ← Pour le nouveau format
        'attachmentStatus'
    ));
}
/**
 * ✅ Récupère le statut des pièces jointes (AJAX)
 */
public function getAttachmentsStatus($id)
{
    try {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employé non trouvé'
            ], 401);
        }
        
        $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)
            ->findOrFail($id);
        
        $leaveType = LeaveType::find($leaveRequest->leave_type_id);
        
        $attachmentsCount = LeaveRequestAttachment::where('leave_request_id', $id)->count();
        $hasAttachments = $attachmentsCount > 0;
        
        // Vérifier si des pièces sont requises
        $isRequired = false;
        $message = 'Aucune pièce justificative requise';
        
        if ($leaveType) {
            if ($leaveType->requires_attachment == 'always') {
                $isRequired = true;
                $message = 'Pièce justificative obligatoire pour ce type de congé';
            } elseif ($leaveType->requires_attachment == 'after_duration') {
                $threshold = $leaveType->requires_attachment_after ?? 3;
                if ($leaveRequest->duration > $threshold) {
                    $isRequired = true;
                    $message = "Pièce justificative requise (durée > {$threshold} jours)";
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'attachments_required' => $isRequired,
                'has_attachments' => $hasAttachments,
                'count' => $attachmentsCount,
                'message' => $message,
                'can_submit' => !$isRequired || $hasAttachments,
            ]
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Erreur getAttachmentsStatus: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * ✅ Mettre à jour une demande de congé - AVEC TOUTES LES VALIDATIONS
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
            
            $validated = $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'period_id' => 'required|exists:leave_periods,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:500',
                'comment' => 'nullable|string|max:500',
            ]);

            // ✅ Récupérer le type de congé
            $leaveType = LeaveType::find($validated['leave_type_id']);
            
            if (!$leaveType) {
                return back()->withErrors(['leave_type_id' => 'Type de congé non trouvé.'])
                    ->withInput();
            }

            $period = LeavePeriod::findOrFail($validated['period_id']);
            if ($period->leave_type_id != $validated['leave_type_id']) {
                return back()->withErrors(['period_id' => 'La période ne correspond pas au type de congé sélectionné.'])
                    ->withInput();
            }

            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);
            $periodStart = Carbon::parse($period->start_date);
            $periodEnd = Carbon::parse($period->end_date);
            $today = Carbon::today();

            if ($start->lt($periodStart) || $end->gt($periodEnd)) {
                return back()->withErrors([
                    'start_date' => "Les dates doivent être comprises entre {$periodStart->format('d/m/Y')} et {$periodEnd->format('d/m/Y')}.",
                ])->withInput();
            }

            if ($period->status !== 'open' || !$period->is_active) {
                return back()->withErrors(['period_id' => 'Cette période n\'est pas ouverte pour les demandes.'])
                    ->withInput();
            }

            // ✅ DATE LIMITE DE POSE (leave_periods.submission_deadline)
            if ($period->submission_deadline) {
                $deadline = Carbon::parse($period->submission_deadline);
                if ($today->gt($deadline)) {
                    return back()->withErrors([
                        'period_id' => "La date limite de pose était le {$deadline->format('d/m/Y')}. Vous ne pouvez plus faire de demande pour cette période."
                    ])->withInput();
                }
            }

            // ✅ Calculer la durée
            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $validated['leave_type_id'],
                $validated['start_date'],
                $validated['end_date'],
                $validated['period_id']
            );

            // ✅ VÉRIFICATION 1 : Durée maximale par demande (leave_types.max_duration_per_request)
            $maxDuration = $leaveType->max_duration_per_request ?? null;
            if ($maxDuration && $duration > $maxDuration) {
                return back()->withErrors([
                    'leave_type_id' => "La durée demandée ({$duration} jour(s)) dépasse la durée maximale autorisée de {$maxDuration} jour(s) pour ce type de congé."
                ])->withInput();
            }

            // ✅ VÉRIFICATION 2 : Délai de prévenance (leave_types.min_notice_days)
            $minNoticeDays = $leaveType->min_notice_days ?? 0;
            if ($minNoticeDays > 0) {
                $noticeRequired = $today->copy()->addDays($minNoticeDays);
                
                if ($start->lt($noticeRequired)) {
                    return back()->withErrors([
                        'start_date' => "Vous devez faire votre demande au moins {$minNoticeDays} jours à l'avance. La date de début doit être après le " . $noticeRequired->format('d/m/Y') . "."
                    ])->withInput();
                }
            }

            // ✅ VÉRIFICATION 3 : Chevauchement avec d'autres congés (leave_types.allow_overlap)
            if (!$leaveType->allow_overlap) {
                $hasOverlap = $this->checkOverlappingLeaves(
                    $employee->ID,
                    $validated['start_date'],
                    $validated['end_date'],
                    $validated['leave_type_id'],
                    $id
                );

                if ($hasOverlap) {
                    return back()->withErrors([
                        'start_date' => "Vous avez déjà une demande de congé sur cette période. Les chevauchements ne sont pas autorisés pour ce type de congé."
                    ])->withInput();
                }
            }

            // VÉRIFICATION : Solde suffisant
            if ($leaveType && $leaveType->deducts_balance) {
                $balance = LeaveBalance::where('employee_id', $employee->ID)
                    ->where('leave_type_id', $validated['leave_type_id'])
                    ->where('period_id', $validated['period_id'])
                    ->first();

                if ($balance) {
                    $availableBalance = $balance->remaining ?? 0;
                    if ($duration > $availableBalance && !$leaveType->allow_negative_balance) {
                        return back()->withErrors([
                            'leave_type_id' => "Solde insuffisant : {$availableBalance} jour(s) disponible(s) pour {$duration} jour(s) demandé(s)."
                        ])->withInput();
                    }
                }
            }
            
            $leaveRequest->update([
                'leave_type_id' => $validated['leave_type_id'],
                'period_id' => $validated['period_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'reason' => $validated['reason'] ?? null,
                'comment' => $validated['comment'] ?? null,
                'duration' => $duration,
            ]);
            
            if ($request->has('submit')) {
                $this->leaveRequestService->submitRequest($id);
                return redirect()->route('employe.leave-requests.index')
                    ->with('success', 'Demande soumise avec succès.');
            }
            
            return redirect()->route('employe.leave-requests.show', $id)
                ->with('success', 'Demande mise à jour avec succès.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Erreur update leave request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur: ' . $e->getMessage())
                ->withInput();
        }
    }

    // app/Http/Controllers/Employe/LeaveRequestController.php

// Ajouter les imports en haut

// app/Http/Controllers/Employe/LeaveRequestController.php



// app/Http/Controllers/Employe/LeaveRequestController.php


public function submit($id)
{
    try {
        $employee = $this->getEmployee();
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }

        $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)->findOrFail($id);

        if ($leaveRequest->status != 'draft') {
            return back()->with('error', 'Cette demande n\'est plus modifiable.');
        }

        // ✅ 1. Trouver le workflow associé au type de congé et au site
        $workflow = LeaveWorkflow::where('leave_type_id', $leaveRequest->leave_type_id)
            ->where(function ($q) use ($employee) {
                $q->where('site_id', $employee->SiegeID)
                  ->orWhereNull('site_id');
            })
            ->where('is_active', true)
            ->first();

        if (!$workflow) {
            return back()->with('error', 'Aucun workflow configuré pour ce type de congé.');
        }

        // ✅ 2. Récupérer les étapes (JSON dans le champ `steps`)
        $steps = is_string($workflow->steps) ? json_decode($workflow->steps, true) : ($workflow->steps ?? []);
        if (empty($steps)) {
            return back()->with('error', 'Le workflow ne contient pas d\'étapes.');
        }

        // ✅ 3. Pour chaque étape, trouver le validateur
        foreach ($steps as $index => $step) {
            $role = $step['role'] ?? null;
            if (!$role) {
                continue;
            }

            $validator = LeaveValidator::where('site_id', $employee->SiegeID)
                ->where('role', $role)
                ->where('is_active', true)
                ->first();

            if (!$validator) {
                return back()->with('error', "Aucun validateur trouvé pour le rôle : {$role}.");
            }

            LeaveApproval::create([
                'leave_request_id' => $leaveRequest->id,
                'workflow_step_id' => null, // optionnel si vous utilisez une table `leave_workflow_steps`
                'approver_id' => $validator->employee_id,
                'step_order' => $index + 1,
                'status' => 'pending',
                'is_current' => ($index === 0),
            ]);
        }

        // ✅ 4. Mettre à jour la demande
        $leaveRequest->status = 'pending';
        $leaveRequest->submitted_at = now();
        $leaveRequest->workflow_id = $workflow->id;
        $leaveRequest->workflow_step = 0; // pas utilisé
        $leaveRequest->save();

        // ✅ 5. Notifier le premier approbateur (déjà fait ?)
        // Vous pouvez garder l'appel à la notification existante ou la supprimer si elle n'est pas nécessaire.

        // Redirection
        return redirect()->route('employe.leave-requests.show', $leaveRequest->id)
            ->with('success', 'Demande soumise avec succès.');

    } catch (\Exception $e) {
        \Log::error('Erreur soumission: ' . $e->getMessage());
        return back()->with('error', 'Erreur lors de la soumission : ' . $e->getMessage());
    }
}
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

        $attachments = LeaveRequestAttachment::where('leave_request_id', $id)->get();
        foreach ($attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $request->delete();

        return redirect()->route('employe.leave-requests.index')
            ->with('success', 'Demande supprimée avec succès.');
    }

    public function uploadAttachment(Request $request, $id)
    {
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun employé associé à ce compte.'
                ], 401);
            }
            
            $leaveRequest = LeaveRequest::where('employee_id', $employee->ID)
                ->whereIn('status', ['draft', 'pending'])
                ->find($id);
            
            if (!$leaveRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demande non trouvée ou non modifiable'
                ], 404);
            }
            
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
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('leave_attachments/' . $id, $fileName, 'public');
            
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
            
        } catch (\Exception $e) {
            \Log::error('Erreur upload attachment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

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

    public function calendar()
    {
        $employee = $this->getEmployee();
        
        if (!$employee) {
            return redirect()->route('employe.login')
                ->with('error', 'Aucun employé associé à ce compte.');
        }
        
        return view('employes.leave_calendar.index');
    }

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
                    'draft' => '#6b7280',
                    'cancelled' => '#9ca3af'
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
            \Log::error('Erreur calendrier: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Récupérer le solde d'un employé
     /**
 * Appliquer le workflow à une demande soumise
 */
private function applyWorkflow($leaveRequest)
{
    // Vérifier si des approbations existent déjà
    $existingApprovals = LeaveApproval::where('leave_request_id', $leaveRequest->id)->count();
    if ($existingApprovals > 0) {
        // Les approbations existent déjà, on ne les recrée pas
        return;
    }

    $employee = $leaveRequest->employee;
    $workflow = LeaveWorkflow::where('leave_type_id', $leaveRequest->leave_type_id)
        ->where(function ($q) use ($employee) {
            $q->where('site_id', $employee->SiegeID)
              ->orWhereNull('site_id');
        })
        ->where('is_active', true)
        ->first();

    if (!$workflow) {
        throw new \Exception('Aucun workflow configuré pour ce type de congé.');
    }

    $steps = is_string($workflow->steps) ? json_decode($workflow->steps, true) : ($workflow->steps ?? []);
    if (empty($steps)) {
        throw new \Exception('Le workflow ne contient pas d\'étapes.');
    }

    foreach ($steps as $index => $step) {
        $role = $step['role'] ?? null;
        if (!$role) {
            continue;
        }

        $approverId = null;

        // ✅ Étape "manager" : utiliser le manager direct de l'employé
        if ($role === 'manager') {
            $approverId = $employee->manager_id;
            if (!$approverId) {
                // Fallback : chercher un validateur avec le rôle "manager"
                $validator = LeaveValidator::where('site_id', $employee->SiegeID)
                    ->where('role', 'manager')
                    ->where('is_active', true)
                    ->first();
                if ($validator) {
                    $approverId = $validator->employee_id;
                } else {
                    throw new \Exception("L'employé n'a pas de manager direct et aucun validateur 'manager' n'est défini pour ce site.");
                }
            }
        } else {
            // ✅ Autres rôles (rh, drh, direction, etc.) : utiliser LeaveValidator
            $validator = LeaveValidator::where('site_id', $employee->SiegeID)
                ->where('role', $role)
                ->where('is_active', true)
                ->first();
            if (!$validator) {
                throw new \Exception("Aucun validateur trouvé pour le rôle : {$role}.");
            }
            $approverId = $validator->employee_id;
        }

        // Créer l'approbation
        LeaveApproval::create([
            'leave_request_id' => $leaveRequest->id,
            'workflow_step_id' => null,
            'approver_id' => $approverId,
            'step_order' => $index + 1,
            'status' => 'pending',
            'is_current' => ($index === 0),
        ]);
    }

    $leaveRequest->status = 'pending';
    $leaveRequest->submitted_at = now();
    $leaveRequest->workflow_id = $workflow->id;
    $leaveRequest->save();
}
    public function getBalance(Request $request)
    {
        try {
            $employee = $this->getEmployee();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employé non trouvé'
                ], 401);
            }

            $validated = $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'period_id' => 'nullable|exists:leave_periods,id'
            ]);

            $balance = LeaveBalance::where('employee_id', $employee->ID)
                ->where('leave_type_id', $validated['leave_type_id'])
                ->when($validated['period_id'] ?? null, function ($query, $periodId) {
                    return $query->where('period_id', $periodId);
                })
                ->first();

            $leaveType = LeaveType::find($validated['leave_type_id']);

            $availableBalance = $balance->remaining ?? 0;
            $totalEntitled = $balance->total_entitled ?? 0;
            $totalTaken = $balance->total_taken ?? 0;
            $totalPending = $balance->total_pending ?? 0;
            $carryover = $balance->carryover_from_previous ?? 0;

            return response()->json([
                'success' => true,
                'balance' => [
                    'available' => (float) $availableBalance,
                    'accrued' => (float) $totalEntitled,
                    'used' => (float) $totalTaken,
                    'pending' => (float) $totalPending,
                    'carryover' => (float) $carryover,
                    'allow_negative' => $leaveType->allow_negative_balance ?? false,
                    'negative_limit' => $leaveType->max_negative_limit ?? 0,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur getBalance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}