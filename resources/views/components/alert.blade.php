@if (session('success'))
    <div class="alert alert-success" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; font-size: 14px;">
        <strong>Berhasil!</strong> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; font-size: 14px;">
        <strong>Perhatian!</strong> {{ session('error') }}
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; background: #e3f2fd; color: #1565c0; border: 1px solid #bbdefb; font-size: 14px;">
        <strong>Informasi:</strong> {{ session('info') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; font-size: 14px;">
        <strong>Terjadi Kesalahan:</strong>
        <ul style="margin: 6px 0 0 20px; padding: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
