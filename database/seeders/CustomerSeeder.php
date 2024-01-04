<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Created one customer with predefined credentials for testing purposes
        User::factory()
        ->create([
            'user_type' => 'customer',
            'password' => Hash::make('customer123'),
            'email' => 'customer@example.com',
        ]);

        User::factory()
        ->count(4)
        ->create([
            'user_type' => 'customer',
            'password' => Hash::make('customer123')
        ]);
    }
}
