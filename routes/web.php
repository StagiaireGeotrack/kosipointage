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

// Authentification (Breeze)
require __DIR__.'/auth.php';

// Route d'accueil et langue
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Langue
    Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile_update_email', [ProfileController::class, 'update_Identifiant_email'])->name('profile.update_Identifiant_email');
    Route::patch('/profile_update_password', [ProfileController::class, 'update_Password'])->name('profile.update_Password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes protégées par le middleware siege.access
    Route::middleware('siege.access')->group(function () {
        // CRUD des entreprises
        Route::resource('entreprises', EntrepriseController::class);
        Route::get('/entreprises/{id}/logo', [EntrepriseController::class, 'getLogo'])->name('entreprises.logo');
        Route::get('/entreprises/{id}/logo/thumbnail', [EntrepriseController::class, 'getLogoThumbnail'])->name('entreprises.logo.thumbnail');
        Route::get('/entreprises-export/excel', [EntrepriseController::class, 'exportExcel'])->name('entreprises.export.excel');
        Route::get('/entreprises-export/pdf', [EntrepriseController::class, 'exportPdf'])->name('entreprises.export.pdf');
        
        // CRUD des sièges
        Route::resource('sieges', EntrepriseSiegeController::class);
        Route::patch("/update-siege" , [EntrepriseSiegeController::class, 'update_siege'])->name('sieges.update_siege');
        Route::get('/sieges-export/excel', [EntrepriseSiegeController::class, 'exportExcel'])->name('sieges.export.excel');
        Route::get('/sieges-export/pdf', [EntrepriseSiegeController::class, 'exportPdf'])->name('sieges.export.pdf');
        
        // CRUD des employés
        Route::resource('employes', EmployeController::class);
        Route::get('/employes/{id}/face', [EmployeController::class, 'getFaceEncoding'])->name('employes.face');
        Route::get('/employes/{id}/face/thumbnail', [EmployeController::class, 'getFaceThumbnail'])->name('employes.face.thumbnail');
        Route::get('/employes-export/excel', [EmployeController::class, 'exportExcel'])->name('employes.export.excel');
        Route::get('/employes-export/pdf', [EmployeController::class, 'exportPdf'])->name('employes.export.pdf');
        
        // CRUD des pointages
        Route::resource('pointages', PointageController::class);
        Route::get('/pointages/{id}/photo', [PointageController::class, 'getPhoto'])->name('pointages.photo');
        Route::get('/pointages/{id}/photo/thumbnail', [PointageController::class, 'getPhotoThumbnail'])->name('pointages.photo.thumbnail');
        Route::get('/pointages-export/excel', [PointageController::class, 'exportExcel'])->name('pointages.export.excel');
        Route::get('/pointages-export/pdf', [PointageController::class, 'exportPdf'])->name('pointages.export.pdf');
        Route::get('/pointages/get-employes-by-siege/{SiegeID}', [PointageController::class, 'getEmployesBySiege'])->name('pointages.employees-by-siege');
        Route::get('/pointages/details/{employe}/{date}', [PointageController::class, 'showDetails'])->name('pointages.show.details');
        
        // Rapports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/reports/day-night', [ReportController::class, 'dayNight'])->name('reports.day-night');
        Route::get('/reports/export/excel/{type}', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf/{type}', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::post('/reports/rapport-auto', [ReportController::class, 'getRapportAuto'])->name('reports.rapport-auto');

        // Congés
        Route::resource('conges', CongeController::class);
        Route::get('/conges-export/excel', [CongeController::class, 'exportExcel'])->name('conges.export.excel');
        Route::get('/conges-export/pdf', [CongeController::class, 'exportPdf'])->name('conges.export.pdf');
        
        // CRUD des jours non travaillés
        Route::resource('jours-non-travailles', JourNonTravailleController::class);
        Route::get('/jours-non-travailles-export/excel', [JourNonTravailleController::class, 'exportExcel'])->name('jours-non-travailles.export.excel');
        Route::get('/jours-non-travailles-export/pdf', [JourNonTravailleController::class, 'exportPdf'])->name('jours-non-travailles.export.pdf');

    });
    
    // Routes accessibles uniquement aux SuperAdmin
    Route::middleware('can:superadmin')->group(function () {

        // CRUD des administrateurs
        Route::resource('administrateurs', AdministrationController::class);
        Route::get('/administrateurs-export/excel', [AdministrationController::class, 'exportExcel'])->name('administrateurs.export.excel');
        Route::get('/administrateurs-export/pdf', [AdministrationController::class, 'exportPdf'])->name('administrateurs.export.pdf');

        // CRUD des vendeurs
        Route::resource('sellers', SellerController::class);
        Route::post('sellers/{id}/toggle-active', [SellerController::class, 'toggleActive'])->name('sellers.toggle-active');

        // Dashboard (SuperAdmin uniquement)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
    });
});

Route::fallback(function () {
    return redirect()->route('sieges.index');
});