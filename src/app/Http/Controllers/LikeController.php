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
        if (!Auth::check()) {
            return response()->json([
                'error' => 'ログインしてください'
            ], 403);
        }

        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        // 自分が出品した商品は「いいね」できない
        if ($item->user_id === $user->id) {
            return response()->json([
                'error' => '自分の商品にはいいねできません'
            ], 403);
        }

        $like = Like::where('item_id', $itemId)->where('user_id', $user->id)->first();

        if ($like) {
            // 「いいね」している場合は削除
            $like->delete();
            $isLiked = false;
        } else {
            // 「いいね」していない場合は新規作成
            Like::create([
                'item_id' => $itemId,
                'user_id' => $user->id,
            ]);
            $isLiked = true;
        }

        $likesCount = Like::where('item_id', $itemId)->count();
        // 商品のlikes_countを更新
    $item->update(['likes_count' => $likesCount]);

    $item->refresh();

        return response()->json([
            'liked' => $isLiked,
            'likes_count' => $likesCount,
        ]);

    }
}