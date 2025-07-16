<p>{{ $item->user->name }}様</p>

<p>購入者に商品が到着し評価がありました。</br>購入者の評価を行って取引を完了してください。</p>

<p>■商品情報</br>
商品名：{{ $item->name }}</br>
価格：￥{{ number_format($item->price) }}</br> 
取引相手：{{ $buyer->name }}様</p>

<p>よろしくお願いいたします。</p>