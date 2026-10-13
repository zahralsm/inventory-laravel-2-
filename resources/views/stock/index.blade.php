@extends('layouts.app')

@section('title', 'Transaksi Stok')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Transaksi Stok Barang</h1>
        <p style="margin: 0; color: var(--muted); font-size: 14px;">Catat penerimaan stok masuk (IN) dan pengeluaran barang (OUT).</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
    <!-- Form Transaksi Stok -->
    <div class="card" style="height: fit-content;">
        <h3 style="margin: 0 0 16px; font-size: 16px; color: #304638;">+ Catat Mutasi Stok</h3>
        <form method="POST" action="{{ route('stock.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="product_id">Pilih Produk <span style="color: red;">*</span></label>
                <select id="product_id" name="product_id" class="form-control" required onchange="updateInfoStok(this)">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-stok="{{ $p->stock }}" {{ old('product_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} (Tersedia: {{ $p->stock }} unit)
                        </option>
                    @endforeach
                </select>
                <small id="infoStokTersedia" style="color: var(--muted); font-size: 11px; display: block; margin-top: 4px;"></small>
                @error('product_id')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="type">Tipe Transaksi <span style="color: red;">*</span></label>
                <select id="type" name="type" class="form-control" required>
                    <option value="IN" {{ old('type') === 'IN' ? 'selected' : '' }}>🟢 Stok Masuk (IN) — Menambah Stok</option>
                    <option value="OUT" {{ old('type') === 'OUT' ? 'selected' : '' }}>🔴 Stok Keluar (OUT) — Mengurangi Stok</option>
                </select>
                @error('type')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="quantity">Jumlah Unit <span style="color: red;">*</span></label>
                <input type="number" id="quantity" name="quantity" min="1" class="form-control" placeholder="1" value="{{ old('quantity', 1) }}" required>
                @error('quantity')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="note">Catatan / Alasan Transaksi</label>
                <textarea id="note" name="note" rows="2" class="form-control" placeholder="Contoh: Penerimaan PO #102, Pengiriman ke Dept HR...">{{ old('note') }}</textarea>
                @error('note')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Transaksi Stok</button>
        </form>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;">
            <h3 style="margin: 0; font-size: 16px; color: #304638;">Riwayat Mutasi Stok</h3>

            <!-- Filter Type -->
            <form method="GET" action="{{ route('stock.index') }}" style="display: flex; gap: 6px;">
                <select name="type" class="form-control" style="padding: 4px 8px; font-size: 12px;" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    <option value="IN" {{ $tipe === 'IN' ? 'selected' : '' }}>Stok Masuk</option>
                    <option value="OUT" {{ $tipe === 'OUT' ? 'selected' : '' }}>Stok Keluar</option>
                </select>
            </form>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Produk</th>
                        <th style="text-align: center;">Tipe</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th>Petugas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $r)
                        <tr>
                            <td><small>{{ $r->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                <strong>{{ $r->product->name ?? 'Produk Dihapus' }}</strong><br>
                                <small style="color: var(--muted);">{{ $r->product->sku ?? '-' }}</small>
                            </td>
                            <td style="text-align: center;">
                                @if($r->type === 'IN')
                                    <span class="badge badge-success">MASUK</span>
                                @else
                                    <span class="badge badge-danger">KELUAR</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <strong>{{ $r->quantity }}</strong>
                            </td>
                            <td><small>{{ $r->user->name ?? '-' }}</small></td>
                            <td><small>{{ $r->note ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: var(--muted);">
                                Belum ada riwayat transaksi stok yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top: 20px;">
            {{ $riwayat->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateInfoStok(select) {
        const option = select.options[select.selectedIndex];
        const stok = option.getAttribute('data-stok');
        const info = document.getElementById('infoStokTersedia');
        if (stok !== null) {
            info.textContent = 'Stok produk saat ini: ' + stok + ' unit';
        } else {
            info.textContent = '';
        }
    }
</script>
@endsection
