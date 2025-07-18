@extends('layouts.navbar')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')

<div class="chat-form">
    <div class="form-nav">
        <p class="nav-header">その他の取引</p>
        @foreach ($items as $chatItem)
            @php
                $isSellerAndSold = $chatItem->user_id === auth()->id() && $chatItem->solds->isNotEmpty();
                $firstSold = $chatItem->solds->first();
                $buyer = $firstSold && $firstSold->user_id === auth()->id();
            @endphp

            @if ($isSellerAndSold || $buyer)
                <div class="nav-items">
                    <a href="{{ route('chatView', ['item' => $chatItem->id]) }}" class="nav-link">{{ $chatItem->name }}</a>
                </div>
            @endif
        @endforeach
    </div>
    <div class="form-header">
        <img src="{{ asset(($sold->user_id === auth()->id() ? $item->user->profile->image : $sold->user->profile->image)) }}" alt="ユーザーアイコン">
        <h3>{{ $sold->user_id === auth()->id() ? $item->user->name : $sold->user->name }}さんとの取引画面</h3>

        @if ($isBuyer)
            @if ($alreadyRated && !$buyerRatedSeller)
                <span class="disabled-link">取引を完了する</span>
            @elseif (!$alreadyRated)
                <a href="#modal" class="modal-button">取引を完了する</a>
            @else
                <span class="disabled-link">取引を完了する</span>
            @endif
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
            <p class="message-content" id="message-text-{{ $chat->id }}">{{ $chat->message }}</p>

            <!-- 編集フォーム -->
            <form id="edit-form-{{ $chat->id }}" class="edit-form" action="{{ route('updateMessage') }}" method="POST" style="display: none;">
                @csrf
                @method('PATCH')
                <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                @if ($errors->has('message'))
            <div class="alert-danger">
                <p>{{ $errors->first('message') }}</p>
            </div>
            @endif
                <textarea name="message" class="edit-message-input">{{ $chat->message }}</textarea>
                <div class="edit-button">
                    <button type="submit">送信</button>
                    <button type="button" onclick="cancelEdit('{{ $chat->id }}')">キャンセル</button>
                </div>
            </form>
            <!-- -- -->

            @if ($chat->image)
                <img src="{{ asset('storage/' . $chat->image) }}" alt="画像" class="chat-image">
            @endif

            @if ($isOwnMessage)
            <div class="message-btn">
                <button class="btn__edit" onclick="startEdit('{{ $chat->id }}')">編集</button>

                <form method="POST" action="{{ route('deleteMessage') }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <button class="btn__delete" type="submit">削除</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    <form class="messages-send-form" method="POST" action="{{ route('sendMessage', ['item' => $item->id]) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="form_type" value="chat">
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="to_user_id" value="{{ $sold->user_id === auth()->id() ? $item->user_id : $sold->user_id }}">

        <div class="message-form"> 
            @if (old('form_type') === 'chat' && $errors->has('message'))
                <div class="alert-danger">
                    <p>{{ $errors->first('message') }}</p>
                </div>
            @endif
            @if ($errors->has('image'))
            <div class="alert-danger">
                <p>{{ $errors->first('image') }}</p>
            </div>
            @endif
            <span id="image-name" class="image-name"></span>
            <div class="form-row">
                <input type="text" class="message-input" name="message" placeholder="取引メッセージを記入してください" value="{{ old('message', session('chat_draft')) }}" id="chat-input">
                <label for="img-input" class="img-label">画像を追加</label>
                <input type="file" id="img-input" name="image" accept=".jpg, .png">
                <button class="message-send" type="submit">
                    <img src="{{ asset('storage/ei-send.png') }}" alt="送信" class="send-icon">
                </button>
            </div>
        </div>
    </form>
</div>

<!-- モーダルウィンドウ -->
<div class="modal-wrapper" id="modal">
    <form action="{{ route('chat.rate') }}" method="POST" class="modal-form">
        @csrf
        <input type="hidden" name="form_type" value="rating">
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="sold_id" value="{{ $sold->id }}">
        <input type="hidden" name="to_user_id" value="{{ $sold->user_id === auth()->id() ? $item->user_id : $sold->user_id }}">
        <a href="#!" class="modal-overlay"></a>
        <div class="modal-window">
            <div class="modal-header">
                <p>取引が完了しました。</p>
            </div>
        <div class="modal-content">
            <p>今回の取引相手はどうでしたか？</p>
            <div class="star-rating" id="rating">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
                <input type="hidden" name="rating" id="rating-value">
            </div>
        </div>
        <div class="modal-btn">
            <button class="btn-submit" type="submit">送信する</button>
        </div>
    </form>
</div>

<script>
    //　編集フォーム表示
    function startEdit(chatId) {
        document.getElementById(`message-text-${chatId}`).style.display = 'none';
        document.getElementById(`edit-form-${chatId}`).style.display = 'block';
    }

    function cancelEdit(chatId) {
        document.getElementById(`edit-form-${chatId}`).style.display = 'none';
        document.getElementById(`message-text-${chatId}`).style.display = 'block';
    }

    // 画像名プレビュー
    document.getElementById('img-input').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const imageName = document.getElementById('image-name');

        if (file) {
            imageName.textContent = file.name;
        } else {
            imageName.textContent = '';
        }
    });

    // 入力情報を保持する
    const input = document.getElementById('chat-input');
    const storageKey = 'chat_draft_{{ $item->id }}';

    window.addEventListener('DOMContentLoaded', () => {
        const savedMessage = localStorage.getItem(storageKey);
        if (savedMessage) {
            input.value = savedMessage;
        } 
    });

    let timeout;
    input.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            localStorage.setItem(storageKey, input.value);
        }, 500);
    });

    // フォーム送信したら入力内容を削除）
    document.querySelector('.messages-send-form').addEventListener('submit', () => {
        localStorage.removeItem(storageKey);
    });

    // 星評価
    document.addEventListener('DOMContentLoaded', () => {
        const stars = document.querySelectorAll('.star-rating .star');
        const ratingValue = document.getElementById('rating-value');

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.dataset.value);
                ratingValue.value = rating;

                stars.forEach((s, i) => {
                    s.classList.toggle('selected', i < rating);
                });
            });
        });
    });
</script>
<!-- 購入者が評価完了の時出品者に表示 -->
@if ($showModal)
<script>
    window.onload = () => {
        if (location.hash !== '#modal') {
            location.hash = '';
            setTimeout(() => {
                location.hash = '#modal';
            }, 10);
        }
    };
</script>
@endif

@endsection
