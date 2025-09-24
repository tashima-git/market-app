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

        $exists = $user->favorites()->where('item_id', $item->id)->exists();

        if ($exists) {
            $user->favorites()->detach($item->id);
            $message = 'お気に入りを解除しました';
        } else {
            $user->favorites()->attach($item->id);
            $message = 'お気に入りに追加しました';
        }

        return back()->with('success', $message);
    }
}
