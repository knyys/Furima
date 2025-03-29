@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="alert">
    @if(session('success'))
    <div class="alert-success">
        <p>{{ session('success') }}</p>
    </div>
    @elseif($errors->any())
    <div class="alert-danger">
        <p>エラーがあります。</p>
    </div>
    @endif
</div>
<div id="favorite-error" class="error-message"></div>

<div class="item-page__detail">
    <div class="detail__content">
        <div class="item__img">
            <img id="image" class="item-icon" src="{{ asset( 'storage/' . $item->image) }}" alt="商品画像:{{ $item->name }} ">
            <output id="image" class="image_output"></output>
            <!--Sold-->
            @if ($item->is_sold)
            <div class="item--sold">
                <span class="sold-label">Sold</span>
            </div>
            @endif
            <!--Sold-->
        </div>
        <div class="item__detail">
            <div class="item__name">
                {{ $item->name }}
            </div>
            <div class="item__brand">
                @if($item->brand)
                    {{ $item->brand }}
                @else
                    <p></p>
                @endif
            </div>
            <div class="item__price">
                <span class="item__currency">￥</span>
                <span>{{ number_format($item->price) }}</span>
                <span class="tax-included">（税込）</span>
            </div>
            <div class="item__action">
                <span class="action--favorite" data-item-id="{{ $item->id }}">
                    <img class="favorite-icon {{ $item->isLikedByUser(Auth::user()) ? 'liked' : '' }}" 
                    src="{{ asset('storage/hoshi.png') }}" 
                    alt="お気に入り"
                    onclick="favorite(event, {{ $item->id }})">
                
                    <!--お気に入り数を下に表示-->    
                    <span class="favorite__count" id="favorite-count-{{ $item->id }}">   
                    @if ($item->likes->count() > 0)
                        {{ $item->likes->count() }}
                    @endif
                    </span>
                </span>

                <span class="action--comment" data-item-id="2">
                    <img class="comment-icon" src="{{ asset( 'storage/comment.png') }}" alt="">
                    <!--コメント数を下に表示-->
                    <span class="comments__count">
                    @if($item->comments->count() > 0)
                        {{ $item->comments->count() }}
                    @endif
                    </span>
                </span>
            </div>
            <div class="item-purchase__btn">
                @if ($item->is_sold || $item->is_user_item)
                    <button class="purchase__btn--disabled" disabled> <!--Soldの場合はボタン非活性-->
                         購入手続きへ
                    </button>
                @else
                    <a class="purchase__btn--submit" href="{{ route('purchase', ['item' => $item->id]) }}">
                        購入手続きへ
                    </a>
                @endif
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
                    @foreach($item->categories as $category)
                    <ul>
                        <li class="item__category-type">{{ $category->category }}</li>
                    </ul>
                    @endforeach
                </div>
                <div class="item__condition">
                    <span class="item__condition-label">商品の状態</span>
                    <span class="item__condition-type">{{ $item->condition->condition }}</span>
                </div>
            </div>

            <!--コメント欄-->
            <div class="item__comment">
                <div class="comment__count">
                コメント
                <!--コメント数-->
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
                            <img class="icon" src="{{ $comment->user->profile->image }} " alt="ユーザー画像">
                        </span>
                        <span class="user__name">
                            {{ $comment->user->name }}
                        </span>
                    </div>
                    <div class="comment__detail">
                        {{ $comment->comment }}
                    </div>
                </div>
                @endforeach
                @endif
                
                <div class="comment-form">
                    <span class="comment-form__label">商品へのコメント</span>
                    <form action="" method="post">
                        @csrf
                        <div class="comment__danger">
                            @error('comment')
                            {{ $message }}
                            @enderror
                        </div>
                    <textarea class="comment__box" name="comment"></textarea>
                    <div class="comment-form__btn">
                        @if ($item->is_sold)  
                            <button class="comment-form__btn--disabled" disabled>
                                コメントを送信する
                            </button>
                        @else
                            <button class="comment-form__btn--submit" type="submit">
                                コメントを送信する
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
function favorite(event, itemId) {
    const icon = event.target;
    icon.classList.toggle('liked');

    fetch(`/items/${itemId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        const errorElement = document.getElementById('favorite-error');
        
        if (data.error) {
            // エラーメッセージをページ内に表示
            errorElement.textContent = data.error;
            errorElement.style.display = 'block';
            
            // いいね状態を元に戻す
            icon.classList.toggle('liked');
            return;
        }

        // エラーがない場合はメッセージを非表示にする
        errorElement.style.display = 'none';

        // いいね数を更新
        const countElement = document.getElementById(`favorite-count-${itemId}`);
        if (countElement) {
            countElement.textContent = data.likes_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


</script>
@endsection