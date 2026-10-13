<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProduk = Product::count();
        $totalKategori = Category::count();
        $totalSupplier = Supplier::count();
        $totalNilaiInventaris = Product::selectRaw('SUM(price * stock) as total_val')->value('total_val') ?? 0;

        $produkStokMenipis = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        $transaksiTerakhir = StockMovement::with(['product', 'user'])
            ->latest()
            ->limit(7)
            ->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalKategori',
            'totalSupplier',
            'totalNilaiInventaris',
            'produkStokMenipis',
            'transaksiTerakhir'
        ));
    }
}
