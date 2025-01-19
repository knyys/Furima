@extends('layouts.navbar')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <div class="register-form__content">
        <div class="register-form__heading">
            <h2>会員登録</h2>
        </div>
        <form class="form" action="/register" method="post">
            @csrf
            <div class="form__group">
                <div class="form__label">
                    <label>ユーザー名</label>
                </div>
                <div class="form__content">
                    <input type="text" name="name" value="{{ old('name') }}">
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__label">
                    <label>メールアドレス</label>
                </div>
                <div class="form__content">
                    <input type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__label">
                    <label>パスワード</label>
                </div>
                <div class="form__content">
                    <input type="password" name="password" value="{{ old('password') }}">
                </div>
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__group">
                <div class="form__label">
                    <label>確認用パスワード</label>
                </div>
                <div class="form__content">
                    <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                </div>
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__btn">
                <button type="submit">登録する</button>
            </div>
        </form>
        <div class="login-btn">
            <a href="/login">ログインはこちら</a>
        </div>
    </div>
</div>

@endsection