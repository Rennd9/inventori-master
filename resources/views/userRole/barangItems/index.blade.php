@extends('template')

@section('content')
<div class="col-lg-12">
    <h3 class="fw-bold mb-3">Data Barang</h3>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Tabel Barang --}}
    <div class="neo-border  p-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="fw-bold {{ $item->stock <= $item->minimum_stock ? 'text-danger' : 'text-success' }}">
                                {{ $item->stock }}
                            </span>
                        </td>
                        <td>{{ $item->unit }}</td>
                        <td>
                            @if($item->stock == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif($item->stock <= $item->minimum_stock)
                                <span class="badge bg-warning text-dark">Stok Menipis</span>
                            @else
                                <span class="badge bg-success">Stok Aman</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($restockRequests) && $restockRequests->where('item_id', $item->id)->where('status', 'pending')->count() > 0)
                                <span class="badge bg-info">Request Pending</span>
                            @else
                             <button type="button" class="btn btn-neo btn-neo-primary btn-sm" data-bs-toggle="modal" data-bs-target="#basicModal">
                    Request Pesan 
                </button>
                            <div class="modal fade modal-neo" id="basicModal" tabindex="-1" aria-labelledby="basicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="basicModalLabel">Form Penambahan Alasan Pesan</h1>
                </div>
                <div class="modal-body">
                    <form action="{{ route('items.restock.request', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="message" rows="5" class="form-control form-control-sm"
                                                placeholder="Alasan request Pesan CTH: Tambah Qty Dan Lainnya ..." required></textarea>
                                    </div>
                                   
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-neo btn-neo-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-neo btn-neo-primary">Konfirmasi</button>
                </div>
                                </form>
            </div>
        </div>
    </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Tidak ada data barang</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- Status Permintaan Restok Chef --}}
    @if(isset($restockRequests) && $restockRequests->count())
    <div class="mt-5">
        <div class="neo-border  p-3">
            <h4 class="fw-bold mb-3">Status Permintaan Restok Anda</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Pesan</th>
                            <th>Status</th>
                            <th>Waktu Request</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restockRequests as $req)
                        <tr>
                            <td>{{ $req->item_name }}</td>
                            <td>{{ $req->message }}</td>
                            <td>
                                @if($req->status == 'pending')
                                    <span class="badge bg-warning text-dark">⏳ Menunggu</span>
                                @elseif($req->status == 'approved')
                                    <span class="badge bg-success">✅ Disetujui</span>
                                @else
                                    <span class="badge bg-danger">❌ Ditolak</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($req->created_at)->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection