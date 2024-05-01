<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LiffController;

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

// ホームページへのルート
Route::get('/', [HomeController::class, 'index'])->name('home');

// 認証ルート（ログイン、ログアウト、登録など）
Auth::routes();

// ホームページへの再定義ルート
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ユーザー管理用ルート（CRUD操作）
Route::resource('users', UserController::class)->except(['show']);

// 主催者管理用ルート（CRUD操作）
Route::resource('organizers', OrganizerController::class);

// 会場管理用ルート（CRUD操作）
Route::resource('venues', VenueController::class);

// イベント管理用ルート（CRUD操作）
Route::resource('events', EventController::class);

// LIFF用ルート（LINE Front-end Framework）
Route::get('/liff', [LiffController::class, 'index'])->name('liff.index');
Route::post('/liff', [LiffController::class, 'store'])->name('liff.store');
Route::post('/liff/check', [LiffController::class, 'check'])->name('liff.check');

// LIFF登録完了ページ
Route::get('/liff/registered', function () {
    return view('liff.registered');
})->name('liff.registered');
