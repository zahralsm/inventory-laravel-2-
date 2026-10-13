@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Tambah Produk Baru</h1>
            <p style="margin: 0; color: var(--muted); font-size: 14px;">Masukkan rincian data produk dan jumlah stok awal.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="sku">Kode Barang (SKU)</label>
                <input type="text" id="sku" name="sku" class="form-control" placeholder="Contoh: PRD-0008 (Opsional)" value="{{ old('sku') }}">
                @error('sku')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="name">Nama Produk <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Kertas A4 PaperOne" value="{{ old('name') }}" required>
                @error('name')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $kat)
                        <option value="{{ $kat->id }}" {{ old('category_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="price">Harga Satuan (Rp) <span style="color: red;">*</span></label>
                    <input type="number" id="price" name="price" step="0.01" min="0" class="form-control" placeholder="0" value="{{ old('price', 0) }}" required>
                    @error('price')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="stock">Stok Awal <span style="color: red;">*</span></label>
                    <input type="number" id="stock" name="stock" min="0" class="form-control" placeholder="0" value="{{ old('stock', 0) }}" required>
                    @error('stock')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="minimum_stock">Peringatan Batas Minimum Stok</label>
                <input type="number" id="minimum_stock" name="minimum_stock" min="0" class="form-control" placeholder="5" value="{{ old('minimum_stock', 5) }}">
                <small style="color: var(--muted); font-size: 11px;">Sistem akan memberi tanda peringatan jika stok berada pada atau di bawah angka ini.</small>
                @error('minimum_stock')
                    <small style="color: var(--danger); display: block;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
