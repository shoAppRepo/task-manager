<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('categories')->insert([
        'name' => '開発作業',
        'period_id' => 1,
        'user_id' => 1,
      ]);

      DB::table('categories')->insert([
        'name' => 'バグ対応',
        'period_id' => 1,
        'user_id' => 1,
      ]);

      DB::table('categories')->insert([
        'name' => '社内お問い合わせ',
        'period_id' => 1,
        'user_id' => 1,
      ]);

      DB::table('categories')->insert([
        'name' => 'チームミーティング',
        'period_id' => 1,
        'user_id' => 1,
      ]);

      DB::table('categories')->insert([
        'name' => 'その他',
        'period_id' => 1,
        'user_id' => 1,
      ]);
    }
}
