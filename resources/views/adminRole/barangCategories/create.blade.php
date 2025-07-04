{{-- File: resources/views/adminRole/barangCategories/create.blade.php --}}

<h2>➕ Tambah Kategori Barang</h2>

<form action="{{ route('kategori.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Nama Kategori:</label><br>
        <input type="text" id="name" name="name" required>
    </div>
    <br>
    <div>
        <input type="checkbox" id="has_expiration" name="has_expiration" value="1">
        <label for="has_expiration">Kategori ini memiliki tanggal kedaluwarsa</label>
    </div>
    <br>
    <button type="submit">💾 Simpan</button>
    <a href="{{ route('kategori.index') }}">⬅️ Kembali</a>
</form>