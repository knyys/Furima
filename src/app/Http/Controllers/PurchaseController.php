<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class PurchaseController extends Controller
{
    
    public function index(Item $item)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'ログインしてください');
        }
        
        $user = Auth::user();
        $profile = $user->profile;
    
        
        return view('purchase', compact('user', 'profile', 'item'));
    }

    /*public function purchase(PurchaseRequest $request, $id)
    {
        $data = $request->only(['method']);
        $item = Item::select(['name', 'price'])->findOrFail($id);
        $user = Auth::user();
        $profile = $user->profile; 

        return view('purchase', compact('user', 'profile', 'item'));
    }*/
    

    
}

