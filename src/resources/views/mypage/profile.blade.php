@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1>プロフィール編集</h1>

    <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
        @csrf
        <!-- ユーザー名 -->
        <div class="form-group">
            <label for="name">ユーザー名 <span class="required">*</span></label>
            <input type="text" name="name" id="name" maxlength="20" value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- メールアドレス（編集不可） -->
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" readonly>
        </div>

        <!-- プロフィール画像 -->
        <div class="form-group">
            <label for="avatar">プロフィール画像 (.jpeg/.png)</label>
            @if($user->profile && $user->profile->avatar)
                <div class="current-avatar">
                    <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="プロフィール画像" width="100">
                </div>
            @endif
            <input type="file" name="avatar" id="avatar" accept=".jpeg,.jpg,.png">
        </div>

        <!-- 郵便番号 -->
        <div class="form-group">
            <label for="postal_code">郵便番号 <span class="required">*</span></label>
            <input type="text" name="postal_code" id="postal_code" maxlength="8" pattern="\d{3}-\d{4}" placeholder="123-4567"
                   value="{{ old('postal_code', $user->profile->postal_code ?? '') }}" required>
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label for="address">住所 <span class="required">*</span></label>
            <input type="text" name="address" id="address"
                   value="{{ old('address', $user->profile->address ?? '') }}" required>
        </div>

        <!-- 建物名（任意） -->
        <div class="form-group">
            <label for="building_name">建物名</label>
            <input type="text" name="building_name" id="building_name"
                   value="{{ old('building_name', $user->profile->building_name ?? '') }}">
        </div>

        <button type="submit" class="auth-btn">更新する</button>
    </form>
</div>
@endsection
