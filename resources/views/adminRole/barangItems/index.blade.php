@extends('template')

@section('content')

<div class="col-lg-12">

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- Tabel Barang --}}
    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Data Barang</h3>
            <a class="btn neo-btn btn-sm" href="{{ route('items.create') }}">+ Tambah Barang</a>
        </div>

         {{-- Alert Request Restok Pending --}}
    @if(isset($pendingRequests) && $pendingRequests->isNotEmpty())
        <div class="neo-border alert alert-info">
            <strong>🔔 Permintaan Restok!</strong> Ada {{ $pendingRequests->count() }} permintaan baru:
            <div class="mt-2">
                @foreach($pendingRequests as $request)
                    <div class="d-flex justify-content-between align-items-center p-2 mb-1 rounded">
                        <div>
                            <strong>{{ $request->item_name }}</strong> (oleh: {{ $request->user_name }})
                            <em class="d-block text-muted">"{{ $request->message }}" — {{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</em>
                        </div>
                        <div class="btn-group">
                            <form action="{{ route('restock.update', [$request->id, 'approved']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">✅ Setuju</button>
                            </form>
                            <form action="{{ route('restock.update', [$request->id, 'rejected']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">❌ Tolak</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

        <div class="table-responsive">
            <table class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Status Barang Kaduluarsa Dan Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category_name ?? 'Tidak Ada' }}</td>
                            <td>
                                <span class="fw-bold">{{ $item->stock }}</span>
                                @if ($item->stock <= $item->minimum_stock)
                                    <a href="{{ route('barang-masuk.create', ['item_id' => $item->id]) }}" class="btn btn-sm btn-outline-success ms-2 py-0 px-1">
                                        + Stok
                                    </a>
                                @endif
                            </td>
                            <td>{{ $item->unit }}</td>
                            <td>
                                {{-- ================================================================ --}}
                                {{-- AWAL DARI LOGIKA STATUS GANDA --}}
                                {{-- ================================================================ --}}

                                {{-- BAGIAN 1: Tampilkan status kedaluwarsa HANYA jika relevan --}}
                                @if($item->status_kadaluarsa == 'KADALUARSA')
                                    <span class="badge bg-danger me-1">Kadaluarsa</span>

                                @elseif($item->status_kadaluarsa == 'SEGERA KADALUARSA')
                                    <span class="badge bg-warning text-dark me-1">Segera Kadaluarsa</span>

                                {{-- BARIS INI DITAMBAHKAN: Cek jika statusnya NORMAL & punya tgl. kedaluwarsa --}}
                                @elseif($item->status_kadaluarsa == 'NORMAL' && !empty($item->expired_date))
                                    <span class="badge bg-success me-1">Aman</span>

                                @endif

                                {{-- BAGIAN 2: Tampilkan status stok (selalu tampil) --}}
                                @if($item->stock == 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($item->stock <= $item->minimum_stock)
                                    <span class="badge bg-warning text-dark">Stok Menipis</span>
                                @else
                                    <span class="badge bg-success">Stok Aman</span>
                                @endif
                                
                                {{-- ================================================================ --}}
                                {{-- AKHIR DARI LOGIKA STATUS GANDA --}}
                                {{-- ================================================================ --}}
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                   
                                   
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus {{ $item->name }}?')">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection