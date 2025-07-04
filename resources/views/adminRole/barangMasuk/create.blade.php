@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border  p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Tambah Barang Masuk</h3>
        </div>

        {{-- Notifikasi Error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Notifikasi Success/Error --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('barang-masuk.store') }}" method="POST">
            @csrf

            <div class="row">
                {{-- Pilih Barang --}}
                <div class="col-md-6 mb-3">
                    <label for="item_id" class="form-label">Barang <span class="text-danger">*</span></label>
                    <select name="item_id" id="item_id" class="form-select" required onchange="loadItemDetails()">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" 
                                data-unit="{{ $item->unit }}"
                                data-current-stock="{{ $item->stock }}"
                                {{ old('item_id', $selectedItem->id ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal Masuk --}}
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" 
                           name="date" 
                           id="date"
                           class="form-control"
                           value="{{ old('date', now()->toDateString()) }}" 
                           max="{{ now()->toDateString() }}"
                           required>
                </div>

                {{-- Jumlah Stok --}}
                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">Jumlah Stok yang Akan Ditambahkan <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="quantity" 
                           id="quantity"
                           value="{{ old('quantity', $lastQuantity ?? '') }}"
                           class="form-control" 
                           min="1" 
                           required
                           placeholder="Masukkan jumlah stok">
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

                {{-- Satuan (readonly) --}}
                <div class="col-md-6 mb-3" id="unit-info" style="display: none;">
                    <label for="unit" class="form-label">Satuan</label>
                    <input type="text" id="unit" class="form-control" readonly>
                </div>
            </div>

            {{-- Info Stok Saat Ini --}}
            <div class="mb-3" id="current-stock-info" style="display: none;">
                <div class="alert alert-info">
                    <strong>Stok Saat Ini:</strong> 
                    <span id="current-stock-value">0</span> 
                    <span id="current-stock-unit"></span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function loadItemDetails() {
    const itemSelect = document.getElementById('item_id');
    const selectedOption = itemSelect.options[itemSelect.selectedIndex];
    
    const currentStockInfo = document.getElementById('current-stock-info');
    const currentStockValue = document.getElementById('current-stock-value');
    const currentStockUnit = document.getElementById('current-stock-unit');
    const unitInfo = document.getElementById('unit-info');
    const unitInput = document.getElementById('unit');
    
    if (selectedOption.value && selectedOption.dataset.unit) {
        currentStockValue.textContent = selectedOption.dataset.currentStock || '0';
        currentStockUnit.textContent = selectedOption.dataset.unit;
        currentStockInfo.style.display = 'block';
        
        unitInput.value = selectedOption.dataset.unit;
        unitInfo.style.display = 'block';
        
        loadLastSupplierAndQuantity(selectedOption.value);
    } else {
        currentStockInfo.style.display = 'none';
        unitInfo.style.display = 'none';
    }
}

function loadLastSupplierAndQuantity(itemId) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('item_id', itemId);
    const currentItemId = currentUrl.searchParams.get('item_id');
    if (currentItemId != itemId) {
        window.location.href = currentUrl.toString();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    if (itemSelect.value) {
        loadItemDetails();
    }
});
</script>
@endsection
