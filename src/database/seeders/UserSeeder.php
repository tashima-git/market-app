<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $driver = DB::getDriverName();

        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        User::truncate();
        Profile::truncate();

        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $users = [
            [
                'name' => 'テストユーザー1',
                'email' => 'test1@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'テストユーザー2',
                'email' => 'test2@example.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'テストユーザー3',
                'email' => 'test3@example.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            // Profile も作成
            Profile::create([
                'user_id' => $user->id,
                'postal_code' => '123-4567',
                'address' => '東京都千代田区1-1-1',
                'building_name' => 'テストビル101',
            ]);
        }
    }
}
