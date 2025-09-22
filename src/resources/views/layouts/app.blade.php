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

        <div class="search-container">
            <input type="text" class="search-bar" placeholder="なにをお探しですか？">
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
    </header>

    <!-- メインコンテンツ -->
    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
