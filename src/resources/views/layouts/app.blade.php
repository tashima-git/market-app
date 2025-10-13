<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="logo">
            <a href="{{ route('items.index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="coachtechロゴ" class="logo-img">
            </a>
        </div>

        {{-- ログイン・会員登録・メール認証ページでは非表示 --}}
        @if (!request()->routeIs([
            'login',
            'register',
            'verification.notice',
            'verification.send',
            'verification.verify',
        ]))
            <div class="search-container">
                @php
                    // 検索が有効なページ
                    $isSearchable =
                        request()->routeIs('items.index') ||
                        request()->routeIs('items.mylist') ||
                        request()->routeIs('mypage.index') ||
                        request()->routeIs('mypage.sales') ||
                        request()->routeIs('mypage.purchases');

                    // 現在のタブ（マイページ系の検索で必要）
                    $currentTab = request('tab', 'sell');

                    // 現在の検索キーワード
                    $currentKeyword = request('keyword', '');
                @endphp

                <form
                    action="{{ $isSearchable ? url()->current() : '#' }}"
                    method="GET"
                    onsubmit="{{ $isSearchable ? '' : 'return false;' }}"
                >
                    {{-- タブ情報をhiddenで送信 --}}
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <input
                        type="text"
                        class="search-bar"
                        name="keyword"
                        value="{{ $currentKeyword }}"
                        placeholder="なにをお探しですか？"
                        {{ $isSearchable ? '' : 'disabled' }}
                    >
                </form>
            </div>

            <div class="header-actions">
                @auth
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="header-btn logout-btn">ログアウト</button>
                    </form>

                    <a href="{{ url('/mypage') }}" class="header-btn mypage-btn">マイページ</a>
                    <a href="{{ url('/sell') }}" class="header-btn sell-btn">出品</a>
                @else
                    <a href="{{ route('login') }}" class="header-btn login-btn">ログイン</a>
                    <a href="{{ route('login') }}" class="header-btn mypage-btn">マイページ</a>
                    <a href="{{ route('login') }}" class="header-btn sell-btn">出品</a>
                @endauth
            </div>
        @endif
    </header>

    <!-- メインコンテンツ -->
    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
