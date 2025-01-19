<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
      <div class="header__inner">
        <img src="{{ asset('storage/logo.svg') }}" alt="logo">
      </div>      
    </header>

    <main>
    @yield('content')
    </main>

</body>

</html>