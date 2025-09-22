@extends('layouts.app')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <h1>会員登録</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" placeholder="ユーザー名を入力">
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" placeholder="example@example.com">
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" placeholder="8文字以上">
        </div>

        <div class="form-group">
            <label for="password_confirmation">確認用パスワード</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="確認用パスワード">
        </div>

        <button type="submit" class="auth-btn">登録する</button>
    </form>

    <a href="{{ route('login') }}">ログインはこちら</a>
</div>
@endsection
