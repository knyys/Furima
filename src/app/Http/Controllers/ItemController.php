<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Like;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request)
{
    $page = $request->query('page', 'index'); // デフォルトは 'index'
    $searchKeyword = $request->input('item_name');

    // すべての商品を検索
    $allItems = Item::nameSearch($searchKeyword)
        ->where('user_id', '!=', auth()->id())
        ->with('solds')
        ->get();

    $allItems->each(function ($item) {
        $item->is_sold = $item->solds()->exists();
    });

    $userItems = collect();

    // ログインしている場合かつ `mylist` ページなら取得
    if (auth()->check() && $page === 'mylist') {
        $user = auth()->user();
        
        $userItems = Like::where('user_id', $user->id)
            ->whereHas('item', function ($query) use ($searchKeyword) {
                if ($searchKeyword) {
                    $query->nameSearch($searchKeyword);
                }
            })
            ->with('item.solds')
            ->get();

        $userItems->each(function ($like) {
            $like->item->is_sold = $like->item->solds()->exists();
        });
    }

    return view('index', compact('allItems', 'userItems', 'searchKeyword', 'page'));
}




    //コメント追加後画面
    public function addComment(CommentRequest $commentrequest, $id)
    {
        
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'ログインしてください');
    }

        $user = Auth::user();
        $item = Item::with(['condition', 'categories', 'comments.user','likes','solds'])->findOrFail($id);

        Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item->id,
        'comment' => $commentrequest->comment,
        ]);

        $item->is_sold = $item->solds()->exists();
        $item->is_user_item = $item->user_id === auth()->id();

        return redirect()->route('item.detail', ['item' => $id])->with('success', 'コメントを追加しました。');
    }

    //商品詳細画面
    public function detail($id)
    {
        $item = Item::with(['condition', 'categories', 'comments.user','likes', 'solds'])->findOrFail($id);

        $item->is_sold = $item->solds()->exists();
        $item->is_user_item = $item->user_id === auth()->id();

        return view('detail', compact('item'));

    }


}
