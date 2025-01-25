<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use APP\Models\Profile;


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

    /*Profile画像反映させるやつ再確認する
    public function upload(ProfileRequest $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('public/profile');

            $url = Profile::url($path);

             return redirect()->back()->with('profile_image_url', $url);
    }

        return redirect()->back()->withErrors(['profile_image' => '画像のアップロードに失敗しました']);
        
    }*/
}