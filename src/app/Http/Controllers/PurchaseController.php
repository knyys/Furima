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
    
    }



}