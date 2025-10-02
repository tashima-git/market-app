<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    /**
     * 配送先住所登録画面表示
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View
     */
    public function create(Item $item)
    {
        $user = Auth::user();

        // 一時的にプロフィールの住所を使用
        $profile = $user->profile;

        // セッションに既に入力済みの住所があればそちらを優先
        $sessionAddress = session('purchase_address');

        $address = $sessionAddress ?? [
            'postal_code'   => $profile->postal_code ?? '',
            'address'       => $profile->address ?? '',
            'building_name' => $profile->building_name ?? '',
        ];

        return view('purchase.address', compact('item', 'address'));
    }

    /**
     * 配送先住所保存（セッションのみ）
     *
     * @param  \App\Http\Requests\AddressRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddressRequest $request, Item $item)
    {
        // DBは変更せずセッションに一時保存
        session([
            'purchase_address' => [
                'postal_code'   => $request->postal_code,
                'address'       => $request->address,
                'building_name' => $request->building_name,
            ]
        ]);

        return redirect()->route('purchase.show', ['item' => $item->id])
                         ->with('success', '配送先住所を更新しました');
    }
}
