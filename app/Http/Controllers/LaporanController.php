<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanController extends Controller
{
    public function index()
    {
        // default: tampilkan semua dengan join ke suppliers dan customers
        $masuk = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            ->select(
                'incoming_goods.*', 
                'items.name as item_name',
                'suppliers.nama as supplier_name'
            )
            ->orderBy('incoming_goods.created_at', 'desc')
            ->get();

        $keluar = DB::table('outgoing_goods')
            ->join('items', 'outgoing_goods.item_id', '=', 'items.id')
            ->join('customers', 'outgoing_goods.customer_id', '=', 'customers.id')
            ->select(
                'outgoing_goods.*', 
                'items.name as item_name',
                'customers.nama as customer_name'
            )
            ->orderBy('outgoing_goods.created_at', 'desc')
            ->get();

        return view('Laporan.index', compact('masuk', 'keluar'));
    }

    public function filter(Request $request)
    {
        $from = $request->from_date;
        $to = $request->to_date;

        $masuk = DB::table('incoming_goods')
            ->join('items', 'incoming_goods.item_id', '=', 'items.id')
            ->join('suppliers', 'incoming_goods.supplier_id', '=', 'suppliers.id')
            ->select(
                'incoming_goods.*', 
                'items.name as item_name',
                'suppliers.nama as supplier_name'
            )
            ->whereDate('incoming_goods.created_at', '>=', $from)
            ->whereDate('incoming_goods.created_at', '<=', $to)
            ->orderBy('incoming_goods.created_at', 'desc')
            ->get();

        $keluar = DB::table('outgoing_goods')
            ->join('items', 'outgoing_goods.item_id', '=', 'items.id')
            ->join('customers', 'outgoing_goods.customer_id', '=', 'customers.id')
            ->select(
                'outgoing_goods.*', 
                'items.name as item_name',
                'customers.nama as customer_name'
            )
            ->whereDate('outgoing_goods.created_at', '>=', $from)
            ->whereDate('outgoing_goods.created_at', '<=', $to)
            ->orderBy('outgoing_goods.created_at', 'desc')
            ->get();

        // Generate laporan stok barang
        $laporan = $this->getLaporanData($from, $to);

        return view('Laporan.index', compact('masuk', 'keluar', 'from', 'to', 'laporan'));
    }

    public function print(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $laporan = $this->getLaporanData($from, $to);
        
        return view('laporan.print', compact('laporan', 'from', 'to'));
    }

    public function pdf(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $laporan = $this->getLaporanData($from, $to);
        
        $pdf = PDF::loadView('laporan.pdf', compact('laporan', 'from', 'to'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('laporan-stok-barang-' . $from . '-' . $to . '.pdf');
    }

    private function getLaporanData($from, $to)
    {
        $items = DB::table('items')->select('id', 'name', 'stock', 'unit')->orderBy('name')->get();
        $laporan = [];

        foreach ($items as $item) {
            // Hitung barang masuk dan keluar dalam periode
            $masuk = DB::table('incoming_goods')
                ->where('item_id', $item->id)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('quantity');
            
            $keluar = DB::table('outgoing_goods')
                ->where('item_id', $item->id)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('quantity');

            // Hitung stok akhir langsung dari pergerakan barang dalam periode
            $stok_akhir = $masuk - $keluar;

            $laporan[] = [
                'kode' => 'ITM-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'nama' => $item->name,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'stok_akhir' => $stok_akhir,
                'unit' => $item->unit,
                'stok_sistem' => $item->stock,
            ];
        }

        return $laporan;
    }

}