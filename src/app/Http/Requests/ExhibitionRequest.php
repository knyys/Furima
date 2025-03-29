<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'detail' => 'required|max:255',
            'image' => 'required|max:1024|mimes:jpeg,png',
            'category' => 'required',
            'condition' => 'required',
            'price' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'detail.required' => '商品説明を入力してください',
            'detail.max' => '255文字以内で入力してください',
            'image.required' => '画像をアップロードしてください',
            'image.max' => '画像のサイズは1MB以下にしてください。',
            'image.mimes' => 'アップロードできる画像の形式はJPEGまたはPNGのみです。',
            'category.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '価格を入力してください',
        ];
    }
}
