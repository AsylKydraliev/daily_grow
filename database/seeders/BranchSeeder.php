<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['name' => 'Алматы', 'address' => 'г. Алматы'],
            ['name' => 'Беловодское', 'address' => 'ул. Фрунзе, 128'],
            ['name' => 'Сокулук', 'address' => 'ул. Фрунзе, 147/2'],
            ['name' => 'Бишкек', 'address' => 'ул. Фрунзе, 127'],
            ['name' => 'Кант', 'address' => 'ул. Ленина, 74'],
            ['name' => 'Ош (К.Датка 650)', 'address' => 'ул. К.Датка, 650'],
            ['name' => 'Ош (А.Алимжанов 10)', 'address' => 'ул. А.Алимжанов, 10'],
            ['name' => 'Ош (Ж.Раимбеков 6)', 'address' => 'ул. Ж.Раимбеков, 6'],
            ['name' => 'Ош (К.Датка 282/3)', 'address' => 'ул. К.Датка, 282/3'],
            ['name' => 'Жалал-Абад', 'address' => 'Проспект Т. Байзакова, 289ж'],
            ['name' => 'Кочкор Ата', 'address' => 'ул. Октябрьская, б/н'],
            ['name' => 'Каракол', 'address' => 'ул. Абдыкеримова, 27/1'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
