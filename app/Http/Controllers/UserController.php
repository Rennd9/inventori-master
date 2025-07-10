<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('adminRole.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('adminRole.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'required|in:0,1,2',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filename = null;

        if ($request->hasFile('image')) {
            $filename = uniqid() . '.' . $request->image->extension();
            $request->image->storeAs('public/users', $filename);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => (int) $request->type, // Pastikan tipe data integer
            'image' => $filename
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('adminRole.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:0,1,2',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user->name = $request->name;
        $user->type = (int) $request->type; // Pastikan tipe data integer

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->image && Storage::exists('public/users/' . $user->image)) {
                Storage::delete('public/users/' . $user->image);
            }

            $filename = uniqid() . '.' . $request->image->extension();
            $request->image->storeAs('public/users', $filename);
            $user->image = $filename;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($user->image && Storage::exists('public/users/' . $user->image)) {
            Storage::delete('public/users/' . $user->image);
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}