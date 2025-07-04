<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $items = DB::table('items')->where('stock', '>', 0)->get();
        $customers = DB::table('customers')->get();

        $selectedItem = null;
        $defaultCustomerId = null;
        $lastQuantity = null;
        $availableStock = null;

        if ($request->has('item_id')) {
            $selectedItem = DB::table('items')
                ->where('id', $request->item_id)
                ->first();
            
            $availableStock = $selectedItem ? $selectedItem->stock : 0;

            $lastOutgoing = DB::table('outgoing_goods')
                ->where('item_id', $request->item_id)
                ->orderByDesc('created_at')
                ->first();

            if ($lastOutgoing) {
                $lastQuantity = $lastOutgoing->quantity;
                $defaultCustomerId = $lastOutgoing->customer_id;
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

    // CREATE BARISTA: Form tambah barang keluar untuk barista (untuk operasional harian)
    public function createBarista(Request $request)
    {
        $data = $this->getCreateData($request);
        // Filter hanya item yang sering digunakan atau kategori tertentu
        $data['items'] = DB::table('items')
            ->where('stock', '>', 0)
            ->whereIn('category_id', [1, 2, 3]) // Sesuaikan dengan kategori yang relevan untuk barista
            ->get();
            
        return view('userRole.barangKeluar.create', $data);
    }

    // STORE BARISTA: Simpan barang keluar untuk barista
    public function storeBarista(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1|max:10', // Batasi quantity untuk barista
            'date' => 'required|date'
        ]);

        $item = DB::table('items')->where('id', $request->item_id)->first();

        // Validasi tambahan untuk barista
        if ($request->quantity > 10) {
            return redirect()->back()->with('error', 'Quantity tidak boleh lebih dari 10 untuk transaksi barista.');
        }

        if ($item && $item->stock >= $request->quantity) {
            DB::table('outgoing_goods')->insert([
                'item_id' => $request->item_id,
                'customer_id' => $request->customer_id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'note' => 'Processed by Barista: ' . auth()->user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kurangi stok dari item terkait
            DB::table('items')->where('id', $request->item_id)->decrement('stock', $request->quantity);

            return redirect()->route('user.barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . ($item->stock ?? 0));
        }
    }
   
    // INDEX BARISTA: Menampilkan list barang keluar untuk barista
    public function indexBarista()
    {
        $data = DB::table('outgoing_goods')
            ->join('items', 'outgoing_goods.item_id', '=', 'items.id')
            ->join('customers', 'outgoing_goods.customer_id', '=', 'customers.id')
            ->select(
                'outgoing_goods.*',
                'items.name as item_name',
                'customers.nama as customer_name'
            )
            ->whereDate('outgoing_goods.date', '>=', now()->subDays(7)) // Hanya 7 hari terakhir
            ->orderBy('outgoing_goods.date', 'desc')
            ->limit(30)
            ->get();

        return view('userRole.barangKeluar.index', compact('data'));
    }
}