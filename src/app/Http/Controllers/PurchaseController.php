<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    /**
     * 商品購入確認画面
     */
    public function show(Item $item)
    {
        $profile = Auth::user()->profile;

        // セッションに一時保存された住所があれば優先
        $address = session('purchase_address', [
            'postal_code'   => $profile->postal_code,
            'address'       => $profile->address,
            'building_name' => $profile->building_name,
        ]);

        return view('purchase.show', compact('item', 'address'));
    }

    /**
     * 商品購入処理（Stripe Checkoutへリダイレクト）
     */
    public function store(Request $request, Item $item)
    {
        // 自分の商品は購入不可
        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.index')
                ->with('error', '自分の商品は購入できません');
        }

        // すでに購入済み
        if ($item->status === 'sold') {
            return redirect()->route('items.index')
                ->with('error', 'すでに購入された商品です');
        }

        // セッションまたはプロフィールから住所取得
        $address = session('purchase_address', [
            'postal_code'   => Auth::user()->profile->postal_code,
            'address'       => Auth::user()->profile->address,
            'building_name' => Auth::user()->profile->building_name,
        ]);

        // Stripe APIキー設定（テストキー）
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Stripe Checkout セッション作成
        $checkoutSession = CheckoutSession::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index') . '?payment=success',
            'cancel_url' => route('purchase.show', ['item' => $item->id]),
            'metadata' => [
                'user_id' => Auth::id(),
                'item_id' => $item->id,
                'payment_method' => $request->payment_method,
                'postal_code' => $address['postal_code'],
                'address' => $address['address'],
                'building_name' => $address['building_name'],
            ],
        ]);

        // Stripe Checkoutページへリダイレクト
        return redirect($checkoutSession->url);
    }

    /**
     * トップページ用：Stripe決済完了時のフラッシュメッセージ
     */
    public function paymentSuccess(Request $request)
    {
        if ($request->query('payment') === 'success') {
            return redirect()->route('items.index')
                ->with('success', '商品購入が完了しました！');
        }

        return redirect()->route('items.index');
    }
}
