<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            BranchSeeder::class,
            CounterpartySeeder::class,
            ProductSeeder::class,
            ProductReceiptSeeder::class,
            SaleSeeder::class,
        ]);
    }
}
