<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\App;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    /**
     * 商品購入確認画面
     */
    public function show(Item $item)
    {
        $profile = Auth::user()->profile;

        // 配送先住所をセッション優先で取得
        $address = session('purchase_address', [
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building_name' => $profile->building_name,
        ]);

        // 前回選択した支払い方法をセッションから取得（初回ロードは null）
        $selected_method = session('purchase_payment_method', null);

        return view('purchase.show', compact('item', 'address', 'selected_method'));
    }

    /**
     * 商品購入処理
     */
    public function store(Request $request, Item $item)
    {
        // バリデーション
        $request->validate([
            'payment_method' => 'required|in:card,konbini',
        ]);

        // 選択した支払い方法をセッションに保存
        session(['purchase_payment_method' => $request->payment_method]);

        // 自分の商品は購入不可
        if ($item->user_id === Auth::id()) {
            abort(403, '自分の商品は購入できません');
        }

        // すでに購入済み
        if ($item->status === 'sold') {
            return redirect()->route('items.index')->with('error', 'すでに購入された商品です');
        }

        // 配送先住所をセッション優先で取得
        $address = session('purchase_address', [
            'postal_code' => Auth::user()->profile->postal_code,
            'address' => Auth::user()->profile->address,
            'building_name' => Auth::user()->profile->building_name,
        ]);

        // テスト環境の場合は Stripe をスキップして直接購入処理
        if (App::environment('testing')) {
            Purchase::create([
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => $request->payment_method,
                'sending_postcode' => $address['postal_code'],
                'sending_address' => $address['address'],
                'sending_building' => $address['building_name'],
            ]);

            $item->update(['status' => 'sold']);

            return redirect('/')->with('success', '商品購入が完了しました（テスト環境）');
        }

        // 本番環境では Stripe Checkout を使用
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $method = $request->payment_method === 'card' ? ['card'] : ['konbini'];

        $unitAmount = $item->price;

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => $method,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index') . '?payment=success',
            'cancel_url' => route('purchase.show', ['item' => $item->id]),
            'payment_intent_data' => [
                'metadata' => [
                    'user_id' => Auth::id(),
                    'item_id' => $item->id,
                    'payment_method' => $request->payment_method,
                    'postal_code' => $address['postal_code'],
                    'address' => $address['address'],
                    'building_name' => $address['building_name'],
                ],
            ],
        ]);

        return redirect($checkoutSession->url);
    }

    /**
     * トップページ用：Stripe決済完了時のフラッシュメッセージ
     */
    public function paymentSuccess(Request $request)
    {
        if ($request->query('payment') === 'success') {
            return redirect()->route('items.index')->with('success', '商品購入が完了しました！');
        }

        return redirect()->route('items.index');
    }
}
