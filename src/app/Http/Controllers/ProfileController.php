<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;

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
        $userProfile = Auth::user()->profile;

        if (!$profilerequest->hasFile('image')) {
            if ($userProfile == null || $userProfile->image == null) {   
                return back()->withErrors(['image' => '画像を選択してください。'])->withInput();
            }
            $imageUrl = $userProfile->image;
        } else {
            $image = $profilerequest->file('image');
            $path = $image->store('profile', 'public');
            $imageUrl = asset('storage/' . $path);

            session()->flash('image_url', $imageUrl);
        }

        $user = Auth::user();

        $profile = $user->profile()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $user->name,
                'address_number' => $data['address_number'],
                'address' => $data['address'],
                'building' => $data['building'],
                'image' => $imageUrl
            ]
        );

        $profile->update($data);
        $profile->update(['image' => $imageUrl]);

        return redirect('/mypage')->with('imageUrl', $imageUrl);
    }


    //プロフィール画面の表示
    public function index()
    {
        if (!Auth::check()) {

        return response('', 200);
    }
        $user = Auth::user();
        $profile = $user->profile; 
        // 出品した商品
        $userItems = $user->items; //自分が出品したものすべて
        $userItems->each(function ($item) {
        $item->is_sold = $item->solds()->exists(); //soldになったもの
        });

        // 購入した商品
        $purchasedItems = Item::whereIn('id', function ($query) use ($user) {
            $query->select('item_id')
                ->from('solds')
                ->where('user_id', $user->id)
                ->where('sold', true);
        })
        ->where('user_id', '!=', $user->id)
        ->get();

        $purchasedItems = $purchasedItems->whereNotIn('id', $userItems->pluck('id'));

        return view('profile', compact('user', 'profile', 'userItems', 'purchasedItems'));
    }
}