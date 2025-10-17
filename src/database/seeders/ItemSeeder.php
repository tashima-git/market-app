<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Condition;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $driver = DB::getDriverName();

        // 外部キー制約を無効化（SQLiteではサポートされないため条件分岐）
        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        Item::truncate();

        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Condition の name => id マップを作成（予約語対応）
        $conditions = Condition::pluck('id', 'name')->toArray();

        // 商品データ
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'path' => 'items/ArmaniMensClock.jpg',
                'condition_id' => $conditions['良好'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'path' => 'items/HDDHardDisk.jpg',
                'condition_id' => $conditions['目立った傷や汚れなし'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'path' => 'items/iLoveIMGd.jpg',
                'condition_id' => $conditions['やや傷や汚れあり'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'path' => 'items/LeatherShoesProductPhoto.jpg',
                'condition_id' => $conditions['状態が悪い'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'path' => 'items/LivingRoomLaptop.jpg',
                'condition_id' => $conditions['良好'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'path' => 'items/MusicMic4632231.jpg',
                'condition_id' => $conditions['目立った傷や汚れなし'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'path' => 'items/Pursefashionpocket.jpg',
                'condition_id' => $conditions['やや傷や汚れあり'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => 'なし',
                'description' => '使いやすいタンブラー',
                'path' => 'items/Tumblersouvenir.jpg',
                'condition_id' => $conditions['状態が悪い'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'path' => 'items/WaitresswithCoffeeGrinder.jpg',
                'condition_id' => $conditions['良好'] ?? 1,
                'status' => 'active',
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'path' => 'items/MakeupSet.jpg',
                'condition_id' => $conditions['目立った傷や汚れなし'] ?? 1,
                'status' => 'active',
            ],
        ];

        // 登録処理
        foreach ($items as $item) {
            $item['user_id'] = rand(1, 3); // 1～3のランダムユーザー
            Item::create($item);
        }
    }
}
