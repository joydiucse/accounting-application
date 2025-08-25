<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Income Categories
        $incomeCategories = [
            ['name' => 'Sales Revenue', 'type' => 'income', 'is_default' => true],
            ['name' => 'Service Income', 'type' => 'income', 'is_default' => true],
            ['name' => 'Interest Income', 'type' => 'income', 'is_default' => true],
            ['name' => 'Investment Income', 'type' => 'income', 'is_default' => true],
            ['name' => 'Other Income', 'type' => 'income', 'is_default' => true],
        ];

        // Default Expense Categories
        $expenseCategories = [
            ['name' => 'Office Supplies', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Rent', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Utilities', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Marketing', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Travel', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Professional Services', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Insurance', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Equipment', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Software', 'type' => 'expense', 'is_default' => true],
            ['name' => 'Other Expenses', 'type' => 'expense', 'is_default' => true],
        ];

        // Create income categories
        foreach ($incomeCategories as $category) {
            Category::create($category);
        }

        // Create expense categories
        foreach ($expenseCategories as $category) {
            Category::create($category);
        }
    }
}