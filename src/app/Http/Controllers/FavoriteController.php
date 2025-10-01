<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle($item_id)
{
    $item = Item::findOrFail($item_id);
    $user = Auth::user();

    $exists = $user->favoriteItems()->where('item_id', $item->id)->exists();

    if ($exists) {
        $user->favoriteItems()->detach($item->id);
        $message = 'お気に入りを解除しました';
    } else {
        $user->favoriteItems()->attach($item->id);
        $message = 'お気に入りに追加しました';
    }

    return back()->with('success', $message);
}

}
