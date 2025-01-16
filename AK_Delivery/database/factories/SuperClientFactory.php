<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuperClient>
 */
class SuperClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id'=>fake()->numberBetween(1,15),
            'name'=>$this->faker->name(),
            'email'=>$this->faker->unique()->safeEmail(),
            'password'=>$this->faker->password(),
            'profile_photo_path'=>$this->faker->imageUrl(),
        ];
    }
}
