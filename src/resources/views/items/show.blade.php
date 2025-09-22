@extends('layouts.app')

@section('title', '購入画面')

@section('head')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <h1>購入確認</h1>

    <div class="product-summary">
        <div class="product-image">商品画像</div>
        <div>
            <div class="product-name">{{ $item->name ?? '商品A' }}</div>
            <div class="product-price">{{ number_format($item->price ?? 47000) }}円</div>
        </div>
    </div>

    <form method="POST" action="#">
        @csrf
        <div class="form-group">
            <label for="address">配送先住所</label>
            <input type="text" id="address" name="address" placeholder="例：東京都渋谷区...">
        </div>
        <button type="submit" class="purchase-btn">購入する</button>
    </form>
</div>
@endsection
