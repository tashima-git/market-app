@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile-section">
    <div class="profile-container">
        {{-- アバター画像 --}}
        <div class="profile-avatar">
            @if(optional($user->profile)->avatar)
                <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="avatar">
            @else
                <div class="placeholder-avatar"></div>
            @endif
        </div>

        <div class="profile-info">
            <h1 class="username">{{ $user->name }}</h1>
            <a href="{{ route('mypage.profile.edit') }}" class="btn-edit-profile">プロフィールを編集</a>
        </div>
    </div>
</div>

{{-- タブリンク --}}
@php
    $activeTab = $tab ?? 'sell';
@endphp

<div class="tabs">
    <a href="{{ route('mypage.sales') }}" class="tab {{ $activeTab === 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('mypage.purchases') }}" class="tab {{ $activeTab === 'buy' ? 'active' : '' }}">購入した商品</a>
</div>

{{-- 商品一覧 --}}
<div class="products-grid">
    @if($activeTab === 'sell')
        @forelse($user->sales as $item)
            <div class="product-card">
                <a href="{{ route('items.show', $item->id) }}">
                    <div class="product-content">
                        <div class="product-image-wrapper">
                            @if($item->path)
                                <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->name }}">
                                @if($item->status === 'sold')
                                    <div class="sold-badge">SOLD</div>
                                @endif
                            @else
                                <div class="image-placeholder">商品画像</div>
                            @endif
                        </div>
                        <div class="product-name">{{ $item->name }}</div>
                        <div class="product-price">¥{{ number_format($item->price) }}</div>
                    </div>
                </a>
            </div>
        @empty
            <p class="empty-message">出品した商品はありません。</p>
        @endforelse
    @elseif($activeTab === 'buy')
        @forelse($user->purchases as $purchase)
            @php $item = $purchase->item; @endphp
            <div class="product-card">
                <a href="{{ route('items.show', $item->id) }}">
                    <div class="product-content">
                        <div class="product-image-wrapper">
                            @if($item->path)
                                <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->name }}">
                                @if($item->status === 'sold')
                                    <div class="sold-badge">sold</div>
                                @endif
                            @else
                                <div class="image-placeholder">商品画像</div>
                            @endif
                        </div>
                        <div class="product-name">{{ $item->name }}</div>
                        <div class="product-price">¥{{ number_format($item->price) }}</div>
                    </div>
                </a>
            </div>
        @empty
            <p class="empty-message">購入した商品はありません。</p>
        @endforelse
    @endif
</div>
@endsection
