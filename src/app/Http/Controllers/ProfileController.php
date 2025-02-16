<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Profile;
use App\Models\User;


class ProfileController extends Controller
{
    //プロフィール編集画面の表示
    public function welcome()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('edit_profile', compact('user', 'profile'));
    }


    //プロフィール編集・更新
    public function upload(ProfileRequest $profilerequest, AddressRequest $addressrequest)
    {
        $data = $addressrequest->only(['address_number', 'address', 'building']);
    
        $image = $profilerequest->file('image');
        if ($image) {
            $path = $image->store('profile', 'public');  
            $imageUrl = asset('storage/' . $path); 
        } else {
            return redirect()->back()->withErrors(['image' => '画像のアップロードに失敗しました']);
        }
 
        $user = Auth::user();

        $profile = $user->profile()->firstOrCreate(
        ['user_id' => $user->id], 
        [
            'name' => $user->id,
            'address_number' => $data['address_number'],
            'address' => $data['address'],
            'building' => $data['building'],
            'image' => $imageUrl
        ]
        );

        $profile->update($data); 
        $profile->update(['image' => $imageUrl]);

        return redirect('/')->with('success', '登録が完了しました。')->with('imageUrl', $imageUrl);

        // 画像がアップロードされていない場合
        return redirect('/mypage/profile')->back()->withErrors(['image' => '画像のアップロードに失敗しました']);
    }

    //プロフィール画面の表示
    public function index()
    {
        if (!Auth::check()) {

        return response('', 200);
    }
        $user = Auth::user();
        $profile = $user->profile; 
        $items = $user->items;

        return view('profile', compact('user', 'profile', 'items'));
    }
}