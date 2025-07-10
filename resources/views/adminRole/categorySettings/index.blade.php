@extends('template')

@section('content')
<div class="col-lg-12">

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    {{-- Tabel Barang --}}
    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Daftar Hak Akses Kategori Pengguna</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered w-100">
        <thead>
            <tr>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Kategori Akses</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if(isset($userCategories[$user->id]))
                            @foreach($userCategories[$user->id] as $category)
                                <span class="badge bg-primary">{{ $category->category_name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Tidak ada kategori</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.categories.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit Akses</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
        </div>
    </div>
</div>
@endsection
