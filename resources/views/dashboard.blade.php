@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Dashboard Inventaris</h1>
        <p style="margin: 0; color: var(--muted); font-size: 14px;">Ringkasan status barang dan pergerakan stok Stockroom.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px;">
        <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; background: #edf4ec; border: 1px solid #dce8d9; border-radius: 10px; color: #56745b; font-size: 12px; font-weight: 600;">
            <span style="width: 7px; height: 7px; background: #789780; border-radius: 50%;"></span>
            {{ ucfirst(Auth::user()->role) }} Account
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin logout?')">
            @csrf
            <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: #fffafa; border: 1px solid #e4d8d5; border-radius: 10px; color: #98665c; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#f8eeeb'" onmouseout="this.style.background='#fffafa'">
                ↪ Logout
            </button>
        </form>
    </div>
</div>

<!-- Statistik Ringkas (4 Cards) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 24px;">
    <div class="card" style="margin: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 10px; background: #e8f5e9; color: #2e7d32; display: flex; align-items: center; justify-content: center; font-size: 22px;">📦</div>
        <div>
            <div style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 600;">Total Produk</div>
            <div style="font-size: 24px; font-weight: 700; color: #304638;">{{ number_format($totalProduk) }}</div>
        </div>
    </div>

    <div class="card" style="margin: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 10px; background: #e3f2fd; color: #1565c0; display: flex; align-items: center; justify-content: center; font-size: 22px;">🗂️</div>
        <div>
            <div style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 600;">Kategori</div>
            <div style="font-size: 24px; font-weight: 700; color: #304638;">{{ number_format($totalKategori) }}</div>
        </div>
    </div>

    <div class="card" style="margin: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 10px; background: #fff8e1; color: #f57f17; display: flex; align-items: center; justify-content: center; font-size: 22px;">🏢</div>
        <div>
            <div style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 600;">Supplier</div>
            <div style="font-size: 24px; font-weight: 700; color: #304638;">{{ number_format($totalSupplier) }}</div>
        </div>
    </div>

    <div class="card" style="margin: 0; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 10px; background: #f3e5f5; color: #7b1fa2; display: flex; align-items: center; justify-content: center; font-size: 22px;">💰</div>
        <div>
            <div style="font-size: 12px; color: var(--muted); text-transform: uppercase; font-weight: 600;">Nilai Inventaris</div>
            <div style="font-size: 18px; font-weight: 700; color: #304638;">Rp {{ number_format($totalNilaiInventaris, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px;">
    <!-- Peringatan Stok Menipis -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 16px; color: #304638;">⚠️ Stok Perlu Perhatian</h3>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>

        @if($produkStokMenipis->isEmpty())
            <p style="color: var(--muted); font-size: 13px; margin: 0;">Semua stok produk saat ini dalam batas aman.</p>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok Saat Ini</th>
                            <th>Batas Min.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produkStokMenipis as $p)
                            <tr>
                                <td>
                                    <strong>{{ $p->name }}</strong><br>
                                    <small style="color: var(--muted);">{{ $p->sku ?? 'Tanpa SKU' }}</small>
                                </td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $p->stock <= 0 ? 'badge-danger' : 'badge-warning' }}">
                                        {{ $p->stock }} unit
                                    </span>
                                </td>
                                <td>{{ $p->minimum_stock }} unit</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Aktivitas Transaksi Terbaru -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 16px; color: #304638;">🔄 Riwayat Transaksi Terbaru</h3>
            <a href="{{ route('stock.index') }}" class="btn btn-secondary btn-sm">Buka Menu Stok</a>
        </div>

        @if($transaksiTerakhir->isEmpty())
            <p style="color: var(--muted); font-size: 13px; margin: 0;">Belum ada riwayat transaksi stok.</p>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiTerakhir as $t)
                            <tr>
                                <td><small>{{ $t->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>{{ $t->product->name ?? 'Produk dihapus' }}</td>
                                <td>
                                    @if($t->type === 'IN')
                                        <span class="badge badge-success">MASUK</span>
                                    @else
                                        <span class="badge badge-danger">KELUAR</span>
                                    @endif
                                </td>
                                <td><strong>{{ $t->quantity }}</strong></td>
                                <td><small>{{ $t->user->name ?? 'User' }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
