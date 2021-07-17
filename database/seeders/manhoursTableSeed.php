<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class manhoursTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('manhours')->insert([
        'task_id' => '1',
        'title' => 'レイアウト検討',
        'start' => '2021-06-01 09:30',
        'end' => '2021-06-01 11:30',
        'remark' => '画面のレイアウトを検討、ペーパープロトタイプ作成',
        'user_id' => 1,
      ]);

      DB::table('manhours')->insert([
        'task_id' => '1',
        'title' => 'カレンダー実装',
        'start' => '2021-06-01 12:15',
        'end' => '2021-06-01 13:30',
        'remark' => 'vueでカレンダー実装',
        'user_id' => 1,
      ]);

      DB::table('manhours')->insert([
        'task_id' => '2',
        'title' => 'モーダル作成',
        'start' => '2021-06-02 12:15',
        'end' => '2021-06-03 13:30',
        'remark' => 'モーダル実装',
        'user_id' => 1,
      ]);
    }
}
