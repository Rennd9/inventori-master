@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-3 h-100">
        <h3 class="fw-bold">Tambah Pengguna</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Peran</label>
                <select name="type" class="form-control" required>
                    <option value="">Pilih Peran</option>
                    <option value="0">Barista</option>
                    <option value="1">Cheff</option>
                    <option value="2">Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Foto (opsional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
