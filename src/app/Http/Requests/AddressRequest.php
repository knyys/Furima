<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
        $routeName = $this->route()->getName(); //ルートによって適用

        // 共通
        $rules = [
            'address_number' => ['required', 'regex:/^\d{3}-\d{4}$/'], 
            'address' => ['required', 'string', 'max:255'],
            'building' => ['required', 'string', 'max:255'],
        ];

        // プロフィール設定ページは名前のバリデーションも追加
        if ($routeName === 'profile.upload') {
            $rules['name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
        'name.required' => 'お名前を入力してください',
        'address_number.required' =>'郵便番号を入力してください。',        
        'address_number.regex' => '郵便番号は「XXX-XXXX」の形式で入力してください。',
        'address.required' => '住所を入力してください',
        'building.required' => '建物名を入力してください',
        ];
    }

}
