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
                <span class="action--favorite" data-item-id="1">
                    <img class="fovorite-icon" src="{{ asset( 'storage/hoshi.png') }}" alt="">
                
                    <span class="favorite__count">
                        <!--お気に入り数を下に表示-->
                    
                    </span>
                </span>

                <span class="action--comment" data-item-id="2">
                    <img class="comment-icon" src="{{ asset( 'storage/comment.png') }}" alt="">

                    <span class="comments__count">
                        <!--コメント数を下に表示-->
                    @if($item->comments->count() > 0)
                        {{ $item->comments->count() }}
                    @endif
                    </span>
                </span>
            </div>
            <div class="item-purchase__btn">
                <a class="purchase__btn" href="{{ route('purchase', ['item' => $item->id]) }}">
                    購入手続きへ
                </a>
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
                    <span class="item__condition-type">{{ $item->condition->first()->condition }}</span>
                </div>
            </div>

            <!--コメント欄-->
            <div class="item__comment">
                <div class="comment__count">
                コメント
               <!-- コメントがある場合は コメント数 表示-->
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
                        <!--Soldの場合はボタン非活性-->
                        @if ($item->is_sold) 
                            <button class="purchase__btn purchase__btn--disabled" disabled>
                                コメントを送信する
                            </button>
                        @endif
                        @if (!$item->is_sold)
                            <button class="purchase__btn" type="submit">
                                コメントを送信する
                            </button>
                        @endif


                    <button class="purchase__btn" type="submit">
                        コメントを送信する
                    </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

