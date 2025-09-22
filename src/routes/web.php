<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MypageController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// ----------------------
// 商品関連
// ----------------------
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('items.show');

// ----------------------
// 会員登録
// ----------------------
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// ----------------------
// ログイン・ログアウト
// ----------------------
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ----------------------
// 商品購入関連（認証必須）
// ----------------------
Route::middleware('auth')->group(function () {
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    Route::get('/purchase/address/{item_id}', [AddressController::class, 'create'])->name('purchase.address.create');
    Route::post('/purchase/address/{item_id}', [AddressController::class, 'store'])->name('purchase.address.store');

    // 出品
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    // マイページ関連
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.profile.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.profile.update');

    Route::get('/mypage?page=buy', [MypageController::class, 'purchases'])->name('mypage.purchases');
    Route::get('/mypage?page=sell', [MypageController::class, 'sales'])->name('mypage.sales');
});
