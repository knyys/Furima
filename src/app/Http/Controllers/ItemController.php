<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function mylist(Request $request)
    {
        //クエリパラメータ
        $page = $request->query('page');

        if ($page === 'mylist') {
        return view('mylist');
        }

        //その他
        return view('index');
    }

    public function index()
    {
        return view('index');
    }
}
