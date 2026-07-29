<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeaveTypeController;
use App\Http\Controllers\Api\CompanyHolidayController;

// Tes nouvelles routes API pour la gestion des congés
Route::prefix('v1')->group(function () {
    Route::post('/leave-types', [LeaveTypeController::class, 'store']);
    Route::delete('/leave-types/{id}', [LeaveTypeController::class, 'destroy']);
    
    Route::post('/company-holidays', [CompanyHolidayController::class, 'store']);
    Route::delete('/company-holidays/{id}', [CompanyHolidayController::class, 'destroy']);
});