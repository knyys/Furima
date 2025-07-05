<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;


class SellController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
        return redirect('/login')->with('error', 'ログインしてください');
    }
        $user = Auth::user();
        return view('exhibit');
    }

    public function getIsSoldAttribute()
    {
        return $this->sells()->exists() ? 'Sold' : '';
    }
    

    //出品
    public function sell(ExhibitionRequest $request)
    {
        $data = $request->only(['name', 'detail', 'price','brand']);
        $data['user_id'] = auth()->user()->id;

        $condition = $request->input('condition');
        $conditionRecord = Condition::where('condition', $condition)->first();
        $data['condition_id'] = $conditionRecord->id;
        
        $categories = $request->input('category');
        $categoryIds = Category::whereIn('category', $categories)->pluck('id')->toArray();
        

        $image = $request->file('image');
        if ($image) {
            $path = $image->store('images', 'public');  
            $data['image'] = $path; 
        } else {
            return redirect()->back()->withErrors(['image' => '画像のアップロードに失敗しました']);
        }

        $item = Item::create($data);
        $item->categories()->attach($categoryIds);
        
        $item->save();

        return redirect()->route('mypage', ['item' => $item->id])->with('success', '商品を出品しました');
    }
}