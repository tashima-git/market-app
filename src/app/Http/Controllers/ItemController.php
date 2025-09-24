<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 商品一覧
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'categories', 'favoritedBy', 'comments'])
            ->where('status', 'selling'); // 出品中のみ

        // 自分の商品は非表示
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 検索（部分一致）
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => null,
        ]);
    }

    /**
     * マイリスト（お気に入り）
     */
    public function mylist(Request $request)
    {
        $items = Auth::user()
            ->favorites()
            ->with(['categories', 'favoritedBy', 'comments'])
            ->latest()
            ->get();

        return view('items.index', [
            'items' => $items,
            'tab' => 'mylist',
        ]);
    }

    /**
     * 商品詳細
     */
    public function show(Item $item)
    {
        $item->load(['user', 'categories', 'condition', 'favoritedBy', 'comments.user']);

        return view('items.show', compact('item'));
    }

    /**
     * 出品画面
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * 出品保存
     */
    public function store(ExhibitionRequest $request)
    {
        $path = null;

        // 画像アップロード
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'status' => 'selling',
            'path' => $path,
        ]);

        // カテゴリ紐付け
        if ($request->has('categories')) {
            $item->categories()->sync($request->categories);
        }

        return redirect()->route('items.index')
            ->with('success', '商品を出品しました');
    }
}
