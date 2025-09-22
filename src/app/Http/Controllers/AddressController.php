<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create($item_id)
    {
        return view('purchase.address', ['item_id' => $item_id]);
    }

    public function store(Request $request, $item_id)
    {
        // ダミー保存
        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }
}
