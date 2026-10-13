@extends('layouts.app')

@section('title', 'Daftar Supplier')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="margin: 0 0 6px; font-size: 24px; font-weight: 700; color: #304638;">Data Supplier</h1>
        <p style="margin: 0; color: var(--muted); font-size: 14px;">Kelola informasi kontak vendor dan mitra penyedia barang.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
    <!-- Form Tambah Supplier -->
    <div class="card" style="height: fit-content;">
        <h3 style="margin: 0 0 16px; font-size: 16px; color: #304638;">+ Tambah Supplier Baru</h3>
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Nama Perusahaan / Supplier <span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="PT Contoh Logistik" value="{{ old('name') }}" required>
                @error('name')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_person">Contact Person (PIC)</label>
                <input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="Nama kontak PIC" value="{{ old('contact_person') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Perusahaan</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="supplier@email.com" value="{{ old('email') }}">
                @error('email')
                    <small style="color: var(--danger);">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Kantor / Gudang</label>
                <textarea id="address" name="address" rows="3" class="form-control" placeholder="Jl. Raya...">{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Supplier</button>
        </form>
    </div>

    <!-- Tabel Supplier -->
    <div class="card">
        <h3 style="margin: 0 0 16px; font-size: 16px; color: #304638;">Daftar Supplier</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Kontak PIC</th>
                        <th>Telepon & Email</th>
                        @if(Auth::user()->isAdmin())
                            <th style="text-align: center; width: 140px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $sup)
                        <tr>
                            <td>
                                <strong>{{ $sup->name }}</strong><br>
                                <small style="color: var(--muted);">{{ Str::limit($sup->address ?? 'Tanpa alamat', 35) }}</small>
                            </td>
                            <td>{{ $sup->contact_person ?? '-' }}</td>
                            <td>
                                {{ $sup->phone ?? '-' }}<br>
                                <small style="color: var(--muted);">{{ $sup->email ?? '-' }}</small>
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 6px;">
                                        <button type="button" class="btn btn-secondary btn-sm" onclick='editSupplier(@json($sup))'>
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('suppliers.destroy', $sup) }}" style="display: inline;" onsubmit="return confirm('Hapus data supplier ini?')">
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
                                Belum ada supplier yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top: 20px;">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>

<!-- Modal Dialog Sederhana untuk Edit Supplier -->
<div id="modalEditSupplier" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 90%; max-width: 480px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0; color: #304638;">Edit Data Supplier</h3>
        <form id="formEditSupplier" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label" for="edit_sup_name">Nama Supplier</label>
                <input type="text" id="edit_sup_name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_sup_pic">Kontak Person</label>
                <input type="text" id="edit_sup_pic" name="contact_person" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_sup_phone">Telepon</label>
                <input type="text" id="edit_sup_phone" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_sup_email">Email</label>
                <input type="email" id="edit_sup_email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_sup_address">Alamat</label>
                <textarea id="edit_sup_address" name="address" rows="2" class="form-control"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="tutupModalSupplier()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editSupplier(data) {
        const form = document.getElementById('formEditSupplier');
        form.action = '/suppliers/' + data.id;
        document.getElementById('edit_sup_name').value = data.name || '';
        document.getElementById('edit_sup_pic').value = data.contact_person || '';
        document.getElementById('edit_sup_phone').value = data.phone || '';
        document.getElementById('edit_sup_email').value = data.email || '';
        document.getElementById('edit_sup_address').value = data.address || '';
        document.getElementById('modalEditSupplier').style.display = 'flex';
    }

    function tutupModalSupplier() {
        document.getElementById('modalEditSupplier').style.display = 'none';
    }
</script>
@endsection
