<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = app(CreateNewUser::class)->create($request->validated());
        
        // メール認証イベントを発火（認証メール送信）
    event(new Registered($user));

    // ユーザーをログインさせる
    Auth::login($user);

        return redirect('/email/verify')->with('success', '登録が完了しました。メールを確認してください。');
    }
}
