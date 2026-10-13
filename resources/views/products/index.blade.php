@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Manajemen Produk</h1>
        <p style="margin: 0; color: var(--muted); font-size: 14px;">Kelola stok barang, informasi harga, dan kategori.</p>
    </div>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            + Tambah Produk Baru
        </a>
    @endif
</div>

<!-- Filter dan Pencarian -->
<div class="card" style="padding: 18px 24px;">
    <form method="GET" action="{{ route('products.index') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 2; min-width: 200px;">
            <label class="form-label" for="cari">Cari Produk / SKU</label>
            <input type="text" id="cari" name="cari" class="form-control" placeholder="Ketik nama atau kode barang..." value="{{ $search }}">
        </div>

        <div style="flex: 1; min-width: 160px;">
            <label class="form-label" for="category_id">Kategori</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">Semua Kategori</option>
                @foreach($categories as $kat)
                    <option value="{{ $kat->id }}" {{ (string)$categoryId === (string)$kat->id ? 'selected' : '' }}>
                        {{ $kat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 160px;">
            <label class="form-label" for="urut">Urutkan Berdasarkan</label>
            <select id="urut" name="urut" class="form-control">
                <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="nama" {{ $sort === 'nama' ? 'selected' : '' }}>Nama Produk (A-Z)</option>
                <option value="harga_asc" {{ $sort === 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="harga_desc" {{ $sort === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="stok" {{ $sort === 'stok' ? 'selected' : '' }}>Stok Paling Sedikit</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>

<!-- Tabel Produk -->
<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode (SKU)</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center; width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $prod)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td><code>{{ $prod->sku ?? '-' }}</code></td>
                        <td>
                            <strong>{{ $prod->name }}</strong>
                        </td>
                        <td>{{ $prod->category->name ?? 'Tanpa Kategori' }}</td>
                        <td style="text-align: right;">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            @if($prod->stock <= 0)
                                <span class="badge badge-danger">Habis (0)</span>
                            @elseif($prod->stock <= $prod->minimum_stock)
                                <span class="badge badge-warning">{{ $prod->stock }} unit</span>
                            @else
                                <span class="badge badge-success">{{ $prod->stock }} unit</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('products.show', $prod) }}" class="btn btn-secondary btn-sm" title="Detail">
                                    Detail
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('products.edit', $prod) }}" class="btn btn-secondary btn-sm" title="Edit">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $prod) }}" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px; color: var(--muted);">
                            Tidak ada produk yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container" style="margin-top: 20px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
