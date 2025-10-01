@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1>プロフィール編集</h1>

    <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
        @csrf

        <!-- プロフィール画像 -->
        <div class="form-group avatar-group">
            <div class="avatar-wrapper">
                @if($user->profile && $user->profile->avatar)
                    <img id="avatar-preview" src="{{ asset('storage/' . $user->profile->avatar) }}" 
                         alt="プロフィール画像" width="100">
                @else
                    <img id="avatar-preview" src="{{ asset('images/default-avatar.png') }}" 
                         alt="プロフィール画像" width="100">
                @endif

                <!-- ファイル添付ボタンをラベルに変更して安定表示 -->
                <label for="avatar" class="avatar-label">画像を選択する</label>
                <input type="file" name="avatar" id="avatar" accept=".jpeg,.jpg,.png" class="avatar-input">
            </div>
        </div>

        <!-- ユーザー名 -->
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" maxlength="20" 
                   value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- 郵便番号 -->
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" maxlength="8" 
                   pattern="\d{3}-\d{4}" placeholder="123-4567"
                   value="{{ old('postal_code', $user->profile->postal_code ?? '') }}" required>
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label for="address">住所</label>
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
