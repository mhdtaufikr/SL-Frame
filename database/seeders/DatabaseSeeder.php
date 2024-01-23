<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ItemCheckGroupsSeeder::class);
        $this->call(DropdownsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
