<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        //クエリパラメータ
        $page = $request->query('page');

        if ($page === 'mylist') {
            if (auth()->check()) {
                $items = auth()->user()->items; 
                return view('mylist', compact('items'));
            }
        }

        //その他
        $items = Item::all();
        return view('index', compact('items'));
    }

    //コメント追加後画面
    public function addComment(CommentRequest $commentrequest, $id)
    {
        // 未認証ユーザーはログインページへリダイレクト
        if (!Auth::check()) {
            return response('', 200);
    }

    $user = Auth::user();
        $item = Item::with(['conditions', 'categories', 'comments.user','likes'])->findOrFail($id);

        Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item->id,
        'comment' => $commentrequest->comment,
        ]);

        return redirect()->route('item.detail', ['item' => $id])->with('success', 'コメントを追加しました。');

    return view('detail', compact('item'));

    }

    //商品詳細画面
    public function detail($id)
    {
        $item = Item::with(['conditions', 'categories', 'comments.user','likes'])->findOrFail($id);

    return view('detail', compact('item'));

    }
}
