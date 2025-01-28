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
        return [
            'name' => 'required',
            'address_number' => 'required|min:8',
            'address' => 'required',
            'building' => 'required',
        ];
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
