<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function welcome()
    {
        return view('profile_first');
    }

    public function index()
    {
        return view('profile');
    }


}
