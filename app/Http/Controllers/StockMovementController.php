<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Display a listing of stock movements and transaction form.
     */
    public function index(Request $request): View
    {
        $tipe = $request->input('type');
        $query = StockMovement::with(['product', 'user'])->latest();

        if (in_array($tipe, ['IN', 'OUT'], true)) {
            $query->where('type', $tipe);
        }

        $riwayat = $query->paginate(10)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('stock.index', compact('riwayat', 'products', 'tipe'));
    }

    /**
     * Store a stock transaction (IN or OUT) with transaction rollback and business validation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', 'in:IN,OUT'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Pilih produk yang akan ditransaksikan.',
            'type.in' => 'Tipe transaksi harus Stok Masuk (IN) atau Stok Keluar (OUT).',
            'quantity.required' => 'Jumlah barang wajib diisi.',
            'quantity.min' => 'Jumlah barang minimal 1.',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // Lock the product row for update to avoid race conditions
                $product = Product::where('id', $validated['product_id'])->lockForUpdate()->firstOrFail();

                if ($validated['type'] === 'OUT') {
                    if ($product->stock < $validated['quantity']) {
                        throw new \Exception(
                            "Stok tidak mencukupi! Stok saat ini: {$product->stock}, permintaan keluar: {$validated['quantity']}."
                        );
                    }
                    $product->decrement('stock', $validated['quantity']);
                } else {
                    $product->increment('stock', $validated['quantity']);
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => $validated['type'],
                    'quantity' => $validated['quantity'],
                    'note' => $validated['note'] ?? null,
                ]);
            });

            $tipeLabel = $validated['type'] === 'IN' ? 'Stok masuk' : 'Stok keluar';

            return redirect()->route('stock.index')
                ->with('success', "Transaksi {$tipeLabel} sebanyak {$validated['quantity']} unit berhasil dicatat.");
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'quantity' => $e->getMessage(),
            ]);
        }
    }
}
