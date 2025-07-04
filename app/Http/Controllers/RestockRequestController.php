<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RestockRequestController extends Controller
{
    public function index()
    {
        // Hanya admin yang bisa melihat semua request
        $requests = DB::table('restock_requests')
            ->join('items', 'restock_requests.item_id', '=', 'items.id')
            ->join('users', 'restock_requests.user_id', '=', 'users.id')
            ->select('restock_requests.*', 'items.name as item_name', 'users.name as user_name')
            ->orderBy('restock_requests.created_at', 'desc')
            ->get();

        return view('adminRole.barangItems.index', compact('requests'));
    }

    public function store(Request $request)
    {
        DB::table('restock_requests')->insert([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'requested_quantity' => $request->requested_quantity,
            'message' => $request->message,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Permintaan restok dikirim.');
    }

    public function updateStatus($id, $status)
    {
        DB::table('restock_requests')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }
}

