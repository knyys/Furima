<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class LikeController extends Controller
{
    // app/Http/Controllers/LikeController.php
public function store(Request $request)
{
    $item = Item::find($request->item_id);
    if ($item && !Auth::user()->likes->contains($item)) {
        $item->likes()->create(['user_id' => Auth::id()]);
        return response()->json(['success' => true, 'action' => 'create']);
    }
    return response()->json(['success' => false]);
}

public function destroy(Request $request)
{
    $item = Item::find($request->item_id);
    if ($item) {
        $item->likes()->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true, 'action' => 'delete']);
    }
    return response()->json(['success' => false]);
}
}
