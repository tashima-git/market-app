<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\StripeWebhookController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ----------------------
// 商品関連
// ----------------------
Route::get('/', [ItemController::class, 'index'])->name('items.index');              // PG01 トップ画面
Route::get('/mylist', [ItemController::class, 'mylist'])->name('items.mylist');     // PG02 マイリスト
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');    // PG05 商品詳細

// ----------------------
// 会員登録
// ----------------------
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register'); // PG03
Route::post('/register', [RegisteredUserController::class, 'store']);

// ----------------------
// 認証必須ルート
// ----------------------
Route::middleware('auth')->group(function () {

    // 商品購入関連
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');   
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item}', [AddressController::class, 'create'])->name('purchase.address.create'); 
    Route::post('/purchase/address/{item}', [AddressController::class, 'store'])->name('purchase.address.store');
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');

    // 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');  
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // マイページ（タブ切替はクエリパラメータ tab=sell / tab=buy で統一）
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');              
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit'); 
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.profile.update');

    // コメント
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    // お気に入り
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Stripe Webhook
Route::post('/webhook/stripe', [StripeWebhookController::class, 'handle']);
