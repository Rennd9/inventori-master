@extends('template')

@section('content')
 <div class="col-lg-12">
                        <div class="neo-border  p-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="fw-bold mb-0">Barang Keluar</h3>
                                <a class="btn neo-btn btn-sm " href="{{ route('barang-keluar.create') }}">
                                  + Tambah Barang Keluar
</a>
                            </div>
                            
                          <table class="table table-bordered">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Penerima</th>
                <th>Tanggal Keluar</th>
                <th>Aksi</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ $d->item_name }}</td>
                    <td>{{ $d->quantity }}</td>
                    <td>{{ $d->customer_name  ?? 'Tidak ada customers' }}</td>
                    <td>{{ $d->date }}</td>
                    <td>
    <form action="{{ route('barang-keluar.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
            <i class="fas fa-trash-alt"></i> Hapus
        </button>
    </form>
</td>

                </tr>
            @endforeach
        </tbody>
    </table>
    </table>
                        </div>
                    </div>

@endsection
