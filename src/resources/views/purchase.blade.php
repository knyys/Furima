@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-form">
    <form action="" method="post">
        @csrf

    <div class="purchase-form__content">
        <div class="item">
            <img  class="item-img" src="" alt="商品画像">
            <div class="item__content">
            <div class="item__name">商品名</div>
            <div class="item__price">
                <span class="item__currency">￥</span>
                <span class="item__amount">47,000</span>
            </div>
            </div>
           
        </div>

        <div class="purchase">
            <label class="purchase-method" for="">支払い方法</label>
            <div class="method__select">
            <select name="method">
                <option value="">選択してください</option>
                <option value="コンビニ払い" {{ old('method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                <option value="カード支払い" {{ old('method') == 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
            </select>
            </div>
        </div>

        <div class="shipping-address">
            <div class="shipping-address__header">
            <label for="">配送先</label>    
            <a class="address__update-button" href="">変更する</a>
            </div>
            <div class="address-number">
                <span class="postal-mark">〒</span>
                <span class="number">000-0000</span>
            </div>
            <div class="address">
                <span class="address__details">ここには住所と建物名が入ります</span>
            </div>
        </div>
    </div>

    <div class="purchase-form__confirmation">
        <div class="confirmation__price">
            <div class="confirmation__label">商品代金</div>
            <div class="confirmation__content">
                <span class="item__currency">￥</span>
                <span class="item__amount">47,000</span>
                </div>
        </div>
        <div class="confirmation__purchase">
            <div class="confirmation__label">支払い方法</div>
            <div class="confirmation__content">
                <span class="payment-method">コンビニ払い</span>
            </div>
        </div>
    

        <div class="purchase-form__btn">
            <button class="purchase__btn" type="submit">購入する</button>
        </div>
    </div>
</form>
</div>
@endsection