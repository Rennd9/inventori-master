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
    $request->validate([
        'nama' => 'required|string|max:255',
        'namasup' => 'required|string|max:255',
        'no_hp' => 'required|numeric',
        'alamat' => 'required|string',
    ]);

    DB::table('suppliers')->insert([
        'nama' => $request->nama,
        'namasup' => $request->namasup,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat
    ]);

    return redirect('/admin/suppliers')->with('success', 'Supplier ditambahkan!');
}
public function destroy($id)
    {
        DB::table('suppliers')->where('id', $id)->delete();
        return redirect()->route('suppliers.index')->with('success', 'Barang masuk berhasil dihapus.');
    }

}

