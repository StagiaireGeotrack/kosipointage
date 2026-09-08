<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\CongeValidationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\EntrepriseSiegeController;
use App\Http\Controllers\JourNonTravailleController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\AllDashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\EventPointageController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveRoleController;
use App\Http\Controllers\LeavePolicyController;
use App\Http\Controllers\RuleFieldController;
use App\Http\Controllers\CalculationRuleController;
use App\Http\Controllers\LeavePeriodController;
use App\Http\Controllers\LeaveWorkflowController;
use App\Http\Controllers\CompanyHolidayController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\JobTitleController;
use App\Http\Controllers\HierarchyLevelController;
use App\Http\Controllers\Auth\EmployeAuthController;
use App\Http\Controllers\EmployePortalController;
use App\Http\Controllers\EmployeCongeController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeavePolicyAssignmentController;
use App\Http\Controllers\Employe\LeaveRequestController;
use App\Http\Controllers\Manager\NotificationController;
use App\Http\Controllers\Manager\ManagerLeaveRequestController;
use App\Http\Controllers\Employe\EmployeNotificationController;
use App\Http\Controllers\Api\LeaveDurationController;
use App\Http\Controllers\LeaveValidatorController;
use App\Http\Controllers\Employe\LeaveValidationController;
use App\Http\Controllers\HoraireTypeController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\EmployePlanningController;

// Authentification (Breeze)
require __DIR__.'/auth.php';

// ============================================
// EMPLOYÉ PORTAL (Authentification employé)
// ============================================
Route::middleware('guest:employe')->group(function () {
    Route::get('/portail/login', [EmployeAuthController::class, 'create'])->name('employe.login');
    Route::post('/portail/login', [EmployeAuthController::class, 'store']);
});

Route::middleware('auth:employe')->group(function () {
    Route::post('/portail/logout', [EmployeAuthController::class, 'destroy'])->name('employe.logout');
    Route::get('/portail', [EmployePortalController::class, 'index'])->name('employe.dashboard');
    Route::get('/portail/profil', [EmployePortalController::class, 'editProfile'])->name('employe.profile');
    Route::patch('/portail/profil', [EmployePortalController::class, 'updateProfile'])->name('employe.profile.update');
    Route::get('/portail/pointages', [EmployePortalController::class, 'pointages'])->name('employe.pointages');
    Route::get('/portail/rapports', [EmployePortalController::class, 'rapports'])->name('employe.rapports');

    // Congés employé (ancien système)
    Route::get('/portail/conges', [EmployeCongeController::class, 'index'])->name('employe.conges.index');
    Route::get('/portail/conges/nouvelle-demande', [EmployeCongeController::class, 'create'])->name('employe.conges.create');
    Route::post('/portail/conges', [EmployeCongeController::class, 'store'])->name('employe.conges.store');
    Route::get('/portail/conges/{id}', [EmployeCongeController::class, 'show'])->name('employe.conges.show');
    Route::delete('/portail/conges/{id}', [EmployeCongeController::class, 'destroy'])->name('employe.conges.destroy');
});

// ============================================
// REDIRECTION ACCUEIL
// ============================================
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->isTrueSuperAdmin()) {
        return redirect()->route('dashboard');
    } elseif ($user->isSimpleAdmin()) {
        return redirect()->route('dashboard.simple-admin');
    } elseif ($user->isSeller()) {
        return redirect()->route('dashboard.seller');
    }

    return redirect()->route('sieges.index');
});

