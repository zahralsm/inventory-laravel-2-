<nav class="app-nav" aria-label="Navigasi utama">
    <a class="app-brand" href="{{ route('dashboard') }}">
        <span class="app-brand-icon">▦</span>
        <span>Stockroom<small>Inventory Management</small></span>
    </a>
    <div class="app-nav-links">
        <a class="{{ request()->routeIs('dashboard', 'home') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
        <a class="{{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
        <a class="{{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Kategori</a>
        <a class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">Supplier</a>
        <a class="{{ request()->routeIs('stock.*') ? 'active' : '' }}" href="{{ route('stock.index') }}">Stok</a>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; margin-left: auto;">
        <span class="app-nav-user" style="display: inline-flex; align-items: center; gap: 6px;">
            <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #789780;"></span>
            {{ Auth::user()->name }} · <strong>{{ ucfirst(Auth::user()->role) }}</strong>
        </span>
        <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin keluar / logout?')">
            @csrf
            <button type="submit" class="btn-logout" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: #fffafa; border: 1px solid #e4d8d5; border-radius: 9px; color: #a13e37; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#ffebee'" onmouseout="this.style.background='#fffafa'">
                ↪ Logout
            </button>
        </form>
    </div>
</nav>
