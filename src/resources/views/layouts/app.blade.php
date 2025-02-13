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
            <a href="/">
                <img src="{{ asset('storage/logo.svg') }}" alt="logo">
            </a>
        </div>
        <div class="header__inner">
            <div class="header__search-form">
                <form class="search-form" action="{{ route('home') }}" method="get">
                <input type="text" name="item_name"  value="{{ request('item_name') }}" placeholder="なにをお探しですか？" />
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
    </main>
</body>
<script>
    document.getElementById('image-input').addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.icon').src = e.target.result; 
            }
            reader.readAsDataURL(file);

            // 画像名の表示
            document.getElementById('image-name').textContent = file.name;
        } else {
            document.getElementById('image-name').textContent = '';
        }
        // 画像選択ラベルを非表示
            document.querySelector('.image-label').style.display = 'none';
    });
</script>

</html>