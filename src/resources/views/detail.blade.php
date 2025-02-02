@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="item-page__detail">
    <div class="detail__content">
        <div class="item__img">
            <img id="image" class="item-icon" src="{{ asset( 'storage/' . $item->image) }}" alt="商品画像:{{ $item->name }} ">
            <output id="image" class="image_output"></output>
        </div>
        <div class="item__detail">
            <div class="item__name">
                {{ $item->name }}
            </div>
            <div class="item__bland">
                ブランド名
            </div>
            <div class="item__price">
                <span class="item__currency">￥</span>
                <span>{{ number_format($item->price) }}</span>
                <span class="tax-included">（税込）</span>
            </div>
            <div class="item__action">
                <span class="action--favorite" data-item-id="1">☆
                    <!--☆の画像にする？-->
                </span>
                <span class="favorite-count" id="favorite-count-{{ $item->id }}">
    {{ $item->favorites_count }} 
</span>
                    <!--ふきだし画像にする？-->
                <span class="action--comment" data-item-id="2">💬</span>
            </div>
            <div class="item-purchase__btn">
                <button class="purchase__btn">
                    購入手続きへ
                </button>
            </div>
            <div class="item__description">
                <span class="description__label">商品説明</span>
                <span>
                    {{ $item->detail }}
                </span>
            </div>
            <div class="item__info">
                <span class="info__label">商品の情報</span>
                <div class="item__category">
                    <span class="item__category-label">カテゴリー</span>
                    @foreach( $item->categories as $category)
                    <ul>
                        <li class="item__category-type">{{ $category->category }}</li>
                    </ul>
                    @endforeach
                </div>
                <div class="item__condition">
                    <span class="item__condition-label">商品の状態</span>
                    @foreach($item->conditions as $condition)
                    <ul>
                        <li class="item__condition-type">{{ $condition->condition }}</li>
                    </ul>
                    @endforeach
                </div>
            </div>

            <!--コメント欄-->
            <div class="item__comment">
                <div class="comment__count">
                コメント
               <!-- コメントがない場合は コメント だけが表示され、コメントがある場合は コメント（3）になる-->
                @if($item->comments->count() > 0)
                （{{ $item->comments->count() }}）
                @endif
                </div>
                <!--コメント表示欄-->
                @if($item->comments->isNotEmpty())
                @foreach($item->comments as $comment)
                <div class="comment__content">
                    <div class="comment__user">
                        <span class="user__img">
                            <img id="image" class="item-icon" src="{{ asset('storage/' . $comment->user->profile_image) }} " alt="ユーザー画像">
                            <output id="image" class="image_output"></output>
                        </span>
                        <span class="user__name">
                            {{ $comment->user->name }}
                        </span>
                    </div>
                    <div class="comment__detail">
                        {{ $comment->content }}
                    </div>
                </div>
                @endforeach
                @endif
                
                <div class="comment-form">
                    <span class="comment-form__label">商品へのコメント</span>
                    <form action="" method="">
                        @csrf
                    <textarea class="comment__box"></textarea>
                    <div class="comment-form__btn">
                    <button class="purchase__btn">
                        コメントを送信する
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".action--favorite").forEach(favorite => {
        favorite.addEventListener("click", function () {
            this.classList.toggle("active"); 
            const itemId = this.dataset.itemId;
            const isFavorite = this.classList.contains("active");

            // お気に入り登録の処理
            console.log(`アイテムID ${itemId} のお気に入り状態: ${isFavorite}`);

        });
    });
});
</script>
