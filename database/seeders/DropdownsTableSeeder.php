<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownsTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data from the table
        DB::table('dropdowns')->truncate();

        // Insert new data
        DB::table('dropdowns')->insert([
            [
                'category' => 'Role',
                'name_value' => 'Super Admin',
                'code_format' => 'SPA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'User',
                'code_format' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'IT',
                'code_format' => 'IT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'QG',
                'code_format' => 'QG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'PDI',
                'code_format' => 'PDI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more dropdowns if needed
        ]);
    }
}
