@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form">
    <div class="profile-form__content">
        <div class="profile-form__heading">
            <h2>商品一覧</h2>
        </div>
        <div class="profile-form__img">
            画像を選択する
        </div>
        <div class="form__error">
            <!--error追記-->
        </div>

@endsection