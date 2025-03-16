<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class LikeController extends Controller
{

      public function favorite(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user(); 

        // いいねが既に存在するか確認
        $like = Like::where('item_id', $itemId)->where('user_id', $user->id)->first();

        if ($like) {
            // 「いいね」がある⇒削除
            $like->delete();
            $isLiked = false;
        } else {
            // 「いいね」がない⇒新規追加
            Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);
            $isLiked = true;
        }

        $likesCount = Like::where('item_id', $itemId)->count();

        return response()->json([
            'liked' => $isLiked,
            'likes_count' => $likesCount,
        ]);
    }

}
