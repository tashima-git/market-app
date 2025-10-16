<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // テスト用ユーザーを作成
        User::create([
            'name' => 'テストユーザー1',
            'email' => 'test1@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'テストユーザー3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
