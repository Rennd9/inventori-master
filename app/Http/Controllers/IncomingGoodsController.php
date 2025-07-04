<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.date', 'desc')
            ->get();

        return view('adminRole.barangMasuk.index', compact('data'));
    }

    public function getCreateData(Request $request)
    {
        $items = DB::table('items')->get();
        $suppliers = DB::table('suppliers')->get();

        $selectedItem = null;
        $defaultSupplierId = null;
        $lastQuantity = null;

        if ($request->has('item_id')) {
            $selectedItem = DB::table('items')
                ->where('id', $request->item_id)
                ->first();

            $lastIncoming = DB::table('incoming_goods')
                ->where('item_id', $request->item_id)
                ->orderByDesc('created_at')
                ->first();

            if ($lastIncoming) {
                $lastQuantity = $lastIncoming->quantity;
                $defaultSupplierId = $lastIncoming->supplier_id;
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
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    // EDIT: Form edit barang masuk (Admin)
    public function edit($id)
    {
        $incomingGood = DB::table('incoming_goods')->where('id', $id)->first();
        
        if (!$incomingGood) {
            return redirect()->route('barang-masuk.index')->with('error', 'Data tidak ditemukan.');
        }

        $items = DB::table('items')->get();
        $suppliers = DB::table('suppliers')->get();

        return view('adminRole.barangMasuk.edit', compact('incomingGood', 'items', 'suppliers'));
    }

    // UPDATE: Update data barang masuk (Admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date'
        ]);

        $oldData = DB::table('incoming_goods')->where('id', $id)->first();
        
        if (!$oldData) {
            return redirect()->route('barang-masuk.index')->with('error', 'Data tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Update data
            DB::table('incoming_goods')->where('id', $id)->update([
                'item_id' => $request->item_id,
                'supplier_id' => $request->supplier_id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'updated_at' => now(),
            ]);

            // Adjust stock: kurangi stok lama, tambah stok baru
            if ($oldData->item_id == $request->item_id) {
                // Item sama, update selisih quantity
                $difference = $request->quantity - $oldData->quantity;
                DB::table('items')->where('id', $request->item_id)->increment('stock', $difference);
            } else {
                // Item berbeda, kurangi stok item lama dan tambah stok item baru
                DB::table('items')->where('id', $oldData->item_id)->decrement('stock', $oldData->quantity);
                DB::table('items')->where('id', $request->item_id)->increment('stock', $request->quantity);
            }

            DB::commit();
            return redirect()->route('barang-masuk.index')->with('success', 'Data barang masuk berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
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

    // INDEX CHEFF: Menampilkan list barang masuk untuk cheff
    public function indexCheff()
    {
        $data = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            ->select(
                'incoming_goods.*',
                'items.name as item_name',
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.date', 'desc')
            ->orderBy('incoming_goods.id', 'desc') // Tambahan ordering by ID untuk konsistensi
            ->get();

        return view('cheffRole.barangMasuk.index', compact('data'));
    }

    // CREATE CHEFF: Form tambah barang masuk untuk cheff
    public function createCheff(Request $request)
    {
        $data = $this->getCreateData($request);
        return view('cheffRole.barangMasuk.create', $data);
    }

    // STORE CHEFF: Simpan barang masuk untuk cheff
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

    // ===============================================================
    // BARISTA ROLE FUNCTIONS
    // ===============================================================

    // INDEX BARISTA: Menampilkan list barang masuk untuk barista (read-only)
    public function indexBarista()
    {
        $data = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            ->select(
                'incoming_goods.*',
                'items.name as item_name',
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.date', 'desc')
            ->limit(50) // Batasi untuk performa
            ->get();

        return view('userRole.barangMasuk.index', compact('data'));
    }

}