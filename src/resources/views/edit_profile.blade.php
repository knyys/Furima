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
            <img id="image" class="profile-icon" src="{{ asset('storage/profile/default.jpg') }}" alt="プロフィール画像">
            <form method="POST" action="{{ route('profile.upload') }}" enctype="multipart/form-data">
                @csrf
            <output id="image" class="image_output"></output>
            <input type="file" id="image-input" name="image" accept="image/*">
        </div>
            画像を選択する
        
        <div class="form__error">
            <!--error追記-->
        </div>

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
<script>
        document.getElementById('image-input').addEventListener('change', function(event) {
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-image').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
    </script>

