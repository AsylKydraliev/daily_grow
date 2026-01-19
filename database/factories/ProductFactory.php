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
            'price' => fake()->randomFloat(2, 5000, 200000),
        ];
    }
}
