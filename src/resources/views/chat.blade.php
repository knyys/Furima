@extends('layouts.navbar')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
<div class="chat-form">
    <div class="form-nav">
        <p class="nav-header">その他の取引</p>
        @foreach ($items as $chatItem)
    @if ($chatItem->user_id === auth()->id() && $chatItem->solds()->exists())
        <div class="nav-items">
            <a href="{{ route('chatView', ['item' => $chatItem->id]) }}" class="nav-link">{{ $chatItem->name }}</a>
        </div>
    @endif
@endforeach
    </div>
    <div class="form-header">
        <img src="{{ asset(($sold->user_id === auth()->id() ? $item->user->profile->image : $sold->user->profile->image)) }}" alt="ユーザーアイコン">
        <h3>{{ $sold->user_id === auth()->id() ? $item->user->name : $sold->user->name }}さんとの取引画面</h3>

        @if ($sold->user_id === auth()->id())
            <span class="deal-closed">取引を完了する</span>
        @endif
    </div>
    <div class="purchase-item">
        <div class="item-img">
            <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
        </div>
        <div class="item-details">
            <h2>{{ $item->name }}</h2>
            <p class="price">￥{{ number_format($item->price) }}</p>
        </div>
    </div>
        
    <div class="chat-messages">
        @foreach ($chats as $chat)
        @php
            $isOwnMessage = $chat->user_id === auth()->id();
            $user = $chat->user;
        @endphp
        <div class="{{ $isOwnMessage ? 'user-message' : 'to-user-message' }}">
            <div class="message-header">
                @if ($isOwnMessage)
                    <span class="sender">{{ $user->name }}</span>
                    <img src="{{ asset($user->profile->image) }}" alt="自分のアイコン">
                @else
                    <img src="{{ asset($user->profile->image) }}" alt="ユーザーアイコン">
                    <span class="sender">{{ $user->name }}</span>
                @endif
            </div>
            <p class="message-content">{{ $chat->message }}</p>

            @if ($chat->image)
                <img src="{{ asset('storage/' . $chat->image) }}" alt="画像" class="chat-image">
            @endif

            @if ($isOwnMessage)
            <div class="message-btn">
                <a class="btn__edit" href="">編集</a>
                <form method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button class="btn__delete" type="submit">削除</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    <form class="messages-send-form" method="POST" action="{{ route('sendMessage', ['item' => $item->id]) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="to_user_id" value="{{ $sold->user_id === auth()->id() ? $item->user_id : $sold->user_id }}">

        <div class="message-form"> 
            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
           @endif
           <div class="form-row">
                <input type="text" class="message-input" name="message" placeholder="取引メッセージを記入してください">
                <label for="img-input" class="img-label">画像を追加</label>
                <input type="file" id="img-input" name="image" accept=".jpg, .png">
                <span id="image-name" class="image-name"></span>
                <button class="message-send" type="submit">
                    <img src="{{ asset('storage/ei-send.png') }}" alt="送信" class="send-icon">
                </button>
            </div>
        </div>
    </form>
</div>
@endsection