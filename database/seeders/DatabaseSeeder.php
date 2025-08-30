<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Moshahed Alam',
            'email' => 'moshahed777@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('Moshahed##2025'),
        ]);
        User::factory()->create([
            'name' => 'Joynal Abedin',
            'email' => 'joy.diu.cse@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('Joy##2025'),
        ]);



        // Create company profile
        CompanyProfile::create([
            'name' => 'Insite Service',
            'address' => 'Your Company Address',
            'phone' => '+1234567890',
            'email' => 'info@yourcompany.com',
            'website' => 'https://yourcompany.com',
        ]);

        // Seed categories
        $this->call(CategorySeeder::class);
    }
}
