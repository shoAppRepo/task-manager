<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('tasks')->insert([
        'category_id' => 1,
        'period_id' => 1,
        'name' => '個人月間画面作成',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 1,
        'period_id' => 1,
        'name' => '予定追加画面',
        'user_id' => 1,
        'sort' => 2,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 2,
        'period_id' => 1,
        'name' => 'バグ対応1',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 2,
        'period_id' => 1,
        'name' => 'バグ対応2',
        'user_id' => 1,
        'sort' => 2,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 4,
        'period_id' => 1,
        'name' => 'デイリースクラム',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 4,
        'period_id' => 1,
        'name' => 'スプリントレビュー',
        'user_id' => 1,
        'sort' => 2,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 5,
        'period_id' => 1,
        'name' => 'その他1',
        'user_id' => 1,
        'sort' => 1,
      ]);

      DB::table('tasks')->insert([
        'category_id' => 5,
        'period_id' => 1,
        'name' => 'その他2',
        'user_id' => 1,
        'sort' => 2,
      ]);
    }
}