@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="alert">
@if(session('success'))
    <div class="alert-success">
        <p>{{ session('success') }}</p>
    </div>
@elseif($errors->any())
    <div class="alert-danger">
        <p>{{ $errors->first() }}</p>
    </div>
@endif
</div>

<div class="address-update__page">
    <form class="address-form" action="{{ route('address.update', ['item' => $item->id]) }}" method="post">  
        @method('PATCH')
        @csrf
        <div class="address-form__header">
            住所の変更
        </div>

        <div class="address-form__content">
            <div class="address-form__details">
                <label>郵便番号</label>
                <input type="text" name="address_number" value="{{ old('address_number') ?: $profile->address_number }}">
                <div class="danger">
                    @error('address_number')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="address-form__details">
                <label>住所</label>
                <input type="text" name="address" value="{{ old('address') ?: $profile->address }}">
                <div class="danger">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="address-form__details">
                <label>建物名</label>
                <input type="text" name="building" value="{{ old('building') ?: $profile->building }}">
                <div class="danger">
                    @error('building')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        
            <div class="address-form__btn">
                <button class="btn--update" type="submit">更新する</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')

@endsection