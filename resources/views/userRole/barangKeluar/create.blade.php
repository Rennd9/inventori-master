@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Tambah Barang Keluar</h3>
            <a href="{{ route('users.barang-keluar.index') }}" class="btn btn-secondary">Kembali</a>
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
                        <label for="item_id" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <select name="item_id" id="item_id" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} (Stok: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="quantity" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
                        <small class="text-muted">Stok tersedia: <span id="stock-info">0</span></small>
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
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity');
    const stockInfo = document.getElementById('stock-info');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('outgoingForm');

    // Update stock info when item is selected
    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock') || 0;
        stockInfo.textContent = stock;
        quantityInput.max = stock;
        
        // Reset quantity if exceeds stock
        if (parseInt(quantityInput.value) > parseInt(stock)) {
            quantityInput.value = '';
        }
    });

    // Validate quantity against stock
    quantityInput.addEventListener('input', function() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const quantity = parseInt(this.value) || 0;

        if (quantity > stock) {
            this.setCustomValidity('Jumlah melebihi stok tersedia (' + stock + ')');
        } else {
            this.setCustomValidity('');
        }
    });

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
</script>
@endsection