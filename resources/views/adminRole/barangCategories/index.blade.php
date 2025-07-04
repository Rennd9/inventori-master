@extends('template')

@section('content')
<div class="col-lg-12">
    <div class="neo-border p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">📂 Daftar Kategori Barang</h3>
            <a class="btn neo-btn btn-sm" href="{{ route('kategori.create') }}">
                + Tambah Kategori
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="70%">Nama Kategori</th>
                        <th width="30%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>
                                <!-- Tombol Trigger Modal -->
                                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $cat->id }}">
                                    ✏️ Edit
                                </a>

                                <!-- Form Delete -->
                                <form action="{{ route('kategori.destroy', $cat->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?')">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit untuk Setiap Kategori -->
@foreach($categories as $cat)
    <div class="modal fade" id="editKategoriModal{{ $cat->id }}" tabindex="-1" aria-labelledby="editKategoriModalLabel{{ $cat->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('kategori.update', $cat->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editKategoriModalLabel{{ $cat->id }}">
                            Edit Kategori #{{ $cat->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name{{ $cat->id }}" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="name{{ $cat->id }}" name="name" value="{{ $cat->name }}" required>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_expiration{{ $cat->id }}" name="has_expiration" value="1" 
                            {{ $cat->has_expiration ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_expiration{{ $cat->id }}">
                                Barang dengan tanggal kedaluwarsa?
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
