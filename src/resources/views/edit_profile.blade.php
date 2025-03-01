@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
@endsection

@section('content')
<div class="alert">
    @if(session('success'))
        <p class="alert--success">{{ session('success') }}</p>
    @endif
</div>
<div class="profile-form">
    <div class="profile-form__content">
        <h2 class="profile-form__heading">プロフィール設定</h2>

        <div class="profile-form__img">
            <!-- 画像URLをセッションから取得して表示 -->
            <img class="icon" src="{{ session('image_url') ? session('image_url') : asset($profile->image ?? '') }}" alt="Profile Image">
            <form method="POST" action="{{ route('profile.upload') }}" enctype="multipart/form-data">
                @csrf
                <output id="image" class="image_output"></output>
                <!--画像選択ラベル-->
                <label for="image-input" class="image-label">画像を選択する</label>
                <input type="file" id="image-input" name="image" accept=".jpg, .png">
                <span id="image-name" class="image-name"></span>
            </div>
            <div class="img__error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>
            
        <div class="form__group">
            <div class="form__label">
                <label>ユーザー名</label>
            </div>
            <div class="form__content">
                <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}">
            </div>
            <div class="form__error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__label">
                <label>郵便番号</label>
            </div>
            <div class="form__content">
                <input type="text" name="address_number" value="{{ old('address_number', $profile->address_number ?? '') }}">
            </div>
            <div class="form__error">
                @error('address_number')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__label">
                <label>住所</label>
            </div>
            <div class="form__content">
                <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}">
            </div>
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__label">
                <label>建物名</label>
            </div>
            <div class="form__content">
                <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}">
            </div>
            <div class="form__error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__btn">
            <button type="submit">更新する</button>
        </div>
        </form>
    </div>
</div>
@endsection

