<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Chat;
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
        $relatedItems = Item::with(['user', 'chats', 'solds', 'rating'])
            ->where(function ($query) use ($user) {
                $query->whereHas('solds', function ($q) use ($user) {
                    // 自分が購入者
                    $q->where('user_id', $user->id);
                })->orWhere(function ($q) use ($user) {
                    // 自分が出品者、かつ 購入された
                    $q->where('user_id', $user->id)
                    ->whereHas('solds');
                });
            })
            ->get();

        // 取引中の商品かつまだ評価されていないもの
        $filteredItems = $relatedItems->filter(function ($item) {
            $ratings = $item->rating ?? collect();
            $soldUser = $item->solds->first();

            if (!$soldUser) return false;

            $sellerRated = $ratings->contains(function ($r) use ($item, $soldUser) {
                return $r->rater_id === $item->user_id && $r->rated_id === $soldUser->user_id;
            });

            return !$sellerRated;
        });
                // 取引中の商品の並べ替え
        $tradingItems = $filteredItems->sortByDesc(function ($item) {
            return optional($item->chats->last())->created_at;
        });


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
        
        // 評価平均取得
        $user->load('receivedRatings');
        $avg = $user->receivedRatings->avg('rating');

        if (is_null($avg)) {
            $averageRating = null; // 評価がない
        } else {
            $averageRating = round($avg, 0); // 四捨五入
        }

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
            'tradingItems',
            'averageRating'        
        ));
    }
}