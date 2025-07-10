@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">➕ Tambah Barang Baru</h3>
        </div>
        
        {{-- Display validation errors if any --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
       <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori:</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="has_expiration" name="has_expiration" value="1">
                <label class="form-check-label" for="has_expiration">
                    Kategori ini memiliki tanggal kedaluwarsa
                </label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn neo-btn">💾 Simpan</button>
                <a href="{{ route('items.index') }}" class="btn neo-btn">⬅️ Kembali</a>
            </div>
        </form>
    </div>
</div>

@endsection
