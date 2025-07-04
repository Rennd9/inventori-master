
@extends('template')

@section('content')
<div class="col-lg-12">


        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Atur Hak Akses Kategori untuk: <a class="text-danger">{{ $user->name }}</a>
            <br/>
            Pilih kategori yang dapat diakses oleh pengguna ini.
        </div>

    <div class="neo-border p-3 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Daftar Hak Akses Kategori Pengguna</h3>
        </div>

        <form action="{{ route('admin.users.updatePermissions', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label><strong>Kategori yang Tersedia:</strong></label>
            <div class="mt-2">
                @foreach($allCategories as $category)
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="categories[]"
                               value="{{ $category->id }}"
                               id="category{{ $category->id }}"
                               {{ in_array($category->id, $assignedCategoryIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="category{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
        <a href="{{ route('admin.users.categories.index') }}" class="btn btn-secondary mt-3">Batal</a>
    </form>
    </div>
</div>


@endsection
