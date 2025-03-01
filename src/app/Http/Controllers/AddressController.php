<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Sold;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AddressController extends Controller
{
    public function index($item)
    {
        $item = Item::findOrFail($item);

        return view('address', compact('item'));
    }


    //配送先変更(できない)
    public function updateAddress(AddressRequest $request, Item $item)
    {
        $userId = auth()->id();

        session([
        'shipping_address' => [
            'address_number' => $request->address_number,
            'address' => $request->address,
            'building' => $request->building,
        ]
    ]);
    $sold = Sold::where('user_id', $userId)
                ->where('item_id', $item->id)
                ->where('sold', false) // 未購入状態のものに限定
                ->first();
    
        if ($sold) {
        $sold->address_number = $request->address_number;
        $sold->address = $request->address;
        $sold->building = $request->building;
        $sold->save();
    
    }
    return redirect()->route('purchase', ['item' => $item->id])
                     ->with('success', '住所が更新されました。');
    }

}
