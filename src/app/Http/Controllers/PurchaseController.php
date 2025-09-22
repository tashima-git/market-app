<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = (object)['id' => $item_id, 'name' => '商品'.$item_id, 'price' => 47000];
        return view('purchase.show', compact('item'));
    }

    public function store(Request $request, $item_id)
    {
        // ダミー購入処理
        return redirect()->route('mypage.index');
    }
}
