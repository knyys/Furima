<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;


class ProfileController extends Controller
{
    public function welcome()
    {
        return view('edit_profile');
    }

    public function index()
    {
        return view('profile');
    }
}
