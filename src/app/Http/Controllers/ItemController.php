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
    $page = $request->query('page');
    $searchKeyword = $request->input('item_name');

    // すべての商品を検索
    $allItems = Item::nameSearch($searchKeyword)
        ->with('solds')
        ->get();

    $allItems->each(function ($item) {
        $item->is_sold = $item->solds()->exists();
    });

    // ログイン
    if (auth()->check()) {
        $user = auth()->user();
        $userItems = $user->likedItems()
        ->with('solds')
        ->where(function ($query) use ($searchKeyword) {
            if (!empty($searchKeyword)) {
                $query->where('name', 'like', "%{$searchKeyword}%");
            }
        })->get();

        $userItems->each(function ($item) {
            $item->is_sold = $item->solds()->exists();
        });
    } else {
        $userItems = collect(); // 未ログイン
    }

    // マイリストページなら 'mylist' を表示
    if ($page === 'mylist') {
        return view('mylist', compact('userItems', 'allItems', 'searchKeyword'));
    }

    // 通常の一覧ページ
    return view('index', compact('userItems', 'allItems', 'searchKeyword'));
}


    //コメント追加後画面
    public function addComment(CommentRequest $commentrequest, $id)
    {
        // 未認証ユーザーはログインページへリダイレクト
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'ログインしてください');
    }

        $user = Auth::user();
        $item = Item::with(['brand','conditions', 'categories', 'comments.user','likes','solds'])->findOrFail($id);

        Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item->id,
        'comment' => $commentrequest->comment,
        ]);

        $item->is_sold = $item->solds()->exists();

        return redirect()->route('item.detail', ['item' => $id])->with('success', 'コメントを追加しました。');

    return view('detail', compact('item'));

    }

    //商品詳細画面
    public function detail($id)
    {
        $item = Item::with(['brand', 'conditions', 'categories', 'comments.user','likes', 'solds'])->findOrFail($id);

        $item->is_sold = $item->solds()->exists();

    return view('detail', compact('item'));

    }



}
