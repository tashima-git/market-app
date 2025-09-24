@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-container">
    <h1>ログイン</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email">
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password">
        </div>

        <button type="submit" class="auth-btn">ログイン</button>
    </form>

    <a href="{{ route('register') }}">会員登録はこちら</a>
</div>
@endsection
