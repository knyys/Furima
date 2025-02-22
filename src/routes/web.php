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
Route::get('mypage/profile', [ProfileController::class, 'welcome']);
Route::get('mypage', [ProfileController::class, 'index'])->name('mypage');

Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');


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
Route::post('/purchase/{item}', [PurchaseController::class, '']);

//住所変更
Route::get('/purchase/address/{item}', [AddressController::class, 'index']);
Route::post('/purchase/address/{item}', [AddressController::class, 'updateAddress'])->name('address.update');