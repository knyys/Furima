<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Sold;
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
        
        return view('purchase', compact('user', 'profile', 'item'));
    }


    // 商品購入
    public function purchase(PurchaseRequest $request, Item $item)
    {
        if ($item->sold) {
            return redirect()->route('purchase.show', ['item' => $item->id])
                ->with('error', 'この商品はすでに売れています。');
        }

        // Soldテーブルにレコードを作成
        $sold = Sold::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'sold' => 1,
            'method' => $request->method,
        ]);

        $item->update(['sold' => 1]);

        ShippingAddress::updateOrCreate(
            ['user_id' => Auth::id(), 'item_id' => $item->id],
            [
                'address_number' => $request->session()->get('shipping_address.address_number'),
                'address' => $request->session()->get('shipping_address.address'),
                'building' => $request->session()->get('shipping_address.building'),
            ]
        );

         return redirect()->route('purchase', ['item' => $item->id])
            ->with('success', '購入が完了しました！');
    }



}