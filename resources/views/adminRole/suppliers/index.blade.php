@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border  p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Data Suppliers</h3>
            <a class="btn neo-btn btn-sm " href="{{ route('suppliers.create') }}">
                + Tambah Suppliers
            </a>
        </div>
        
        <!-- Form Filter -->
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Toko</th>
                <th>Nama Supplier</th>
                <th>Alamat Toko</th>
                <th>Nomor dihubungi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $key => $supplier)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $supplier->nama }}</td>
                <td>{{ $supplier->namasup }}</td>
                <td>{{ $supplier->alamat }}</td>
                <td>{{ $supplier->no_hp }}</td>
                 <td><form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Yakin Anda Hapus Data Supplier ini?')">🗑️ Hapus</button>
                        </form></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection