@extends('layouts.app')

@section('title', 'Detail Produk — ' . $product->name)

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Detail Produk</h1>
            <p style="margin: 0; color: var(--muted); font-size: 14px;">Informasi lengkap barang dan catatan riwayat mutasi stok.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Edit Produk</a>
            @endif
        </div>
    </div>

    <div class="card" style="margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase;">Kode SKU</span>
                <div style="font-size: 16px; font-weight: 600; margin-top: 4px;">{{ $product->sku ?? '-' }}</div>
            </div>

            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase;">Nama Produk</span>
                <div style="font-size: 16px; font-weight: 600; margin-top: 4px;">{{ $product->name }}</div>
            </div>

            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase;">Kategori</span>
                <div style="font-size: 16px; font-weight: 600; margin-top: 4px;">{{ $product->category->name ?? 'Tanpa Kategori' }}</div>
            </div>

            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase;">Harga Satuan</span>
                <div style="font-size: 18px; font-weight: 700; margin-top: 4px; color: #2e7d32;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            </div>

            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase;">Jumlah Stok Tersedia</span>
                <div style="margin-top: 4px;">
                    <span class="badge {{ $product->stock <= $product->minimum_stock ? 'badge-warning' : 'badge-success' }}" style="font-size: 14px; padding: 6px 12px;">
                        {{ $product->stock }} unit
                    </span>
                </div>
            </div>

            <div>
                <span style="font-size: 12px; color: var(--muted); text-transform: uppercase;">Batas Minimum Stok</span>
                <div style="font-size: 16px; font-weight: 600; margin-top: 4px;">{{ $product->minimum_stock }} unit</div>
            </div>
        </div>
    </div>

    <!-- Riwayat Stok Produk Ini -->
    <div class="card">
        <h3 style="margin: 0 0 16px; font-size: 16px; color: #304638;">Riwayat Pergerakan Stok Produk</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->stockMovements as $sm)
                        <tr>
                            <td>{{ $sm->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($sm->type === 'IN')
                                    <span class="badge badge-success">MASUK</span>
                                @else
                                    <span class="badge badge-danger">KELUAR</span>
                                @endif
                            </td>
                            <td><strong>{{ $sm->quantity }} unit</strong></td>
                            <td>{{ $sm->user->name ?? '-' }}</td>
                            <td><small>{{ $sm->note ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--muted); padding: 20px;">
                                Belum ada transaksi pergerakan stok untuk produk ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
