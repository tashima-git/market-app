@extends('layouts.app')

@section('title', 'メール認証完了')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<main class="verify-main">
    <div class="verify-content">
        {{-- 認証完了メッセージ --}}
        <p class="verify-message">
            メール認証が完了しました。<br>
            プロフィール設定画面に進んでください。
        </p>

        {{-- プロフィール設定画面へのボタン --}}
        <a href="{{ route('mypage.profile.edit') }}" class="verify-button">
            プロフィール設定へ
        </a>
    </div>
</main>
@endsection
