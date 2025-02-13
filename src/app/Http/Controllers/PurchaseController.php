<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    
    public function index($id)
    {
        if (!auth()->check()) {
        return redirect('/login'); 
        }
        
        $user = Auth::user();
        return view('purchase');
    }
    

    
}

