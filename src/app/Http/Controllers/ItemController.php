<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 商品一覧
     * 
     * 売り切れも含めて表示可能に変更
     */
    public function index(Request $request)
    {
        $query = Item::with(['user', 'categories', 'favoritedBy', 'comments']);

        // 自分の商品は非表示
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 🔍 検索（部分一致）
        $keyword = $request->input('keyword');
        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // 出品中と売り切れを両方取得
        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => null,
            'keyword' => $keyword ?? '',
        ]);
    }

    /**
     * マイリスト（お気に入り）
     */
    public function mylist(Request $request)
    {
        $keyword = $request->input('keyword');

        if (Auth::check()) {
            $items = Auth::user()
                ->favoriteItems()
                ->with(['categories', 'favoritedBy', 'comments'])
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                })
                ->latest()
                ->get();
        } else {
            $items = collect(); // 空のコレクション
        }

        return view('items.index', [
            'items' => $items,
            'tab' => 'mylist',
            'keyword' => $keyword ?? '',
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
        $conditions = Condition::all();
        return view('items.create', compact('categories', 'conditions'));
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
            'status' => 'selling', // 出品時は常に selling
            'path' => $path,
        ]);

        // カテゴリ紐付け
        if ($request->has('categories')) {
            $item->categories()->sync($request->categories);
        }

        return redirect()->route('items.index')
            ->with('success', '商品を出品しました');
    }

    /**
     * 商品ステータスを売り切れに変更
     * (購入後などに使用)
     */
    public function markSold(Item $item)
    {
        $item->update(['status' => 'sold']);

        return redirect()->route('items.show', $item)
            ->with('success', '商品を売り切れにしました');
    }
}
