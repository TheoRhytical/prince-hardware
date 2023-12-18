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
        User::factory()->count(500)->has(
            Customer::factory()->state(function (array $attributes, User $user) {
                return ['full_name' => $user->full_name];
            })
        )->create([
            'user_type' => 'customer',
            'password' => Hash::make('customer123')
        ]);
    }
}
