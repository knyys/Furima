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
                <span>{{ $user->name }}</span>
            </div>
            <div class="profile__edit">
                <a class="edit-page" href="/mypage/profile">プロフィールを編集</a>
            </div>
        </div>
    </div>

    @php
        $currentPage = request('page', 'sell'); 
    @endphp

    <div class="profile-form__tab-box">
        <ul class="tab-list">
            <li class="tab">
                <a href="{{ route('mypage', ['page' => 'sell']) }}" class="{{ $currentPage == 'sell' ? 'active' : '' }}">
                    出品した商品
                </a>   
            </li>
            <li class="tab">
                <a href="{{ route('mypage', ['page' => 'buy']) }}" class="{{ $currentPage == 'buy' ? 'active' : '' }}">
                    購入した商品
                </a>
            </li>
        </ul>

        <div class="tabContentList">
            @if ($currentPage == 'sell')
            <article class="tab-content active" id="content_1">
                <div class="items__list">
                @foreach ($userItems as $item)
                <div class="item">
                    <div class="item-img">
                        <a href="{{ route('item.detail', ['item' => $item->id]) }}">
                        <img class="item-icon" src="{{ asset('storage/' . $item->image) }}" alt="商品画像:{{ $item->name }}">
                        </a>
                    </div>
                   
                    <!--Sold-->
                    @if ($item->is_sold)
                    <div class="item--sold">
                        <span class="sold-label">Sold</span>
                    </div>
                    @endif
                    <!--Sold-->
                    <span class="item-label">{{ $item->name }}</span>
                </div>
                @endforeach
                </div>    
            </article>
            @endif

            @if ($currentPage == 'buy')
            <article class="tab-content active" id="content_2">
                <div class="items__list">
                @foreach ($purchasedItems as $item)
                <div class="item">
                    <div class="item-img">
                        <a href="{{ route('item.detail', ['item' => $item->id]) }}">
                        <img class="item-icon" src="{{ asset('storage/' . $item->image) }}" alt="商品画像:{{ $item->name }}">
                        </a>
                    </div>
                        
                    <span class="item-label">{{ $item->name }}</span>
                </div>
                @endforeach
                </div>    
            </article>
            @endif  
        </div>       
    </div>
</div>
@endsection