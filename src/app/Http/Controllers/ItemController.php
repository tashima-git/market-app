<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // トップ画面（商品一覧）
    public function index(Request $request)
    {
        $tab = $request->tab ?? null;

        $items = [
            (object)['id' => 1, 'name' => '商品A', 'price' => 1000, 'image' => ''],
            (object)['id' => 2, 'name' => '商品B', 'price' => 2000, 'image' => ''],
            (object)['id' => 3, 'name' => '商品C', 'price' => 3000, 'image' => ''],
        ];

        return view('items.index', compact('items', 'tab'));
    }

    // お気に入りタブ
    public function mylist()
    {
        $items = [
            (object)['id' => 2, 'name' => '商品B', 'price' => 2000, 'image' => ''],
        ];
        return view('items.index', compact('items'))->with('tab', 'mylist');
    }

    // 商品詳細
    public function show($id)
    {
        $item = (object)[
            'id' => $id,
            'name' => '商品'.$id,
            'brand' => 'ブランド名',
            'price' => 47000,
            'color' => 'グレー',
            'state' => '新品',
            'description' => '商品の状態は良好です。傷もありません。',
            'delivery' => '購入後、即発送いたします。',
            'categories' => ['洋服', 'メンズ'],
            'comments' => [
                ['author' => 'admin', 'text' => 'こちらにコメントが入ります。']
            ]
        ];

        return view('items.show', compact('item'));
    }

    // 商品出品画面
    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        // ダミー保存処理
        return redirect()->route('items.index');
    }
}
