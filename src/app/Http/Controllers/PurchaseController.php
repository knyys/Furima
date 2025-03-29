<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use App\Models\Sold;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;


class PurchaseController extends Controller
{
    // 購入画面表示
    public function index(Item $item)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'ログインしてください');
        }
        
        $user = Auth::user();
        $profile = $user->profile;
        
        $shippingAddress = Session::get('shipping_address', [
        'address_number' => $profile->address_number ?? '',
        'address' => $profile->address ?? '',
        'building' => $profile->building ?? '',
    ]);

    return view('purchase', compact('user', 'profile', 'item', 'shippingAddress'));
}


    // 商品購入
    public function purchase(PurchaseRequest $request, $itemId)
{
    $item = Item::findOrFail($itemId);
    $profile = Profile::where('user_id', Auth::id())->first();

    $shippingAddress = Session::get('shipping_address', [
        'address_number' => $profile->address_number,
        'address' => $profile->address,
        'building' => $profile->building,
    ]);

    $selectedMethod = $request->input('method');

    $stripePaymentMethod = match($selectedMethod) {
        'コンビニ払い' => 'konbini',
        'カード支払い' => 'card',
        default => 'card',
    };

    // StripeのAPIキー設定
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

    // アイテム価格
    $amount = $item->price * 1; 

    // Checkout セッションを作成
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => [$stripePaymentMethod],
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $item->name,
                ],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' =>route('mypage', ['page' => 'buy']),
        'metadata' => [
            'item_id' => $item->id,
            'user_id' => Auth::id(),
        ],
    ]);

    // セッション削除
    Session::forget('shipping_address');

    // Stripeの決済ページにリダイレクト
    return redirect($session->url);
}


}
