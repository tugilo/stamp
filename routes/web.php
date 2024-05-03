<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LiffController;
use App\Http\Controllers\StampController;
use App\Http\Controllers\PresentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| アプリケーションのルート定義。Webアプリケーションの機能ごとにルートが設定されています。
| RouteServiceProviderによって"web"ミドルウェアグループと共にロードされます。
|
*/

// ホームページへのルート
Route::get('/', [HomeController::class, 'index'])->name('home');

// イベントデータ取得のAPIルート
Route::get('/api/events', [HomeController::class, 'events'])->name('api.events');

// 標準の認証ルート
Auth::routes();

// ホームページに再定義されたルート
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ユーザー管理用のCRUD操作ルート
Route::resource('users', UserController::class)->except(['show']);

// 主催者管理用のCRUD操作ルート
Route::resource('organizers', OrganizerController::class);

// 会場管理用のCRUD操作ルート
Route::resource('venues', VenueController::class);

// イベント管理用のCRUD操作ルート
Route::resource('events', EventController::class);

// プレゼント管理用のCRUD操作ルート
Route::resource('presents', PresentController::class);

// LIFFアプリのメインページルート
Route::get('/liff', [LiffController::class, 'index'])->name('liff.index');

// LIFF初期化ルート
Route::post('/liff/init', [LiffController::class, 'initializeLiff']);

// 登録状態チェック用ルート
Route::post('/liff/check', [LiffController::class, 'check'])->name('liff.check');

// LIFFからのデータ保存用ルート
Route::post('/liff/store', [LiffController::class, 'store'])->name('liff.store');

// アンケートページ表示ルート
Route::get('/liff/survey', [LiffController::class, 'showSurvey'])->name('liff.survey.show');

// アンケート回答保存ルート
Route::post('/liff/survey', [LiffController::class, 'storeSurvey'])->name('liff.survey.store');

// スタンプページ表示ルート
Route::get('/liff/stamp', [StampController::class, 'index'])->name('liff.stamp.index');

// スタンプ獲得処理ルート
Route::post('/liff/stamp', [StampController::class, 'store'])->name('liff.stamp.store');

// プレゼント応募フォームを表示するためのルートを追加（syubetsu_id パラメータ付き）
Route::get('/liff/stamp/applyPresentForm/{customer_id}/{syubetsu_id}', [StampController::class, 'applyPresentForm'])->name('liff.stamp.applyPresentForm');

// プレゼント応募処理用ルート
Route::post('/liff/stamp/apply-for-present', [StampController::class, 'applyForPresent'])->name('liff.stamp.applyForPresent');
