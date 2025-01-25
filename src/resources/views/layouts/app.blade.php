<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
      @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__logo">
                <img src="{{ asset('storage/logo.svg') }}" alt="logo">
        </div>
        <div class="header__inner">
            <div class="header__search-form">
                <form class="search-form" action="" method="">
                <input type="text" name="search-form" value="なにをお探しですか？" />
                </form>
            </div>
            <div class="header__nav">
                <div class="nav__btn">
                    @yield('nav')   
                </div>
                <div class="nav__btn--mypage">
                    <a class="mypage-btn" href="">マイページ</a>
                </div>
                <div class="nav__btn--sell">
                    <a class="sell-btn" href="">出品</a>
                </div>
            </div>
        </div>
    </header>

    <main>
      @yield('content')
    </main>
</body>

</html>