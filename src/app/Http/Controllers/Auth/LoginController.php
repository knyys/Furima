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
            return redirect()->intended(route('/'));
        }

        // 認証失敗時、エラーメッセージを表示
        return redirect()->route('login')->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ]);
    }
}