// ============================================
// ✅ ROUTES AUTHENTIFIÉES (Tous les admins)
// ============================================
Route::middleware('auth')->group(function () {

    // Langue
    Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile_update_email', [ProfileController::class, 'update_Identifiant_email'])->name('profile.update_Identifiant_email');
    Route::patch('/profile_update_password', [ProfileController::class, 'update_Password'])->name('profile.update_Password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Impersonation
    Route::post('/impersonate-leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');
    Route::post('/impersonate-employe-leave', [ImpersonateController::class, 'leaveEmploye'])->name('impersonate.employe.leave');

    // ==========================================
    // DASHBOARDS
    // ==========================================
    Route::get('/dashboard/seller', [AllDashboardController::class, 'dashboardSeller'])->name('dashboard.seller')->middleware('only.sellers');
    Route::get('/details-employe/{id}', [SellerController::class, 'show_employee'])->name('employe.show.seller')->middleware('only.sellers');
    Route::get('/dashboard/simple-admin', [AllDashboardController::class, 'dashboardSimpleAdmin'])
        ->name('dashboard.simple-admin')
        ->middleware('block.sellers');

    // ==========================================
    // ROUTES AVEC SIEGE.ACCESS
    // ==========================================
    Route::middleware('siege.access')->group(function () {

        // ===== SIÈGES =====
        Route::middleware(['block.sellers', 'block.simple.admin.sieges'])->group(function () {
            Route::get('/sieges/create', [EntrepriseSiegeController::class, 'create'])->name('sieges.create');
            Route::post('/sieges', [EntrepriseSiegeController::class, 'store'])->name('sieges.store');
            Route::get('/sieges/{siege}/edit', [EntrepriseSiegeController::class, 'edit'])->name('sieges.edit');
            Route::put('/sieges/{siege}', [EntrepriseSiegeController::class, 'update'])->name('sieges.update');
            Route::patch('/sieges/{siege}', [EntrepriseSiegeController::class, 'update']);
            Route::delete('/sieges/{siege}', [EntrepriseSiegeController::class, 'destroy'])->name('sieges.destroy');
            Route::delete('/sieges/reset/{siege}', [EntrepriseSiegeController::class, 'reset'])->name('sieges.reset');
            Route::patch("/update-siege", [EntrepriseSiegeController::class, 'update_siege'])->name('sieges.update_siege');
        });

        Route::get('/sieges', [EntrepriseSiegeController::class, 'index'])->name('sieges.index');
        Route::get('/sieges-export/excel', [EntrepriseSiegeController::class, 'exportExcel'])->name('sieges.export.excel');
        Route::get('/sieges-export/pdf', [EntrepriseSiegeController::class, 'exportPdf'])->name('sieges.export.pdf');
        Route::get('/sieges/{siege}', [EntrepriseSiegeController::class, 'show'])->name('sieges.show');

        // ===== ENTREPRISES =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/entreprises/create', [EntrepriseController::class, 'create'])->name('entreprises.create');
            Route::post('/entreprises', [EntrepriseController::class, 'store'])->name('entreprises.store');
            Route::get('/entreprises/{entreprise}/edit', [EntrepriseController::class, 'edit'])->name('entreprises.edit');
            Route::put('/entreprises/{entreprise}', [EntrepriseController::class, 'update'])->name('entreprises.update');
            Route::patch('/entreprises/{entreprise}', [EntrepriseController::class, 'update']);
            Route::delete('/entreprises/{entreprise}', [EntrepriseController::class, 'destroy'])->name('entreprises.destroy');
            Route::delete('/entreprises/reset/{entreprise}', [EntrepriseController::class, 'reset'])->name('entreprises.reset');
        });

        Route::get('/entreprises', [EntrepriseController::class, 'index'])->name('entreprises.index');
        Route::get('/entreprises-export/excel', [EntrepriseController::class, 'exportExcel'])->name('entreprises.export.excel');
        Route::get('/entreprises-export/pdf', [EntrepriseController::class, 'exportPdf'])->name('entreprises.export.pdf');
        Route::get('/entreprises/{entreprise}', [EntrepriseController::class, 'show'])->name('entreprises.show');
        Route::get('/entreprises/{id}/logo', [EntrepriseController::class, 'getLogo'])->name('entreprises.logo');
        Route::get('/entreprises/{id}/logo/thumbnail', [EntrepriseController::class, 'getLogoThumbnail'])->name('entreprises.logo.thumbnail');

        // ===== EMPLOYÉS =====
        Route::middleware('block.sellers')->group(function () {
            Route::post('/employes/{employe}/assign-web-access', [EmployeController::class, 'assignWebAccess'])->name('employes.assign-web-access');
            Route::get('/employes/create', [EmployeController::class, 'create'])->name('employes.create');
            Route::post('/employes', [EmployeController::class, 'store'])->name('employes.store');
            Route::get('/employes/{employe}/edit', [EmployeController::class, 'edit'])->name('employes.edit');
            Route::put('/employes/{employe}', [EmployeController::class, 'update'])->name('employes.update');
            Route::patch('/employes/{employe}', [EmployeController::class, 'update']);
            Route::delete('/employes/{employe}', [EmployeController::class, 'destroy'])->name('employes.destroy');
            Route::delete('/employes/reset/{employe}', [EmployeController::class, 'reset'])->name('employes.reset');
            Route::patch('employes/{id}/reset-pin', [EmployeController::class, 'resetCodePin'])->name('employes.reset-pin');
        });

        Route::get('/employes', [EmployeController::class, 'index'])->name('employes.index');
        Route::get('/employes-export/excel', [EmployeController::class, 'exportExcel'])->name('employes.export.excel');
        Route::get('/employes-export/pdf', [EmployeController::class, 'exportPdf'])->name('employes.export.pdf');
        Route::get('/employes/{employe}', [EmployeController::class, 'show'])->name('employes.show');
        Route::get('/employes/{id}/face', [EmployeController::class, 'getFaceEncoding'])->name('employes.face');
        Route::get('/employes/{id}/face/thumbnail', [EmployeController::class, 'getFaceThumbnail'])->name('employes.face.thumbnail');

        // ============================================
        // PLANNING (NOUVEAU)
        // ============================================
        Route::middleware(['block.sellers'])->prefix('planning')->name('planning.')->group(function () {
            // Configuration des horaires types
            Route::prefix('horaires-types')->name('horaires-types.')->group(function () {
                Route::get('/', [HoraireTypeController::class, 'index'])->name('index');
                Route::get('/create', [HoraireTypeController::class, 'create'])->name('create');
                Route::post('/', [HoraireTypeController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [HoraireTypeController::class, 'edit'])->name('edit');
                Route::put('/{id}', [HoraireTypeController::class, 'update'])->name('update');
                Route::delete('/{id}', [HoraireTypeController::class, 'destroy'])->name('destroy');
            });

            // API pour charger les postes par service
            Route::get('/api/job-titles-by-department/{departmentId}', [PlanningController::class, 'getJobTitlesByDepartment'])
                ->name('api.job-titles-by-department');

            // Détail d'un événement (AJAX)
            Route::get('/event-detail/{id}/{type}', [PlanningController::class, 'getEventDetail'])
                ->name('event.detail');

            // Gestion des plannings
            Route::get('/', [PlanningController::class, 'index'])->name('index');
            Route::get('/calendar', [PlanningController::class, 'calendar'])->name('calendar');
            Route::get('/create', [PlanningController::class, 'create'])->name('create');
            Route::post('/', [PlanningController::class, 'store'])->name('store');
            Route::get('/{id}', [PlanningController::class, 'show'])->name('show');

            // Événements
            Route::get('/events', [PlanningController::class, 'getEvents'])->name('events');
            Route::post('/events', [PlanningController::class, 'storeEvent'])->name('events.store');

            // API AJAX
            Route::get('/api/employees-by-service/{serviceId}', [PlanningController::class, 'getEmployeesByService'])
                ->name('api.employees-by-service');
            Route::get('/api/work-schedules-by-job-title/{jobTitleId}', [PlanningController::class, 'getWorkSchedulesByJobTitle'])
                ->name('api.work-schedules-by-job-title');

            // Exports
            Route::get('/export/excel', [PlanningController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [PlanningController::class, 'exportPdf'])->name('export.pdf');
        });

        // ============================================
        // PLANNING - EMPLOYÉ (Mon planning)
        // ============================================
        Route::middleware(['auth:employe'])->prefix('employe/planning')->name('employe.planning.')->group(function () {
            Route::get('/', [EmployePlanningController::class, 'index'])->name('index');
            Route::get('/events', [EmployePlanningController::class, 'getEvents'])->name('events');
        });

        // ===== POINTAGES =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/pointages', [PointageController::class, 'index'])->name('pointages.index');
            Route::get('/pointages/create', [PointageController::class, 'create'])->name('pointages.create');
            Route::post('/pointages', [PointageController::class, 'store'])->name('pointages.store');
            Route::get('/pointages-export/excel', [PointageController::class, 'exportExcel'])->name('pointages.export.excel');
            Route::get('/pointages-export/pdf', [PointageController::class, 'exportPdf'])->name('pointages.export.pdf');
            Route::get('/pointages/get-employes-by-siege/{SiegeID}', [PointageController::class, 'getEmployesBySiege'])->name('pointages.employees-by-siege');
            Route::get('/pointages/details/{employe}/{date}/{type_travail?}', [PointageController::class, 'showDetails'])->name('pointages.show.details');
            Route::get('/pointages/{pointage}', [PointageController::class, 'show'])->name('pointages.show');
            Route::get('/pointages/{pointage}/edit', [PointageController::class, 'edit'])->name('pointages.edit');
            Route::put('/pointages/{pointage}', [PointageController::class, 'update'])->name('pointages.update');
            Route::patch('/pointages/{pointage}', [PointageController::class, 'update']);
            Route::delete('/pointages/{pointage}', [PointageController::class, 'destroy'])->name('pointages.destroy');
            Route::get('/pointages/{id}/photo', [PointageController::class, 'getPhoto'])->name('pointages.photo');
            Route::get('/pointages/{id}/photo/thumbnail', [PointageController::class, 'getPhotoThumbnail'])->name('pointages.photo.thumbnail');
        });

        // ===== RAPPORTS =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
            Route::get('/reports/day-night', [ReportController::class, 'dayNight'])->name('reports.day-night');
            Route::get('/reports/export/excel/{type}', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
            Route::get('/reports/export/pdf/{type}', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
            Route::post('/reports/rapport-auto', [ReportController::class, 'getRapportAuto'])->name('reports.rapport-auto');
        });

        // ===== CONGÉS (Ancien système) =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/conges', [CongeController::class, 'index'])->name('conges.index');
            Route::get('/conges/create', [CongeController::class, 'create'])->name('conges.create');
            Route::post('/conges', [CongeController::class, 'store'])->name('conges.store');
            Route::get('/conges-export/excel', [CongeController::class, 'exportExcel'])->name('conges.export.excel');
            Route::get('/conges-export/pdf', [CongeController::class, 'exportPdf'])->name('conges.export.pdf');
            Route::get('/conges/{conge}', [CongeController::class, 'show'])->name('conges.show');
            Route::get('/conges/{conge}/edit', [CongeController::class, 'edit'])->name('conges.edit');
            Route::put('/conges/{conge}', [CongeController::class, 'update'])->name('conges.update');
            Route::patch('/conges/{conge}', [CongeController::class, 'update']);
            Route::delete('/conges/{conge}', [CongeController::class, 'destroy'])->name('conges.destroy');
        });

        // ===== VALIDATION CONGÉS =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/conge-validations', [CongeValidationController::class, 'index'])->name('conge-validations.index');
            Route::post('/conge-validations/{id}/approve', [CongeValidationController::class, 'approve'])->name('conge-validations.approve');
            Route::post('/conge-validations/{id}/reject', [CongeValidationController::class, 'reject'])->name('conge-validations.reject');
        });

        // ===== JOURS NON TRAVAILLÉS =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/jours-non-travailles', [JourNonTravailleController::class, 'index'])->name('jours-non-travailles.index');
            Route::get('/jours-non-travailles/create', [JourNonTravailleController::class, 'create'])->name('jours-non-travailles.create');
            Route::post('/jours-non-travailles', [JourNonTravailleController::class, 'store'])->name('jours-non-travailles.store');
            Route::get('/jours-non-travailles-export/excel', [JourNonTravailleController::class, 'exportExcel'])->name('jours-non-travailles.export.excel');
            Route::get('/jours-non-travailles-export/pdf', [JourNonTravailleController::class, 'exportPdf'])->name('jours-non-travailles.export.pdf');
            Route::get('/jours-non-travailles/{jours_non_travaille}', [JourNonTravailleController::class, 'show'])->name('jours-non-travailles.show');
            Route::get('/jours-non-travailles/{jours_non_travaille}/edit', [JourNonTravailleController::class, 'edit'])->name('jours-non-travailles.edit');
            Route::put('/jours-non-travailles/{jours_non_travaille}', [JourNonTravailleController::class, 'update'])->name('jours-non-travailles.update');
            Route::patch('/jours-non-travailles/{jours_non_travaille}', [JourNonTravailleController::class, 'update']);
            Route::delete('/jours-non-travailles/{jours_non_travaille}', [JourNonTravailleController::class, 'destroy'])->name('jours-non-travailles.destroy');
        });

        // ===== ÉVÉNEMENTS POINTAGE =====
        Route::middleware('block.sellers')->group(function () {
            Route::get('/evenements', [EventPointageController::class, 'index'])->name('evenements.index');
            Route::post('/evenements/acknowledge', [EventPointageController::class, 'acknowledge'])->name('evenements.acknowledge');
            Route::delete('/evenements/acknowledge/{id}', [EventPointageController::class, 'removeAcknowledge'])->name('evenements.remove-acknowledge');
        });
    });

    // ===== ADMINISTRATEURS =====
    Route::middleware('block.sellers')->group(function () {
        Route::get('/administrateurs', [AdministrationController::class, 'index'])->name('administrateurs.index');
        Route::get('/administrateurs/create', [AdministrationController::class, 'create'])->name('administrateurs.create');
        Route::post('/administrateurs', [AdministrationController::class, 'store'])->name('administrateurs.store');
        Route::get('/administrateurs-export/excel', [AdministrationController::class, 'exportExcel'])->name('administrateurs.export.excel');
        Route::get('/administrateurs-export/pdf', [AdministrationController::class, 'exportPdf'])->name('administrateurs.export.pdf');
        Route::get('/administrateurs/{administrateur}', [AdministrationController::class, 'show'])->name('administrateurs.show');
        Route::get('/administrateurs/{administrateur}/edit', [AdministrationController::class, 'edit'])->name('administrateurs.edit');
        Route::put('/administrateurs/{administrateur}', [AdministrationController::class, 'update'])->name('administrateurs.update');
        Route::patch('/administrateurs/{administrateur}', [AdministrationController::class, 'update']);
        Route::delete('/administrateurs/{administrateur}', [AdministrationController::class, 'destroy'])->name('administrateurs.destroy');
        Route::delete('/administrateurs/reset/{administrateur}', [AdministrationController::class, 'reset'])->name('administrateurs.reset');
    });

    // ===== VENDEURS =====
    Route::middleware('block.simple.admin.sieges')->group(function () {
        Route::get('/sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::get('/sellers/create', [SellerController::class, 'create'])->name('sellers.create');
        Route::post('/sellers', [SellerController::class, 'store'])->name('sellers.store');
        Route::post('/sellers/{id}/toggle-active', [SellerController::class, 'toggleActive'])->name('sellers.toggle-active');
        Route::get('/sellers/{seller}', [SellerController::class, 'show'])->name('sellers.show');
        Route::get('/sellers-export/excel', [SellerController::class, 'exportExcel'])->name('sellers.export.excel');
        Route::get('/sellers-export/pdf', [SellerController::class, 'exportPdf'])->name('sellers.export.pdf');
        Route::get('/sellers/{seller}/edit', [SellerController::class, 'edit'])->name('sellers.edit');
        Route::put('/sellers/{seller}', [SellerController::class, 'update'])->name('sellers.update');
        Route::patch('/sellers/{seller}', [SellerController::class, 'update']);
        Route::delete('/sellers/{seller}', [SellerController::class, 'destroy'])->name('sellers.destroy');
        Route::delete('/sellers/reset/{seller}', [SellerController::class, 'reset'])->name('sellers.reset');
    });

    // ===== ROUTES SUPER ADMIN UNIQUEMENT =====
    Route::middleware('can:superadmin')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/export/csv', [ActivityLogController::class, 'exportCsv'])->name('activity-logs.export.csv');
        Route::post('/impersonate/{id}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
        Route::post('/impersonate-employe/{id}', [ImpersonateController::class, 'impersonateEmploye'])->name('impersonate.employe');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // ==========================================
    // ADMIN / CONGÉS — Paramétrage
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('leave-roles', LeaveRoleController::class);

        // ===== LEAVE TYPES =====
        Route::resource('leave-types', LeaveTypeController::class);
        Route::patch('leave-types/{id}/restore', [LeaveTypeController::class, 'restore'])->name('leave-types.restore');
        Route::get('leave-types-export/excel', [LeaveTypeController::class, 'exportExcel'])->name('leave-types.export.excel');
        Route::get('leave-types-export/pdf', [LeaveTypeController::class, 'exportPdf'])->name('leave-types.export.pdf');

        // ===== LEAVE POLICIES =====
        Route::resource('leave-policies', LeavePolicyController::class);
        Route::patch('leave-policies/{id}/restore', [LeavePolicyController::class, 'restore'])->name('leave-policies.restore');
        Route::get('leave-policies-export/excel', [LeavePolicyController::class, 'exportExcel'])->name('leave-policies.export.excel');
        Route::get('leave-policies-export/pdf', [LeavePolicyController::class, 'exportPdf'])->name('leave-policies.export.pdf');

        // ===== LEAVE PERIODS =====
        Route::resource('leave-periods', LeavePeriodController::class);
        Route::patch('leave-periods/{id}/restore', [LeavePeriodController::class, 'restore'])->name('leave-periods.restore');
        Route::get('leave-periods-export/excel', [LeavePeriodController::class, 'exportExcel'])->name('leave-periods.export.excel');
        Route::get('leave-periods-export/pdf', [LeavePeriodController::class, 'exportPdf'])->name('leave-periods.export.pdf');

        // ===== COMPANY HOLIDAYS =====
        Route::resource('company-holidays', CompanyHolidayController::class);
        Route::patch('company-holidays/{id}/restore', [CompanyHolidayController::class, 'restore'])->name('company-holidays.restore');
        Route::get('company-holidays-export/excel', [CompanyHolidayController::class, 'exportExcel'])->name('company-holidays.export.excel');
        Route::get('company-holidays-export/pdf', [CompanyHolidayController::class, 'exportPdf'])->name('company-holidays.export.pdf');

        // ===== LEAVE WORKFLOWS =====
        Route::resource('leave-workflows', LeaveWorkflowController::class);
        Route::patch('leave-workflows/{id}/restore', [LeaveWorkflowController::class, 'restore'])->name('leave-workflows.restore');
        Route::get('leave-workflows-export/excel', [LeaveWorkflowController::class, 'exportExcel'])->name('leave-workflows.export.excel');
        Route::get('leave-workflows-export/pdf', [LeaveWorkflowController::class, 'exportPdf'])->name('leave-workflows.export.pdf');

        // ===== LEAVE VALIDATORS =====
        Route::resource('leave-validators', LeaveValidatorController::class);
        Route::get('leave-validators/get-employees/{site_id}', [LeaveValidatorController::class, 'getEmployeesBySite'])
            ->name('leave-validators.get-employees');

        // ===== RULE FIELDS =====
        Route::get('/leave-types/{leaveType}/rule-fields', [RuleFieldController::class, 'index'])->name('leave-types.rule-fields');
        Route::post('/leave-types/{leaveType}/rule-fields', [RuleFieldController::class, 'store'])->name('leave-types.rule-fields.store');
        Route::post('/leave-types/{leaveType}/rule-fields/seed-defaults', [RuleFieldController::class, 'seedDefaults'])->name('leave-types.rule-fields.seed');
        Route::post('/leave-types/{leaveType}/rule-fields/reorder', [RuleFieldController::class, 'reorder'])->name('leave-types.rule-fields.reorder');
        Route::put('/rule-fields/{ruleField}', [RuleFieldController::class, 'update'])->name('rule-fields.update');
        Route::delete('/rule-fields/{ruleField}', [RuleFieldController::class, 'destroy'])->name('rule-fields.destroy');

        // ===== CALCULATION RULES =====
        Route::get('/leave-types/{leaveType}/calculations', [CalculationRuleController::class, 'index'])->name('leave-types.calculations');
        Route::post('/leave-types/{leaveType}/calculations', [CalculationRuleController::class, 'store'])->name('leave-types.calculations.store');
        Route::put('/calculation-rules/{calculationRule}', [CalculationRuleController::class, 'update'])->name('calculation-rules.update');
        Route::delete('/calculation-rules/{calculationRule}', [CalculationRuleController::class, 'destroy'])->name('calculation-rules.destroy');
        Route::post('/calculation-rules/test', [CalculationRuleController::class, 'testFormula'])->name('calculation-rules.test');

        // ===== RÉFÉRENTIELS ORGANISATION =====
        Route::resource('departments', DepartmentController::class);
        Route::patch('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
        Route::get('departments-export/excel', [DepartmentController::class, 'exportExcel'])->name('departments.export.excel');
        Route::get('departments-export/pdf', [DepartmentController::class, 'exportPdf'])->name('departments.export.pdf');

        Route::resource('job-titles', JobTitleController::class);
        Route::patch('job-titles/{id}/restore', [JobTitleController::class, 'restore'])->name('job-titles.restore');
        Route::get('job-titles-export/excel', [JobTitleController::class, 'exportExcel'])->name('job-titles.export.excel');
        Route::get('job-titles-export/pdf', [JobTitleController::class, 'exportPdf'])->name('job-titles.export.pdf');

        Route::get('/get-job-titles-by-department', [EmployeController::class, 'getJobTitlesByDepartment'])
            ->name('get.job-titles.by.department')
            ->middleware('auth');

        Route::resource('hierarchy-levels', HierarchyLevelController::class);
        Route::patch('hierarchy-levels/{id}/restore', [HierarchyLevelController::class, 'restore'])->name('hierarchy-levels.restore');
        Route::get('hierarchy-levels-export/excel', [HierarchyLevelController::class, 'exportExcel'])->name('hierarchy-levels.export.excel');
        Route::get('hierarchy-levels-export/pdf', [HierarchyLevelController::class, 'exportPdf'])->name('hierarchy-levels.export.pdf');

        // ===== LEAVE POLICY ASSIGNMENTS =====
        Route::resource('leave-policy-assignments', LeavePolicyAssignmentController::class);
        Route::patch('leave-policy-assignments/{id}/toggle', [LeavePolicyAssignmentController::class, 'toggle'])
            ->name('leave-policy-assignments.toggle');
    });

    // ==========================================
    // LEAVE BALANCES
    // ==========================================
    Route::get('leave-balances', [LeaveBalanceController::class, 'index'])->name('leave-balances.index');
    Route::get('leave-balances/initialize', [LeaveBalanceController::class, 'initialize'])->name('leave-balances.initialize');
    Route::post('leave-balances/initialize', [LeaveBalanceController::class, 'storeInitialization'])->name('leave-balances.store-initialization');
    Route::get('leave-balances/{id}/transactions', [LeaveBalanceController::class, 'transactions'])->name('leave-balances.transactions');
    Route::post('leave-balances/{id}/adjust', [LeaveBalanceController::class, 'adjust'])->name('leave-balances.adjust');
    Route::get('leave-balances/import', [LeaveBalanceController::class, 'import'])->name('leave-balances.import');
    Route::post('leave-balances/import', [LeaveBalanceController::class, 'storeImport'])->name('leave-balances.store-import');
    Route::get('leave-balances/export', [LeaveBalanceController::class, 'export'])->name('leave-balances.export');
    Route::get('leave-balances/download-template', [LeaveBalanceController::class, 'downloadTemplate'])->name('leave-balances.download-template');

    // ==========================================
    // ROUTES SUPPLÉMENTAIRES (leave-periods override)
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::delete('leave-periods/override/{override}', [LeavePeriodController::class, 'destroySiteOverride'])
            ->name('leave-periods.site-override.destroy');
        Route::post('leave-periods/{leave_period}/site-override', [LeavePeriodController::class, 'storeSiteOverride'])
            ->name('leave-periods.site-override.store');
        Route::delete('site-leave-periods/{site_leave_period}', [LeavePeriodController::class, 'destroySiteOverride'])
            ->name('leave-periods.site-override.destroy');
    });
});

