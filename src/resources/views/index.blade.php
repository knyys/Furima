@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="item-list">
    @php
        $currentPage = request('page', 'index'); 
    @endphp

    <div class="tab-box">
        <input type="radio" id="tab_1" class="tabRadio" name="tab" checked>
        <input type="radio" id="tab_2" class="tabRadio" name="tab">

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
            <article class="tab-content active" id="content_1">
                <div class="items__list">
                    @foreach ($allItems as $allItem)
                    <div class="item">
                        <div class="item-img">
                            <a href="{{ route('item.detail', ['item' => $allItem->id]) }}">
                                <img id="image" class="item-icon" src="{{ asset( 'storage/' . $allItem->image) }}" alt="商品画像:{{ $allItem->name }}">
                                <output id="image" class="image_output"></output>
                                <!--Sold-->
                                @if ($allItem->is_sold)
                                <div class="item--sold">
                                    <span class="sold-label">Sold</span>
                                </div>
                                @endif
                            </a>
                        </div>
                        
                        <span class="item-label">
                            {{ $allItem->name }}
                        </span>

                    </div>
                    @endforeach
                </div>    
            </article>

            <!--マイリストタブ-->
            @if ($currentPage == 'mylist')
            <article class="tab-content active" id="content_2">
            <div class="items__list">
            @foreach ($userItems as $like)
                <div class="item">
                    <div class="item-img">
                        <a href="{{ route('item.detail', ['item' => $like->item->id]) }}">
                            <img id="image" class="item-icon" src="{{ asset( 'storage/' . $like->item->image) }}" alt="商品画像:{{ $like->item->item_name }}">
                            <output id="image" class="image_output"></output>
                            <!--Sold-->
                            @if ($like->item->is_sold)
                            <div class="item--sold">
                                <span class="sold-label">Sold</span>
                            </div>
                            @endif
                        </a>
                    </div>
                    <span class="item-label">
                        {{ $like->item->name }}
                    </span>

                </div>
                @endforeach
            </div>
        </article>
           @endif 
        </div>
    </div>
</div>
@endsection
