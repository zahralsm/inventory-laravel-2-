@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div style="max-width: 680px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Edit Data Produk</h1>
            <p style="margin: 0; color: var(--muted); font-size: 14px;">Perbarui detail informasi produk.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="sku">Kode Barang (SKU)</label>
                <input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                @error('sku')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="name">Nama Produk <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="category_id">Kategori</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $kat)
                        <option value="{{ $kat->id }}" {{ old('category_id', $product->category_id) == $kat->id ? 'selected' : '' }}>
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
                    <input type="number" id="price" name="price" step="0.01" min="0" class="form-control" value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Stok Saat Ini (Tercatat)</label>
                    <input type="text" class="form-control" value="{{ $product->stock }} unit" disabled style="background: #f5f5f5;">
                    <small style="color: var(--muted); font-size: 11px;">Perubahan jumlah stok dilakukan melalui menu transaksi stok.</small>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="minimum_stock">Peringatan Batas Minimum Stok</label>
                <input type="number" id="minimum_stock" name="minimum_stock" min="0" class="form-control" value="{{ old('minimum_stock', $product->minimum_stock) }}">
                @error('minimum_stock')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
