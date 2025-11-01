@extends('layouts.app')

@section('title', '商品購入')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
@php
    // 配送先住所
    $address = session('purchase_address', [
        'postal_code'   => auth()->user()->profile->postal_code,
        'address'       => auth()->user()->profile->address,
        'building_name' => auth()->user()->profile->building_name,
    ]);

    // 支払い方法（old() があれば優先、なければセッション値）
    $selected_method = old('payment_method', $selected_method ?? null);
@endphp

<div class="container">
    {{-- 左側 --}}
    <div class="left-section">
        {{-- 商品情報 --}}
        <div class="product-info">
            <div class="product-image">
                @if ($item->path)
                    <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->name }}">
                @else
                    <div class="placeholder">商品画像</div>
                @endif
            </div>
            <div class="product-details">
                <h2>{{ $item->name }}</h2>
                <div class="underline"></div>
                <p class="product-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        {{-- 購入フォーム --}}
        <form method="POST" action="{{ route('purchase.store', $item->id) }}">
            @csrf

            {{-- 支払い方法 --}}
            <div class="section">
                <h3>支払い方法</h3>
                <select name="payment_method" required>
                    <option value="" disabled {{ !$selected_method ? 'selected' : '' }}>選択してください</option>
                    <option value="konbini" {{ $selected_method === 'konbini' ? 'selected' : '' }}>コンビニ支払い</option>
                    <option value="card" {{ $selected_method === 'card' ? 'selected' : '' }}>カード支払い</option>
                </select>
                @error('payment_method')
                    <p class="error-text" style="color: red; margin-left: 90px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- 配送先 --}}
            <div class="section delivery-section">
                <div class="delivery-title">
                    <h3>配送先</h3>
                    <a href="{{ route('purchase.address.create', $item->id) }}" class="change-link">変更する</a>
                </div>

                <div class="delivery-info">
                    <div class="postal-row">
                        <span class="postal-icon">〒</span>
                        <span class="postal-code">{{ $address['postal_code'] }}</span>
                    </div>
                    <div class="address-text-wrapper">
                        <div class="address-text">
                            <div class="address">{{ $address['address'] }}</div>
                            <div class="building-name">{{ $address['building_name'] ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    {{-- 右側 --}}
    <div class="right-section">
        <div class="summary-box">
            <div class="summary-row">
                <span class="summary-label">商品代金</span>
                <span class="summary-value">¥{{ number_format($item->price) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">支払い方法</span>
                <span class="summary-value">
                    @switch($selected_method)
                        @case('konbini') コンビニ払い @break
                        @case('card') カード払い @break
                        @default 未選択
                    @endswitch
                </span>
            </div>
        </div>
        <button type="submit" class="purchase-button">購入する</button>
    </div>
    </form>
</div>
@endsection
