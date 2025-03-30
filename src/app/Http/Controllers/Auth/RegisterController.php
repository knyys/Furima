<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $registerData = $request->only(['name', 'email', 'password']);
        
        // セッションにデータを保存
    Session::put('register_data', $registerData);

    // 認証用のURLを生成（仮のID）
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => 0,  // 仮のID（本来はDBから取得する）
            'hash' => sha1($registerData['email']), 
        ]
    );

    // メール送信
    Mail::to($registerData['email'])->send(new VerifyEmail($verificationUrl));

    return redirect()->route('verification.notice')->with('message', '認証メールを確認してください。');
}
}
