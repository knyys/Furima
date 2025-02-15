@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form">
    <div class="profile-form__content">
        <div class="profile-form__header">
            <div class="profile__img">
                <img id="image" class="profile-icon" src="{{ asset($profile->image) }}" alt="プロフィール画像">
                <form method="POST" action="{{ route('profile.upload') }}" enctype="multipart/form-data">
                @csrf
                <output id="image" class="image_output"></output>
            </div>    
            <div class="profile__user-name">    
                <!--ユーザー名を表示できるようにする-->
                <span>{{ $user->name }}</span>
            </div>
            <div class="profile__edit">
                <a class="edit-page" href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
    </div>

    <div class="profile-form__tab-box">
        <input type="radio" id="tab_1" class="tabRadio" name="tab" checked>
        <input type="radio" id="tab_2" class="tabRadio" name="tab">

        <ul class="tab-list">
            <li class="tab">
                <label name="tab_label_1" for="tab_1">出品した商品</label>
            </li>
            <li class="tab">
                <label name="tab_label_2" for="tab_2">購入した商品</label>
            </li>
        </ul>
        <div class="tabContentList">
            <!--出品した商品タブ-->
            <article class="tab-content" id="content_1">
                <div class="items__list">
                @foreach ($items as $item)
                <div class="item">
                    <div class="item-img">
                        <img id="image" class="item-icon" src="{{ asset( 'storage/profile/' . $item->image) }}" alt="商品画像:{{ $item->name }}">
                        <output id="image" class="image_output"></output>
                    </div>
                    <span class="item-label">
                        {{ $item->name }}
                    </span>
                </div>
                @endforeach
                </div>    
            </article>

            <!--購入した商品-->
            <article class="tabContent" id="content_2">
                <div class="items__list">
                @foreach ($items as $item)
                <div class="item">
                    <div class="item-img">
                        <img id="image" class="item-icon" src="{{ asset( 'storage/' . $item->image) }}" alt="商品画像:{{ $item->name }}">
                        <output id="image" class="image_output"></output>
                    </div>
                    <span class="item-label">
                        {{ $item->name }}
                    </span>
                </div>
                @endforeach
                </div>    
            </article>   
        </div>       
    </div>
</div>

@endsection