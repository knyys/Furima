<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
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
Route::get('profile', [ProfileController::class, 'index']);

//ログイン
Route::get('login', [LoginController::class, 'loginView'])->name('login');
Route::post('login', [LoginController::class, 'store']);

//商品一覧
Route::get('/', [ItemController::class, 'index'])->name('/');
