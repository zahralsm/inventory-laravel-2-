<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun default Admin & Staff dari P4
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Stockroom',
                'email' => 'admin@stockroom.local',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Gudang',
                'email' => 'staff@stockroom.local',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]
        );

        // 2. Kategori Awal
        $kategoriList = [
            'Elektronik',
            'Alat Tulis Kantor',
            'Perlengkapan Kebersihan',
            'Peralatan Komputer',
            'Makanan & Minuman',
        ];

        $categories = collect();
        foreach ($kategoriList as $kat) {
            $categories->push(Category::firstOrCreate(['name' => $kat]));
        }

        // 3. Supplier Awal
        $suppliers = [
            [
                'name' => 'PT Sumber Makmur Logistik',
                'contact_person' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'info@sumbermakmur.co.id',
                'address' => 'Jl. Industri No. 12, Jakarta',
            ],
            [
                'name' => 'CV Abadi Stationery',
                'contact_person' => 'Siti Rahma',
                'phone' => '082198765432',
                'email' => 'sales@abadistationery.com',
                'address' => 'Jl. Pahlawan No. 45, Bandung',
            ],
            [
                'name' => 'PT Mitra Komputindo Nusantara',
                'contact_person' => 'Andi Wijaya',
                'phone' => '081377889900',
                'email' => 'andi@mitrakomputindo.co.id',
                'address' => 'Jl. Gajah Mada No. 88, Surabaya',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }

        // 4. Produk Awal
        $produkAwal = [
            [
                'sku' => 'PRD-0001',
                'name' => 'Laptop Asus Vivobook 14',
                'category_id' => $categories[0]->id,
                'price' => 7500000,
                'stock' => 15,
                'minimum_stock' => 5,
            ],
            [
                'sku' => 'PRD-0002',
                'name' => 'Mouse Wireless Logitech M220',
                'category_id' => $categories[3]->id,
                'price' => 175000,
                'stock' => 40,
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'PRD-0003',
                'name' => 'Kertas HVS A4 80gr PaperOne (Rim)',
                'category_id' => $categories[1]->id,
                'price' => 55000,
                'stock' => 120,
                'minimum_stock' => 20,
            ],
            [
                'sku' => 'PRD-0004',
                'name' => 'Keyboard Mechanical Royal Kludge RK61',
                'category_id' => $categories[3]->id,
                'price' => 620000,
                'stock' => 8,
                'minimum_stock' => 5,
            ],
            [
                'sku' => 'PRD-0005',
                'name' => 'Pulpen Gel Pilot G2 0.5 Black',
                'category_id' => $categories[1]->id,
                'price' => 18000,
                'stock' => 250,
                'minimum_stock' => 50,
            ],
            [
                'sku' => 'PRD-0006',
                'name' => 'Kopi Kapal Api Special Mix (Pouch)',
                'category_id' => $categories[4]->id,
                'price' => 14500,
                'stock' => 60,
                'minimum_stock' => 15,
            ],
            [
                'sku' => 'PRD-0007',
                'name' => 'Cairan Pembersih Lantai Wipol 750ml',
                'category_id' => $categories[2]->id,
                'price' => 19500,
                'stock' => 4, // Di bawah minimum_stock untuk tes alert stok menipis
                'minimum_stock' => 10,
            ],
        ];

        foreach ($produkAwal as $p) {
            $prod = Product::firstOrCreate(['sku' => $p['sku']], $p);

            // Buat initial stock movement IN
            StockMovement::firstOrCreate(
                [
                    'product_id' => $prod->id,
                    'user_id' => $admin->id,
                    'type' => 'IN',
                    'quantity' => $prod->stock,
                ],
                [
                    'note' => 'Stok awal sistem (Migration/Seeder)',
                ]
            );
        }
    }
}
