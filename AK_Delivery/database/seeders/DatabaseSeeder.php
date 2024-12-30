<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\PhoneNumber;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        Address::factory(10)->create();
        Client::factory(10)->create();
        Store::factory(10)->create();
        Product::factory(10)->create();
        PhoneNumber::factory(10)->create();
    }
}
