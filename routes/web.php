<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CongeController;
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

// Authentification (Breeze)
require __DIR__.'/auth.php';

// Route d'accueil et langue
Route::get('/', function () 
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();    
    
    // Redirection selon le rôle
    if ($user->isTrueSuperAdmin()) 
    {
        return redirect()->route('dashboard');
    } 
    elseif ($user->isSimpleAdmin()) 
    {
        return redirect()->route('dashboard.simple-admin');
    } 
    elseif ($user->isSeller()) {
        return redirect()->route('dashboard.seller');
    }
    
    // Par défaut, redirection vers sieges.index
    return redirect()->route('sieges.index');
});

Route::middleware('auth')->group(function () {
    // Langue
    Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile_update_email', [ProfileController::class, 'update_Identifiant_email'])->name('profile.update_Identifiant_email');
    Route::patch('/profile_update_password', [ProfileController::class, 'update_Password'])->name('profile.update_Password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ========== DASHBOARDS ==========
    // Dashboard pour Vendeurs uniquement
    Route::get('/dashboard/seller', [AllDashboardController::class, 'dashboardSeller'])->name('dashboard.seller')->middleware('only.sellers');
    Route::get('/details-employe/{id}', [SellerController::class, 'show_employee'])->name('employe.show.seller')->middleware('only.sellers');
    
    // Dashboard pour Simple Admin uniquement
    Route::get('/dashboard/simple-admin', [AllDashboardController::class, 'dashboardSimpleAdmin'])
        ->name('dashboard.simple-admin')
        ->middleware('block.sellers');
    
    Route::middleware('siege.access')->group(function () {
        
        // ========== SIÈGES : CRUD (Super Admin uniquement) ==========
        // ⚠️ CRITIQUE : Ces routes doivent être AVANT les routes de consultation
        Route::middleware(['block.sellers', 'block.simple.admin.sieges'])->group(function () {
            Route::get('/sieges/create', [EntrepriseSiegeController::class, 'create'])->name('sieges.create');
            Route::post('/sieges', [EntrepriseSiegeController::class, 'store'])->name('sieges.store');
            Route::get('/sieges/{siege}/edit', [EntrepriseSiegeController::class, 'edit'])->name('sieges.edit');
            Route::put('/sieges/{siege}', [EntrepriseSiegeController::class, 'update'])->name('sieges.update');
            Route::patch('/sieges/{siege}', [EntrepriseSiegeController::class, 'update']);
            Route::delete('/sieges/{siege}', [EntrepriseSiegeController::class, 'destroy'])->name('sieges.destroy');
            Route::patch("/update-siege", [EntrepriseSiegeController::class, 'update_siege'])->name('sieges.update_siege');
        });
        
        // ========== SIÈGES : Consultation (Tous) ==========
        Route::get('/sieges', [EntrepriseSiegeController::class, 'index'])->name('sieges.index');
        Route::get('/sieges-export/excel', [EntrepriseSiegeController::class, 'exportExcel'])->name('sieges.export.excel');
        Route::get('/sieges-export/pdf', [EntrepriseSiegeController::class, 'exportPdf'])->name('sieges.export.pdf');
        Route::get('/sieges/{siege}', [EntrepriseSiegeController::class, 'show'])->name('sieges.show');
        
        // ========== ENTREPRISES : CRUD (Super Admin + Simple Admin) ==========
        // ⚠️ CRITIQUE : Ces routes doivent être AVANT les routes de consultation
        Route::middleware('block.sellers')->group(function () {
            Route::get('/entreprises/create', [EntrepriseController::class, 'create'])->name('entreprises.create');
            Route::post('/entreprises', [EntrepriseController::class, 'store'])->name('entreprises.store');
            Route::get('/entreprises/{entreprise}/edit', [EntrepriseController::class, 'edit'])->name('entreprises.edit');
            Route::put('/entreprises/{entreprise}', [EntrepriseController::class, 'update'])->name('entreprises.update');
            Route::patch('/entreprises/{entreprise}', [EntrepriseController::class, 'update']);
            Route::delete('/entreprises/{entreprise}', [EntrepriseController::class, 'destroy'])->name('entreprises.destroy');
        });
        
        // ========== ENTREPRISES : Consultation (Tous) ==========
        Route::get('/entreprises', [EntrepriseController::class, 'index'])->name('entreprises.index');
        Route::get('/entreprises-export/excel', [EntrepriseController::class, 'exportExcel'])->name('entreprises.export.excel');
        Route::get('/entreprises-export/pdf', [EntrepriseController::class, 'exportPdf'])->name('entreprises.export.pdf');
        Route::get('/entreprises/{entreprise}', [EntrepriseController::class, 'show'])->name('entreprises.show');
        Route::get('/entreprises/{id}/logo', [EntrepriseController::class, 'getLogo'])->name('entreprises.logo');
        Route::get('/entreprises/{id}/logo/thumbnail', [EntrepriseController::class, 'getLogoThumbnail'])->name('entreprises.logo.thumbnail');
        
        // ========== EMPLOYÉS : CRUD (Super Admin + Simple Admin) ==========
        // ⚠️ CRITIQUE : Ces routes doivent être AVANT les routes de consultation
        Route::middleware('block.sellers')->group(function () {
            Route::get('/employes/create', [EmployeController::class, 'create'])->name('employes.create');
            Route::post('/employes', [EmployeController::class, 'store'])->name('employes.store');
            Route::get('/employes/{employe}/edit', [EmployeController::class, 'edit'])->name('employes.edit');
            Route::put('/employes/{employe}', [EmployeController::class, 'update'])->name('employes.update');
            Route::patch('/employes/{employe}', [EmployeController::class, 'update']);
            Route::delete('/employes/{employe}', [EmployeController::class, 'destroy'])->name('employes.destroy');
        });
        
        // ========== EMPLOYÉS : Consultation (Tous) ==========
        Route::get('/employes', [EmployeController::class, 'index'])->name('employes.index');
        Route::get('/employes-export/excel', [EmployeController::class, 'exportExcel'])->name('employes.export.excel');
        Route::get('/employes-export/pdf', [EmployeController::class, 'exportPdf'])->name('employes.export.pdf');
        Route::get('/employes/{employe}', [EmployeController::class, 'show'])->name('employes.show');
        Route::get('/employes/{id}/face', [EmployeController::class, 'getFaceEncoding'])->name('employes.face');
        Route::get('/employes/{id}/face/thumbnail', [EmployeController::class, 'getFaceThumbnail'])->name('employes.face.thumbnail');
        Route::patch('employes/{id}/reset-pin', [EmployeController::class, 'resetCodePin'])->name('employes.reset-pin');
        
        // ========== POINTAGES : CRUD complet (Super Admin + Simple Admin) ==========
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
        
        // ========== RAPPORTS (Super Admin + Simple Admin) ==========
        Route::middleware('block.sellers')->group(function () {
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
            Route::get('/reports/day-night', [ReportController::class, 'dayNight'])->name('reports.day-night');
            Route::get('/reports/export/excel/{type}', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
            Route::get('/reports/export/pdf/{type}', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
            Route::post('/reports/rapport-auto', [ReportController::class, 'getRapportAuto'])->name('reports.rapport-auto');
        });

        // ========== CONGÉS : CRUD complet (Super Admin + Simple Admin) ==========
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
        
        // ========== JOURS NON TRAVAILLÉS : CRUD complet (Super Admin + Simple Admin) ==========
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
    });
    
    // ========== Routes accessibles uniquement aux VRAIS SuperAdmin ==========
    Route::middleware('can:superadmin')->group(function () {

        // ========== CRUD des administrateurs ==========
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

        // ========== CRUD des vendeurs ==========
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

        // ========== Dashboard (SuperAdmin uniquement) ==========
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

Route::fallback(function () {
    return redirect()->route('sieges.index');
});