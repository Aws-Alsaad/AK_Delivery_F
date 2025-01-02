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
        return [
            'store_id'=>fake()->numberBetween(1,20),
            'name'=>fake()->word(),
            'price'=>fake()->numberBetween(1,100),
            'quantity'=>fake()->numberBetween(1,100),
            'product_date'=>fake()->date(),
            'end_date'=>fake()->date(),
            'product_image_path'=>fake()->text,
        ];
    }
}
