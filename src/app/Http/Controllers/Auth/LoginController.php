<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class LoginController extends Controller
{
     public function loginView()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        // ユーザー認証
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/?page=mylist');
        }
        

        // 認証失敗時、エラーメッセージを表示し入力値を保持
        return redirect()->route('login')->withInput()->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ]);
    }
}
