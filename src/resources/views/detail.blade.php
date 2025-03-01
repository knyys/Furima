@extends('layouts.app')

@if(request()->ajax() && request('item_id'))
    @php
        $itemId = request('item_id');
        $item = App\Models\Item::find($itemId);
        if ($item->isLikedByUser(Auth::user())) {
            $item->likes()->where('user_id', Auth::id())->delete();
            return response()->json(['success' => true, 'action' => 'delete']);
        } else {
            $item->likes()->create(['user_id' => Auth::id()]);
            return response()->json(['success' => true, 'action' => 'create']);
        }
    @endphp
@endif


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
                <span class="action--favorite" data-item-id="{{ $item->id }}">
                <img class="favorite-icon" src="{{ asset( 'storage/hoshi.png') }}" alt="お気に入り">
                    <!--お気に入り数を下に表示-->    
                    <span class="favorite__count">   
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
                <!--Soldの場合はボタン非活性-->
                @if ($item->is_sold) 
                    <button class="purchase__btn--disabled" disabled>
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
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.action--favorite').forEach(favorite => {
        favorite.addEventListener('click', async function () {
            const itemId = this.dataset.itemId;
            const icon = this.querySelector('.favorite-icon');
            const count = this.querySelector('.favorite__count');
            
            // icon が null でないかを確認
            if (!icon) {
                console.error('favorite-icon が見つかりません');
                return;
            }
            if (!count) {
                console.error('favorite__count が見つかりません');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            let method = icon.classList.contains('liked') ? 'DELETE' : 'POST';
            let url = `/api/items/${itemId}/like`;

            const formData = new FormData();
            formData.append('item_id', itemId);

            await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (method === 'POST') {
                        icon.classList.add('liked');
                        count.textContent = parseInt(count.textContent) + 1;
                    } else {
                        icon.classList.remove('liked');
                        count.textContent = parseInt(count.textContent) - 1;
                    }
                }
            }).catch(error => {
                console.error('APIエラー:', error);
            });
        });
    });
});

</script>
@endsection