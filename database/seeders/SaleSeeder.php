<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Counterparty;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();
        $counterparties = \App\Models\Counterparty::all();

        if ($branches->isEmpty() || $products->isEmpty() || $counterparties->isEmpty()) {
            return;
        }

        Sale::factory()->count(800)->create([
            'branch_id' => fn() => $branches->random()->id,
            'product_id' => fn() => $products->random()->id,
            'counterparty_id' => fn() => $counterparties->random()->id,
        ]);
    }
}
