<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
Route::get('mypage', [ProfileController::class, 'index']);

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
//Route::get('/items', [ItemController::class, 'detail']);



//商品出品画面
Route::get('/sell', [SellController::class, 'index']);