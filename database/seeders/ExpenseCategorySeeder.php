<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user)
            return;

        $categories = [
            'Fuel',
            'Maintenance',
            'Electricity',
            'Rent',
            'Salaries',
            'Water Supply',
            'General Office',
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create([
                'name' => $category,
                'user_id' => $user->id,
            ]);
        }
    }
}
