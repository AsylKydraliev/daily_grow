<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();
        $users = User::all();

        if ($branches->isEmpty() || $products->isEmpty() || $users->isEmpty()) {
            return;
        }

        ProductReceipt::factory()->count(300)->create([
            'branch_id' => fn() => $branches->random()->id,
            'product_id' => fn() => $products->random()->id,
            'user_id' => fn() => $users->random()->id,
        ]);
    }
}
