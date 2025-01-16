<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state'=>fake()->country,
            'city'=>fake()->city,
            'town'=>fake()->city,
            'area'=>fake()->city,
            'street'=>fake()->streetAddress,
            'notes'=>fake()->text,
            'display_name'=>fake()->name,
        ];
    }
}
