<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    /**
     * マイページTOP
     * 出品・購入の両方表示、検索対応
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // タブ指定（デフォルトは出品）
        $tab = $request->input('tab', 'sell');
        $keyword = $request->input('keyword');

        if ($tab === 'buy') {
            // 購入した商品一覧
            $itemsQuery = $user->purchases()->with('item.categories');

            // 検索（商品名部分一致）
            if ($keyword) {
                $itemsQuery->whereHas('item', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            }

            // 結果を取得して item モデルだけに変換
            $items = $itemsQuery->get()->map(fn($purchase) => $purchase->item);
        } else {
            // 出品商品一覧
            $itemsQuery = $user->sales()->with('categories');

            // 検索（商品名部分一致）
            if ($keyword) {
                $itemsQuery->where('name', 'like', "%{$keyword}%");
            }

            $items = $itemsQuery->get();
        }

        return view('mypage.index', [
            'user' => $user,
            'tab' => $tab,
            'items' => $items,
            'keyword' => $keyword,
        ]);
    }

    /**
     * プロフィール編集画面
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('mypage.profile', compact('user'));
    }

    /**
     * プロフィール更新
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // アバター画像アップロード
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            if ($user->profile) {
                if ($user->profile->avatar) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }
                $user->profile->update([
                    'avatar' => $avatarPath,
                    'postal_code' => $request->postal_code,
                    'address' => $request->address,
                    'building_name' => $request->building_name,
                ]);
            } else {
                $user->profile()->create([
                    'avatar' => $avatarPath,
                    'postal_code' => $request->postal_code,
                    'address' => $request->address,
                    'building_name' => $request->building_name,
                ]);
            }
        } else {
            // 画像なし更新
            if ($user->profile) {
                $user->profile->update([
                    'postal_code' => $request->postal_code,
                    'address' => $request->address,
                    'building_name' => $request->building_name,
                ]);
            } else {
                $user->profile()->create([
                    'postal_code' => $request->postal_code,
                    'address' => $request->address,
                    'building_name' => $request->building_name,
                ]);
            }
        }

        // ユーザー名更新
        $user->update(['name' => $request->name]);

        // 更新後はマイページにリダイレクト
        return redirect()->route('mypage.index')
                         ->with('success', 'プロフィールを更新しました');
    }
}
