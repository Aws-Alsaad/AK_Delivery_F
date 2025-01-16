<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreEmail>
 */
class StoreEmailFactory extends Factory
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
            'type'=>fake()->randomElement(['gmail', 'telegram', 'facebook', 'instagram','whatsapp']),
            'link'=>fake()->email(),
        ];
    }
}
