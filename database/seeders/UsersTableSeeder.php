<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;

final class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(DatabaseManager $manager, Hasher $hasher)
    {
      $datatime = Carbon::now()->toDateTimeString();
      $userId = $manager->table('users')->insertGetId([
        'name' => 'laravel user',
        'email' => 'mail@gmail.com',
        'password' => $hasher->make('password'),
        'created_at' => $datatime,
      ]);

      $manager->table('user_tokens')->insert([
        'user_id' => $userId,
        'api_token' => 'aaaaa',
        'created_at' => $datatime,
      ]);
    }
}