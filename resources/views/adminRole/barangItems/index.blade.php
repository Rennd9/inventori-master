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
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-info">
                                        👁️ Lihat
                                    </a>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#basicModal{{ $item->id }}" class="btn btn-sm btn-outline-primary">
                                        ✏️ Edit
                                    </button>
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
                            <td colspan="7" class="text-center">Tidak ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@foreach($items as $item)
    {{-- Modal EDIT --}}
    <div class="modal fade modal-neo" id="basicModal{{ $item->id }}" tabindex="-1" aria-labelledby="basicModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-header">
                        <h1 class="modal-title" id="basicModalLabel{{ $item->id }}">Edit Barang: {{ $item->name }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name{{ $item->id }}" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="name{{ $item->id }}" name="name" value="{{ old('name', $item->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id{{ $item->id }}" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id{{ $item->id }}" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input "Satuan" diubah menjadi <select> --}}
                        <div class="mb-3">
                            <label for="unit{{ $item->id }}" class="form-label">Satuan</label>
                            <select class="form-select" id="unit{{ $item->id }}" name="unit" required>
                                <option value="">-- Pilih Satuan --</option>
                                @php
                                    // Daftar satuan untuk memudahkan looping
                                    $units = ['kg', 'gram', 'liter', 'ml', 'buah', 'pak', 'lusin', 'botol', 'kaleng', 'dus', 'sak', 'karung', 'meter', 'centimeter', 'roll', 'renceng', 'unit'];
                                @endphp
                                @foreach($units as $unit)
                                    <option value="{{ $unit }}" {{ $item->unit == $unit ? 'selected' : '' }}>
                                        {{ ucfirst($unit) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Field "Minimum Stok" sudah dihapus sesuai permintaan --}}

                        {{-- Anda bisa menambahkan kembali input untuk expired_date di sini jika diperlukan --}}
                        <div class="mb-3">
                           <label for="expired_date{{ $item->id }}" class="form-label">Tanggal Kedaluwarsa</label>
                           <input type="date" class="form-control" id="expired_date{{ $item->id }}" name="expired_date" value="{{ old('expired_date', $item->expired_date) }}">
                           <small class="form-text text-danger">Kosongkan jika tidak ada tanggal kedaluwarsa.</small>
                        </div>

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection