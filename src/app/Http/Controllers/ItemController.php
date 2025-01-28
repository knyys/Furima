<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

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

}
