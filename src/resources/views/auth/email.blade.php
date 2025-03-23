@extends('layouts.navbar')

@section('content')
<div class="container">
    <h1>メール認証が必要です</h1>
    <p>登録したメールアドレスに認証リンクを送信しました。</p>
    <p>メールが届かない場合は、下のボタンから再送信できます。</p>

    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">認証メールを再送信</button>
    </form>
</div>
@endsection
