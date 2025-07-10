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

        {{-- Step 1: Pilih Barang Terlebih Dahulu --}}
        <div id="step-1" class="step-container">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="item_id" class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                    
                    {{-- Cek apakah variabel $items memiliki isi --}}
                    @if(isset($items) && $items->isNotEmpty())
                        <select name="item_id" id="item_id" class="form-select js-example-disabled-results">
                            <option value="">-- Pilih Barang --</option>
                            
                            {{-- Kelompokkan barang berdasarkan nama kategorinya --}}
                            @foreach ($items->groupBy('category_name') as $categoryName => $itemsInCategory)
                                    @foreach ($itemsInCategory as $item)
                                        <option value="{{ $item->id }}"
                                            data-category="{{ $categoryName ?: 'Tanpa Kategori' }}"
                                            {{ old('item_id', $selectedItem->id ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
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
            </div>
        </div>

        {{-- Step 2: Form Detail (Hidden Initially) --}}
        <div id="step-2" class="step-container" style="display: none;">
            <form action="{{ route('cheff.barang-masuk.store') }}" method="POST" id="form-barang-masuk">
                @csrf
                <input type="hidden" name="item_id" id="selected_item_id" value="">
                
                {{-- Info Barang yang Dipilih --}}
                <div class="alert alert-info mb-3" id="selected-item-info">
                    <strong>Barang yang dipilih:</strong> <span id="selected-item-name"></span>
                    <button type="button" class="btn btn-sm neo-btn float-end" onclick="resetForm()">
                        <i class="fas fa-edit"></i> Ubah Barang
                    </button>
                </div>

                <div class="row">
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
                            <button type="button" class="btn neo-btn" onclick="adjustQuantity(-1)">–</button>
                            <input type="number" name="quantity" id="quantity"
                                   value="{{ old('quantity', $lastQuantity ?? 1) }}"
                                   class="form-control text-center" min="1" required placeholder="Jumlah">
                            <button type="button" class="btn neo-btn" onclick="adjustQuantity(1)">+</button>
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

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Fungsi untuk adjust quantity
function adjustQuantity(amount) {
    const input = document.getElementById('quantity');
    let current = parseInt(input.value) || 0;
    current += amount;
    if (current < 1) current = 1;
    input.value = current;
}

// Fungsi ketika barang dipilih
function onItemSelected() {
    const itemSelect = document.getElementById('item_id');
    const selectedItemId = itemSelect.value;
    const selectedItemName = itemSelect.options[itemSelect.selectedIndex].text;
    
    if (selectedItemId) {
        // Set nilai barang yang dipilih
        document.getElementById('selected_item_id').value = selectedItemId;
        document.getElementById('selected-item-name').textContent = selectedItemName;
        
        // Tampilkan step 2 dan sembunyikan step 1
        document.getElementById('step-1').style.display = 'none';
        document.getElementById('step-2').style.display = 'block';
        
        // Load data supplier dan quantity jika ada
        loadLastSupplierAndQuantity(selectedItemId);
    }
}

// Fungsi untuk reset form kembali ke step 1
function resetForm() {
    // Reset nilai
    document.getElementById('item_id').value = '';
    document.getElementById('selected_item_id').value = '';
    document.getElementById('selected-item-name').textContent = '';
    
    // Tampilkan step 1 dan sembunyikan step 2
    document.getElementById('step-1').style.display = 'block';
    document.getElementById('step-2').style.display = 'none';
    
    // Reset form values
    document.getElementById('form-barang-masuk').reset();
    
    // Set tanggal ke hari ini
    document.getElementById('date').value = new Date().toISOString().split('T')[0];
    document.getElementById('quantity').value = 1;
}

// Fungsi untuk load supplier dan quantity terakhir
function loadLastSupplierAndQuantity(itemId) {
    if (itemId) {}
}

// Event listener untuk dropdown barang
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    
    // Jika sudah ada item yang dipilih (dari old input), langsung tampilkan step 2
    if (itemSelect.value) {
        onItemSelected();
    }
    
    // Event listener untuk perubahan pilihan barang
    itemSelect.addEventListener('change', function() {
        if (this.value) {
            onItemSelected();
        }
    });
    
    // Inisialisasi select2 jika tersedia
    if (typeof $.fn.select2 !== 'undefined') {
        $('#item_id').select2({
            placeholder: "-- Pilih Barang --",
            allowClear: true,
            width: '100%'
        });
        
        $('#item_id').on('change', function() {
            if (this.value) {
                onItemSelected();
            }
        });
    }
});
</script>
@endsection