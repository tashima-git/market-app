@extends('layouts.app')

@section('title', '商品出品')

@section('head')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h1>商品を出品する</h1>

    <form method="POST" action="#" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" placeholder="例：Tシャツ">
        </div>
        <div class="form-group">
            <label for="price">価格</label>
            <input type="number" name="price" id="price" placeholder="例：1000">
        </div>
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image">
        </div>
        <div class="form-group">
            <label for="category">カテゴリー</label>
            <select name="category" id="category">
                <option value="">選択してください</option>
                <option value="clothes">洋服</option>
                <option value="shoes">靴</option>
            </select>
        </div>
        <button type="submit" class="sell-btn">出品する</button>
    </form>
</div>
@endsection
