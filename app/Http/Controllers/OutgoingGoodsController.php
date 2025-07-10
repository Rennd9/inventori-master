<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OutgoingGoodsController extends Controller
{
    // INDEX: Menampilkan list barang keluar (Admin)
    public function index()
    {
        $data = DB::table('outgoing_goods')
            ->join('items', 'outgoing_goods.item_id', '=', 'items.id')
            ->join('customers', 'outgoing_goods.customer_id', '=', 'customers.id')
            ->select(
                'outgoing_goods.*',
                'items.name as item_name',
                'customers.nama as customer_name'
            )
            ->orderBy('outgoing_goods.date', 'desc')
            ->get();

        return view('adminRole.barangKeluar.index', compact('data'));
    }

    public function getCreateData(Request $request)
    {
        // 1. Dapatkan user dan hak akses kategorinya
        $user = Auth::user();
        $allowedCategoryIds = DB::table('category_user')
            ->where('user_id', $user->id)
            ->pluck('category_id');

        // 2. MODIFIKASI DI SINI: Ambil HANYA barang yang diizinkan & punya stok
        $items = DB::table('items')
            ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
           
            ->whereIn('items.category_id', $allowedCategoryIds) // <-- Filter hak akses ditambahkan di sini
            ->select('items.*', 'kategori.name as category_name') // Ambil nama kategori untuk grouping di view
            ->orderBy('items.name', 'asc')
            ->get();

        // Baris ini tidak perlu diubah
        $customers = DB::table('customers')->get();

        // --- Logika selanjutnya tidak perlu diubah ---
        $selectedItem = null;
        $defaultCustomerId = null;
        $lastQuantity = null;
        $availableStock = null;

        if ($request->has('item_id')) {
            // Cek item yang dipilih dari koleksi yang sudah difilter
            $selectedItem = $items->firstWhere('id', $request->item_id);
            
            if ($selectedItem) {
                $availableStock = $selectedItem->stock;

                $lastOutgoing = DB::table('outgoing_goods')
                    ->where('item_id', $request->item_id)
                    ->orderByDesc('created_at')
                    ->first();

                if ($lastOutgoing) {
                    $lastQuantity = $lastOutgoing->quantity;
                    $defaultCustomerId = $lastOutgoing->customer_id;
                }
            }
        }

        return compact('items', 'customers', 'selectedItem', 'defaultCustomerId', 'lastQuantity', 'availableStock');
    }

    // CREATE: Form tambah barang keluar (Admin)
    public function create(Request $request)
    {
        $data = $this->getCreateData($request);
        return view('adminRole.barangKeluar.create', $data);
    }

    // STORE: Simpan ke DB dan update stok (Admin)
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        $item = DB::table('items')->where('id', $request->item_id)->first();

        if ($item && $item->stock >= $request->quantity) {
            DB::table('outgoing_goods')->insert([
                'item_id' => $request->item_id,
                'customer_id' => $request->customer_id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kurangi stok dari item terkait
            DB::table('items')->where('id', $request->item_id)->decrement('stock', $request->quantity);

            return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . ($item->stock ?? 0));
        }
    }


    // EDIT: Form edit barang keluar (Admin)
    public function edit($id)
    {
        $outgoingGood = DB::table('outgoing_goods')->where('id', $id)->first();
        
        if (!$outgoingGood) {
            return redirect()->route('barang-keluar.index')->with('error', 'Data tidak ditemukan.');
        }

        $items = DB::table('items')->get();
        $customers = DB::table('customers')->get();

        return view('adminRole.barangKeluar.edit', compact('outgoingGood', 'items', 'customers'));
    }

    // UPDATE: Update data barang keluar (Admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        $oldData = DB::table('outgoing_goods')->where('id', $id)->first();
        
        if (!$oldData) {
            return redirect()->route('barang-keluar.index')->with('error', 'Data tidak ditemukan.');
        }

        // Cek stok untuk perubahan
        $item = DB::table('items')->where('id', $request->item_id)->first();
        $currentAvailableStock = $item->stock;

        // Jika item sama, tambah kembali quantity lama ke stok
        if ($oldData->item_id == $request->item_id) {
            $availableStock = $currentAvailableStock + $oldData->quantity;
            if ($availableStock < $request->quantity) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $availableStock);
            }
        } else {
            // Item berbeda, cek stok item baru
            if ($currentAvailableStock < $request->quantity) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $currentAvailableStock);
            }
        }

        // Update data
        DB::table('outgoing_goods')->where('id', $id)->update([
            'item_id' => $request->item_id,
            'customer_id' => $request->customer_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'updated_at' => now(),
        ]);

        // Adjust stock: tambah kembali stok lama, kurangi stok baru
        if ($oldData->item_id == $request->item_id) {
            // Item sama, update selisih quantity
            $difference = $request->quantity - $oldData->quantity;
            DB::table('items')->where('id', $request->item_id)->decrement('stock', $difference);
        } else {
            // Item berbeda, kembalikan stok item lama dan kurangi stok item baru
            DB::table('items')->where('id', $oldData->item_id)->increment('stock', $oldData->quantity);
            DB::table('items')->where('id', $request->item_id)->decrement('stock', $request->quantity);
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Data barang keluar berhasil diupdate.');
    }

    // DESTROY: Hapus data barang keluar (Admin)
    public function destroy($id)
    {
        $outgoingGood = DB::table('outgoing_goods')->where('id', $id)->first();
        
        if (!$outgoingGood) {
            return redirect()->route('barang-keluar.index')->with('error', 'Data tidak ditemukan.');
        }

        // Kembalikan stok item
        DB::table('items')->where('id', $outgoingGood->item_id)->increment('stock', $outgoingGood->quantity);
        
        // Hapus data
        DB::table('outgoing_goods')->where('id', $id)->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Data barang keluar berhasil dihapus dan stok dikembalikan.');
    }

    // ===============================================================
    // CHEFF ROLE FUNCTIONS
    // ===============================================================

    // INDEX CHEFF: Menampilkan list barang keluar untuk cheff
    public function indexCheff()
    {
        // 1. Dapatkan user yang sedang login
        $user = Auth::user();

        // 2. Ambil ID kategori yang diizinkan untuk user ini
        $allowedCategoryIds = DB::table('category_user')
            ->where('user_id', $user->id)
            ->pluck('category_id');

        // 3. Modifikasi query utama dengan filter
        $data = DB::table('outgoing_goods')
            ->join('items', 'outgoing_goods.item_id', '=', 'items.id')
            ->join('customers', 'outgoing_goods.customer_id', '=', 'customers.id')
            
            // FILTER DITAMBAHKAN DI SINI
            ->whereIn('items.category_id', $allowedCategoryIds) 
            
            ->select(
                'outgoing_goods.*',
                'items.name as item_name',
                'customers.nama as customer_name'
            )
            ->orderBy('outgoing_goods.date', 'desc')
            ->get();

        return view('cheffRole.barangKeluar.index', compact('data'));
    }

    // CREATE CHEFF: Form tambah barang keluar untuk cheff
    public function createCheff(Request $request)
    {
        $data = $this->getCreateData($request);
        return view('cheffRole.barangKeluar.create', $data);
    }

    // STORE CHEFF: Simpan barang keluar untuk cheff
    public function storeCheff(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        $item = DB::table('items')->where('id', $request->item_id)->first();

        if ($item && $item->stock >= $request->quantity) {
            DB::table('outgoing_goods')->insert([
                'item_id' => $request->item_id,
                'customer_id' => $request->customer_id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kurangi stok dari item terkait
            DB::table('items')->where('id', $request->item_id)->decrement('stock', $request->quantity);

            return redirect()->route('cheff.barang-keluar.index')->with('success', 'Barang keluar berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . ($item->stock ?? 0));
        }
    }


    // ===============================================================
    // BARISTA/USER ROLE FUNCTIONS
    // ===============================================================

    public function createBarista(Request $request)
        {
            // 1. Ambil user dan hak akses kategorinya
            $user = Auth::user();
            $allowedCategoryIds = DB::table('category_user')
                ->where('user_id', $user->id)
                ->pluck('category_id');

            // 2. Ambil item HANYA dari kategori yang diizinkan untuk Barista
            $items = DB::table('items')
                ->leftJoin('categories as kategori', 'kategori.id', '=', 'items.category_id')
                ->where('items.stock', '>', 0)
                ->whereIn('items.category_id', $allowedCategoryIds) // <-- Filter dinamis digunakan di sini
                ->select('items.*', 'kategori.name as category_name')
                ->orderBy('items.name', 'asc')
                ->get();
                
            $customers = DB::table('customers')->get();

            return view('userRole.barangKeluar.create', compact('items', 'customers'));
        }

        /**
         * STORE BARISTA: Simpan barang keluar untuk Barista
         * Dengan validasi hak akses sebelum menyimpan.
         */
        public function storeBarista(Request $request)
        {
            $request->validate([
                'item_id' => 'required|exists:items,id',
                'customer_id' => 'required|exists:customers,id',
                'quantity' => 'required|integer|min:1',
                'date' => 'required|date'
            ]);

            $user = Auth::user();
            $allowedCategoryIds = DB::table('category_user')
                ->where('user_id', $user->id)
                ->pluck('category_id');

            // VALIDASI HAK AKSES: Pastikan item yang dipilih ada dalam kategori yang diizinkan
            $isItemAllowed = DB::table('items')
                ->where('id', $request->item_id)
                ->whereIn('category_id', $allowedCategoryIds)
                ->exists();

            if (!$isItemAllowed) {
                return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk barang ini.');
            }

            $item = DB::table('items')->where('id', $request->item_id)->first();

            if ($item && $item->stock >= $request->quantity) {
                DB::table('outgoing_goods')->insert([
                    'item_id' => $request->item_id,
                    'customer_id' => $request->customer_id,
                    'quantity' => $request->quantity,
                    'date' => $request->date,
                    'note' => 'Processed by Barista: ' . $user->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('items')->where('id', $request->item_id)->decrement('stock', $request->quantity);

                // Arahkan ke route index milik Barista/User
                return redirect()->route('user.barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil disimpan.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . ($item->stock ?? 0));
            }
        }

        /**
         * INDEX BARISTA: Menampilkan list barang keluar untuk Barista
         * Hanya menampilkan data dari kategori yang diizinkan.
         */
        public function indexBarista()
        {
            // 1. Ambil user dan hak akses kategorinya
            $user = Auth::user();
            $allowedCategoryIds = DB::table('category_user')
                ->where('user_id', $user->id)
                ->pluck('category_id');

            // 2. Query utama ditambahkan filter hak akses
            $data = DB::table('outgoing_goods')
                ->join('items', 'outgoing_goods.item_id', '=', 'items.id')
                ->join('customers', 'outgoing_goods.customer_id', '=', 'customers.id')
                ->whereIn('items.category_id', $allowedCategoryIds) // <-- FILTER DINAMIS DITERAPKAN DI SINI
                ->select(
                    'outgoing_goods.*',
                    'items.name as item_name',
                    'customers.nama as customer_name'
                )
                ->orderBy('outgoing_goods.date', 'desc')
                ->get();

            return view('userRole.barangKeluar.index', compact('data'));
        }
}