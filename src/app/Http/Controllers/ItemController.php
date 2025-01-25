<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function mylist()
    {
        return view('mylist');
    }
    //public function index(Request $request)
    //{ $page = $request->query('page');  
    // クエリパラメータ 'page' を取得
    // 例えば、$page が 'mylist' ならば、対応する処理を行う
    //if ($page === 'mylist') {
        // マイリスト表示の処理
    //}
    
    // 他の処理:/
}
