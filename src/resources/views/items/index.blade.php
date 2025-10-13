@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="tabs">
    <a href="{{ route('items.index') }}" class="tab {{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>
    <a href="{{ route('items.mylist') }}" class="tab {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div class="products-grid">
    @forelse($items as $item)
        <div class="product-card">
            <a href="{{ route('items.show', ['item' => $item->id]) }}">
                <div class="product-content">
                    <div class="product-image-wrapper">
                        @if($item->path)
                            <img src="{{ asset('storage/'.$item->path) }}" alt="{{ $item->name }}">
                        @else
                            画像なし
                        @endif
                        @if($item->status === 'sold')
                            <div class="sold-badge">sold</div>
                        @endif
                    </div>
                    <div class="product-name">{{ $item->name }}</div>
                </div>
            </a>
        </div>
    @empty
        <p>商品はまだ登録されていません。</p>
    @endforelse
</div>

@endsection
