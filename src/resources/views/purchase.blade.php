@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="alert">
@if(session('success'))
    <div class="alert-success">
        <p>{{ session('success') }}</p>
    </div>
@elseif($errors->any())
    <div class="alert-danger">
        <p>{{ session('error') }}</p>
    </div>
@endif
</div>

<div class="purchase-form">
    <form action="{{ route('purchase.complete', ['item' => $item->id]) }}" method="post">
        @csrf

    <div class="purchase-form__content">
        <div class="item">
            <img  class="item-img" src="{{ asset( 'storage/' . $item->image) }}" alt="商品画像">
            <div class="item__content">
            <div class="item__name">{{ $item->name }}</div>
            <div class="item__price">
                <span class="item__currency">￥</span>
                <span class="item__amount">{{ number_format($item->price) }}</span>
            </div>
            </div>
           
        </div>

        <div class="purchase">
            <label class="purchase-method" for="">支払い方法</label>
            <div class="method__select">
            <select id="options" name="method">
                <option value="">選択してください</option>
                <option value="コンビニ払い" {{ old('method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                <option value="カード支払い" {{ old('method') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
            </select>
            </div>
            <div class="danger">
                @error('method')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="shipping-address">
            <div class="shipping-address__header">
            <label for="">配送先</label>    
            <a class="address__update-button" href="/purchase/address/{{ $item->id }}">変更する</a>
            </div>
            <div class="address-number">
                <span class="postal-mark">〒</span>
                <span class="number"> {{ session('shipping_address.address_number', old('address_number', $profile->address_number)) }}</span>
            </div>
            <div class="address">
                <span class="address__details">
                   {{ session('shipping_address.address', old('address', $profile->address)) }}
        {{ session('shipping_address.building', old('building', $profile->building)) }}
    </span>
                </span>
            </div>
        </div>
    </div>

    <div class="purchase-form__confirmation">
        <div class="confirmation__price">
            <div class="confirmation__label">商品代金</div>
            <div class="confirmation__content">
                <span class="item__currency">￥</span>
                <span class="item__amount">{{ number_format($item->price) }}</span>
                </div>
        </div>
        <div class="confirmation__purchase">
            <div class="confirmation__label">支払い方法</div>
            <div class="confirmation__content">
                <span id="selectedText" class="payment-method">{{ old('method') }}</span>
            </div>
        </div>        

        <div class="purchase-form__btn">
            <button class="purchase__btn" type="submit">購入する</button>
        </div>
    </div>
</form>
</div>

<script>
document.getElementById("options").addEventListener("change", function() {
    document.getElementById("selectedText").textContent = this.value;
});
</script>
@endsection