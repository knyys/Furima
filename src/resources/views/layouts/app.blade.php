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
        <div class="header__inner">
            <img src="{{ asset('storage/logo.svg') }}" alt="logo">
        </div>           
        <div class="header__search-form">
            <form class="search-form" action="" method="">
            <input type="text" name="search-form" value="なにをお探しですか？" />
            </form>
        </div>
        <div class="header__nav">
            <form class="logout-btn" action="" method="">
            <button type="submit">ログアウト</button>
            </form>
        </div>
        <div class="header__nav">
            <a class="mypage-btn" href="">マイページ</a>
            <a class="sell-btn" href="">出品</a>
        </div>
    </header>

    <main>
      @yield('content')
    </main>
</body>

</html>