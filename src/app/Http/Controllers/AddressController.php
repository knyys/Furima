<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    
    }

}
