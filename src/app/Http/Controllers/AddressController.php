<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Item;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    /**
     * 配送先住所登録画面
     */
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $addresses = Auth::user()->addresses()->get();

        return view('purchase.address', compact('item', 'addresses'));
    }

    /**
     * 配送先住所保存
     */
    public function store(AddressRequest $request, $item_id)
    {
        $user = Auth::user();

        // 新しい配送先を作成
        $address = $user->addresses()->create([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building_name' => $request->building_name,
        ]);

        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('success', '配送先住所を保存しました');
    }
}
