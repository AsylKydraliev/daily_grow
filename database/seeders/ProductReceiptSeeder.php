<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductReceipt;
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

        if ($branches->isEmpty() || $products->isEmpty()) {
            return;
        }

        ProductReceipt::factory()->count(300)->create([
            'branch_id' => fn() => $branches->random()->id,
            'product_id' => fn() => $products->random()->id,
        ])->each(function ($receipt) {
            // Обновляем current_quantity товара
            $product = Product::find($receipt->product_id);
            if ($product && $product->branch_id == $receipt->branch_id) {
                $product->increment('current_quantity', $receipt->quantity);
            }
        });
    }
}
