<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->bothify('PRD-####'),
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'price' => fake()->randomFloat(2, 5000, 500000),
            'stock' => fake()->numberBetween(5, 100),
            'minimum_stock' => fake()->numberBetween(2, 10),
        ];
    }
}
