@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/exhibit.css') }}">
@endsection

@section('content')
<div class="exhibit-page">
<h2 class="exhiit-form__heading">商品の出品</h2>

	<div class="form__content">
		<div class="item__img">
			<span class="content__label">商品画像</span>
			<img class="item-icon" src="">
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
            <output id="image" class="image_output"></output>
            <!--画像選択ラベル-->
            <label for="image-input" class="image-label">画像を選択する</label>
            <input type="file" id="image-input" name="image" accept=".jpg, .png">
            <span id="image-name" class="image-name"></span>
		</div>
		
		<h3 class="content__title">商品の詳細</h3>
		
		<div class="item__brand">
			<span class="content__label">ブランド</span>
			<input type="text" name="brand__name">
		</div>
		
		<div class="item__category">
			<span class="content__label">カテゴリー</span>
			<div class="category__check">

				<label><input type="checkbox" value="ファッション" name="category"><span class="category-label">ファッション</span></label>
				<label><input type="checkbox" value="家電" name="category"><span class="category-label">家電</span></label>
				<label><input type="checkbox" value="インテリア" name="category"><span class="category-label">インテリア</span></label>
				<label><input type="checkbox" value="レディース" name="category"><span class="category-label">レディース</span></label>
				<label><input type="checkbox" value="メンズ" name="category"><span class="category-label">メンズ</span></label>
				<label><input type="checkbox" value="コスメ" name="category"><span class="category-label">コスメ</span></label>
				<label><input type="checkbox" value="本" name="category"><span class="category-label">本</span></label>
				<label><input type="checkbox" value="ゲーム" name="category"><span class="category-label">ゲーム</span></label>
				<label><input type="checkbox" value="スポーツ" name="category"><span class="category-label">スポーツ</span></label>
				<label><input type="checkbox" value="キッチン" name="category"><span class="category-label">キッチン</span></label>
				<label><input type="checkbox" value="ハンドメイド" name="category"><span class="category-label">ハンドメイド</span></label>
				<label><input type="checkbox" value="アクセサリー" name="category"><span class="category-label">アクセサリー</span></label>
				<label><input type="checkbox" value="おもちゃ" name="category"><span class="category-label">おもちゃ</span></label>
				<label><input type="checkbox" value="ベビー・キッズ" name="category"><span class="category-label">ベビー・キッズ</span></label>

			</div>
		</div>
		
		<div class="item__condition">
			<span class="content__label">商品の状態</span>
			<select class="condition" name="condition">
			<option value="">選択してください</option>
			<option value="">良好</option>
			<option value="">目立った傷や汚れなし</option>
			<option value="">やや傷や汚れあり</option>
			<option value="">状態が悪い</option>
		</div>

		<h3 class="content__title">商品名と説明</h3>
			
		<div class="item__name">
			<span class="content__label">商品名</span>
			<input type="text" name="name">
		</div>
		
		<div class="item__detail">
			<span class="content__label">商品の説明</span>
			<textarea name="detail" rows="4" clos="40"></textarea>
		</div>
			
		<div class="item__price">
			<span class="content__label">販売価格</span>
			<input type="" name="price">
		</div>
		<div class="exhiit-form__btn">
			<button class="exhibit__btn" type="submit">出品する</button>
		</div>
		</form>
	</div>
	
	
	
	
</div>

@endsection