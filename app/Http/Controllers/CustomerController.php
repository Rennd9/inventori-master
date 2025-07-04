<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::table('customers')->get();
        return view('adminRole.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('adminRole.customers.create');
    }

    public function store(Request $request)
    {
        DB::table('customers')->insert([
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Costumers berhasil diperbarui.');
    }
    public function destroy($id)
    {
        DB::table('customers')->where('id', $id)->delete();
        return redirect()->route('customers.index')->with('success', 'Barang masuk berhasil disimpan.');
    }
}
