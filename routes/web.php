<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\EventController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// ユーザー管理用ルート
Route::resource('users', UserController::class)->except(['show']);
// 主催者管理用ルート
Route::resource('organizers', OrganizerController::class);

// 会場管理用ルート
Route::resource('venues', VenueController::class);

// イベント管理用ルート
Route::resource('events', EventController::class);

// LIFF用ルート
Route::get('/liff', 'LiffController@index');
Route::post('/liff', 'LiffController@store');
Route::post('/liff/check', 'LiffController@check');
Route::get('/liff/registered', function () {
    return view('liff.registered');
});