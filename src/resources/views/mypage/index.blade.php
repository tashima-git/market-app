@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <h1>マイページ</h1>

    <section class="user-info">
        <h2>ユーザー情報</h2>
        <p>名前: 山田 太郎</p>
        <p>メール: example@example.com</p>
        <a href="{{ url('/mypage/profile') }}">プロフィール編集</a>
    </section>

    <section class="purchase-history">
        <h2>購入履歴</h2>
        <ul>
            <li>商品A - ¥47,000</li>
            <li>商品B - ¥30,000</li>
        </ul>
    </section>

    <section class="sell-history">
        <h2>出品中の商品</h2>
        <ul>
            <li>商品C - ¥25,000</li>
            <li>商品D - ¥15,000</li>
        </ul>
    </section>
</div>
@endsection
