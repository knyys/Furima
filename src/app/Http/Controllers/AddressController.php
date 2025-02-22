<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index($item)
    {
        $item = Item::findOrFail($item);

        return view('address', compact('item'));
    }


    //配送先変更
    public function updateAddress(AddressRequest $request, Item $item)
    {
        $user = Auth::user();

        $shippingAddress = Shippingaddress::updateOrCreate(
        [
            'user_id' => $user->id,
            'item_id' => $item->id
        ],
        [
            'address_number' => $request->address_number,
            'address' => $request->address,
            'building' => $request->building,
        ]
    );

    // セッションに保存
    session([
        'shipping_address' => [
        'address_number' => $shippingAddress->address_number,
        'address' => $shippingAddress->address,
        'building' => $shippingAddress->building,
        ]
    ]);

        return redirect()->route('purchase', ['item' => $item->id])->with('success', '配送先が更新されました。');
    }

}
