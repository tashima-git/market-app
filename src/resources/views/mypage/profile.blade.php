@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1>プロフィール編集</h1>

    {{-- novalidate を追加：ブラウザの標準バリデーション無効化 --}}
    <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- バリデーションエラー表示 -->
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- プロフィール画像 -->
        <div class="form-group avatar-group">
            <div class="avatar-wrapper">
                @if($user->profile && $user->profile->avatar)
                    <img id="avatar-preview" src="{{ asset('storage/' . $user->profile->avatar) }}" 
                         alt="プロフィール画像" width="100">
                @else
                    <div class="avatar-placeholder"></div>
                @endif

                <!-- ファイル添付ボタンをラベルに変更して安定表示 -->
                <label for="avatar" class="avatar-label">画像を選択する</label>
                <input type="file" name="avatar" id="avatar" accept=".jpeg,.jpg,.png" class="avatar-input">
            </div>
            @error('avatar')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <!-- ユーザー名 -->
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" maxlength="20" 
                   value="{{ old('name', $user->name) }}">
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 郵便番号 -->
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" maxlength="8" 
                   value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
            @error('postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" 
                   value="{{ old('address', $user->profile->address ?? '') }}">
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 建物名（任意） -->
        <div class="form-group">
            <label for="building_name">建物名</label>
            <input type="text" name="building_name" id="building_name"
                   value="{{ old('building_name', $user->profile->building_name ?? '') }}">
            @error('building_name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-btn">更新する</button>
    </form>
</div>
@endsection
