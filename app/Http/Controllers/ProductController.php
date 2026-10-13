<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with search, filter, sort, and pagination.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('cari', ''));
        $categoryId = $request->input('category_id');
        $sort = $request->input('urut', 'terbaru');

        $query = Product::with('category');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (! empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        switch ($sort) {
            case 'nama':
                $query->orderBy('name', 'asc');
                break;
            case 'harga_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stok':
                $query->orderBy('stock', 'asc');
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(5)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'sort', 'categoryId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'sku.unique' => 'SKU/Kode barang sudah terdaftar.',
            'price.min' => 'Harga tidak boleh bernilai negatif.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
        ]);

        if (empty($validated['minimum_stock'])) {
            $validated['minimum_stock'] = 5;
        }

        $product = Product::create($validated);

        if ($product->stock > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'IN',
                'quantity' => $product->stock,
                'note' => 'Stok awal saat penambahan produk',
            ]);
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'stockMovements.user']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'sku.unique' => 'SKU sudah dipakai produk lain.',
            'price.min' => 'Harga tidak boleh negatif.',
        ]);

        if (empty($validated['minimum_stock'])) {
            $validated['minimum_stock'] = 5;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $nama = $product->name;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk "' . $nama . '" berhasil dihapus.');
    }
}
