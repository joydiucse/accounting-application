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
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('Developer#1'),
        ]);

        // Create accountant user
        User::factory()->create([
            'name' => 'Accountant User',
            'email' => 'accountant@example.com',
            'role' => 'accountant',
        ]);

        // Create viewer user
        User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'role' => 'viewer',
        ]);

        // Create company profile
        CompanyProfile::create([
            'name' => 'Your Company Name',
            'address' => 'Your Company Address',
            'phone' => '+1234567890',
            'email' => 'info@yourcompany.com',
            'website' => 'https://yourcompany.com',
        ]);

        // Seed categories
        $this->call(CategorySeeder::class);
    }
}
