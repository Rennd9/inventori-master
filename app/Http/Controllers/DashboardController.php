<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
     public function __construct()

    {

        $this->middleware('auth');

    }
   public function adminView() 
{
    $totalItems = DB::table('items')->count();

    $totalStock = DB::table('items')->sum('stock');

    $barangMasuk = DB::table('incoming_goods')
        ->whereMonth('date', Carbon::now()->month)
        ->sum('quantity');

    $barangKeluar = DB::table('outgoing_goods')
        ->whereMonth('date', Carbon::now()->month)
        ->sum('quantity');

    $lowStocks = DB::table('items')
        ->whereColumn('stock', '<=', 'minimum_stock')
        ->count();

    return view('adminRole.dashboard', compact(
        'totalItems', 'totalStock', 'barangMasuk', 'barangKeluar', 'lowStocks'
    ));
}

public function cheffView() 
{
 return view('cheffRole.dashboard');
}

public function userView() 
{
     return view('userRole.dashboard');
}
}