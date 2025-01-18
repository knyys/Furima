@extends('layouts.navbar')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-form">
    <div class="login-form__content">
        <div class="login-form__heading">
            <h2>ログイン</h2>
        </div>
        <form class="form" action="/login" method="post">
            @csrf
        <div class="form__group">
            <div class="form__label">
                <label>ユーザー名 / メールアドレス</label>
            </div>
            <div class="form__content">
                <input type="text" name="name" value="">
            </div>
            <div class="form__error">
                <!--error後で記述-->
            </div>
        </div>
        
        <div class="form__group">
            <div class="form__label">
                <label>パスワード</label>
            </div>
            <div class="form__content">
                <input type="password" name="password" value="">
            </div>
            <div class="form__error">
                <!--error後で記述-->
            </div>
        </div>
        
        <div class="form__btn">
            <button type="submit">ログインする</button>
        </div>
        </form>
        <div class="register-btn">
            <a href="/register">会員登録はこちら</a>
        </div>
    </div>
</div>

@endsection