// ============================================
// ✅ ROUTES POUR LES EMPLOYÉS (employe.)
// ============================================
Route::middleware(['auth:employe'])->prefix('employe')->name('employe.')->group(function () {

    // ============================================
    // 1. DASHBOARD
    // ============================================
    Route::get('dashboard', [LeaveRequestController::class, 'dashboard'])->name('dashboard');

    // ============================================
    // 2. ROUTES SPÉCIFIQUES (SANS PARAMÈTRE {id})
    // ============================================
    Route::get('leave-requests/calculate-duration', [LeaveRequestController::class, 'calculateDurationAjax'])
        ->name('leave-requests.calculate-duration');

    Route::get('leave-requests/periods/{leaveTypeId}', [LeaveRequestController::class, 'getPeriodsByType'])
        ->name('leave-requests.periods-by-type');

    Route::get('leave-requests/balance', [LeaveRequestController::class, 'getBalance'])
        ->name('leave-requests.get-balance');

    Route::get('notifications', [EmployeNotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [EmployeNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('notifications/unread-count', [EmployeNotificationController::class, 'unreadCount'])->name('notifications.unread');

    // ============================================
    // 3. ROUTES CRUD (AVEC PARAMÈTRE {id})
    // ============================================
    Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
    Route::get('leave-requests/{id}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::get('leave-requests/{id}/edit', [LeaveRequestController::class, 'edit'])->name('leave-requests.edit');
    Route::put('leave-requests/{id}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
    Route::delete('leave-requests/{id}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');
    Route::post('leave-requests/{id}/submit', [LeaveRequestController::class, 'submit'])->name('leave-requests.submit');
    Route::post('leave-requests/{id}/cancel-approved', [LeaveRequestController::class, 'cancelApproved'])
        ->name('leave-requests.cancel-approved');

    // ============================================
    // 4. ROUTES SPÉCIFIQUES AVEC ID
    // ============================================
    Route::get('validations', [LeaveValidationController::class, 'index'])->name('validations.index');
    Route::post('validations/{id}/approve', [LeaveValidationController::class, 'approve'])->name('validations.approve');
    Route::post('validations/{id}/reject', [LeaveValidationController::class, 'reject'])->name('validations.reject');
    Route::get('validations/{id}', [LeaveValidationController::class, 'show'])->name('validations.show');

    Route::get('leave-requests/{id}/calculate-duration', [LeaveRequestController::class, 'calculateDurationForDraft'])
        ->name('leave-requests.calculate-duration-draft');

    Route::get('leave-requests/{id}/attachments/status', [LeaveRequestController::class, 'getAttachmentsStatus'])
        ->name('leave-requests.attachments-status');

    // ============================================
    // 5. ROUTES POUR LES PIÈCES JOINTES
    // ============================================
    Route::post('leave-requests/{id}/attachments', [LeaveRequestController::class, 'uploadAttachment'])
        ->name('leave-requests.upload-attachment');
    Route::delete('leave-requests/attachments/{id}', [LeaveRequestController::class, 'deleteAttachment'])
        ->name('leave-requests.delete-attachment');
    Route::get('leave-requests/attachments/{id}/download', [LeaveRequestController::class, 'downloadAttachment'])
        ->name('leave-requests.download-attachment');

    // ============================================
    // 6. CALENDRIER
    // ============================================
    Route::get('leave-calendar', [LeaveRequestController::class, 'calendar'])->name('leave-calendar.index');
    Route::get('calendar/events', [LeaveRequestController::class, 'getCalendarEvents'])->name('calendar.events');
});

// ============================================
// ROUTES POUR LE PORTAIL (employé) - déjà définies plus haut, on garde cette redirection
// ============================================
Route::middleware(['auth:employe'])->prefix('portail')->name('portail.')->group(function () {
    Route::get('/', [LeaveRequestController::class, 'dashboard'])->name('dashboard');
    Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
});

// ============================================
// ROUTES POUR LES MANAGERS
// ============================================
Route::middleware(['auth'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('dashboard', [ManagerLeaveRequestController::class, 'dashboard'])->name('dashboard');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread');
    Route::get('leave-requests', [ManagerLeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('leave-requests/{id}', [ManagerLeaveRequestController::class, 'show'])->name('leave-requests.show');
    Route::post('leave-requests/{id}/approve', [ManagerLeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('leave-requests/{id}/reject', [ManagerLeaveRequestController::class, 'reject'])->name('leave-requests.reject');
});

// ============================================
// API / AJAX (Routes pour les appels AJAX)
// ============================================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/departments-by-site/{siteId}', function ($siteId) {
        return \App\Models\Department::where('site_id', $siteId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    })->name('departments-by-site');

    Route::get('/managers-by-site/{siteId}', function ($siteId) {
        return \App\Models\Employe::where('SiegeID', $siteId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get(['ID as id', 'Nom as name']);
    })->name('managers-by-site');

    Route::get('/job-titles-by-site/{siteId}', function ($siteId) {
        return \App\Models\JobTitle::where('company_id', $siteId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    })->name('job-titles-by-site');

    Route::get('/hierarchy-levels-by-site/{siteId}', function ($siteId) {
        return \App\Models\HierarchyLevel::where('company_id', $siteId)
            ->orderBy('rank', 'desc')
            ->get(['id', 'name', 'rank', 'is_managerial']);
    })->name('hierarchy-levels-by-site');

    Route::get('/employees-by-site/{siteId}', function ($siteId) {
        return \App\Models\Employe::where('SiegeID', $siteId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get(['ID as id', 'Nom as name', 'num_mat']);
    })->name('employees-by-site');

    Route::get('/leave-types-by-site/{siteId}', function ($siteId) {
        return \App\Models\LeaveType::where('is_active', true)
            ->where(function ($q) use ($siteId) {
                $q->where('site_id', $siteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    })->name('leave-types-by-site');

    Route::get('/periods-by-site/{siteId}', function ($siteId) {
        return \App\Models\LeavePeriod::where('is_active', true)
            ->where(function ($q) use ($siteId) {
                $q->where('site_id', $siteId)
                  ->orWhereNull('site_id');
            })
            ->orderBy('start_date')
            ->get(['id', 'name', 'start_date', 'end_date']);
    })->name('periods-by-site');
});

// ============================================
// SÉLECTION DU SIÈGE (Session)
// ============================================
Route::post('/select-siege', function (\Illuminate\Http\Request $request) {
    $request->validate(['siege_id' => 'required|integer']);
    Session::put('admin_selected_siege_id', $request->siege_id);
    return back();
})->name('admin.select-siege');

// ============================================
// FALLBACK
// ============================================
Route::fallback(function () {
    return redirect()->route('sieges.index');
});