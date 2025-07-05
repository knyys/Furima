@extends('layouts.navbar')

@section('css')
<link rel="stylesheet" href="{{ asset('css/email.css') }}">
@endsection

@section('content')
<div class="container">
    <p>登録していただいたメールアドレスに認証メールを送信しました。<br>メール認証を完了してください。</p>

    <button type="submit" class="auth">
        <a href="https://mailtrap.io/home">認証はこちらから</a>
    </button>

    <form class="auth__retry" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="auth__retry button">認証メールを再送する</button>
    </form>
</div>
@endsection
