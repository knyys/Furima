<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use App\Models\Sold;
use App\Models\Profile;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\DB;


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


    // 商品購入(できない)
    public function purchase(PurchaseRequest $request, $itemId)
{
    $item = Item::findOrFail($itemId);
    $profile = Profile::where('user_id', Auth::id())->first();


    DB::transaction(function () use ($item, $profile, $request) {
        $sold = new Sold();
        $sold->user_id = Auth::id();
        $sold->item_id = $item->id;
        $sold->sold = 1;
        $sold->method = $request->method;
        $sold->address_number = $profile->address_number;
        $sold->address = $profile->address;
        $sold->building = $profile->building;
        $sold->save();
    });

    return redirect()->route('mypage', ['page' => 'buy'])->with('success', '購入が完了しました');
}


}
