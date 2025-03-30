<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * ログアウト処理を行います。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // ログアウト処理
        Auth::logout();

        // セッション無効化
        $request->session()->invalidate();

        $response = redirect('/');

        // セッションID再生成
        $request->session()->regenerateToken();

        // トップページにリダイレクト
        return $response;
    }
}
