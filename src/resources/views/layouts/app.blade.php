<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
      @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('storage/logo.svg') }}" alt="logo">
            </a>
        </div>
        <div class="header__inner">
            <div class="header__search-form">
                <form class="search-form" action="{{ route('home') }}" method="get">
                <input type="text" name="item_name"  value="{{ request('item_name') }}" placeholder="なにをお探しですか？" />
                <input type="hidden" name="page" value="{{ request('page') }}">
                </form>
            </div>
            <div class="header__nav">
                <div class="nav__btn">
                    @if (Auth::check())
                    <form class="logout-btn" action="{{ route('logout') }}" method="POST">
                        @csrf
                    <button type="submit">ログアウト</button>
                    </form>
                    @else
                    <a class="login-btn" href="/login">ログイン</a>
                    @endif 
                </div>
                <div class="nav__btn--mypage">
                    <a class="mypage-btn" href="{{ route('mypage') }}">マイページ</a>
                </div>
                <div class="nav__btn--sell">
                    <a class="sell-btn" href="/sell">出品</a>
                </div>
            </div>
        </div>
    </header>

    <main>
      @yield('content')
      @yield('js')
    </main>
</body>

</html>