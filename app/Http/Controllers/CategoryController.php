<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')->get();
        return view('adminRole.barangCategories.index', compact('categories'));
    }

    public function create()
    {
        return view('adminRole.barangCategories.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
        ]);

        DB::table('categories')->insert([
            'name' => $request->name,
            // Cek jika checkbox dicentang, simpan sbg true (1), jika tidak, false (0)
            'has_expiration' => $request->has('has_expiration'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Beri feedback ke user
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::table('categories')->where('id', $id)->update([
            'name' => $request->name,
            'has_expiration' => $request->has('has_expiration'),
            'updated_at' => now(),
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }


    public function destroy($id)
    {
        DB::table('categories')->where('id', $id)->delete();
        return redirect('/kategori');
    }



}
