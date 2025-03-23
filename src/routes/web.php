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
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

/// メール認証の通知を送信
Route::get('/email/verify', function () {
    return view('auth.email');
})->middleware('auth')->name('verification.notice');

// メール認証の処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('mypage/profile'); // 認証後にリダイレクト
})->middleware(['auth', 'signed'])->name('verification.verify');

// メール再送信
Route::post('/email/resend', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return redirect('mypage/profile');
    }
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//プロフィール編集画面
Route::get('mypage/profile', [ProfileController::class, 'welcome'])->middleware(['auth', 'verified']);

Route::post('/profile/upload', [ProfileController::class, 'upload'])
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

