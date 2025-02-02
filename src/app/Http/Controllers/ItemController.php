<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;

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

    //商品詳細画面
    public function detail($id)
    {
        $item = Item::with(['conditions', 'categories', 'comments.user','likes'])->findOrFail($id);

        return view('detail', compact('item'));
     // return view('detail');
    }

}
