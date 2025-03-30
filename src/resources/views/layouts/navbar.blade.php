<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>COACHTECH</title>
<<<<<<< HEAD
        <link rel="stylesheet" href="{{ secure_asset('css/sanitize.css') }}" />
        <link rel="stylesheet" href="{{ secure_asset('css/navbar.css') }}" />
=======
        <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/navbar.css') }}" />
>>>>>>> origin/main
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('css')
    </head>
    <body>
        <header class="header">
          <div class="header__inner">
            <a href="{{ Auth::check() ? route('home', ['page' => 'mylist']) : route('home') }}">
            <img src="{{ asset('storage/logo.svg') }}" alt="logo">
            </a>
          </div>      
        </header>
        <main>
        @yield('content')
        </main>
    </body>
</html>