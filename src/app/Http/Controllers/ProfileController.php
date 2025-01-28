<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
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


    public function upload(ProfileRequest $request)
    {
        $data = $request->only(['name', 'address_number', 'address', 'building']);

        $profile = Auth::user()->profile;
        $profile->update($data); 

        $image = $request->file('image');
        $path = $image->store('profile', 'public');  
        $imageUrl = asset('storage/' . $path); 

        $profile->update(['profile_image' => $imageUrl]);

        return back()->with('success', '画像がアップロードされました。');

        // 画像がアップロードされていない場合
        return redirect()->back()->withErrors(['profile_image' => '画像のアップロードに失敗しました']);
    }
}