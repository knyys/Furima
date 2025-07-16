@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="alert">
    @if(session('success'))
    <div class="alert-success">
        <p>{{ session('success') }}</p>
    </div>
    @endif
</div>
@if ($purchaseCompleted)
    <div class="alert alert-success">
        購入が完了しました！
    </div>
@endif

<div class="profile-form">
    <div class="profile-form__content">
        <div class="profile-form__header">
            <div class="profile__img">
                <img id="image" class="profile-icon" src="{{ asset($profile->image) }}" alt="プロフィール画像">
                <output id="image" class="image_output"></output>
            </div>    
            <div class="profile__user-name">    
                {{ $user->name }}
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
            <li class="tab">
                <a href="{{ route('mypage', ['page' => 'chat']) }}" class="{{ $currentPage == 'chat' ? 'active' : '' }}">
                    取引中の商品
                    @if($unreadCount > 0)
                        <span class="badge">{{ $unreadCount }}</span>
                    @endif
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
                            <!--Sold-->
                            @if ($item->is_sold)
                            <div class="item--sold">
                                <span class="sold-label">Sold</span>
                            </div>
                            @endif
                        </a>
                    </div>
                   
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
            
            @if ($currentPage == 'chat')
            <article class="tab-content active" id="content_3">
                <div class="items__list">
                @foreach ($tradingItems as $item)
                <div class="item">
                    <div class="item-img">
                        <a href="{{ route('chatView', ['item' => $item->id]) }}">
                        @if($unreadByItem->has($item->id))
                            <span class="unread-badge">{{ $unreadByItem[$item->id] }}</span>
                        @endif
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

@section('js')
@endsection