<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TodoCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('todo_categories')->insert([
        'name' => 'ToDo',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('todo_categories')->insert([
        'name' => 'Doing',
        'user_id' => 1,
        'sort' => 2,
      ]);

      DB::table('todo_categories')->insert([
        'name' => 'Done',
        'user_id' => 1,
        'sort' => 5,
      ]);
    }
}
