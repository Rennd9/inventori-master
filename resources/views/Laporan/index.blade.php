@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border  p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Laporan Stok Barang</h3>
        </div>
        
        <!-- Form Filter -->
        <form method="POST" action="{{ route('laporan.filter') }}" class="row g-3 mb-4">    
            @csrf
            <div class="col-auto">
                <label>Dari</label>
                <input type="date" name="from_date" class="form-control" value="{{ $from ?? '' }}" required>
            </div>
            <div class="col-auto">
                <label>Sampai</label>
                <input type="date" name="to_date" class="form-control" value="{{ $to ?? '' }}" required>
            </div>
            <div class="col-auto align-self-end">
                <button type="submit" class="btn neo-btn ">Filter</button>
            </div>
        </form>

        @if(isset($laporan))
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Laporan Stok Barang ({{ $from }} - {{ $to }})</h5>
                <div>
                    <a href="{{ route('laporan.print', ['from' => $from, 'to' => $to]) }}" 
                       class="btn neo-btn  btn-sm" target="_blank">
                        <i class="fas fa-print"></i> Print
                    </a>
                    <a href="{{ route('laporan.pdf', ['from' => $from, 'to' => $to]) }}" 
                       class="btn neo-btn  btn-sm">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-dark text-center">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Nama Barang</th>
                            <th colspan="2">Transaksi Barang</th>
                            <th rowspan="2">Stok Sekarang</th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Masuk</th>
                            <th>Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporan as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $item['kode'] }}</td>
                            <td>{{ $item['nama'] }}</td>
                            <td class="text-center">{{ $item['masuk'] }}</td>
                            <td class="text-center">{{ $item['keluar'] }}</td>
                            <td class="text-center">{{ $item['stok_akhir'] }}</td>
                            <td class="text-center">-</td>
                        </tr>
                        @endforeach
                        <tr class="fw-bold">
                            <td colspan="3" class="text-center">Jumlah Total</td>
                            <td class="text-center">{{ collect($laporan)->sum('masuk') }}</td>
                            <td class="text-center">{{ collect($laporan)->sum('keluar') }}</td>
                            <td class="text-center">{{ collect($laporan)->sum('stok_akhir') }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Detail Barang Masuk -->
        <h5>Detail Barang Masuk</h5>
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Supplier</th>
                        <th>Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($masuk as $index => $m)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $m->item_name }}</td>
                            <td class="text-center">{{ $m->quantity }}</td>
                            <td>{{ $m->supplier_name }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($m->created_at)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Detail Barang Keluar -->
        <h5>Detail Barang Keluar</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Customer</th>
                        <th>Tanggal Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($keluar as $index => $k)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $k->item_name }}</td>
                            <td class="text-center">{{ $k->quantity }}</td>
                            <td>{{ $k->customer_name }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($k->created_at)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang keluar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
