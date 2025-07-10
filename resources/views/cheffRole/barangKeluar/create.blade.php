@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border  p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Tambah Barang Keluar</h3>
            
        </div>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cheff.barang-keluar.store') }}" method="POST" id="outgoingForm">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
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
                                            {{ $item->name }} Stock: ({{$item->stock}})
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

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="adjustQuantity(-1)">–</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center" min="1" value="{{ old('quantity', 1) }}" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="adjustQuantity(1)">+</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="customer_id" class="form-label">Penerima <span class="text-danger">*</span></label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">-- Pilih Penerima --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="date" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('outgoingForm');

    // Prevent double submission
    form.addEventListener('submit', function(e) {
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        
        // Re-enable button after 3 seconds (in case of validation errors)
        setTimeout(function() {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            }
        }, 3000);
    });

    // Initialize stock info on page load
    if (itemSelect.value) {
        itemSelect.dispatchEvent(new Event('change'));
    }
});

function adjustQuantity(change) {
    const input = document.getElementById('quantity');
    let current = parseInt(input.value) || 1;
    current += change;
    if (current < 1) current = 1;
    input.value = current;
}
</script>
@endsection