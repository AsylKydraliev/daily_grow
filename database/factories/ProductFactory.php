<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appliances = [
            'Холодильник', 'Стиральная машина', 'Посудомоечная машина', 'Микроволновая печь',
            'Духовой шкаф', 'Варочная панель', 'Вытяжка', 'Пылесос', 'Утюг', 'Чайник',
            'Кофемашина', 'Блендер', 'Миксер', 'Мультиварка', 'Хлебопечка', 'Мясорубка',
            'Кондиционер', 'Обогреватель', 'Вентилятор', 'Телевизор', 'Ноутбук', 'Планшет',
            'Смартфон', 'Наушники', 'Колонки', 'Роутер', 'Принтер', 'Монитор',
        ];

        $brands = [
            'Samsung', 'LG', 'Bosch', 'Siemens', 'Electrolux', 'Indesit', 'Beko',
            'Ariston', 'Whirlpool', 'Candy', 'Haier', 'Panasonic', 'Philips',
            'Xiaomi', 'Apple', 'HP', 'Lenovo', 'Asus', 'Acer', 'Dell',
        ];

        $name = fake()->randomElement($appliances) . ' ' . fake()->randomElement($brands) . ' ' . fake()->bothify('##??');

        return [
            'branch_id' => \App\Models\Branch::factory(),
            'name' => $name,
            'purchase_price_usd' => fake()->randomFloat(2, 50, 2000),
            'purchase_price_kzt' => fake()->randomFloat(2, 20000, 800000),
            'wholesale_price_usd' => fake()->randomFloat(2, 60, 2500),
            'wholesale_price_kzt' => fake()->randomFloat(2, 25000, 1000000),
            'current_quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
