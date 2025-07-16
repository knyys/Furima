<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Chat;
use App\Models\Rating;
use App\Models\Sold;
use Illuminate\Support\Facades\DB;



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
        return redirect('/login')->with('error', 'ログインしてください');
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

        // 取引中の商品
        $soldItems = Sold::with(['item.user', 'user', 'item.rating', 'item.chats']) 
        ->where(function ($query) use ($user) {
            $query->where('user_id', $user->id) 
                ->orWhereHas('item', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
        })
        ->get();

        // 取引中の商品かつまだ評価されていないもの
        $tradingItems = $soldItems->filter(function ($sold) {
            $item = $sold->item;
            if (!$item) return false;

            $ratings = $item->rating ?? collect();

            $buyerRated = $ratings->contains(function ($r) use ($sold) {
                return $r->rater_id === $sold->user_id && $r->rated_id === $sold->item->user_id;
            });

            $sellerRated = $ratings->contains(function ($r) use ($sold) {
                return $r->rater_id === $sold->item->user_id && $r->rated_id === $sold->user_id;
            });

            return !($buyerRated && $sellerRated);
        });

        // 取引中の商品の並べ替え
        $tradingItems = $tradingItems->sortByDesc(function ($sold) {
            return optional($sold->item->chats->last())->created_at;
        })->pluck('item');


        // チャットの未読数(合計)
        $unreadCount = Chat::where('to_user_id', $user->id)
            ->where('is_read', false)
            ->count();
        // チャットの未読数(アイテムごと)
        $unreadByItem = Chat::where('to_user_id', $user->id)
            ->where('is_read', false)
            ->select('item_id', DB::raw('count(*) as unread_count'))
            ->groupBy('item_id')
            ->pluck('unread_count', 'item_id');

        $purchaseCompleted = session('purchase_completed', false);
        session()->forget('purchase_completed');

        return view('profile', compact(
            'user', 
            'profile', 
            'userItems', 
            'purchasedItems', 
            'purchaseCompleted', 
            'unreadCount',
            'unreadByItem',
            'tradingItems'            
        ));
    }
}