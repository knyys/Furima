<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use GuzzleHttp\Promise\Create;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Brand;

class SellController extends Controller
{
    
    public function index()
    {
        if (!Auth::check()) {
        return response('', 200);
    }
        $user = Auth::user();
        return view('exhibit');
    }
    

    //出品（できない）
    public function sell(ExhibitionRequest $request)
    {
        $data = $request->only(['name', 'detail', 'price']);
        $data['user_id'] = auth()->user()->id;

        $condition = $request->input('condition');
        $conditionId = Condition::where('condition', $condition)->first()->id;
        $data['condition_id'] = $conditionId;
        
        $category = $request->input('category');
        $categoryId = Category::where('category', $category)->first()->id;
        $data['category_id'] = $categoryId;

        $image = $request->file('image');
        if ($image) {
            $path = $image->store('images', 'public');  
            $imageUrl = asset('storage/' . $path);
            $data['image'] = $imageUrl; 
        } else {
            return redirect()->back()->withErrors(['image' => '画像のアップロードに失敗しました']);
        }

        $item = Item::create($data);

        $brand = Brand::firstOrCreate(['brand' => $request->input('brand')]);
        
        $brand->item_id = $item->id; 
        $brand->save();
        $item->brand_id = $brand->id;
        $item->save();

        $item->categories()->attach($categoryId);
        $item->condition()->associate($conditionId);

        $item->save();

        return redirect()->route('home', ['item' => $item->id]);
    }
}