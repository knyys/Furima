@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mylist.css') }}">
@endsection

@section('content')
<div class="tab-box">
    <input type="radio" id="tab_1" class="tabRadio" name="tab">
    <input type="radio" id="tab_2" class="tabRadio" name="tab" checked>

    <ul class="tab-list">
        <li class="tab">
            <label name="tab_label_1" for="tab_1">おすすめ</label>
        </li>
        <li class="tab">
            <label name="tab_label_2" for="tab_2">マイリスト</label>
        </li>
    </ul>
    <div class="tabContentList">
        <!--おすすめタブ-->
        <article class="tab-content" id="content_1">
            <div class="items__list">
            @foreach ($allItems as $allItem)
                <div class="item">
                    <a href="{{ route('item.detail', ['item' => $allItem->id]) }}">
                    <div class="item-img">
                        <img id="image" class="item-icon" src="{{ asset( 'storage/' . $allItem->image) }}" alt="商品画像:{{ $allItem->name }}">
                        <output id="image" class="image_output"></output>
                    </div>
                    </a>
                    <span class="item-label">
                        {{ $allItem->name }}
                    </span>

                </div>
                @endforeach
            </div>    
        </article>

        <!--マイリストタブ-->
        <article class="tab-content active" id="content_2">
            <div class="items__list">
            @foreach ($userItems as $userItem)
                <div class="item">
                    <a href="{{ route('item.detail', ['item' => $userItem->id]) }}">
                    <div class="item-img">
                        <img id="image" class="item-icon" src="{{ asset( 'storage/' . $userItem->image) }}" alt="商品画像:{{ $userItem->name }}">
                        <output id="image" class="image_output"></output>
                    </div>
                    </a>
                    <span class="item-label">
                        {{ $userItem->name }}
                    </span>

                </div>
                @endforeach
            </div>
        </article>
        
    </div>
</div>


@endsection