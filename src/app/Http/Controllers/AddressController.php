<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function index($item)
    {
        $item = Item::findOrFail($item);

        return view('address', compact('item'));
    }

    public function update(AddressRequest $request, $item)
    {
        $profile = $request->only(['address_number', 'address', 'building']);
        session(['profile' => $profile]);


        $item = Item::findOrFail($item);

        return redirect()->route('purchase', ['item' => $item->id]) ->with('success', '配送先を更新しました')->with('profile', $profile);
    }
}
