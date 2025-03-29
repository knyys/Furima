<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        
        Session::put('register_data', $registerData);

        // ユーザーの仮IDを取得
        $userId = 0;

        // 認証用のURLを生成（仮のURL）
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $userId,  // ユーザーの仮ID
                'hash' => sha1($registerData['email']), 
            ]
        );

        // メール送信
        Mail::to($registerData['email'])->send(new VerifyEmail($verificationUrl));

        return redirect()->route('verification.notice')->with('message', '認証メールを確認してください。');
    }
}
