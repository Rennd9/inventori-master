@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border  p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Data Karyawan</h3>
            <a class="btn neo-btn btn-sm " href="{{ route('customers.create') }}">
                + Tambah Karyawan
            </a>
        </div>
        
 <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kontak</th>
                <th>Alamat</th>
                 <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $key => $customer)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $customer->nama }}</td>
                <td>{{ $customer->kontak }}</td>
                 <td>{{ $customer->alamat }}</td>
                 <td><form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Apakah Yakin Anda Hapus Karyawan ini?')">🗑️ Hapus</button>
                        </form></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection