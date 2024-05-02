<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LiffController;
use App\Http\Controllers\StampController;

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
// HomeControllerを使ったイベントデータ取得のルート
Route::get('/api/events', [HomeController::class, 'events'])->name('api.events');

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

Route::get('/liff', [LiffController::class, 'index'])->name('liff.index');
Route::post('/liff/init', [LiffController::class, 'initializeLiff']);
Route::post('/liff/check', [LiffController::class, 'check'])->name('liff.check');
Route::post('/liff/store', [LiffController::class, 'store'])->name('liff.store');
// 'customer_id'パラメータを含むようにルートを調整
Route::get('/liff/survey', [LiffController::class, 'showSurvey'])->name('liff.survey.show');
Route::post('/liff/survey', [LiffController::class, 'storeSurvey'])->name('liff.survey.store');
// スタンプコントローラのルート設定を更新
Route::get('/liff/stamp', [StampController::class, 'index'])->name('liff.stamp.index');
Route::post('/liff/stamp', [StampController::class, 'store'])->name('liff.stamp.store');
Route::post('/liff/stamp/apply-prize-4', [StampController::class, 'applyPrize4'])->name('liff.stamp.applyPrize4');
Route::post('/liff/stamp/apply-prize-9', [StampController::class, 'applyPrize9'])->name('liff.stamp.applyPrize9');
