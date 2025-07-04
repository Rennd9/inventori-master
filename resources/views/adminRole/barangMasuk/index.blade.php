@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border  p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Barang Masuk</h3>
            <a class="btn neo-btn btn-sm " href="{{ route('barang-masuk.create') }}">
                + Tambah Barang Masuk
            </a>
        </div>
       
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Supplier</th>
                        <th>Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $d->item_name }}</td>
                            <td>{{ number_format($d->quantity) }}</td>
                            <td>{{ $d->supplier_name ?? 'Tidak ada supplier' }}</td>
                            <td>{{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 