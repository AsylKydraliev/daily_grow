<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Counterparty;
use Illuminate\Database\Seeder;

class CounterpartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();

        Counterparty::factory()->count(50)->create()->each(function ($counterparty) use ($branches) {
            // 70% вероятность наличия филиала
            if (rand(1, 100) <= 70 && $branches->isNotEmpty()) {
                $counterparty->branch_id = $branches->random()->id;
                $counterparty->save();
            }
        });
    }
}
