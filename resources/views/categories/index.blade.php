@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Kategori Produk</h1>
        <p style="margin: 0; color: var(--muted); font-size: 14px;">Kelompokkan produk inventaris agar mudah dicari dan dikelola.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
    <!-- Form Tambah Kategori -->
    <div class="card" style="height: fit-content;">
        <h3 style="margin: 0 0 16px; font-size: 16px; color: #304638;">+ Tambah Kategori Baru</h3>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Nama Kategori <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Elektronik, Alat Tulis" value="{{ old('name') }}" required>
                @error('name')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Kategori</button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="card">
        <h3 style="margin: 0 0 16px; font-size: 16px; color: #304638;">Daftar Kategori Tersedia</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th style="text-align: center;">Jumlah Produk</th>
                        @if(Auth::user()->isAdmin())
                            <th style="text-align: center; width: 140px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $cat)
                        <tr>
                            <td>{{ $categories->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $cat->name }}</strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-info">{{ $cat->products_count }} produk</span>
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 6px;">
                                        <!-- Tombol Trigger Edit Inline Modal/Prompt -->
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="editKategori({{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('categories.destroy', $cat) }}" style="display: inline;" onsubmit="return confirm('Hapus kategori ini? Produk terkait akan menjadi tanpa kategori.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isAdmin() ? 4 : 3 }}" style="text-align: center; padding: 20px; color: var(--muted);">
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top: 20px;">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- Modal Dialog Sederhana untuk Edit Kategori -->
<div id="modalEdit" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 90%; max-width: 420px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0; color: #304638;">Perbarui Nama Kategori</h3>
        <form id="formEditKategori" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label" for="edit_name">Nama Kategori</label>
                <input type="text" id="edit_name" name="name" class="form-control" required>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function editKategori(id, name) {
        const form = document.getElementById('formEditKategori');
        form.action = '/categories/' + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('modalEdit').style.display = 'flex';
    }

    function tutupModal() {
        document.getElementById('modalEdit').style.display = 'none';
    }
</script>
@endsection
