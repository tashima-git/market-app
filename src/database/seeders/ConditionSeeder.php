<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Condition;

class ConditionSeeder extends Seeder
{
    public function run()
    {
        // SQLite では外部キー制約を無効化する SQL は不要
        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い',
        ];

        foreach ($conditions as $name) {
            Condition::firstOrCreate(['name' => $name]);
        }
    }
}
