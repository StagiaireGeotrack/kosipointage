<?php
// routes/web.php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\EntrepriseSiegeController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard (SuperAdmin uniquement)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('can:superadmin')
        ->name('dashboard');
    
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
        
        // Rapports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/reports/day-night', [ReportController::class, 'dayNight'])->name('reports.day-night');
        Route::get('/reports/export/excel/{type}', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf/{type}', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    });
    
    // Routes accessibles uniquement aux SuperAdmin
    Route::middleware('can:superadmin')->group(function () {
        // CRUD des administrateurs
        Route::resource('administrateurs', AdministrationController::class);
        Route::get('/administrateurs-export/excel', [AdministrationController::class, 'exportExcel'])->name('administrateurs.export.excel');
        Route::get('/administrateurs-export/pdf', [AdministrationController::class, 'exportPdf'])->name('administrateurs.export.pdf');
    });
});