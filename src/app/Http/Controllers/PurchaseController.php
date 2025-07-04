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
use App\Services\StripeService;


class PurchaseController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

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
        if (!Auth::check()) {
        return redirect()->route('login');
    }
        $item = Item::findOrFail($itemId);
        $profile = Profile::where('user_id', Auth::id())->first();

        $shippingAddress = Session::get('shipping_address', [
            'address_number' => $profile->address_number,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        $selectedMethod = $request->input('method');

        switch ($selectedMethod) {
            case 'コンビニ払い':
                $stripePaymentMethod = 'konbini';
                break;
            case 'カード支払い':
                $stripePaymentMethod = 'card';
                break;
            default:
                $stripePaymentMethod = 'card';
        }

        

        // アイテム価格
        $amount = $item->price * 1; 

        // Checkout セッションを作成
        // Stripe CheckoutセッションをService経由で作成
        $session = $this->stripeService->createCheckoutSession([
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
            'success_url' => route('mypage', ['page' => 'buy']),
            'metadata' => [
                'item_id' => $item->id,
                'user_id' => Auth::id(),
            ],
        ]);

        DB::transaction(function () use ($item, $profile, $shippingAddress, $session) {
            Sold::create([
                'user_id' => $profile->user_id,
                'item_id' => $item->id,
                'sold' => 1,
                'method' => $session->payment_method_types[0], 
                'address_number' => $shippingAddress['address_number'],
                'address' => $shippingAddress['address'],
                'building' => $shippingAddress['building'],
            ]);
        });

        // セッション削除
        Session::forget('shipping_address');

        // Stripeの決済ページにリダイレクト
        return redirect($session->url);
    }
}
