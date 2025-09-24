<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'コメントを投稿しました');
    }
}
