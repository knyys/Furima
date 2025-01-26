@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('nav')
<form class="logout-btn" action="{{ route('logout') }}" method="POST">
    @csrf
<button type="submit">ログイン</button>
</form>
@endsection

@section('content')
<div class="item-list">

<div class="tab-box">
    <input type="radio" id="tab_1" class="tabRadio" name="tab" checked>
    <input type="radio" id="tab_2" class="tabRadio" name="tab">

    <ul class="tab-list">
        <li class="tab">
            <label name="tab_label_1" for="tab_1">おすすめ</label>
        </li>
        <li class="tab">
            <label name="tab_label_2" for="tab_2">マイリスト</label>
        </li>
    </ul>
    <div class="tabContentList">
        <article class="tabContent active" id="content_1">
            <p>商品一覧</p>
        </article>
        <article class="tabContent" id="content_2">
            <p>マイページ商品一覧</p>
        </article>
        
    </div>
</div>


@endsection