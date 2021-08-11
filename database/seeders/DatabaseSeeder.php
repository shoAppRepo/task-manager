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
      $this->call([
        UsersTableSeeder::class,
        PeriodsTableSeeder::class,
        CategoryTableSeeder::class,
        TodoCategoryTableSeeder::class,
        TasksTableSeeder::class,
        TodoTasksTableSeeder::class,
        manhoursTableSeed::class,
      ]);
    }
}
