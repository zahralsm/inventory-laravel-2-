<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Stockroom Inventory</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-brand">
                <div class="brand-icon">▦</div>
                <div>
                    <h1>Stockroom <span>Inventory</span></h1>
                    <p>Sistem Manajemen Stok & Inventaris</p>
                </div>
            </div>

            <div class="login-heading">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk dengan kredensial akun Anda.</p>
            </div>

            <x-alert />

            <form class="login-form" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="admin atau staff" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: -6px; margin-bottom: 20px;">
                    <input type="checkbox" id="remember" name="remember" style="width: auto; min-height: auto; margin: 0;">
                    <label for="remember" style="margin: 0; font-weight: normal; font-size: 13px; cursor: pointer;">Ingat Saya</label>
                </div>

                <button type="submit" class="login-button">
                    <span>Masuk ke Sistem</span>
                    <span>→</span>
                </button>
            </form>

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e0e9e0; font-size: 12px; color: #829287; text-align: center;">
                Akun Demo Bawaan: <br>
                <strong>admin</strong> / <code>admin123</code> &bull; <strong>staff</strong> / <code>staff123</code>
            </div>
        </div>
    </div>
</body>
</html>
