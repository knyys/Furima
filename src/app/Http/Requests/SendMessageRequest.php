<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
        // 評価フォームの場合
        if ($this->input('form_type') === 'rating') {
            return [
                'rating' => 'nullable|integer|between:0,5',
            ];
        }

        // チャット送信用
        return [
            'message' => 'required|string|max:400',
            'image' => 'nullable|mimes:jpg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'message.required' => '本文を入力してください',
            'message.max' => '本文は400文字以内で入力してください',
            'image.max' => '画像のサイズは1MB以下にしてください。',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
