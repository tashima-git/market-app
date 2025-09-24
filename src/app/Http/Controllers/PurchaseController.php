<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * 商品購入確認画面
     */
    public function show($item_id)
    {
        $item = Item::with(['categories', 'user'])->findOrFail($item_id);

        // ユーザー自身の商品は購入不可
        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.index')->with('error', '自分の商品は購入できません');
        }

        // ユーザーの登録住所を取得（あれば）
        $addresses = Auth::user()->profile ? [Auth::user()->profile] : [];

        return view('purchase.show', compact('item', 'addresses'));
    }

    /**
     * 商品購入処理
     */
    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // すでに購入済み
        if ($item->status === 'sold') {
            return redirect()->route('items.index')->with('error', 'すでに購入された商品です');
        }

        // 配送先住所
        $address = Address::find($request->address_id);
        if (!$address) {
            return back()->withErrors(['address_id' => '配送先住所が選択されていません']);
        }

        // 購入記録作成
        Purchase::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'payment_method' => $request->payment_method,
            'sending_postcode' => $address->postal_code,
            'sending_address' => $address->address,
            'sending_building' => $address->building_name,
        ]);

        // 商品ステータスを sold に更新
        $item->update(['status' => 'sold']);

        return redirect()->route('mypage.purchases')->with('success', '商品を購入しました');
    }
}
