@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-3 h-100">
        <h3 class="fw-bold">Edit Pengguna</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required value="{{ $user->name }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>

            <div class="mb-3">
                <label>Peran</label>
                <select name="type" class="form-control" required>
                <option value="0" @if($user->type === 'barista') selected @endif>Barista</option>
                <option value="1" @if($user->type === 'admin') selected @endif>Admin</option>
                <option value="2" @if($user->type === 'cheff') selected @endif>Cheff</option>
            </select>

            </div>

            <div class="mb-3">
                <label>Foto Baru (opsional)</label>
                <input type="file" name="image" class="form-control">
                @if($user->image)
                    <img src="{{ asset('storage/users/' . $user->image) }}" alt="User Image" width="60" class="mt-2 rounded">
                @endif
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
