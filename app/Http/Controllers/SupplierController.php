<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = DB::table('suppliers')->get();
        return view('adminRole.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('adminRole.suppliers.create');
    }

    public function store(Request $request)
    {
        DB::table('suppliers')->insert([
            'nama' => $request->nama,
            'namasup' => $request->kontak,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]);
        return redirect('/admin/suppliers')->with('success', 'Supplier ditambahkan!');
    }
}

