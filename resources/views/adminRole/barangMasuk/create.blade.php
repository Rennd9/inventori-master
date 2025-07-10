@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">📝 Tambah Barang Masuk</h3>
        </div>

        {{-- Notifikasi Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Notifikasi Sukses/Gagal dari Session --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('barang-masuk.store') }}" method="POST">
            @csrf
            <div class="row">

                {{-- ================================================================ --}}
                {{-- AWAL PERBAIKAN PADA DROPDOWN BARANG --}}
                {{-- ================================================================ --}}
                <div class="col-md-6 mb-3">
                    <label for="item_id" class="form-label">Barang <span class="text-danger">*</span></label>
                    
                    {{-- Cek apakah variabel $items memiliki isi --}}
                    @if(isset($items) && $items->isNotEmpty())
                        <select name="item_id" id="item_id" class="form-select" required onchange="loadLastSupplierAndQuantity(this.value)">
                            <option value="">-- Pilih Barang --</option>
                            
                            {{-- Kelompokkan barang berdasarkan nama kategorinya --}}
                            @foreach ($items->groupBy('category_name') as $categoryName => $itemsInCategory)
                                <optgroup label="{{ $categoryName ?: 'Tanpa Kategori' }}">
                                    @foreach ($itemsInCategory as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('item_id', $selectedItem->id ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    @else
                        {{-- Jika $items kosong, tampilkan pesan ini --}}
                        <div class="form-control is-invalid" style="background-color: #f8d7da; border-color: #f5c2c7;">
                            Tidak ada barang yang bisa Anda akses.
                        </div>
                        <div class="invalid-feedback">
                            Silakan hubungi Administrator untuk mendapatkan hak akses barang.
                        </div>
                    @endif
                </div>
                {{-- ================================================================ --}}
                {{-- AKHIR PERBAIKAN --}}
                {{-- ================================================================ --}}

                {{-- Tanggal Masuk --}}
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="date" class="form-control"
                           value="{{ old('date', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                </div>

                {{-- Jumlah Stok --}}
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary" onclick="adjustQuantity(-1)">–</button>
                        <input type="number" name="quantity" id="quantity"
                               value="{{ old('quantity', $lastQuantity ?? 1) }}"
                               class="form-control text-center" min="1" required placeholder="Jumlah">
                        <button type="button" class="btn btn-outline-secondary" onclick="adjustQuantity(1)">+</button>
                    </div>
                </div>

                {{-- Supplier --}}
                <div class="col-md-6 mb-3">
                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ old('supplier_id', $defaultSupplierId ?? '') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Script Anda tidak perlu diubah
function adjustQuantity(amount) {
    const input = document.getElementById('quantity');
    let current = parseInt(input.value) || 0;
    current += amount;
    if (current < 1) current = 1;
    input.value = current;
}

function loadLastSupplierAndQuantity(itemId) {
    if (itemId) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('item_id', itemId);
        window.location.href = currentUrl.toString();
    }
}
</script>
@endsection