@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1>プロフィール編集</h1>

    <form method="POST" action="#">
        @csrf
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="Yu Tashima">
        </div>
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="example@example.com">
        </div>
        <button type="submit" class="auth-btn">更新する</button>
    </form>
</div>
@endsection
