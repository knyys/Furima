@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form">
    <div class="profile-form__content">
        <div class="profile-form__heading">
            <h2>プロフィール設定</h2>
        </div>
        <div class="profile-form__img">
            画像を選択する
        </div>
        <div class="form__error">
            <!--error追記-->
        </div>

        <form class="form" action="/profile/first" method="post">
            @csrf
            <div class="form__group">
                <div class="form__label">
                    <label>ユーザー名</label>
                </div>
                <div class="form__content">
                    <input type="text" name="name" value="{{ old('name') }}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__label">
                    <label>郵便番号</label>
                </div>
                <div class="form__content">
                    <input type="text" name="address_number" value="{{ old('address_number') }}">
                    <!--ハイフンありの8文字-->
                </div>
            </div>
            <div class="form__group">
                <div class="form__label">
                    <label>住所</label>
                </div>
                <div class="form__content">
                    <input type="text" name="address" value="{{ old('address') }}">
                </div>
            </div>
            <div class="form__group">
                <div class="form__label">
                    <label>建物名</label>
                </div>
                <div class="form__content">
                    <input type="text" name="building" value="{{ old('building') }}">
                </div>
            </div>
            <div class="form__btn">
                <button type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>





@endsection