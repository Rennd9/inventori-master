<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IncomingGoodsController extends Controller
{
    // INDEX: Menampilkan list barang masuk (Admin)
    public function index()
    {
        $data = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            ->select(
                'incoming_goods.*',
                'items.name as item_name',
                'items.unit',
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.date', 'desc')
            ->get();

        return view('adminRole.barangMasuk.index', compact('data'));
    }

    public function getCreateData(Request $request)
    {
        // 1. Dapatkan user dan hak akses kategorinya
        $user = Auth::user();
        $allowedCategoryIds = DB::table('category_user')
            ->where('user_id', $user->id)
            ->pluck('category_id');

        // 2. MODIFIKASI DI SINI: Ambil HANYA barang yang kategorinya diizinkan
        $items = DB::table('items')
            ->whereIn('category_id', $allowedCategoryIds) // <-- Filter diterapkan di sini
            ->orderBy('name', 'asc')
            ->get();

        // Baris ini tidak perlu diubah
        $suppliers = DB::table('suppliers')->get();

        // --- Logika selanjutnya tidak perlu diubah ---
        $selectedItem = null;
        $defaultSupplierId = null;
        $lastQuantity = null;

        if ($request->has('item_id')) {
            // Pengecekan keamanan: pastikan item yang diminta ada dalam daftar yang diizinkan
            $selectedItem = $items->firstWhere('id', $request->item_id);

            if ($selectedItem) {
                $lastIncoming = DB::table('incoming_goods')
                    ->where('item_id', $request->item_id)
                    ->orderByDesc('created_at')
                    ->first();

                if ($lastIncoming) {
                    $lastQuantity = $lastIncoming->quantity;
                    $defaultSupplierId = $lastIncoming->supplier_id;
                }
            }
        }

        return compact('items', 'suppliers', 'selectedItem', 'defaultSupplierId', 'lastQuantity');
    }

    // CREATE: Form tambah barang masuk (Admin)
    public function create(Request $request)
    {
        $data = $this->getCreateData($request);
        return view('adminRole.barangMasuk.create', $data);
    }

    // STORE: Simpan ke DB dan update stok (Admin)
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah sudah ada entry dengan kombinasi yang sama pada hari yang sama
            $existingEntry = DB::table('incoming_goods')
                ->where('item_id', $request->item_id)
                ->where('supplier_id', $request->supplier_id)
                ->where('date', $request->date)
                ->first();

            if ($existingEntry) {
                // Jika sudah ada, update quantity yang sudah ada
                DB::table('incoming_goods')
                    ->where('id', $existingEntry->id)
                    ->update([
                        'quantity' => $existingEntry->quantity + $request->quantity,
                        'updated_at' => now(),
                    ]);

                // Tambah stok ke item terkait
                DB::table('items')->where('id', $request->item_id)->increment('stock', $request->quantity);

                DB::commit();
                return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan ke data yang sudah ada.');
            } else {
                // Jika belum ada, buat entry baru
                DB::table('incoming_goods')->insert([
                    'item_id' => $request->item_id,
                    'supplier_id' => $request->supplier_id,
                    'quantity' => $request->quantity,
                    'date' => $request->date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tambah stok ke item terkait
                DB::table('items')->where('id', $request->item_id)->increment('stock', $request->quantity);

                DB::commit();
                return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil disimpan.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }


    // DESTROY: Hapus data barang masuk (Admin)
    public function destroy($id)
    {
        $incomingGood = DB::table('incoming_goods')->where('id', $id)->first();
        
        if (!$incomingGood) {
            return redirect()->route('barang-masuk.index')->with('error', 'Data tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Kurangi stok item
            DB::table('items')->where('id', $incomingGood->item_id)->decrement('stock', $incomingGood->quantity);
            
            // Hapus data
            DB::table('incoming_goods')->where('id', $id)->delete();

            DB::commit();
            return redirect()->route('barang-masuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // ===============================================================
    // CHEFF ROLE FUNCTIONS
    // ===============================================================

    public function indexCheff()
    {
        // 1. Dapatkan user yang sedang login
        $user = Auth::user();

        // 2. Ambil ID kategori yang diizinkan untuk user ini
        $allowedCategoryIds = DB::table('category_user')
            ->where('user_id', $user->id)
            ->pluck('category_id');

        // 3. Modifikasi query utama dengan filter
        $data = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            
            // INI BAGIAN KUNCINYA: Terapkan filter pada category_id di tabel items
            ->whereIn('items.category_id', $allowedCategoryIds) 
            
            ->select(
                'incoming_goods.*',
                'items.name as item_name',
                'items.unit',
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.date', 'desc')
            ->get();

        return view('cheffrole.barangMasuk.index', compact('data'));
    }

    public function createCheff(Request $request)
    {
        $data = $this->getCreateData($request);
        return view('cheffRole.barangMasuk.create', $data);
    }

    public function storeCheff(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah sudah ada entry dengan kombinasi yang sama pada hari yang sama
            $existingEntry = DB::table('incoming_goods')
                ->where('item_id', $request->item_id)
                ->where('supplier_id', $request->supplier_id)
                ->where('date', $request->date)
                ->first();

            if ($existingEntry) {
                // Jika sudah ada, update quantity yang sudah ada
                DB::table('incoming_goods')
                    ->where('id', $existingEntry->id)
                    ->update([
                        'quantity' => $existingEntry->quantity + $request->quantity,
                        'updated_at' => now(),
                    ]);

                // Tambah stok ke item terkait
                DB::table('items')->where('id', $request->item_id)->increment('stock', $request->quantity);

                DB::commit();
                return redirect()->route('cheff.barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan ke data yang sudah ada.');
            } else {
                // Jika belum ada, buat entry baru
                DB::table('incoming_goods')->insert([
                    'item_id' => $request->item_id,
                    'supplier_id' => $request->supplier_id,
                    'quantity' => $request->quantity,
                    'date' => $request->date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tambah stok ke item terkait
                DB::table('items')->where('id', $request->item_id)->increment('stock', $request->quantity);

                DB::commit();
                return redirect()->route('cheff.barang-masuk.index')->with('success', 'Barang masuk berhasil disimpan.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function indexBarista()
    {
        // ... (kode ini sudah benar, tidak perlu diubah)
        $user = Auth::user();
        $allowedCategoryIds = DB::table('category_user')
            ->where('user_id', $user->id)
            ->pluck('category_id');
        $data = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            ->whereIn('items.category_id', $allowedCategoryIds) 
            ->select(
                'incoming_goods.*',
                'items.name as item_name',
                'items.unit',
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.date', 'desc')
            ->get();
        return view('userrole.barangMasuk.index', compact('data'));
    }

    public function createBarista(Request $request)
    {
        // Memanggil method getCreateData yang sudah memiliki filter hak akses
        $data = $this->getCreateData($request);

        // Arahkan ke view khusus untuk Barista
        return view('userRole.barangMasuk.create', $data);
    }

    public function storeBarista(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah sudah ada entry dengan kombinasi yang sama pada hari yang sama
            $existingEntry = DB::table('incoming_goods')
                ->where('item_id', $request->item_id)
                ->where('supplier_id', $request->supplier_id)
                ->where('date', $request->date)
                ->first();

            if ($existingEntry) {
                // Jika sudah ada, update quantity
                DB::table('incoming_goods')
                    ->where('id', $existingEntry->id)
                    ->update([
                        'quantity' => $existingEntry->quantity + $request->quantity,
                        'updated_at' => now(),
                    ]);
            } else {
                // Jika belum ada, buat entry baru
                DB::table('incoming_goods')->insert([
                    'item_id' => $request->item_id,
                    'supplier_id' => $request->supplier_id,
                    'quantity' => $request->quantity,
                    'date' => $request->date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Tambah stok ke item terkait
            DB::table('items')->where('id', $request->item_id)->increment('stock', $request->quantity);

            DB::commit();

            // Redirect ke halaman index Barista
            return redirect()->route('barista.barang-masuk.index')->with('success', 'Barang masuk berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }


}