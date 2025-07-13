<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AddressController extends Controller
{
    public function index($item)
    {
        $item = Item::findOrFail($item);
        $profile = Profile::where('user_id', Auth::id())->first();

        return view('address', compact('item','profile'));
    }


    //配送先変更
    public function updateAddress(AddressRequest $request, Item $item) 
    {
        $shippingAddress = $request->only(['address_number', 'address', 'building']);

        Session::put('shipping_address', $shippingAddress);

        return redirect()->route('purchase', ['item' => $item->id])
                        ->with('success', '住所が更新されました')
                        ->with('address_number', $shippingAddress['address_number'])
                        ->with('address', $shippingAddress['address'])
                        ->with('building', $shippingAddress['building']);
    }

}
