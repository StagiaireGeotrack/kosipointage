<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Models\Pointage;
use Illuminate\Http\Request;

class AllDashboardController extends Controller
{
    // Vendeur
    public function dashboardSeller() 
    {
        $user = auth()->user();
        $siegeIds = $admin->getSiegeIdsAccessibles();
        $sieges = EntrepriseSiege::whereIn( "SiegeID" , $siegeIds )->get() ;
    }

    // Simple Admin
    public function dashboardSimpleAdmin() 
    {
        $user = auth()->user();
        $siege = EntrepriseSiege::find( $user->SiegeID ) ;
        $employes = Employe::where("SiegeID" , "=" , $user->SiegeID )->get() ;
        $pointages = Pointage::where("SiegeID" , "=" , $user->SiegeID )->get() ;
    }
}
