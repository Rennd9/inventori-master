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
        
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Barang:</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori:</label>
                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        {{-- PERBAIKAN 1: Tambahkan atribut data-has-expiration --}}
                        <option value="{{ $cat->id }}" 
                                data-has-expiration="{{ $cat->has_expiration ? '1' : '0' }}" 
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- PERBAIKAN 2: Wrapper untuk field tanggal kedaluwarsa --}}
            <div class="mb-3" id="expired_date_wrapper" style="display: none;">
                <label for="expired_date" class="form-label">Tanggal Kedaluwarsa:</label>
                {{-- PERBAIKAN 3: Hilangkan id ganda dan sesuaikan value --}}
                <input type="date" id="expired_date" name="expired_date" class="form-control @error('expired_date') is-invalid @enderror" 
                       value="{{ old('expired_date') }}">
                @error('expired_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="unit" class="form-label">Satuan:</label>
<select id="unit" name="unit" class="form-control @error('unit') is-invalid @enderror" required>
    <option value="">-- Pilih Satuan --</option>
    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
    <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
    <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
    <option value="buah" {{ old('unit') == 'buah' ? 'selected' : '' }}>Buah</option>
    <option value="pak" {{ old('unit') == 'pak' ? 'selected' : '' }}>Pak</option>
    <option value="lusin" {{ old('unit') == 'lusin' ? 'selected' : '' }}>Lusin</option>
    <option value="botol" {{ old('unit') == 'botol' ? 'selected' : '' }}>Botol</option>
    <option value="kaleng" {{ old('unit') == 'kaleng' ? 'selected' : '' }}>Kaleng</option>
    <option value="dus" {{ old('unit') == 'dus' ? 'selected' : '' }}>Dus</option>
    <option value="sak" {{ old('unit') == 'sak' ? 'selected' : '' }}>Sak</option>
    <option value="karung" {{ old('unit') == 'karung' ? 'selected' : '' }}>Karung</option>
    <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meter</option>
    <option value="centimeter" {{ old('unit') == 'centimeter' ? 'selected' : '' }}>Centimeter</option>
    <option value="roll" {{ old('unit') == 'roll' ? 'selected' : '' }}>Roll</option>
    <option value="renceng" {{ old('unit') == 'renceng' ? 'selected' : '' }}>Renceng</option>
    <option value="unit" {{ old('unit') == 'unit' ? 'selected' : '' }}>Unit</option>
</select>
@error('unit')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

            </div>

            
            <div class="d-flex gap-2">
                <button type="submit" class="btn neo-btn">💾 Simpan</button>
                <a href="{{ route('items.index') }}" class="btn neo-btn">⬅️ Kembali</a>
            </div>
        </form>
    </div>
</div>

{{-- PERBAIKAN 4: Update script untuk berjalan saat halaman dimuat --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const expiredDateWrapper = document.getElementById('expired_date_wrapper');
    const expiredDateInput = document.getElementById('expired_date');

    function toggleExpiredField() {
        // Temukan option yang sedang dipilih
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        
        // Pastikan ada option yang terpilih sebelum melanjutkan
        if (!selectedOption || !selectedOption.value) {
            expiredDateWrapper.style.display = 'none';
            expiredDateInput.required = false;
            return;
        }

        // Cek nilai atribut data-has-expiration
        const hasExpiration = selectedOption.getAttribute('data-has-expiration') === '1';

        if (hasExpiration) {
            // Jika ya, tampilkan div dan buat inputnya required
            expiredDateWrapper.style.display = 'block';
            expiredDateInput.required = true;
        } else {
            // Jika tidak, sembunyikan div, hapus 'required', dan kosongkan nilainya
            expiredDateWrapper.style.display = 'none';
            expiredDateInput.required = false;
            expiredDateInput.value = ''; // Kosongkan nilai untuk menghindari pengiriman data
        }
    }

    // Jalankan fungsi saat ada perubahan pada pilihan kategori
    categorySelect.addEventListener('change', toggleExpiredField);

    // Jalankan fungsi sekali saat halaman selesai dimuat untuk menangani `old()`
    toggleExpiredField();
});
</script>
@endsection