<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItemController extends Controller
{
/**
 * View index untuk admin saja
 */
public function index()
{
    $items = DB::table('items')
        ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
        ->select('items.*', 'kategori.name as category_name')
        ->selectRaw('CASE 
            WHEN items.expired_date IS NOT NULL AND items.expired_date < NOW() THEN "KADALUARSA"
            WHEN items.expired_date IS NOT NULL AND items.expired_date <= DATE_ADD(NOW(), INTERVAL 7 DAY) THEN "SEGERA KADALUARSA"
            ELSE "NORMAL"
        END as status_kadaluarsa')
        ->get();

    $lowStocks = DB::table('items')
        ->whereColumn('stock', '<=', 'minimum_stock')
        ->get();

    $expiredItems = DB::table('items')
        ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
        ->select('items.*', 'kategori.name as category_name')
        ->where('items.expired_date', '<', now())
        ->whereNotNull('items.expired_date')
        ->get();

    $soonExpiredItems = DB::table('items')
        ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
        ->select('items.*', 'kategori.name as category_name')
        ->where('items.expired_date', '<=', Carbon::now()->addDays(7))
        ->where('items.expired_date', '>=', now())
        ->whereNotNull('items.expired_date')
        ->get();

    $pendingRequests = DB::table('restock_requests')
        ->join('items', 'restock_requests.item_id', '=', 'items.id')
        ->join('users', 'restock_requests.user_id', '=', 'users.id')
        ->where('restock_requests.status', 'pending')
        ->select('restock_requests.*', 'items.name as item_name', 'users.name as user_name')
        ->orderByDesc('restock_requests.created_at')
        ->get();
    
    $categories = DB::table('categories')->get();

    return view('adminRole.barangItems.index', compact('items', 'lowStocks', 'categories', 'pendingRequests', 'expiredItems', 'soonExpiredItems'));
}

/**
 * View index untuk Cheff
 */
public function indexCheff()
{
    $user = Auth::user();

    // 1. Dapatkan ID kategori yang diizinkan untuk user ini
    $allowedCategoryIds = DB::table('category_user')
        ->where('user_id', $user->id)
        ->pluck('category_id');

    // 2. Ambil item HANYA dari kategori yang diizinkan
    // Jika tidak ada kategori yang diizinkan, query akan mengembalikan hasil kosong
    $items = DB::table('items')
        ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
        ->whereIn('items.category_id', $allowedCategoryIds) // <-- INI BAGIAN KUNCINYA
        ->select('items.*', 'kategori.name as category_name')
        ->selectRaw('CASE 
            WHEN items.expired_date IS NOT NULL AND items.expired_date < NOW() THEN "KADALUARSA"
            WHEN items.expired_date IS NOT NULL AND items.expired_date <= DATE_ADD(NOW(), INTERVAL 7 DAY) THEN "SEGERA KADALUARSA"
            ELSE "NORMAL"
        END as status_kadaluarsa')
        ->get();

    // Query untuk restock requests tidak perlu diubah
    $restockRequests = DB::table('restock_requests')
        ->join('items', 'restock_requests.item_id', '=', 'items.id')
        ->where('restock_requests.user_id', $user->id)
        ->select('restock_requests.*', 'items.name as item_name')
        ->orderByDesc('restock_requests.created_at')
        ->get();

    return view('cheffRole.barangItems.index', compact('items', 'restockRequests'));
}

/**
 * View index untuk User biasa
 */
public function indexUser()
{
    $user = Auth::user();

    // 1. Dapatkan ID kategori yang diizinkan untuk user ini
    $allowedCategoryIds = DB::table('category_user')
        ->where('user_id', $user->id)
        ->pluck('category_id');

    // 2. Ambil item HANYA dari kategori yang diizinkan
    $items = DB::table('items')
        ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
        ->whereIn('items.category_id', $allowedCategoryIds) // <-- INI BAGIAN KUNCINYA
        ->select('items.*', 'kategori.name as category_name')
        ->selectRaw('CASE 
            WHEN items.expired_date IS NOT NULL AND items.expired_date < NOW() THEN "KADALUARSA"
            WHEN items.expired_date IS NOT NULL AND items.expired_date <= DATE_ADD(NOW(), INTERVAL 7 DAY) THEN "SEGERA KADALUARSA"
            ELSE "NORMAL"
        END as status_kadaluarsa')
        ->get();

    return view('userRole.barangItems.index', compact('items'));
}

    /**
     * Chef mengirim permintaan restok
     */
    public function requestRestock(Request $request, $item_id)
    {
        $request->validate([
            'message' => 'nullable|string|max:500'
        ]);

        $userId = Auth::id();

        $item = DB::table('items')->where('id', $item_id)->first();
        if (!$item) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        $existingRequest = DB::table('restock_requests')
            ->where('user_id', $userId)
            ->where('item_id', $item_id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Anda sudah memiliki permintaan restok pending untuk barang ini.');
        }

        DB::table('restock_requests')->insert([
            'user_id' => $userId,
            'item_id' => $item_id,
            'message' => $request->message ?? 'Permintaan restok untuk barang: ' . $item->name,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Permintaan pesan berhasil dikirim.');
    }

    /**
     * Admin mengubah status permintaan restok
     */
    public function updateRestockStatus($request_id, $status)
    {
        $validStatuses = ['pending', 'approved', 'rejected'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Status tidak valid.');
        }

        $requestData = DB::table('restock_requests')->where('id', $request_id)->first();
        if (!$requestData) {
            return back()->with('error', 'Request tidak ditemukan.');
        }

        DB::table('restock_requests')->where('id', $request_id)->update([
            'status' => $status,
            'updated_at' => now()
        ]);

        $messages = [
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'pending' => 'pending'
        ];

        return back()->with('success', 'Request pesan berhasil ' . $messages[$status] . '.');
    }

    /**
     * Tampilkan form tambah item (admin only)
     */
    public function create()
    {
        $categories = DB::table('categories')->get();
        return view('adminRole.barangItems.create', compact('categories'));
    }

    /**
     * Simpan item baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'expired_date' => 'nullable|date|after:today'
        ]);

        DB::table('items')->insert([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'minimum_stock' => '5',
            'expired_date' => $request->expired_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }


    /**
     * Update item
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'minimum_stock' => 'required|numeric|min:0',
            'expired_date' => 'nullable|date'
        ]);

        $affected = DB::table('items')->where('id', $id)->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit,
            'minimum_stock' => $request->minimum_stock,
            'expired_date' => $request->expired_date,
            'updated_at' => now()
        ]);

        if ($affected === 0) {
            return redirect()->route('items.index')->with('error', 'Barang tidak ditemukan atau tidak ada perubahan.');
        }

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus item (admin only)
     */
    public function destroy($id)
    {
        $hasRequests = DB::table('restock_requests')
            ->where('item_id', $id)
            ->where('status', 'pending')
            ->exists();

        if ($hasRequests) {
            return back()->with('error', 'Tidak dapat menghapus barang yang memiliki request restok pending.');
        }

        $deleted = DB::table('items')->where('id', $id)->delete();

        if ($deleted === 0) {
            return back()->with('error', 'Barang tidak ditemukan.');
        }

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
  

}