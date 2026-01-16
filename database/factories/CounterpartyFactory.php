<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Counterparty>
 */
class CounterpartyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $branches = Branch::all();
        
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'branch_id' => (fake()->boolean(70) && $branches->isNotEmpty()) 
                ? $branches->random()->id 
                : null, // 70% вероятность наличия филиала
        ];
    }
}
