@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibit.css') }}">
@endsection

@section('content')
<div class="exhibit-page">
<h2 class="exhiit-form__heading">商品の出品</h2>

<div class="form__content">
	<span class="content__label">商品画像</span>
	<div class="item__img">
		<img class="icon" src="">
        <form method="post" action="{{ route('sell') }}" enctype="multipart/form-data">
           @csrf
        <output id="image" class="image_output"></output>
        <!--画像選択ラベル-->
        <label for="image-input" class="image-label">画像を選択する</label>
        <input type="file" id="image-input" name="image" accept=".jpg, .png">
        <span id="image-name" class="image-name"></span>
		
		<div class="form__error">
            @error('image')
            {{ $message }}
            @enderror
        </div>
	</div>
		
	<h3 class="content__title">商品の詳細</h3>
		
	<div class="item__category">
		<span class="content__label">カテゴリー</span>
		<div class="category__check">
			<label><input type="checkbox" value="ファッション" name="category[]" {{ in_array('ファッション', old('category', [])) ? 'checked' : '' }}><span class="category-label">ファッション</span></label> 
			<label><input type="checkbox" value="家電" name="category[]" {{ in_array('家電', old('category', [])) ? 'checked' : '' }}><span class="category-label">家電</span></label>
			<label><input type="checkbox" value="インテリア" name="category[]" {{ in_array('インテリア', old('category', [])) ? 'checked' : '' }}><span class="category-label">インテリア</span></label>
			<label><input type="checkbox" value="レディース" name="category[]" {{ in_array('レディース', old('category', [])) ? 'checked' : '' }}><span class="category-label">レディース</span></label>
			<label><input type="checkbox" value="メンズ" name="category[]" {{ in_array('メンズ', old('category', [])) ? 'checked' : '' }}><span class="category-label">メンズ</span></label>
			<label><input type="checkbox" value="コスメ" name="category[]" {{ in_array('コスメ', old('category', [])) ? 'checked' : '' }}><span class="category-label">コスメ</span></label>
			<label><input type="checkbox" value="本" name="category[]" {{ in_array('本', old('category', [])) ? 'checked' : '' }}><span class="category-label">本</span></label>
			<label><input type="checkbox" value="ゲーム" name="category[]" {{ in_array('ゲーム', old('category', [])) ? 'checked' : '' }}><span class="category-label">ゲーム</span></label>
			<label><input type="checkbox" value="スポーツ" name="category[]" {{ in_array('スポーツ', old('category', [])) ? 'checked' : '' }}><span class="category-label">スポーツ</span></label>
			<label><input type="checkbox" value="キッチン" name="category[]" {{ in_array('キッチン', old('category', [])) ? 'checked' : '' }}><span class="category-label">キッチン</span></label>
			<label><input type="checkbox" value="ハンドメイド" name="category[]" {{ in_array('ハンドメイド', old('category', [])) ? 'checked' : '' }}><span class="category-label">ハンドメイド</span></label>
			<label><input type="checkbox" value="アクセサリー" name="category[]" {{ in_array('アクセサリー', old('category', [])) ? 'checked' : '' }}><span class="category-label">アクセサリー</span></label>
			<label><input type="checkbox" value="おもちゃ" name="category[]" {{ in_array('おもちゃ', old('category', [])) ? 'checked' : '' }}><span class="category-label">おもちゃ</span></label>
			<label><input type="checkbox" value="ベビー・キッズ" name="category[]" {{ in_array('ベビー・キッズ', old('category', [])) ? 'checked' : '' }}><span class="category-label">ベビー・キッズ</span></label>
		</div>

		<div class="form__error">
			@error('category')
				{{ $message }}
			@enderror
		</div>
	</div>
	
	<div class="item__condition">
		<span class="content__label">商品の状態</span>
		<select class="condition" name="condition">
		<option value="" selected hidden>選択してください</option>
		<option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
		<option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
		<option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
		<option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
		</select>

		<div class="form__error">
			@error('condition')
				{{ $message }}
			@enderror
		</div>
	</div>
	
		
	<h3 class="content__title">商品名と説明</h3>
				
	<div class="item__name">
		<span class="content__label">商品名</span>
		<input type="text" name="name" value="{{ old('name') }}">

		<div class="form__error">
			@error('name')
				{{ $message }}
			@enderror
		</div>
	</div>
	
	<div class="item__brand">
		<span class="content__label">ブランド名</span>
		<input type="text" name="brand"  value="{{ old('brand') }}">
	</div>

	<div class="item__detail">
		<span class="content__label">商品の説明</span>
		<textarea name="detail" rows="4" cols="40">{{ old('detail') }}</textarea>

		<div class="form__error">
			@error('detail')
				{{ $message }}
			@enderror
		</div>
	</div>
		
			
	<div class="item__price">
		<span class="content__label">販売価格</span>
		<input type="number" name="price" min="0" step="1" placeholder="￥"  value="{{ old('price') }}">

		<div class="form__error">
		@error('price')
			{{ $message }}
		@enderror
		</div>
	</div>
		

	<div class="exhiit-form__btn">
		<button class="exhibit__btn" type="submit">出品する</button>
	</div>
	</form>
</div>

</div>

@endsection
