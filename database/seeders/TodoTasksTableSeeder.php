<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TodoTasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('todo_tasks')->insert([
        'category_id' => 1,
        'name' => '個人月間画面作成',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('todo_tasks')->insert([
        'category_id' => 1,
        'name' => '予定追加画面',
        'user_id' => 1,
        'sort' => 2,
      ]);

      DB::table('todo_tasks')->insert([
        'category_id' => 2,
        'name' => 'バグ対応1',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('todo_tasks')->insert([
        'category_id' => 2,
        'name' => 'バグ対応2',
        'user_id' => 1,
        'sort' => 2,
      ]);
    }
}