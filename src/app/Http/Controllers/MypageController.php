<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;

class MypageController extends Controller
{
    /**
     * マイページTOP
     */
    public function index()
    {
        $user = Auth::user()->load([
            'profile',
            'purchases.item.categories',
            'sales'
        ]);

        return view('mypage.index', [
            'user' => $user,
            'tab' => null,
        ]);
    }

    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = Auth::user()->load('profile');
        return view('mypage.profile', compact('user'));
    }

    /**
     * プロフィール更新
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // 画像アップロード
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            if ($user->profile) {
                // 古い画像を削除
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

        // 更新後はトップページにリダイレクト
        return redirect()->route('items.index')->with('success', 'プロフィールを更新しました');
    }

    /**
     * 購入した商品一覧
     */
    public function purchases()
    {
        $user = Auth::user()->load('purchases.item.categories');

        return view('mypage.index', [
            'user' => $user,
            'tab' => 'buy',
        ]);
    }

    /**
     * 出品した商品一覧
     */
    public function sales()
    {
        $user = Auth::user()->load('sales.categories');

        return view('mypage.index', [
            'user' => $user,
            'tab' => 'sell',
        ]);
    }
}
