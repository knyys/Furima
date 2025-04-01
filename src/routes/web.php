<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\LikeController;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Webhook;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// 会員登録画面
Route::get('register', [RegisterController::class, 'create'])->name('register');
Route::post('register', [RegisterController::class, 'store']);

//プロフィール画面
Route::get('mypage', [ProfileController::class, 'index'])->name('mypage');

//メール認証
    /* メール認証してくださいの画面 */
    Route::get('/email/verify', function () {
        return view('auth.email');
    })->middleware('auth')->name('verification.notice');

    /* メール認証の処理 */
    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $registerData = Session::get('register_data'); // セッションからユーザー情報を取得

        if (!$registerData) {
            return redirect('/register')->withErrors(['email' => '登録データが見つかりません。']);
        }
        
        if (sha1($registerData['email']) !== $hash || $registerData['email'] !== $registerData['email']) {
            return redirect('/register')->withErrors(['email' => '認証リンクが無効です。']);
        }

        $user = User::where('email', $registerData['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $registerData['name'],
                'email' => $registerData['email'],
                'password' => Hash::make($registerData['password']),
            ]);
            $user->email_verified_at = now(); //新規登録
            $user->save();
        } else {
            $user->email_verified_at = now(); //更新
            $user->save();
        }

        auth()->login($user);

        Session::forget('register_data');

        return redirect('mypage/profile');
    })->name('verification.verify');



    /* メール認証再送信 */
    Route::post('/email/resend', function (Request $request) {
        // すでに認証済みのとき
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('mypage/profile');
        }

        // 認証メールを再送信
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//プロフィール編集画面（メール認証していないと×）
Route::get('mypage/profile', [ProfileController::class, 'welcome'])
->middleware(['auth', 'verified']);

Route::post('mypage/profile', [ProfileController::class, 'upload'])
->middleware(['auth', 'verified'])
->name('profile.upload');

//ログイン
Route::get('/login', [LoginController::class, 'loginView'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

//ログアウト
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

//商品一覧
Route::get('/', [ItemController::class, 'index'])->name('home');

//商品詳細
Route::get('/items/{item}', [ItemController::class, 'detail'])->name('item.detail');
Route::post('/items/{item}', [ItemController::class, 'addComment']);

//商品出品画面
Route::get('/sell', [SellController::class, 'index']);
Route::post('/sell', [SellController::class, 'sell'])->name('sell');

//商品購入
Route::get('/purchase/{item}', [PurchaseController::class, 'index'])->name('purchase');
Route::post('/purchase/{item}', [PurchaseController::class, 'purchase'])->name('purchase.complete');

//住所変更
Route::get('/purchase/address/{item}', [AddressController::class, 'index']);
Route::patch('/purchase/address/{item}', [AddressController::class, 'updateAddress'])->name('address.update');

//お気に入り
Route::post('/items/{itemId}/like', [LikeController::class, 'favorite']);



Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('webhook');