<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Fortifyによる認証とメール認証機能を含むWebルート。
| 公開ルート・認証必須ルート・メール認証関連を分離して定義。
|
*/

// =====================================================
// メール認証関連（Fortify + 独自画面対応）
// =====================================================

// メール認証誘導画面（ログイン済みだが未認証ユーザー用）
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メール内リンククリック後の認証処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

// 認証メール再送信処理
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// =====================================================
// 商品関連（公開ルート）
// =====================================================
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('items.mylist');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');


// =====================================================
// 認証必須ルート（メール認証済みユーザーのみ）
// =====================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // 商品購入関連
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item}', [AddressController::class, 'create'])->name('purchase.address.create');
    Route::post('/purchase/address/{item}', [AddressController::class, 'store'])->name('purchase.address.store');
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');

    // 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.profile.update');

    // コメント機能
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // お気に入り機能
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});


// =====================================================
// Stripe Webhook
// =====================================================
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle'])->name('webhook.stripe');
