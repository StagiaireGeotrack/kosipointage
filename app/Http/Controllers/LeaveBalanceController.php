<?php
// app/Http/Controllers/LeaveBalanceController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveImportBatch;
use App\Models\EntrepriseSiege;
use App\Services\LeaveBalanceService;
use App\Imports\LeaveBalanceImport;
use App\Exports\LeaveBalanceExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LeaveBalanceController extends Controller
{
    protected $balanceService;

    public function __construct(LeaveBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    /**
     * Afficher les soldes des employés
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->IsSuperAdmin ?? false;
        $userSiteId = $user->SiegeID ?? null;

        $query = LeaveBalance::with(['employee', 'leaveType', 'period']);

        // Filtre par siège (pour les admins non super)
        if (!$isSuperAdmin && $userSiteId) {
            $query->whereHas('employee', function ($q) use ($userSiteId) {
                $q->where('SiegeID', $userSiteId);
            });
        }

        // Filtres
        if ($request->filled('site_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('SiegeID', $request->site_id);
            });
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        $balances = $query->orderBy('id', 'desc')->paginate(20);

        // Données pour les filtres
        $employees = Employe::where('Actived', 1)
            ->where('deleted', 0)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where('SiegeID', $userSiteId);
            })
            ->orderBy('Nom')
            ->get();

        $leaveTypes = LeaveType::where('is_active', true)
            ->when(!$isSuperAdmin && $userSiteId, function ($q) use ($userSiteId) {
                return $q->where(function ($sub) use ($userSiteId) {
                    $sub->where('site_id', $userSiteId)
                        ->orWhereNull('site_id');
                });
            })
            ->orderBy('name')
            ->get();

        $sites = $isSuperAdmin 
            ? EntrepriseSiege::orderBy('Nom')->get() 
            : EntrepriseSiege::where('ID', $userSiteId)->orderBy('Nom')->get();

        return view('leave_balances.index', compact('balances', 'employees', 'leaveTypes', 'sites'));
    }

    /**
     * Formulaire d'initialisation des soldes
     */
    public function initialize()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->IsSuperAdmin ?? false;
        $userSiteId = $user->SiegeID ?? null;

        // Récupérer tous les sites pour le Super Admin
        $sites = EntrepriseSiege::orderBy('Nom')->get();

        // Super Admin : charger TOUTES les données
        if ($isSuperAdmin) {
            $employees = Employe::where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get();

            $leaveTypes = LeaveType::where('is_active', true)
                ->orderBy('name')
                ->get();

            $periods = LeavePeriod::where('is_active', true)
                ->orderBy('start_date')
                ->get();
        } else {
            // Simple Admin : seulement son siège
            $employees = Employe::where('Actived', 1)
                ->where('deleted', 0)
                ->where('SiegeID', $userSiteId)
                ->orderBy('Nom')
                ->get();

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
                ->orderBy('start_date')
                ->get();
        }

        return view('leave_balances.initialize', compact('employees', 'leaveTypes', 'periods', 'sites'));
    }

    /**
     * Initialiser les soldes
     */
    public function storeInitialization(Request $request)
    {
        $request->validate([
            'site_id' => 'required|exists:entreprises_sieges,ID',
            'employee_id' => 'required|exists:employes,ID',
            'leave_type_id' => 'required|exists:leave_types,id',
            'period_id' => 'required|exists:leave_periods,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        // Vérifier que l'employé appartient bien au siège sélectionné
        $employee = Employe::find($request->employee_id);
        if (!$employee) {
            return back()
                ->withErrors(['employee_id' => 'Employé non trouvé.'])
                ->withInput();
        }

        if ($employee->SiegeID != $request->site_id) {
            return back()
                ->withErrors(['employee_id' => 'Cet employé n\'appartient pas au siège sélectionné.'])
                ->withInput();
        }

        // Vérifier que le type de congé appartient au siège
        $leaveType = LeaveType::find($request->leave_type_id);
        if (!$leaveType) {
            return back()
                ->withErrors(['leave_type_id' => 'Type de congé non trouvé.'])
                ->withInput();
        }

        if ($leaveType->site_id && $leaveType->site_id != $request->site_id) {
            return back()
                ->withErrors(['leave_type_id' => 'Ce type de congé n\'appartient pas au siège sélectionné.'])
                ->withInput();
        }

        // Vérifier que la période appartient au siège
        $period = LeavePeriod::find($request->period_id);
        if (!$period) {
            return back()
                ->withErrors(['period_id' => 'Période non trouvée.'])
                ->withInput();
        }

        if ($period->site_id && $period->site_id != $request->site_id) {
            return back()
                ->withErrors(['period_id' => 'Cette période n\'appartient pas au siège sélectionné.'])
                ->withInput();
        }

        try {
            // Vérifier si un solde existe déjà
            $existing = LeaveBalance::where('employee_id', $request->employee_id)
                ->where('leave_type_id', $request->leave_type_id)
                ->where('period_id', $request->period_id)
                ->first();

            if ($existing) {
                return back()
                    ->withErrors(['employee_id' => 'Un solde existe déjà pour cet employé, ce type et cette période.'])
                    ->withInput();
            }

            $this->balanceService->initializeBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id,
                $request->amount,
                $request->description ?? 'Solde initial'
            );

            return redirect()->route('leave-balances.index')
                ->with('success', 'Solde initial créé avec succès pour ' . $employee->Nom . ' (' . ($employee->siege->Nom ?? 'N/A') . ')');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Historique des transactions
     */
    public function transactions($id)
    {
        $balance = LeaveBalance::with(['employee', 'leaveType', 'period'])->findOrFail($id);
        
        $transactions = LeaveBalanceTransaction::where('employee_id', $balance->employee_id)
            ->where('leave_type_id', $balance->leave_type_id)
            ->where('period_id', $balance->period_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('leave_balances.transactions', compact('balance', 'transactions'));
    }

    /**
     * ✅ Ajuster un solde - Version avec Ajouter / Retirer
     */
    public function adjust(Request $request, $id)
    {
        $balance = LeaveBalance::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.5',
            'description' => 'required|string|min:3|max:255',
            'type' => 'required|in:credit,debit', // ✅ AJOUT : type obligatoire
        ]);

        $amount = $request->input('amount');
        $type = $request->input('type');
        $description = $request->input('description');

        try {
            if ($type === 'debit') {
                // ✅ Retirer des jours = débit (montant négatif)
                // Vérifier le solde disponible avant le retrait
                $available = $balance->remaining - ($balance->total_pending ?? 0);
                
                if ($available < $amount) {
                    // Autoriser quand même, mais avec un avertissement
                    \Log::warning('Retrait de jours avec solde potentiellement insuffisant', [
                        'employee_id' => $balance->employee_id,
                        'available' => $available,
                        'amount' => $amount
                    ]);
                }

                $this->balanceService->debitBalance(
                    $balance->employee_id,
                    $balance->leave_type_id,
                    $balance->period_id,
                    $amount,
                    null,
                    $description . ' (Retrait manuel)'
                );

                $message = 'Retrait de ' . $amount . ' jours effectué pour ' . $balance->employee->Nom;
            } else {
                // ✅ Ajouter des jours = ajustement (montant positif)
                $this->balanceService->adjustBalance(
                    $balance->employee_id,
                    $balance->leave_type_id,
                    $balance->period_id,
                    $amount,
                    $description . ' (Ajout manuel)'
                );

                $message = 'Ajout de ' . $amount . ' jours effectué pour ' . $balance->employee->Nom;
            }

            return redirect()->route('leave-balances.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Formulaire d'import
     */
    public function import()
    {
        $periods = LeavePeriod::where('is_active', true)->orderBy('start_date')->get();
        $batches = LeaveImportBatch::where('type', 'opening_balance')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Récupérer les sites pour le Super Admin
        $sites = EntrepriseSiege::orderBy('Nom')->get();
        
        return view('leave_balances.import', compact('periods', 'batches', 'sites'));
    }

    /**
     * Importer des soldes
     */
    public function storeImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'period_id' => 'required|exists:leave_periods,id',
        ]);

        // FORCER le mode réel (pas de simulation)
        $simulate = false;
        $periodId = $request->period_id;
        
        // Récupérer le site_id
        $user = Auth::user();
        $isSuperAdmin = $user->IsSuperAdmin ?? false;
        $userSiteId = $user->SiegeID ?? null;

        if ($isSuperAdmin && $request->filled('site_id')) {
            $siteId = $request->site_id;
        } else {
            $siteId = $userSiteId;
        }

        if (!$siteId) {
            return back()->with('error', 'Aucun siège sélectionné.');
        }

        try {
            $import = new LeaveBalanceImport($periodId, $siteId, $simulate);
            Excel::import($import, $request->file('file'));

            $batch = $import->getBatch();
            $errors = $import->getErrors();

            // Vérifier s'il y a eu des erreurs
            if ($import->getFailureCount() > 0) {
                $message = "Import terminé avec des erreurs. " . $import->getSuccessCount() . " soldes créés, " . $import->getFailureCount() . " erreurs.";
                return redirect()->route('leave-balances.import')->with('warning', $message)->with('import_errors', $errors);
            }

            $message = "Import terminé avec succès. " . $import->getSuccessCount() . " soldes créés.";
            return redirect()->route('leave-balances.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger le modèle Excel
     */
    public function downloadTemplate()
    {
        $filename = 'modele_import_soldes.csv';
        $headers = ['matricule', 'code_conge', 'solde', 'description'];
        $rows = [
            ['EMP001', 'CP', 25, 'Solde initial CP 2026'],
            ['EMP002', 'RTT', 8, 'Solde initial RTT 2026'],
            ['EMP003', 'MAL', 0, 'Solde initial Maladie'],
        ];

        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM pour Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers, ';');
            foreach ($rows as $row) {
                fputcsv($handle, $row, ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Exporter les soldes
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->IsSuperAdmin ?? false;
        $userSiteId = $user->SiegeID ?? null;

        $query = LeaveBalance::with(['employee', 'leaveType', 'period']);

        // Filtre par siège
        if (!$isSuperAdmin && $userSiteId) {
            $query->whereHas('employee', function ($q) use ($userSiteId) {
                $q->where('SiegeID', $userSiteId);
            });
        }

        if ($request->filled('site_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('SiegeID', $request->site_id);
            });
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        $balances = $query->get();

        $export = new LeaveBalanceExport($balances);
        
        return Excel::download($export, 'soldes_conges_' . date('Y-m-d') . '.xlsx');
    }
}