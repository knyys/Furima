<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function favorite(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $itemId = $request->item_id;

        $like = Like::where('user_id', $user->id)
                    ->where('item_id', $itemId)
                    ->first();

        if ($like) {
            $like->delete();
            $action = 'delete';
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $itemId,
            ]);
            $action = 'create';
        }

        $likeCount = Like::where('item_id', $itemId)->count();

        return response()->json([
            'success' => true,
            'action' => $action,
            'like_count' => $likeCount,
        ]);
    }
}