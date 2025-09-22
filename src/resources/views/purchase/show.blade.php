@extends('layouts.app')

@section('title', '商品購入')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <h1>商品購入</h1>
    <div class="product-summary">
        <div class="product-name">商品名: 商品A</div>
        <div class="product-price">価格: ¥47,000</div>
        <div class="product-image">画像表示</div>
    </div>

    <a href="{{ url('/purchase/address') }}" class="purchase-btn">購入手続きへ</a>
</div>
@endsection
