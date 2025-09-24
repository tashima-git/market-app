<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
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
Route::get('/mylist', [ItemController::class, 'mylist'])->middleware('auth')->name('items.mylist'); // PG02 マイリスト
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');      // PG05 商品詳細

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
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');   // PG06
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item}', [AddressController::class, 'create'])->name('purchase.address.create'); // PG07
    Route::post('/purchase/address/{item}', [AddressController::class, 'store'])->name('purchase.address.store');

    // 商品出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');  // PG08
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');              // PG09
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit'); // PG10
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.profile.update');

    // マイページのタブ切り替えはクエリパラメータで統一
    Route::get('/mypage/purchases', [MypageController::class, 'purchases'])->name('mypage.purchases'); // PG11
    Route::get('/mypage/sales', [MypageController::class, 'sales'])->name('mypage.sales');           // PG12

    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');

    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
