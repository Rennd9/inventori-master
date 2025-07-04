@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Penambahan Suppliers</h3>
            <a class="btn neo-btn btn-sm" href="{{ route('barang-keluar.create') }}">
                + Tambah Barang Keluar
            </a>
        </div>
        
        <!-- Form Filter -->
       <form action="{{ url('/admin/suppliers') }}" method="POST">
        @csrf
         <div class="mb-3">
            <label>Nama Toko</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nama Supplier</label>
            <input type="text" name="namasup" class="form-control" required>
        </div>
         <div class="mb-3">
            <label>Nomor dihubungi</label>
            <input type="text" name="no_hp" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ url('/admin/suppliers') }}" class="btn btn-secondary">Kembali</a>
    </form>
    </div>
</div>
@endsection