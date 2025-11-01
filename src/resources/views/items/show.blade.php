@extends('layouts.app')

@section('title', '商品詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="container">
    {{-- 商品画像 --}}
    <div class="product-image">
        @if ($item->path)
            {{-- 売り切れ時ラベル --}}
            @if ($item->purchases()->exists())
                <div class="sold-badge">sold</div>
            @endif

            <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->name }}" class="product-main-image">
        @else
            <div class="image-placeholder">商品画像</div>
        @endif
    </div>

    <div class="product-details">
        {{-- 商品名 & ブランド --}}
        <h1 class="product-title">{{ $item->name }}</h1>
        @if ($item->brand)
            <div class="brand-name">{{ $item->brand }}</div>
        @endif

        {{-- 価格 --}}
        <div class="price">
            ¥{{ number_format($item->price) }}
            <span class="price-tax">(税込)</span>
        </div>

        {{-- アクションアイコン --}}
        <div class="action-icons">
            {{-- お気に入りボタン --}}
            @auth
                <form method="POST" action="{{ route('favorites.toggle', $item->id) }}">
                    @csrf
                    <button type="submit" class="icon-button {{ auth()->user()->favoriteItems->contains($item->id) ? 'favorited' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span class="favorite-count">{{ $item->favoritedBy->count() }}</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="icon-button">
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span class="favorite-count">{{ $item->favoritedBy->count() }}</span>
                </a>
            @endauth

            {{-- コメント数 --}}
            <button class="icon-button">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 11.5C21 16.75 16.97 21 12 21C10.73 21 9.5 20.75 8.36 20.28L3 21L4.09 16.43C3.38 15.14 3 13.64 3 12C3 7.03 7.03 3 12 3C16.97 3 21 6.75 21 11.5Z"/>
                </svg>
                <span>{{ $item->comments->count() }}</span>
            </button>
        </div>

        {{-- 購入ボタン --}}
        @auth
            @if ($item->isSold() || $item->user_id === auth()->id())
                {{-- 売り切れ時：ボタン無効 --}}
                <button class="purchase-button sold-out" disabled>購入手続きへ</button>
            @else
                {{-- 通常購入 --}}
                <a href="{{ route('purchase.show', $item->id) }}">
                    <button class="purchase-button">購入手続きへ</button>
                </a>
            @endif
        @else
            {{-- 未ログイン時 --}}
            <a href="{{ route('login') }}">
                <button class="purchase-button">購入手続きへ</button>
            </a>
        @endauth

        {{-- 商品説明 --}}
        <div class="description">
            <h2 class="section-title">商品説明</h2>
            <p>{{ $item->description }}</p>
        </div>

        {{-- 商品情報 --}}
        <div class="product-info">
            <h2 class="section-title">商品の情報</h2>
            <div class="info-row">
                <div class="info-label">カテゴリー</div>
                <div class="info-value">
                    @foreach($item->categories as $category)
                        <span class="tag">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">商品の状態</div>
                <div class="info-value">{{ $item->condition->name }}</div>
            </div>
        </div>

        {{-- コメント欄 --}}
        <div class="comments-section">
            <div class="comment-count">コメント ({{ $item->comments->count() }})</div>

            @foreach($item->comments as $comment)
                <div class="comment">
                    {{-- アバター画像 --}}
                    <div class="comment-avatar">
                        @if ($comment->user->profile && $comment->user->profile->avatar)
                            <img src="{{ asset('storage/' . $comment->user->profile->avatar) }}" alt="{{ $comment->user->name }}">
                        @else
                            <div class="placeholder-avatar"></div>
                        @endif
                    </div>

                    {{-- コメント内容 --}}
                    <div class="comment-content">
                        <div class="comment-author">{{ $comment->user->name }}</div>
                        <div class="comment-text">{{ $comment->comment }}</div>
                    </div>
                </div>
            @endforeach

            {{-- コメント投稿フォーム --}}
            <div class="comment-form">
                <h3 class="section-title">商品へのコメント</h3>
                <form method="POST" action="{{ route('comments.store', $item->id) }}">
                    @csrf
                    <textarea name="comment" placeholder="コメントを入力">{{ old('comment') }}</textarea>
                    
                    @auth
                        <button type="submit" class="submit-button">コメントを送信する</button>
                    @else
                        <a href="{{ route('login') }}" class="submit-button" style="display:block;text-align:center;text-decoration:none;">
                            コメントを送信する
                        </a>
                    @endauth
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
