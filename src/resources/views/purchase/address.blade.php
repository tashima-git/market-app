@extends('layouts.app')

@section('title', '住所変更')

@section('head')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1>住所変更</h1>

    <form method="POST" action="#">
        @csrf
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" placeholder="例：150-0001">
        </div>
        <div class="form-group">
            <label for="prefecture">都道府県</label>
            <input type="text" id="prefecture" name="prefecture" placeholder="例：東京都">
        </div>
        <div class="form-group">
            <label for="city">市区町村</label>
            <input type="text" id="city" name="city" placeholder="例：渋谷区">
        </div>
        <div class="form-group">
            <label for="street">番地</label>
            <input type="text" id="street" name="street" placeholder="例：1-2-3">
        </div>
        <button type="submit" class="purchase-btn">更新する</button>
    </form>
</div>
@endsection
