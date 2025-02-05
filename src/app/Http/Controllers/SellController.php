<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {

        return response('', 200);
    }
        
        $user = Auth::user();
        return view('exhibit');
    }
}
