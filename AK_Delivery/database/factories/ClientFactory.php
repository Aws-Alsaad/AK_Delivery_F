<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'phone_number'=>fake()->phoneNumber(),
            'password'=>fake()->password(),
            'email'=>fake()->email(),
            'profile_photo_path'=>fake()->text,
            'address_id'=>fake()->numberBetween(1,10),
        ];
    }
}
